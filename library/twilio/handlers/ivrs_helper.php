<?php
function parseAmp($str)
{
	return str_replace('&','&amp;',$str);
}
function set_message()
{
	$message = "";	
	if($action_type_details['is_promt_recording'] != 0)
	{
		if($action_type_details['file_name'] != ""){
			$file_name = DOC_ROOT."uploads/".$campaign_id."/".$action_type_details['file_name'];
			$file_message = HTTP_PATH."uploads/".$campaign_id."/".$action_type_details['file_name'];
			//log_request("file :".$file_name);				
			$message = $file_message;
			$is_message_file = TRUE;	
				
		}else{
			$message = $action_type_details['description'];
		}				
	}
	if($is_message_file == TRUE){
		$set_message = "<Play>{$message}</Play>";	
	}else{
		$set_message = "<Say>{$message}</Say>";	
	}
	return $set_message;
}
?>