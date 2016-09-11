<?php
header('Content-type: text/xml');
include("log_request.php");
require_once('twilio_messages.php');

//log_request("app_error.php");
echo "<Response><Say>{$twilio_messages['app_error']}</Say></Response>";
?>
