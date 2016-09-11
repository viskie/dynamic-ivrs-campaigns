<?php
require_once("../../Config.php");
function log_request($message=FALSE){
	/*if(!$message){
		$message = '';
		foreach($_REQUEST as $key => $value){
			$message.= $key." => ".$value." || ";
		}
	}
	elseif(is_array($message))
	{
		foreach($message as $key => $value){
			$message.= $key." => ".$value." || ";
		}
	}
	else
		$message = basename($_SERVER['SCRIPT_NAME'])." --- ".$message;
	updateData("insert into twilio_debug set message='".$message."',time_stamp='".date('Y-m-d H:i:s')."'");*/
}
//log_request();