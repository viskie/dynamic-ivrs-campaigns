<?php
include("cron_config.php");

	$arr_numbers = getData("SELECT * FROM campaign_numbers WHERE is_allocated = 1");
	for($i=0; $i<count($arr_numbers); $i++)
	{
		$chk_timediff = round(abs( date("m/d/Y h:i:s a", time()) - $arr_numbers[$i]['last_used']) / 60,2);
		if($chk_timediff > 10)
		{	
			$update_number = array(	
							'number_id' => $arr_numbers[$i]['number_id'],	
							'is_allocated' => 0							
							);
	
			$updateQry = getUpdateDataString($update_number,'number_id', 'campaign_numbers');	
			updateData($updateQry);	
		}
	}
?>