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
	
	//getAvailableNumbers : Use this method to get list of all available phone numbers
	// $country : country code, default is US
	//$SearchParams : all other search parameters
	function getAvailableNumbers($country = 'US', $SearchParams=array()) 
	{
		$client = $this->getTwilioObject();
		$numbers = $client->account->available_phone_numbers->getList($country, 'Local', $SearchParams);
		$arr_numbers = array();
		foreach($numbers->available_phone_numbers as $number) 
		{
			$arr_numbers[] = $number->phone_number;
		}
		return ($arr_numbers);
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
		if($number != "")
		{
			try {
			$number = $client->account->incoming_phone_numbers->create(array(
					"FriendlyName" => $friendlyname,
					"PhoneNumber" => $number					
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
			$appsid = $this->getAppSid();
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
	
	// deleteNumber : Use this method to release number using phone number's sid
	// $numberSid : phone number's sid
	function deleteNumber($numberSid)
	{
		$client = $this->getTwilioObject();
		$client->account->incoming_phone_numbers->delete($numberSid);
		
	}
	
	function buy_tollfree_numbers($total_num)
	{
		$client = $this->getTwilioObject();
		$numbers = $client->account->available_phone_numbers->getList('US', 'TollFree', array(				
					"Contains" => "88888"
				));
		$arr_number = array();
		$i=0;
		foreach($numbers->available_phone_numbers as $number)
		{
			$i++;
			$arr_number[] = $number->phone_number;
			if($i == $total_num)
				break;			
		}
		// if available numbers are less than required numbers
		if($i < $total_num)
		{
			$extra_numbers =  $client->account->available_phone_numbers->getList('US', 'TollFree', array());
			foreach($numbers->available_phone_numbers as $number)
			{
				$i++;
				$arr_number[] = $number->phone_number;
				if($i == $total_num)
					break;	
			}
		}
		return $arr_number;
	}
	
	// sayMessage : Use this method to create account
	// $friendlyName : friendly name of newly created account
	function createAccount($friendlyName)
	{
		$client = $this->getTwilioObject();
		$account = $client->accounts->create(array(
				"FriendlyName" => $friendlyName
				));
		$account_det = array(
					"FriendlyName" => $friendlyName,
					"sid" => $account->sid
					);		
		return $account_det;
	}
	
	// deleteAccount : Use this method to delete account using account sid or friendlyname
	// $status : is 1 if you want to temporarily suspend account and is 2 if you want to irreversibly close this account. 
	function deleteAccount($status ='1', $account_sid='', $friendlyname='' )
	{
		$client = $this->getTwilioObject();
		if($status == 1)
		{
			$status_name = 'suspended';
		}
		elseif($status == 2)
		{
			$status_name = 'closed';
		}
		if($account_sid != "")
		{
			$account = $client->accounts->get($account_sid);
			$account->update(array(
				"Status" => $status_name
				));
		}
		elseif($friendlyname != "")
		{
			foreach ($client->accounts->getIterator(0, 50, array(
						"FriendlyName" => $friendlyname
						)) as $account)
			{
				$account->update(array(
					"Status" => $status_name
					));				
			}			
		}
		
	}
	
	// sayMessage : This is for say message in dial response
	function sayMessage($voice = 'woman', $msg)
	{
		$response =  "<Say voice='".$voice."'>
							  ".$msg."
					  </Say>";
		return $response;
	}
	
	// dialResponse : This is to get dial response for simple dial, conference and for number and direct agent
	// $isconference : if you want response for conference set it to 1
	// $isagent : if you want response for agent set it to 1
	function dialResponse($isconference='', $isagent ='', $to)
	{
		if($isconference == 1)
		{
			$response =  "<Dial>
								<Conference>
								   ".$to."
								<Conference>
						 </Dial>";
		}
		elseif($isagent == 1)
		{
			$response =  "<Dial CallerId='".$to."'>
								<Client>
								   ".$to."
								<Client>
							</Dial>";
		}
		else
		{
			$response =  "<Dial CallerId='".$to."'>
								<Number>
								   ".$to."
								</Number>  
							</Dial>";
		}
		return $response;
	}
	
	// dialNumber : Use this method to get dial response.
	// $isconference : if you want response for conference set it to 1
	// $isagent : if you want response for agent set it to 1
	// $to : is the number in case of simple call and agent name in case of agent call
	// $say : say message 
	function dialNumber($isconference='', $isagent ='', $to='', $say='')
	{
		$response = "<Response>";
		if($say != "")
			$response .= sayMessage($say);
		if($to != "")
			$response .= dialResponse($isconference, $isagent,$to);
		$response .= "</Response>";
		return $response;		
	}
	
	// makeCall : Use this method to make call using rest api
	// $from : from number
	// $to : to number
	// $url : url to redirect
	// $otherParameters ; for other parameters if you want
	function makeCall($from,$to,$url,$otherParameters=array())
	{
		$client = $this->getTwilioObject();
		$call = $client->account->calls->create($from, $to, $url, $otherParameters);
		return $call->sid;
	}
	
	// modifyCall : Use this method to modify live calls
	// $call_sid : call sid of modifying call
	// $url : url where to modify live call
	function modifyCall($call_sid,$url)
	{
		$client = $this->getTwilioObject();
		$call = $client->account->calls->get($call_sid);
		$call->update(array(
						"Url" => $url,
						"Method" => "POST"
						));					
	}
	
	// getAllConference : Use this method to get all conference
	function getAllConference()
	{
		$arr_conference = array();
		$client = $this->getTwilioObject();
		foreach ($client->account->conferences->getIterator(0, 50, array(
																"Status" => "in-progress",
																"DateCreated" => date('Y-m-d')
																)) as $conference
																) 
		{
			$arr_conference[] = $conference->friendly_name;
		}
		return $arr_conference;
	}
	
	// getConfParticipants : Use this method to get all participants of conference
	// $conf_name : name of conference 
	function getConfParticipants($conf_name)
	{
		$arr_participant = array();
		$client = $this->getTwilioObject();
		foreach ($client->account->conferences->getIterator(0, 50, array(
																"Status" => "in-progress",
																"DateCreated" => date('Y-m-d')
																)) as $conference
																) 
		{
			if($conference->friendly_name==$conf_name)
			{
				foreach($conference->participants as $participant)
				{
					$arr_participant[] = $participant->call_sid;					
				}
			}
			break;			
		}
		return $arr_participant;
	}
	
	// getAllTranscriptions :  Use this method to get all transcriptions
	function getAllTranscriptions()
	{
		$client = $this->getTwilioObject();
		$arr_transcription = array();
		foreach ($client->account->transcriptions as $transcription)
		{
			$arr_transcription[] = $transcription->transcription_text;
		}
		return $arr_transcription;
	}
	
	// getTranscription : Use this method to get transcriptions by transcription sid
	// $sid : transcription sid
	function getTranscription($sid)
	{
		$client = $this->getTwilioObject();
		$transcription = $client->account->transcriptions->get($sid);
		return $transcription->transcription_text;
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
		//<Say>For store hours, press 1.</Say>
		//<Say>To speak to an agent, press 2.</Say>
		//<Say>To check your package status, press 3.</Say>
	}
	
}
?>