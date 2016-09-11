<?php
header('Content-type: text/xml');
require_once('../../Config.php');
require_once('../../constants.php');
require_once('twilio_messages.php'); // File used to handle all the static messages
require_once('../../commonObject.php');
include("log_request.php");

require_once('../../campaignManager.php');
$campaignObject = new CampaignManager();

if($_SERVER['QUERY_STRING']!==""){
	if($exist){
	}
}

$called_number=$_REQUEST['To'];
//$called_number='+14046206444';

$call_center_number=isset($_REQUEST['call_center_number'])?$_REQUEST['call_center_number']:'';

function parseAmp($str)
{
	return str_replace('&','&amp;',$str);
}
$main_url=PATH."/library/twilio/handlers/handle_incoming_call.php"; // URL of the same page.

$ivr_try=isset($_REQUEST['ivr_try'])?$_REQUEST['ivr_try']:0; // how many times same ivr played (first time 0)
$digit=isset($_REQUEST['Digits'])?intval($_REQUEST['Digits']):FALSE; // Digits(Which user have pressed in IVR, sent via Twilio) got from query strings(otherwise FALSE);
// Below is the string to handle the next action if user doesn't input anything
$no_input_str="";
$current_url="";
if($ivr_try==1){
	$no_input_str="<Say>{$twilio_messages['no_input_2']}</Say><Hangup/>";
}else{
	
	$current_url=$main_url."?ivr_try=1";
	if($_SERVER['QUERY_STRING']!==""){
		$current_url=$current_url."&".$_SERVER['QUERY_STRING'];
	}
	if($digit){
		$current_url.="&Digits=".$digit;
	}
	//log_request($current_url); 
	$current_url=parseAmp($current_url);
	$no_input_str="<Say>{$twilio_messages['no_input_1']}</Say><Redirect>{$current_url}</Redirect>";
}

$level=isset($_REQUEST['level'])?$_REQUEST['level']:-1; // level got from query strings(otherwise -1)
$campaign_id=isset($_REQUEST['campaign_id'])?$_REQUEST['campaign_id']:-1; // campaign_id got from query strings(otherwise -1)

$first_time=FALSE; // This will be true if request came first time.

$action=isset($_REQUEST['action'])?intval($_REQUEST['action']):0; // action got from query strings(otherwise 0);
$message="";
$message1="";
$location='';
/*
Type of actions:
	db_id
	 3	   10: Ask a question
		   11: After IVR digit clicked
	 2	   20: Forward Call to call center
	 4	   30: Verify the caller location
		   31: Next step after Callers location is verified(Forward to call center)
		   32: Ask for callers zip code
	 1	   40: Hang up
*/

$wrong_pincode=FALSE; // If user has entered pincode and it is wrong, this will be set to true
$wrong_key=FALSE; // If user has pressed a key on ask a question and it is wrong key, this will be set to true
$wrong_key_message=""; // The message to pronounce if user has enetered wrong key.
$is_message_file = FALSE; // use to check wether message is file or text 

//If action is 32, and digit sent by user is 1, that means we have correct location of user. We need to forward call to call center
if($action===32 && $digit===1){
	$action=31;
}else if($action===32 && $digit!==2){
	$wrong_key=TRUE;
	$action=30; // if user has pressed key another than 1 or 2
}

//If action is 34, we need to identify the pincode number.
if($action===34){
	$call_center_number=$_REQUEST['call_center_number'];
	$call_center_number = str_replace(' ','+',$call_center_number);
	$valid_zip = $campaignObject->isZipCodeExists($digit);
	
	if($valid_zip != ""){
		$verified=TRUE;
		$called_number = $called_number;
		$mt_id = $campaignObject->getMtIdOfLocation($called_number); // get mt_id to update record for new zip code given by user
		$campaignObject->getPostalCode($mt_id); // get the current postal code and save it as a old postal code.
		$campaignObject->updateNewPostalCode($valid_zip,$mt_id);
	}
	if($verified){
		$action=31;
	}else{
		$wrong_pincode=TRUE;
		$action=32;
	}
}
//echo "level: ".$level."<br>campaign_id: ".$campaign_id."<br>action".$action;
if($level===-1 || $campaign_id===-1 || $action===11) // If the request came directly via call. OR if it is action 11 (IVR reply)
{
	if($action!==11)
	{
		$campaignObject->save_call($_REQUEST);
				
		$location_of_number = $campaignObject->getLocation($called_number);
		$location = $location_of_number['city_name'];
		//get campaign_id of attached number
		//LOGIC FOR GETTING CAMPAIGN ID GOES HERE
		$campaign_details = $campaignObject->getCampaignDetailsUsingNumber($called_number);
		if($campaign_details == false)
		{
			echo "<Response><Say>{$twilio_messages['offer_deallocate']}</Say><Hangup/></Response>"; 
			exit;
		}
		$campaign_id = $campaign_details['camp_id'];
				
		$first_time=TRUE;
		$ivrs_action_id=$campaign_details['ivrs_action_id'];
		if($ivrs_action_id != 0){
			$action_type_details = $campaignObject->getActionTypeId($ivrs_action_id);
			if($action_type_details['action_type_id'] == 1){
				$action = 40;
			}else if($action_type_details['action_type_id'] == 2){
				$action = 20;
				$call_center_number = $action_type_details['call_forword_no'];
			}else if($action_type_details['action_type_id'] == 4){
				$action = 30;
				$call_center_number = $action_type_details['call_forword_no'];
			}
			
			if($action_type_details['is_promt_recording'] == 1)
			{
				$is_message_play = TRUE;
				$message = $action_type_details['description'];
			}
			if($action_type_details['is_play_mp3'] == 1)
			{
				if($action_type_details['file_name'] != ""){
					$file_name = DOC_ROOT."uploads/".$campaign_id."/".$action_type_details['file_name'];
					$file_message = HTTP_PATH."uploads/".$campaign_id."/".$action_type_details['file_name'];
					//log_request("file :".$file_name);					
					$message1 = $file_message;
					$is_message_file = TRUE;						
				}/*else{
					$message = $action_type_details['description'];
				}*/				
			}
		}
		else{
			$action_type_details = $campaignObject->getDetailsOfIvrs($campaign_id);
			$action=10;
			
			if($action_type_details['is_promt_recording'] == 1)
			{
				$is_message_play = TRUE;
				$message = $action_type_details['description'];	
			}
			if($action_type_details['is_play_mp3'] == 1)
			{
				if($action_type_details['file_name'] != ""){
					
					$file_name = DOC_ROOT."uploads/".$campaign_id."/".$action_type_details['file_name'];
					$file_message = HTTP_PATH."uploads/".$campaign_id."/".$action_type_details['file_name'];
					//log_request("file :".$file_message);					
					$message1 = $file_message;
					$is_message_file = TRUE;	
						
				}/*else{
					$message = $action_type_details['description'];
				}	*/			
			}
		}
		$main_url = $main_url."?";
	}
	else
	{
		$location_of_number = $campaignObject->getLocation($called_number);
		$location = $location_of_number['city_name'];
		$arr_key_exist = $campaignObject->check_key($campaign_id,$level,$digit);
				
		if(!$arr_key_exist)
		{
			$wrong_key=TRUE;
			$wrong_key_message="<Say>{$twilio_messages['wrong_key']}</Say>";
			if(isset($_REQUEST['route']))
			{	
				$arr_route = explode('_',$_REQUEST['route']);
				$digit = $arr_route[count($arr_route)-1];	
				$level--;
				$arr_key_exist = $campaignObject->check_key($campaign_id,$level,$digit);			
			}
			else
			{
				echo "<Response>{$wrong_key_message}<Redirect>$main_url</Redirect></Response>"; 
				exit;
			}	
			$main_url = $main_url."?";		
		}
		else
		{
			$route=isset($_REQUEST['route'])?$_REQUEST['route'].'_':'';
			$route=isset($digit)?$route.$digit:FALSE;	
			$main_url = $main_url."?route=".$route."&amp;";			
		}
		
		$action_type_id=$arr_key_exist['action_type_id'];	
		if($action_type_id == 1){
			$action = 40;
		}
		else if($action_type_id == 2){
			$action = 20;
			$call_center_number=$arr_key_exist['call_forword_no'];
		}
		else if($action_type_id == 4){
			$action = 30;
			$call_center_number=$arr_key_exist['call_forword_no'];
		}
		else if($action_type_id == 3){
			$action = 10;
		}
		
		if($arr_key_exist['is_promt_recording'] == 1)
		{
			$is_message_play = TRUE;	
			$message = $arr_key_exist['description'];
		}
		if($arr_key_exist['is_play_mp3'] == 1)
		{
			if($arr_key_exist['file_name'] != ""){
				$file_name = DOC_ROOT."uploads/".$campaign_id."/".$arr_key_exist['file_name'];
				$file_message = HTTP_PATH."uploads/".$campaign_id."/".$arr_key_exist['file_name'];
				$message1 = $file_message;
				$is_message_file = TRUE;						
			}/*else{
				$message = $arr_key_exist['description'];
			}*/
		}
		
	}
}
else
{
	$main_url = $main_url."?";
}
//To make the level 0 if it is -1. But if action is 10, it will be done letter
if($first_time && $action!==10){
	$level=0;
}
//echo "<br><br>level: ".$level."<br>campaign_id: ".$campaign_id."<br>action".$action."<br>messsage: ".$message;
//IF user has pressed wrong input in IVRS
if($wrong_key)
{
	//log_request("Wrong_key");
	$wrong_key_message="<Say>{$twilio_messages['wrong_key']}</Say>";
}

$set_message = '';
if($is_message_play == TRUE)
{
	$set_message = "<Say>{$message}</Say>";	
}
if($is_message_file == TRUE)
{
	$set_message .= "<Play>{$message1}</Play>";	
}
/*else{
	$set_message = "<Say>{$message}</Say>";	
}*/
//log_request("Final variables -  Action: ".$action.' || set_message:'.$set_message.' || level: '.$level.' || campaign_id: '.$campaign_id.' || Digits: '.$digit." || wrong_key: ".($wrong_key?"TRUE":"FALSE")." || ivr_try: ".$ivr_try." || call_center_number: ".$call_center_number);

if($action===10) // If action is ask a question
{
	$level=$level+1;
	$qrystring="action=11&campaign_id={$campaign_id}&level={$level}";
	$qrystring=parseAmp($qrystring);	
	echo   "<Response>
				{$wrong_key_message}
				<Gather action='{$main_url}{$qrystring}' numDigits='1' method='POST'>
					".$set_message."
				</Gather>
				{$no_input_str}
			</Response>";
}
else if($action===20) // If action is forward call to call center
{
	echo   "<Response>
				".$set_message."
				<Dial action=\"postback.php\" method=\"GET\" record=\"true\">
					<Number>{$call_center_number}</Number>
				</Dial>
			</Response>";
}
else if($action===30) // If action is Verify the location of caller
{
	if (strpos($message,'###') !== false) 
		$message = str_replace('###',$location,$message);
	/*if($is_message_file == TRUE)
	{	
		$set_message = $set_message."<Say>Press 1 for correct location. Press 2 for wrong location.</Say>";
	}
	else	
		$set_message = "<Say>{$message} Press 1 for correct location. Press 2 for wrong location.</Say>";*/
	$set_message = $set_message."<Say>Press 1 for correct location. Press 2 for wrong location.</Say>";
	$qrystring="campaign_id={$campaign_id}&level={$level}&action=32&call_center_number={$call_center_number}";
	$qrystring=parseAmp($qrystring);

	echo   "<Response>
				{$wrong_key_message}
				<Gather action='{$main_url}{$qrystring}' numDigits='1' method='POST'>
					".$set_message."
				</Gather>
				{$no_input_str}
			</Response>";
}
else if($action===31)// If user location is verified.
{
	//$call_center_number="9876543210";
	echo   "<Response>
				".$set_message."
				<Dial record='true'>
					<Number>{$call_center_number}</Number>
				</Dial>
			</Response>";
}
else if($action===32)// If user presses 2 to give his pincode number.
{	
	//echo $call_center_number;
	$message=$twilio_messages['ask_for_pincode'];
	if($wrong_pincode){
		$message=$twilio_messages['wrong_pincode'];
	}
	$set_message = "<Say>{$message}</Say>";	
	$call_center_number = str_replace(' ','+',$call_center_number);
	$qrystring="action=34&campaign_id={$campaign_id}&level={$level}&call_center_number={$call_center_number}";
	$qrystring=parseAmp($qrystring);
	echo   "<Response>
				<Gather action='{$main_url}{$qrystring}' numDigits='5' method='POST'>
					".$set_message."
				</Gather>
				{$no_input_str}
			</Response>";
}
else if($action===40) // If action is hangup
{
	echo   "<Response>
				".$set_message."
				<Hangup/>
			</Response>";
}

?>