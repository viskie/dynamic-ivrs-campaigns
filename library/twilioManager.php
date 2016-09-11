<?php 
include_once('library/twilio/Twilio.php');
include_once('library/constants.php');
class twilioManager
{
	// getTwlioCreds : Use this method to get Twilio credentials 
	function getTwlioCreds() 
	{
		$twilio_creds = array();
		$twilio_creds['accountSid'] = "ACa3cba87b7d5e460cbef8914271158553";
		$twilio_creds['authToken'] = "1551ca24a3737a97d6c833bf8b98ebe0";
		return $twilio_creds;
	}
	
	// getAppSid : Use this method to get mTrack application sid
	function getAppSid()	
	{		
		$VoiceApplicationSid = "AP521527f698c443e2c37b21817c82d04c";
		return $VoiceApplicationSid;
	}
	
	// getAllCreds : Use this method to get all twilio credentials
	function getAllCreds() 
	{
		$twilio_creds = $this->getTwlioCreds();
		$twilio_creds['VoiceApplicationSid'] = $this->getAppSid();
		return $twilio_creds;
	}
	
	// getTwilioObject :  Use this method to get twilio object
	function getTwilioObject() 
	{
		$twilio_creds = $this->getTwlioCreds();
		$client = new Services_Twilio($twilio_creds['accountSid'], $twilio_creds['authToken']);
		return $client;
	}
	
	// buyNumber : Use this method to buy number using phone number or area code
	// $friendlyname : is A human readable description of the new incoming phone number. Maximum 64 characters. Defaults to a formatted version of the number.
	// $number : if you want to buy phone number directly by number, send that number to this method
	// $AreaCode : If you want to buy phone number using area code, send that area code to this methos 
	function buyNumber($friendlyname, $number='', $AreaCode='') 
	{
		$client = $this->getTwilioObject();
		$det_number = array();
		$chkbuy = 1;
		$appsid = $this->getAppSid();
		if($number != "")
		{
			try {
			$number = $client->account->incoming_phone_numbers->create(array(
					"FriendlyName" => $friendlyname,
					"PhoneNumber" => $number,
					"VoiceApplicationSid" => $appsid					
					));
			}
			catch (Exception $e)
			{
				$chkbuy = 0;
				$det_number['error'] = $e->getMessage();
			}
		}
		else
		{
			try {
			
			$number = $client->account->incoming_phone_numbers->create(array(
						"FriendlyName" => $friendlyname,
						"AreaCode" => $AreaCode,
						"VoiceApplicationSid" => $appsid
						));
			}
			catch (Exception $e)
			{
				$chkbuy = 0;
				$det_number['error'] = $e->getMessage();
			}
		}	
		if($chkbuy == 1)
		{	
			$det_number['number'] = $number->phone_number;
			$det_number['sid'] = $number->sid;
		}
		return $det_number;		
	}
	
	// function to buy tollfree numbers
	// $total_num = how many numbers you want to buy
	function buy_tollfree_numbers($total_num,$num_type=0) // $num_type : 0 : Tollfree  1: Local
	{
		$client = $this->getTwilioObject();
		if($num_type == 1)
		{
			
			$extra_numbers =  $client->account->available_phone_numbers->getList('US', 'Local', array());
			$arr_number = array();
			$i=0;
			foreach($extra_numbers->available_phone_numbers as $number)
			{				
				//var_dump($number); exit;				
				$number_details = $this->buyNumber($number->friendly_name,$number->phone_number);
				$arr_number[$i] = array(
								'number' => $number->phone_number,
								'friendly_name' => $number->friendly_name,
								'sid' => $number_details['sid'],
								);
				$arr_number[$i]['is_tollfree'] ='0';
				$i++;
				if($i == $total_num)
					break;			
			}	
		}
		else
		{
			//$client = $this->getTwilioObject();
			$numbers = $client->account->available_phone_numbers->getList('US', 'TollFree', array(	
						"Contains" => "888"
					));
	
			$arr_number = array();
			$i=0;	
			foreach($numbers->available_phone_numbers as $number)	
			{	
				$number_details = $this->buyNumber($number->friendly_name,$number->phone_number);
				$arr_number[$i] = array(
									'number' => $number->phone_number,
									'friendly_name' => $number->friendly_name,
									'sid' => $number_details['sid'],
									);
				$arr_number[$i]['is_tollfree'] ='1';
				$i++;
				if($i == $total_num)
					break;	
			}
			// if available numbers are less than required numbers
			if($i < $total_num)
			{
				$numbers = $client->account->available_phone_numbers->getList('US', 'TollFree', array());
				foreach($numbers->available_phone_numbers as $number)	
				{
					if($i == $total_num)
						break;		
					$number_details = $this->buyNumber($number->friendly_name,$number->phone_number);
					$arr_number[$i] = array(
										'number' => $number->phone_number,
										'friendly_name' => $number->friendly_name,
										'sid' => $number_details['sid'],
										);
					$arr_number[$i]['is_tollfree'] ='1';
					$i++;					
				}
			}
			// if available tollfree numbers are less than required numbers
			if($i < $total_num)
			{
				$extra_numbers =  $client->account->available_phone_numbers->getList('US', 'TollFree', array());
				foreach($extra_numbers->available_phone_numbers as $number)
				{	
					if($i == $total_num)
						break;	
					//var_dump($number); exit;		
					$number_details = $this->buyNumber($number->friendly_name,$number->phone_number);
					$arr_number[$i] = array(
									'number' => $number->phone_number,
									'friendly_name' => $number->friendly_name,
									'sid' => $number_details['sid'],
									);
					$arr_number[$i]['is_tollfree'] ='0';
					$i++;
				}
			}
		}
		//echo "here"; echo "<pre>"; print_r($arr_number); exit;
		return $arr_number;
	}
	
	// deleteNumber : Use this method to release number using phone number's sid
	// $numberSid : phone number's sid
	function deleteNumber($numberSid)
	{
		$client = $this->getTwilioObject();
		$client->account->incoming_phone_numbers->delete($numberSid);		
	}
	
	function createIVR($arr_say,$success_action = 'handle-user-input.php',$failure_action = '>handle-incoming-call.xml')
	{
		$response = "<Response>
						<Gather action='".$success_action."' numDigits='1'>";
		for($i=0;$i<count($arr_say); $i++)
		{
			$response .= '<say>'.$arr_say[$i]['question'].', press '.$arr_say[$i]['digit'].'</say>';
		}
		$response .= "</Gather>
						<!-- If customer doesn't input anything, prompt and try again. -->
						<Say>Sorry, I didn't get your response.</Say>
						<Redirect>".$failure_action."</Redirect>
					</Response>";		
	}
	
	function get_response($say_msg,$action)
	{
		$response = "<Response>";
		if($action == 'hangup')
		{
			$response .=	"<Say>
								{$say_msg}
							</Say>
							<Hangup/>"; 
		}		
		$response .=	"</Response>"; 
		return $response;
	}
	 function set_action_response($data,$action)
	 {		
		$response = "<Response>";
		if($action == 'hangup')
		{
			$response .=	"{$data}
							<Hangup/>"; 							
		}
		elseif($action == 'forword')
		{
			$response .=	"{$data['message']}
							<Dial>
								<Number>{$data['call_forword_no']}</Number>
							</Dial>"; 	
		}
		elseif($action == 'verify')
		{
			$response .=  "{$wrong_key_message}
								<Gather action='{$main_url}{$qrystring}' numDigits='1' method='POST'>
									".$data['message']."
								</Gather>
								{$no_input_str}";
		}
		$response .=	"</Response>"; 
		return $response; 
	 }
	
}
?>