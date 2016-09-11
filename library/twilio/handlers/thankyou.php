<?php 
	date_default_timezone_set('America/Mexico_City');
	require_once('log_request.php');
	header("content-type:application/xml");
	//log_request("***Postback***");
?>
<Response>
	<Say>Hello Caller, You are successfully connected to call.</Say>
    <Say>Thank You. Have a nice day!</Say>
</Response>