<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

require_once('Config.php');

class IvrsManager extends commonObject
{
	public $db_fields;
	function CampaignManager()
	{
		$this->db_fields = array();	
		$this->table_name = "campaign";
	}
	// for handle incoming call		
	function isZipCodeExists($zip_code)		
	{			
		$resultSet = getRow("select * from us_zip where zip='".$zip_code."'");			
		return $resultSet;			
	}
	// for handle incoming call
	function getCampaignDetailsUsingNumber($number)
	{
		//$resultSet = getRow("SELECT campaign.* FROM `calls`,`campaign` WHERE campaign.camp_id = calls.camp_id and CallTo=".$number);
		$number = substr($number,-10);
		$resultSet = getRow("SELECT campaign.* FROM `campaign_numbers`,`campaign` WHERE campaign.camp_id = campaign_numbers.camp_id and campaign_numbers.number LIKE '%".$number."%' and campaign_numbers.is_allocated = 1");
		return $resultSet;
	}
	
	// for handle incoming call - used to get action type id
	function getActionTypeId($ivrs_action_id){
		$resultSet = getRow("select * from ivrs_action where ivrs_action_id=".$ivrs_action_id);
		return $resultSet;	
	}
	
	// for handle incoming call
	function getDetailsOfIvrs($campaign_id)
	{
		$resultSet = getRow("select * from ivrs where camp_id='".$campaign_id."' and level=0");
		return $resultSet;	
	}
	
	// for handle incoming call
	function getLocation($called_number)
	{			//echo "SELECT `city_name` FROM `click_locations`,active_number WHERE click_locations.mt_id = active_number.mt_id and active_number.number = '+".$called_number."'";
		$resultSet = getRow("SELECT `city_name` FROM `click_locations`,active_number WHERE click_locations.mt_id = active_number.mt_id and active_number.number = '".$called_number."'");				
		return $resultSet;
	}
	// for handle incoming call		
	function getMtIdOfLocation($called_number){			
		$result = getOne("select mt_id from active_number where number='".$called_number."'");
		return $result;		
	}				
	
	// for handle incoming call		
	function getPostalCode($mt_id)		
	{			
		$postal_code = getOne("select postal_code from click_locations where mt_id='".$mt_id."'");			
		updateData("update click_locations set old_postal_code='".$postal_code."',given_by_user=1 where mt_id='".$mt_id."'");		
	}		
	
	// for handle incoming call		
	function updateNewPostalCode($valid_zip,$mt_id)		
	{			
		updateData("update click_locations set postal_code='".$valid_zip['zip']."', city_name='".$valid_zip['primary_city']."', area_code='".$valid_zip['area_codes']."', latitude='".$valid_zip['latitude']."', longitude='".$valid_zip['longitude']."', country_code='".$valid_zip['country']."', country_name='".$valid_zip['country_name']."', region_name='', metro_code='', continent_code='', country_confidence='', city_confidence='', region_confidence='', postal_confidence='' where mt_id='".$mt_id."'");		
	}		
	// for handle incoming call - check wether pressed key digit valid or not
	function check_key($capaign_id,$level,$digit)
	{
		//echo $capaign_id."--".$level."--".$digit;
		$resultSet = array();
		$ivrs_id = getOne("select ivrs_id from ivrs where camp_id='".$capaign_id."' and level='".$level."'");
		if($ivrs_id != ""){
			$resultSet = getRow("select * from ivrs_action where ivrs_id='".$ivrs_id."' and key_val='".$digit."'");
		}
		return $resultSet;
	}
	// for handle incoming call
	function save_call($arr_details,$is_end=0)
	{
		$details = getRow("SELECT id,camp_id, mt_id FROM active_number WHERE number = '".$arr_details['To']."'");
		if($is_end == 0)
		{	
			$arr_call = array(
						'CallSid' => $arr_details['CallSid'],
						'AccountSid' => $arr_details['AccountSid'],
						'CallFrom' => $arr_details['From'],
						'CallTo' => $arr_details['To'],
						'Direction' => $arr_details['Direction'],
						'ForwardedFrom' => $arr_details['ForwardedFrom'],
						'FromCity' => $arr_details['FromCity'],
						'FromState' => $arr_details['FromState'],
						'FromZip' => $arr_details['FromZip'],
						'FromCountry' => $arr_details['FromCountry'],
						'ToCity' => $arr_details['ToCity'],
						'ToState' => $arr_details['ToState'],
						'ToZip' => $arr_details['ToZip'],
						'ToCountry' => $arr_details['ToCountry'],							
						'CallStartTime' => date('Y-m-d H:i:s'),
						'camp_id' => $details['camp_id'],
						'mt_id' => $details['mt_id']
						);
			$insertQry = $this->getInsertDataString($arr_call, 'calls');
			updateData($insertQry);	
		}
		elseif($is_end == 1)
		{
			$arr_call = array(							
						'camp_id' => $details['camp_id'],
						'mt_id' => $details['mt_id']
						);
			if(isset($arr_details['CallDuration']))
				$arr_call['CallDuration'] = $arr_details['CallDuration'];
			if(isset($arr_details['RecordingUrl']))
				$arr_call['RecordingUrl'] = $arr_details['RecordingUrl'];
			if(isset($arr_details['RecordingSid']))
				$arr_call['RecordingSid'] = $arr_details['RecordingSid'];
			if(isset($arr_details['RecordingDuration']))
				$arr_call['RecordingDuration'] = $arr_details['RecordingDuration'];
							
			$updateQry = $this->getUpdateDataString($arr_call,'mt_id', 'calls');
			updateData($updateQry);
			
			updateData("DELETE FROM active_number WHERE id = '".$details['id']."'");
			
			$arr_update = array(
						'number' => $arr_details['To'],
						'is_allocated' => 0,
						'last_used' => date('Y-m-d H:i:s')
						);	
			$updateQry = $this->getUpdateDataString($arr_update,'number', 'campaign_numbers');
			updateData($updateQry);	
			//return $arr_call;
		}
		return $details['mt_id'];
	}
	
	
}
?>