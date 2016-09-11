<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

require_once('Config.php');

class CampaignManager extends commonObject
{
		public $db_fields;
		function CampaignManager()
		{
			$this->db_fields = array();	
			$this->table_name = "campaign";
		}
		
		function getAllActiveCampaigns($group_id,$user_id,$show_status=1)
		{
			if($group_id != developer_grpid && $group_id != 3)
			{
				if($group_id == 2)
				{
					$arr_user = getData("SELECT user_id FROM users WHERE user_manager = '".$user_id."' and is_active = 1");
					$in_array = array();				
					foreach($arr_user as $k=>$v)
					{
						$in_array[] = $v['user_id'];
					}	
					$arr_data = getData("SELECT * FROM campaign WHERE user_id IN (".implode(',',$in_array).") and status='".$show_status."' and is_active = 1");					
				}
				elseif($group_id == 4)
					$arr_data = getData("SELECT * FROM campaign WHERE user_id = '".$user_id."' and status='".$show_status."' and is_active = 1");	
			}
			else
				$arr_data = getData("SELECT * FROM campaign WHERE status='".$show_status."' and is_active = 1");
				
			return $arr_data;
		}
		
		function getCampaignsDetails($group_id,$user_id,$show_status=1)
		{
			if($group_id != developer_grpid && $group_id != 3)
			{
				if($group_id == 2)
				{
					$arr_user = getData("SELECT user_id FROM users WHERE user_manager = '".$user_id."' and is_active = 1");
					$in_array = array();				
					foreach($arr_user as $k=>$v)
					{
						$in_array[] = $v['user_id'];
					}
					$arr_data = getData("SELECT CAMPAIGN.*, CALL1.call_cnt, CLICKS.clicks_cnt  FROM campaign AS CAMPAIGN LEFT JOIN (SELECT camp_id, count(call_id) AS call_cnt from calls where 1 group by camp_id) as CALL1 ON CAMPAIGN.camp_id = CALL1.camp_id LEFT JOIN (SELECT camp_id, count(mt_id) AS clicks_cnt from clicks where 1 group by camp_id) AS CLICKS ON   CAMPAIGN.camp_id = CLICKS.camp_id  where user_id IN (".implode(',',$in_array).") and CAMPAIGN.status='".$show_status."' and is_active = 1");
					//$arr_data = getData("SELECT * FROM campaign WHERE user_id IN (".implode(',',$in_array).") and status='".$show_status."' and is_active = 1");					
				}
				elseif($group_id == 4)
					$arr_data = getData("SELECT CAMPAIGN.*, CALL1.call_cnt, CLICKS.clicks_cnt  FROM campaign AS CAMPAIGN LEFT JOIN (SELECT camp_id, count(call_id) AS call_cnt from calls where 1 group by camp_id) as CALL1 ON CAMPAIGN.camp_id = CALL1.camp_id LEFT JOIN (SELECT camp_id, count(mt_id) AS clicks_cnt from clicks where 1 group by camp_id) AS CLICKS ON   CAMPAIGN.camp_id = CLICKS.camp_id  where user_id = '".$user_id."' and CAMPAIGN.status='".$show_status."' and is_active = 1");
					//$arr_data = getData("SELECT * FROM campaign WHERE user_id = '".$user_id."' and status='".$show_status."' and is_active = 1");
			}
			else
				//$arr_data = getData("SELECT * FROM campaign WHERE status='".$show_status."' and is_active = 1");
				$arr_data = getData("SELECT CAMPAIGN.*, CALL1.call_cnt, CLICKS.clicks_cnt  FROM campaign AS CAMPAIGN LEFT JOIN (SELECT camp_id, count(call_id) AS call_cnt from calls where 1 group by camp_id) as CALL1 ON CAMPAIGN.camp_id = CALL1.camp_id LEFT JOIN (SELECT camp_id, count(mt_id) AS clicks_cnt from clicks where 1 group by camp_id) AS CLICKS ON   CAMPAIGN.camp_id = CLICKS.camp_id  where CAMPAIGN.status='".$show_status."' and is_active = 1");
			return $arr_data;
		}
		
		function get_allcounts($group_id,$user_id)
		{
			if($group_id != developer_grpid && $group_id != 3)
			{
				if($group_id == 2)
				{
					$arr_user = getData("SELECT user_id FROM users WHERE user_manager = '".$user_id."' and is_active = 1");
					$in_array = array();				
					foreach($arr_user as $k=>$v)
					{
						$in_array[] = $v['user_id'];
					}	
					$active = getOne("SELECT count(*) AS CNT FROM campaign WHERE user_id IN (".implode(',',$in_array).") and status=1 and is_active = 1");
					$arr_counts['active'] = $active;
					$inactive = getOne("SELECT count(*) AS CNT FROM campaign WHERE user_id IN (".implode(',',$in_array).") and status=2 and is_active = 1");
					$arr_counts['inactive'] = $inactive;
					$archived = getOne("SELECT count(*) AS CNT FROM campaign WHERE user_id IN (".implode(',',$in_array).") and status=3 and is_active = 1");
					$arr_counts['archived'] = $archived;					
				}
				elseif($group_id == 4)
					$active = getOne("SELECT count(*) AS CNT FROM campaign WHERE user_id = '".$user_id."' and status=1 and is_active = 1");
					$arr_counts['active'] = $active;
					$inactive = getOne("SELECT count(*) AS CNT FROM campaign WHERE user_id = '".$user_id."' and status=2 and is_active = 1");
					$arr_counts['inactive'] = $inactive;
					$archived = getOne("SELECT count(*) AS CNT FROM campaign WHERE user_id = '".$user_id."' and status=3 and is_active = 1");
					$arr_counts['archived'] = $archived;
			}
			else{
				$active = getOne("SELECT count(*) AS CNT FROM campaign WHERE status=1 and is_active = 1");
				$arr_counts['active'] = $active;
				$inactive = getOne("SELECT count(*) AS CNT FROM campaign WHERE status=2 and is_active = 1");
				$arr_counts['inactive'] = $inactive;
				$archived = getOne("SELECT count(*) AS CNT FROM campaign WHERE status=3 and is_active = 1");
				$arr_counts['archived'] = $archived;
			}
			return $arr_counts;
		}
		
		function getCampaignVariables()
		{
			$this->db_fields['name'] = $_POST['name'];
			$this->db_fields['offer_url'] = $_POST['offer_url'];
			$this->db_fields['description'] = $_POST['description'];
			$this->db_fields['status'] = $_POST['status'];
		}
		
		// for handle incoming call
		function getCampaignDetailsUsingNumber($number)
		{
			//$resultSet = getRow("SELECT campaign.* FROM `calls`,`campaign` WHERE campaign.camp_id = calls.camp_id and CallTo=".$number);
			$number = substr($number,-10);
			$resultSet = getRow("SELECT campaign.* FROM `campaign_numbers`,`campaign` WHERE campaign.camp_id = campaign_numbers.camp_id and campaign_numbers.number LIKE '%".$number."%'"); // and campaign_numbers.is_allocated = 1
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
		function isZipCodeExists($zip_code)		
		{			
			$resultSet = getRow("select * from us_zip where zip='".$zip_code."'");			
			return $resultSet;			
		}
		// for handle incoming call
		function save_call($arr_details,$is_end=0,$is_postback=0)
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
				return $details['mt_id'];
			}
			elseif($is_end == 1)
			{
				$callid = getOne("SELECT call_id FROM calls WHERE mt_id = '".$details['mt_id']."' ORDER BY call_id DESC LIMIT 0,1");
				$arr_call = array(							
							'camp_id' => $details['camp_id'],
							'mt_id' => $details['mt_id'],
							'call_id' => $callid
							);
				if(isset($arr_details['DialCallDuration']))
					$arr_call['CallDuration'] = $arr_details['DialCallDuration'];
				if(isset($arr_details['RecordingUrl']))
					$arr_call['RecordingUrl'] = $arr_details['RecordingUrl'];
				if(isset($arr_details['RecordingSid']))
					$arr_call['RecordingSid'] = $arr_details['RecordingSid'];
				if(isset($arr_details['RecordingDuration']))
					$arr_call['RecordingDuration'] = $arr_details['RecordingDuration'];
                //if($is_postback == 1)
                //    $arr_call['is_postback'] = 1;

				$updateQry = $this->getUpdateDataString($arr_call,'call_id', 'calls');
				updateData($updateQry);
				
				//updateData("DELETE FROM active_number WHERE id = '".$details['id']."'");
				
				$arr_update = array(
							'number' => $arr_details['To'],
							//'is_allocated' => 0,
							'last_used' => date('Y-m-d H:i:s')
							);	
				$updateQry = $this->getUpdateDataString($arr_update,'number', 'campaign_numbers');
				updateData($updateQry);
				
				$results = array('mt_id' => $details['mt_id'], 'call_id' =>$callid);	
				return $results;
			}			
		}
		function postback_hasoffer($call_id)
		{
			$arr_call = array(							
							'is_postback' => 1,
							'call_id' => $call_id
							);
			$updateQry = $this->getUpdateDataString($arr_call,'call_id', 'calls');
			updateData($updateQry);
		}
		function get_postback_options($mt_id)
		{
            //$details = getRow("SELECT id,camp_id, mt_id FROM active_number WHERE number = '".trim($to)."'");
            //return "SELECT id,camp_id, mt_id FROM active_number WHERE number = '".trim($to)."'";
			$options = getRow("SELECT CLICK.mt_id, CLICK.tid, CLICK.camp_id AS camp_id, CAMP.offer_url as offer_url,  CAMP.call_conversion_criteria FROM clicks AS CLICK LEFT JOIN (SELECT camp_id, offer_url, call_conversion_criteria FROM campaign) AS CAMP ON CLICK.camp_id = CAMP.camp_id WHERE CLICK.mt_id ='".$mt_id."'");
			return $options;
		}
        function check_is_postback($mt_id)
        {
            return getOne("SELECT is_postback FROM calls WHERE mt_id = '".$mt_id."'");
        }
		function chk_conversion_days($camp_id,$from_number)
		{
			$chk_conversion = getData("SELECT CALLS.camp_id, CALLS.call_id, CALLS.CallStartTime, CAMP.call_conversion_days FROM calls AS CALLS LEFT JOIN (SELECT camp_id,call_conversion_days FROM campaign WHERE 1) AS CAMP ON CALLS.camp_id = CAMP.camp_id WHERE CALLS.camp_id = '".$camp_id."' AND CALLS.CallFrom = '".$from_number."' and CALLS.is_postback = '1' and DATE(CALLS.CallStartTime) >= DATE_SUB(CURDATE(), INTERVAL CAMP.call_conversion_days day)");
			if(count($chk_conversion) > 0)
				return false;
			else
				return true;
		}		
		
		function saveivrs($arr_data)
		{
			//echo "<pre>"; print_r($arr_data);  //exit;
			// insert campaign related data in campaign table
			if(!isset($arr_data['is_recordcall']) || $arr_data['is_recordcall'] != 1)
				$arr_data['is_recordcall'] = 0;
			$arr_campaign = array(
							'name' => trim($arr_data['name']),
							'offer_url' => trim($arr_data['offer_url']),
							'description' => trim($arr_data['description']),
							'status' => trim($arr_data['status']),
							'extra_number' => trim($arr_data['extra_number']),
							'deallocation_time' => trim($arr_data['deallocation_time']),
							'call_conversion_criteria' => trim($arr_data['call_conversion_criteria']),
							'call_conversion_days' => trim($arr_data['call_conversion_days']),
							'offer_id' => trim($arr_data['offer_id']),
							'ivrs_action_id' => 0,
							//'call_center_number' => preg_replace("/[^0-9]/", '',  trim($arr_data['call_center_number'])),
							'is_recordcall' => trim($arr_data['is_recordcall']),
							'is_active' => 1
							);
			if(isset($arr_data['campaign_id']) && ($arr_data['campaign_id'] != ""))
			{
				$camp_id = $arr_data['campaign_id'];
				$arr_old =  getRow("SELECT ivrs_action_id FROM campaign WHERE camp_id='".$camp_id."'");
				if($arr_old['ivrs_action_id'] != "0")
					updateData("DELETE FROM ivrs_action WHERE ivrs_action_id ='".$arr_old['ivrs_action_id']."'");
						
				$arr_campaign['camp_id'] = $camp_id;
				$updateQry = $this->getUpdateDataString($arr_campaign,'camp_id', 'campaign');
				updateData($updateQry);
			}
			else
			{
				$insertQry = $this->getInsertDataString($arr_campaign, 'campaign');
				updateData($insertQry);
				$camp_id = mysql_insert_id();
			}
			//echo "camp id --- ".$camp_id;
			// for update delete previous entries and insert new
			$get_allivrs = getData("SELECT ivrs_id, ivrs_action_id FROM ivrs WHERE camp_id='".$camp_id."'");
			//echo "SELECT ivrs_id, ivrs_action_id FROM ivrs WHERE camp_id='".$camp_id."'";
			//echo "<pre>"; print_r($get_allivrs);
			updateData("DELETE FROM ivrs WHERE camp_id ='".$camp_id."'");
			foreach($get_allivrs as $k=>$v)
			{
				//echo $v['ivrs_id']."</br>";
				updateData("DELETE FROM ivrs_action WHERE ivrs_id ='".$v['ivrs_id']."'");
			}
			//exit;
			if(!isset($arr_data['details']['is_promt_recording']) || $arr_data['details']['is_promt_recording'] != 1)
					$arr_data['details']['is_promt_recording'] = 0;
			if(!isset($arr_data['details']['is_play_mp3']) || $arr_data['details']['is_play_mp3'] != 1)
					$arr_data['details']['is_play_mp3'] = 0;
			if($arr_data['action_type_id'] == 1 || $arr_data['action_type_id'] == 2 || $arr_data['action_type_id'] == 4)
			{
				$arr_ivrs_action = array(
									'description' => trim($arr_data['details']['description']),							
									'is_promt_recording' => $arr_data['details']['is_promt_recording'],
									'is_play_mp3' => $arr_data['details']['is_play_mp3'],
									'action_type_id' => trim($arr_data['action_type_id']),									
									);
				if(trim($arr_data['details']['file_name']) != '')
					$arr_ivrs_action['file_name'] = trim($arr_data['details']['file_name']);
				if($arr_data['action_type_id'] == 2 || $arr_data['action_type_id'] == 4)
					$arr_ivrs_action['call_forword_no'] = preg_replace("/[^0-9]/", '',  trim($arr_data['details']['call_forword_no']));
				$insertQry = $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
				updateData($insertQry);
				$ivrs_action_id = mysql_insert_id();				
				updateData("UPDATE campaign SET ivrs_action_id='".$ivrs_action_id."' WHERE camp_id='".$camp_id."'");
			}
			elseif($arr_data['action_type_id'] == 3)
			{
				// insert ivrs data in ivrs table
				if(isset($arr_data['details']))
				{	
					//echo "<pre>"; print_r($arr_data['details']); 					
					$arr_ivrs = array(
							'camp_id' => $camp_id,
							'description' => $arr_data['details']['description'],
							'level' => 0,
							'is_promt_recording' => trim($arr_data['details']['is_promt_recording']),
							'is_play_mp3' => $arr_data['details']['is_play_mp3'],
							);
					//echo "<pre>"; print_r($arr_ivrs); exit; 
					if(trim($arr_data['details']['file_name']) != '')
						$arr_ivrs['file_name'] = trim($arr_data['details']['file_name']);
					$insertQry = $this->getInsertDataString($arr_ivrs, 'ivrs');
					updateData($insertQry);
					$ivrs_id = mysql_insert_id();
					
					//echo "<pre>"; print_r($arr_data['details']);
					// insert all keys data in ivrs_action table 
					if(isset($arr_data['details']['keys']))
					{
						$num_key = $arr_data['details']['keys'];
						//echo $num_key;
						for($i=1;$i<=$num_key; $i++)
						{
							if(isset($arr_data['key_'.$i]))
							{	//echo "here"; echo "<pre>"; print_r($arr_data['key_'.$i]); exit;
								if(!isset($arr_data['key_'.$i]['details']['is_promt_recording']) || $arr_data['key_'.$i]['details']['is_promt_recording'] != 1)
									$arr_data['key_'.$i]['details']['is_promt_recording'] = 0;
								if(!isset($arr_data['key_'.$i]['details']['is_play_mp3']) || $arr_data['key_'.$i]['details']['is_play_mp3'] != 1)
									$arr_data['key_'.$i]['details']['is_play_mp3'] = 0;
								$arr_ivrs_action = array(
													'ivrs_id' => $ivrs_id, 
													'key_val' => $i,
													'description' => trim($arr_data['key_'.$i]['details']['description']),
													'action_type_id' => $arr_data['key_'.$i]['details']['action_type_id'],
													'is_promt_recording' => $arr_data['key_'.$i]['details']['is_promt_recording'],
													'is_play_mp3' => $arr_data['key_'.$i]['details']['is_play_mp3']													
													);
								if(trim($arr_data['key_'.$i]['details']['file_name']) != '')
									$arr_ivrs_action['file_name'] = trim($arr_data['key_'.$i]['details']['file_name']);
								
								// if action type is forword to call center
								if($arr_data['key_'.$i]['details']['action_type_id'] == 2 || $arr_data['key_'.$i]['details']['action_type_id'] == 4)
									$arr_ivrs_action['call_forword_no'] =  preg_replace("/[^0-9]/", '',  trim($arr_data['key_'.$i]['details']['call_forword_no'])); 
								
								$insertQry = $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
								updateData($insertQry);
								$ivrs_action_id = mysql_insert_id();
								
								// if action type is ask a question 														
								if($arr_data['key_'.$i]['details']['action_type_id'] == 3)
								{	
									//echo "<pre>"; print_r($arr_ivrs); exit; 
									$arr_ivrs = array(
											'camp_id' => $camp_id,
											'description' => $arr_data['key_'.$i]['details']['description'],
											'level' => 1,
											'is_promt_recording' => trim($arr_data['key_'.$i]['details']['is_promt_recording']),
											'is_play_mp3' => $arr_data['key_'.$i]['details']['is_play_mp3'],													
											'ivrs_action_id' => $ivrs_action_id,
											);
									
									if(trim($arr_data['key_'.$i]['details']['file_name']) != '')
										$arr_ivrs['file_name'] = trim($arr_data['key_'.$i]['details']['file_name']);
									$insertQry = $this->getInsertDataString($arr_ivrs, 'ivrs');
									updateData($insertQry);
									$ivrs_id1 = mysql_insert_id();
									
									$this->save_ivrs_tree($arr_data['key_'.$i]['ivrs'],$camp_id,$ivrs_action_id,1,$ivrs_id1);
								}
								
							}
						}
					}
				}				
			}
			return $camp_id;			
		}
		
		
		function save_ivrs_tree($arr_data,$camp_id,$get_ivrs_action_id,$level,$old_ivrs_id)
		{
			//echo "<pre>"; print_r($arr_data); exit;
			if(isset($arr_data))
			{
				for($i=1; $i<=count($arr_data); $i++)
				{	
					if(isset($arr_data['key_'.$i]))
					{	
						if(!isset($arr_data['key_'.$i]['details']['is_promt_recording']) || $arr_data['key_'.$i]['details']['is_promt_recording'] != 1)
								$arr_data['key_'.$i]['details']['is_promt_recording'] = 0;
						if(!isset($arr_data['key_'.$i]['details']['is_play_mp3']) || $arr_data['key_'.$i]['details']['is_play_mp3'] != 1)
								$arr_data['key_'.$i]['details']['is_play_mp3'] = 0;				
						if($arr_data['key_'.$i]['details']['action_type_id'] == 3)
						{	
							$arr_ivrs_action = array(
												'ivrs_id' => $old_ivrs_id, 
												'key_val' => $i,
												'description' => trim($arr_data['key_'.$i]['details']['description']),
												'action_type_id' => $arr_data['key_'.$i]['details']['action_type_id'],
												'is_promt_recording' => $arr_data['key_'.$i]['details']['is_promt_recording'],
												'is_play_mp3' => $arr_data['key_'.$i]['details']['is_play_mp3']																										
												);
							if(trim($arr_data['key_'.$i]['details']['file_name']) != '')
								$arr_ivrs_action['file_name'] = trim($arr_data['key_'.$i]['details']['file_name']);
							
							// if action type is forword to call center
							if($arr_data['key_'.$i]['details']['action_type_id'] == 2 || $arr_data['key_'.$i]['details']['action_type_id'] == 4)
								$arr_ivrs_action['call_forword_no'] = preg_replace("/[^0-9]/", '',  trim($arr_data['key_'.$i]['details']['call_forword_no']));  
							
							$insertQry = $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
							updateData($insertQry);
							$ivrs_action_id = mysql_insert_id();							
							
							//echo "<pre>"; print_r($arr_ivrs); exit; 
							$arr_ivrs = array(
									'camp_id' => $camp_id,
									'description' => $arr_data['key_'.$i]['details']['description'],
									'level' => ++$level,
									'is_promt_recording' => trim($arr_data['key_'.$i]['details']['is_promt_recording']),
									'is_play_mp3' => trim($arr_data['key_'.$i]['details']['is_play_mp3']),
									'ivrs_action_id' => $ivrs_action_id,
									);
							
							if(trim($arr_data['key_'.$i]['details']['file_name']) != '')
								$arr_ivrs['file_name'] = trim($arr_data['key_'.$i]['details']['file_name']);
							$insertQry = $this->getInsertDataString($arr_ivrs, 'ivrs');
							updateData($insertQry);
							$ivrs_id1 = mysql_insert_id();
							
							$this->save_ivrs_tree($arr_data['key_'.$i]['ivrs'],$camp_id,$ivrs_action_id,$level,$ivrs_id1);
							
						}
						else
						{	
							$arr_ivrs_action = array(
												'ivrs_id' => $old_ivrs_id, 
												'key_val' => $i,
												'description' => trim($arr_data['key_'.$i]['details']['description']),
												'action_type_id' => $arr_data['key_'.$i]['details']['action_type_id'],
												'is_promt_recording' => $arr_data['key_'.$i]['details']['is_promt_recording'],
												'is_play_mp3' => trim($arr_data['key_'.$i]['details']['is_play_mp3']),																									
												);
							if(trim($arr_data['key_'.$i]['details']['file_name']) != '')
								$arr_ivrs_action['file_name'] = trim($arr_data['key_'.$i]['details']['file_name']);
							if($arr_data['key_'.$i]['details']['action_type_id'] == 2 || $arr_data['key_'.$i]['details']['action_type_id'] == 4)
								$arr_ivrs_action['call_forword_no'] = preg_replace("/[^0-9]/", '',  trim($arr_data['key_'.$i]['details']['call_forword_no']));  
							$insertQry = $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
							updateData($insertQry);
							//$ivrs_action_id = mysql_insert_id();
						}
					}
				}
			} // end of if 			
		}
		
		function remove_ivrsfile($incorrect_file)
		{
			$incorrect_file_cond = $this->get_in($incorrect_file,'file_name'); 			
			$updateQry = "UPDATE ivrs SET file_name = '' WHERE ".$incorrect_file_cond; 
			updateData($updateQry);			
			$updateQry = "UPDATE ivrs_action SET file_name = '' WHERE ".$incorrect_file_cond; 
			updateData($updateQry);
		}
		function get_in($arr,$name)
		{
			$in = '';
			foreach($arr as $a)
			{
				$in .= "'".$a."',"; 				
			}		
			$in = trim(trim($in),',');
			$str = $name." IN ( ".$in." )";
			return $str;
		}
				
		function get_campaign($camp_id)
		{
			$arr_data = getRow("SELECT * FROM campaign WHERE camp_id='".$camp_id."'");
			$check_action = getRow("SELECT * FROM ivrs_action WHERE ivrs_action_id='".$arr_data['ivrs_action_id']."'");
			
			if($check_action['action_type_id'] == 1 || $check_action['action_type_id'] == 2 || $check_action['action_type_id'] == 4)
			{	
				$arr_data['details'] = $check_action;
			}
			else
			{	
				$arr_ivrs1 = getRow("SELECT * FROM ivrs WHERE camp_id='".$camp_id."' and level = 0");
				if($arr_ivrs1 != ""){
					$arr_ivrs_action = getData("SELECT ivrs_action_id FROM ivrs_action WHERE ivrs_id='".$arr_ivrs1['ivrs_id']."'");
					
					$arr_data['details'] = $arr_ivrs1;
					if(count($arr_ivrs_action) > 0)
					{
						$arr_data['details']['action_type_id'] = 3;
						$arr_data['details']['keys'] = count($arr_ivrs_action);
					}
				}
			}
			//echo "<pre>"; print_r($arr_data); exit;
			return $arr_data;
		}
		function delete_campaign($camp_id)
		{
			$arr_update = array(
						'camp_id' =>$camp_id,
						'is_active' => 0
						);
			$updateQry = $this->getUpdateDataString($arr_update,'camp_id', 'campaign');
			updateData($updateQry);
		}
		
		/*function get_allivrs($camp_id)
		{
			$arr_data = getRow("SELECT * FROM campaign WHERE camp_id='".$camp_id."'");	
			$check_action = getRow("SELECT * FROM ivrs_action WHERE ivrs_action_id='".$arr_data['ivrs_action_id']."'");	 
			if($check_action['action_type_id'] == 1 || $check_action['action_type_id'] == 2 || $check_action['action_type_id'] == 4)
			{
				$arr_data['details'] = $check_action;
			}
			elseif(!$check_action)
			{			
				$arr_ivrs1 = getRow("SELECT ivrs_id, description, is_promt_recording, file_name FROM ivrs WHERE camp_id='".$camp_id."' and level = 0");
				
				$arr_data['details'] = $arr_ivrs1; 
				if($arr_ivrs1['ivrs_id'])
				{
					$arr_ivrs_action = getData("SELECT * FROM ivrs_action WHERE ivrs_id='".$arr_ivrs1['ivrs_id']."'");
					$arr_data['details']['keys'] = count($arr_ivrs_action);	
					
					if(count($arr_ivrs_action) > 0)
					{
						for($i=1; $i<=count($arr_ivrs_action); $i++)
						{
							$arr_data['key_'.$i]['details'] = $arr_ivrs_action[$i-1];	
							if($arr_ivrs_action[$i-1]['action_type_id'] == 3)
							{
								//echo "<pre>"; print_r($arr_data); echo $i;  exit;
								$arr_data = $this->get_ivrs_tree($arr_ivrs_action[$i-1]['ivrs_action_id'],$i,$arr_data);
								
							}
						}
					}					
				}
			}
			//echo "<pre>"; print_r($arr_data1); exit;
			return $arr_data;
		}
		function get_ivrs_tree($ivrs_action_id,$level,$arr_data)
		{
			$arr1_LastElement = end($arr_data);
			//echo "<pre>"; print_r($arr1_LastElement); echo key($arr1_LastElement); 
			//$arr2_LastElement = end($arr1_LastElement);
			$arr_end = $this->recursiveEndOfArrayFinder($arr_data);
			echo "<pre>"; print_r($arr_end); exit;
			//echo "<pre>"; print_r($arr1_LastElement);
			
			/*if(!is_numeric($level))
				$arr_level = explode('_',$level);
			$str_name = '';
			
			if(!isset($arr_level))
			{
				$str_name .=  'key_'.$level;	
				$str_name1 =  'ivrs';							
			}
			else
			{
				echo "here";
				
				echo "<pre>"; print_r($arr_data);   exit;	
				for($j=0; $j<count($arr_level); $j++)
				{
					$str_name .=  '[ivrs][key_'.$arr_level[$j].']';																
				}
			}
			echo $str_name;	*/		
			
			/*$arr_ivrs = getRow("SELECT * FROM ivrs WHERE ivrs_action_id='".$ivrs_action_id."'");
			$arr_ivrs_action = getData("SELECT * FROM ivrs_action WHERE ivrs_id='".$arr_ivrs['ivrs_id']."'");
			//$arr_append['ivrs']['details'] = $arr_ivrs_action;
			
			//echo "<pre>"; print_r($arr_data); //exit;
			
			if(count($arr_ivrs_action) > 0)
			{				
				$arr_end['details']['keys'] = count($arr_ivrs_action);					
				//echo "<pre>"; print_r($arr_end); echo "<pre>"; print_r($arr_data); 
				//$arr_data['key_'.$level]['details']['keys'] = count($arr_ivrs_action);
					
				for($i=1; $i<=count($arr_ivrs_action); $i++)
				{
					//$test_str = '$arr_data[$str_name][$str_name1]';
					//$test_str['details'] = $arr_ivrs_action[$i-1];	
					//$arr_data['key_'.$level]['ivrs'] = $arr_ivrs_action[$i-1];	
					$arr1_LastElement['ivrs']['details'] = $arr_ivrs_action[$i-1];	
					
					if($arr_ivrs_action[$i-1]['action_type_id'] == 3)
					{
						$this->get_ivrs_tree($arr_ivrs_action[$i-1]['ivrs_action_id'],$level.'_'.$i,$arr_data);
					}					
				}
			}			
			
			//echo "<pre>"; print_r($arr_data);  exit;
			return $arr_data;			
		}

		function recursiveEndOfArrayFinder($multiarr,$str=''){
		
			$listofkeys = array_keys($multiarr);
			
			$lastkey = end($listofkeys);	//echo "<pre>"; print_r($listofkeys); //exit;
			if(is_array($multiarr[$lastkey]) && (count($listofkeys) > 1))
			{
				if($str == '')
					$str = '['.$lastkey.']';
				else
				{
					$str .= '['.$lastkey.']';
				}
				//echo "here"; echo "<pre>"; print_r($multiarr[$lastkey]); 
				$this->recursiveEndOfArrayFinder($multiarr[$lastkey],$str);
			}else{
				
				$arr_details = array(
							'lastkey' => $lastkey,
							'str' => $str
							);
				echo "<pre>"; print_r($arr_details); //exit;
				return $arr_details;
			}
		}*/  
		
		function save_numbers($camp_id , $numbers)
		{
			for($i=0;$i<count($numbers); $i++)
			{				
				$check_exist = getData("SELECT number_id FROM campaign_numbers WHERE camp_id='".$camp_id."' and number_sid ='".$numbers[$i]['sid']."' and is_active= 1");
				$arr_number = array(
									'camp_id' => $camp_id,
									'number' => $numbers[$i]['number'],
									'friendly_name' => $numbers[$i]['friendly_name'],
									'number_sid' => $numbers[$i]['sid'],
									'is_tollfree' => $numbers[$i]['is_tollfree'],									
									'is_active' => 1
								);
				if(count($check_exist) > 0)
				{
					$arr_number['number_id'] = $check_exist[0]['number_id'];
					$updateQry = $this->getUpdateDataString($arr_number,'number_id', 'campaign_numbers');
					updateData($updateQry);
				}
				else
				{					
					$insertQry = $this->getInsertDataString($arr_number, 'campaign_numbers');
					updateData($insertQry);
				}
			}				
		}
		
		function release_number($arr_numbers)
		{
			//echo "<pre>"; print_r($arr_numbers); exit;
			foreach($arr_numbers as $k=>$v)
			{
				$arr_update = array(
							'number_id' => $v['number_id'],
							'is_active' => 0
							);
				$updateQry = $this->getUpdateDataString($arr_update,'number_id', 'campaign_numbers');
				updateData($updateQry);
			}			
		}
		
		function get_numbers_details($arr_data)
		{
			$arr_details = array();
			foreach($arr_data['numbers'] as $k=>$v)
			{
				$number_details = getRow("select * from campaign_numbers where camp_id = '".$arr_data['campaign_id']."' and number ='".$v."'");				
				$arr_number = array(
								'number_id' => $number_details['number_id'],
								'is_active' => 0,
								);
				$updateQry = $this->getUpdateDataString($arr_number,'number_id', 'campaign_numbers');
				updateData($updateQry);
				$arr_details[] = $number_details;
			}
			return $arr_details;
		}
		
		function get_numbers($camp_id)
		{
			$arr_numbers = getData("select number_id, number, friendly_name from campaign_numbers where camp_id='".$camp_id."' and is_active =1");
			return $arr_numbers;
		}
		
		function save_step3($arr_data)
		{
			$deleteQry = "DELETE FROM subid_meta WHERE camp_id = '".$arr_data['campaign_id']."'";
			updateData($deleteQry);
			for($i=1;$i<=5;$i++)
			{	
				if($arr_data["subid".$i] != "")
				{
					$insert_data = array(
							'camp_id' => $arr_data['campaign_id'],
							'subid' => 'subid'.$i,
							'value' => $arr_data['subid'.$i],							
							);						
					$insertQry = $this->getInsertDataString($insert_data, 'subid_meta');
					updateData($insertQry);
				}
			}			
			//$updateQry = $this->getUpdateDataString($arr_data,'camp_id', 'campaign');
			//updateData($updateQry);			
		}
		function get_subids($camp_id)
		{
			$arr_subid = array();
			for($i=1;$i<=5;$i++)
			{
				$subid_detail = getRow("select * from subid_meta where camp_id='".$camp_id."' and subid = 'subid".$i."'");
				$arr_subid[] = $subid_detail; 
				
			}
			return $arr_subid;
		}
		
		function check_subid_exist($camp_id, $sub_id)
		{
			$chk_exist = getData("SELECT camp_id FROM campaign WHERE camp_id !='".$camp_id."' AND subId = '".$sub_id."'");
			if(count($chk_exist) > 0)
				return true;
			else
				return false;
		}
        function check_offer_id_exist($offer_id,$edit_id='')
        {
			$str_attach = "";
			if($edit_id != "")
				$str_attach = " and camp_id != '".$edit_id."'";
            $chk_exist = getData("SELECT * FROM campaign WHERE offer_id = '".$offer_id."'".$str_attach);
            if(count($chk_exist) > 0)
                return true;
            else
                return false;
        }
		
		function getAllManager(){
			return getData("select * from users where user_group=2 and is_active=1");
		}
		
		function getAllUserOfManager($user_id){
			if($user_id != 0)
				return getData("select * from users where user_manager='".$user_id."' and is_active=1");
			else
				return false;
		}
		
		function getManagerOfUser($user_id)
		{
			return getRow("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `users` WHERE user_id ='".$user_id."'  ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id`");	
		}
		
		function getCompoundStatsCampaigns($camp_id){
			$stats=array();
			if(is_array($camp_id)){
				if(count($camp_id)>=1){
					$transactions=getOne("SELECT count(`mt_id`) FROM `clicks` where camp_id in (".implode(',',$camp_id).")");
					$calls=getOne("SELECT count(`call_id`) FROM `calls` where camp_id in (".implode(',',$camp_id).") and is_postback = '1'");					
				}
			}
			else
			{
				$transactions=getOne("SELECT count(`mt_id`) FROM `clicks` where camp_id = '".$camp_id."'");
				$calls=getOne("SELECT count(`call_id`) FROM `calls` where camp_id = '".$camp_id."' and is_postback = '1'");
			}
			$stats['transactions']=$transactions;
					$stats['calls']=$calls;
			return $stats;
		}
		
		function get_calls($camp_id)
		{
			$arr_calls = getData("select * from calls where camp_id='".$camp_id."'");
			return $arr_calls;
		}
		function get_clicks($camp_id)
		{
			$arr_calls = getData("select * from clicks where camp_id='".$camp_id."'");
			return $arr_calls;
		}
		function get_click_location_details($mt_id)
		{
			$arr_click_location = getRow("select * from click_locations where mt_id='".$mt_id."'");
			return $arr_click_location;
		}
		
		function get_click_details($mt_id)
		{
			$arr_call = getRow("select * from clicks where mt_id='".$mt_id."'");
			return $arr_call;
		}
		
		function get_call_details($call_id)
		{
			$arr_call = getRow("select * from calls where call_id='".$call_id."'");
			return $arr_call;
		}
		
		function get_survey_details($mt_id){
			$arr_survey = getData("select * from survey_data where mt_id='".$mt_id."'");			
			return $arr_survey;
		}
		function get_survey_info($mt_id)
		{
			$arr_survey_info = getRow("select name,address,email from clicks where mt_id='".$mt_id."'");
			return $arr_survey_info;
		}
		
		function get_campaign_details($camp_id)
		{
			$camp_details = getRow("select camp_id, name from campaign where camp_id='".$camp_id."'");
			return $camp_details;
		}
		
		function get_device_details($mt_id)
		{
			$device = getRow("select * from devices where mt_id='".$mt_id."'");
			return $device;
		}
		
		function get_mail_admin()
		{
			$arr_user = getData("select * from users where user_group='3'");
			return $arr_user;
		}
		
		function getCampaignStats($camp_id,$from_date,$to_date)
		{
			$stats=array();
			if(is_array($camp_id)){
				if(count($camp_id)>=1){
					foreach($camp_id as $camp){
						$stats[$camp]['transactions']=0;
						$stats[$camp]['calls']=0;
					}
					$transactions=getData("SELECT count(`mt_id`) as tr_count,camp_id FROM `clicks` where camp_id in (".implode(',',$camp_id).") group by `camp_id`");
					foreach($transactions as $transaction){
						$stats[$transaction['camp_id']]['transactions']=$transaction['tr_count'];
					}
					$calls=getData("SELECT count(`call_id`) as call_count,camp_id FROM `calls` where camp_id in (".implode(',',$camp_id).") group by `camp_id`");
					foreach($calls as $call){
						$stats[$call['camp_id']]['calls']=$call['call_count'];
					}
				}else{
					return array();
				}
			}
			else
			{
				$stats['details'] = getOne("SELECT * FROM campaign where camp_id='".$camp_id."'");
				$stats['transactions'] = getOne("SELECT count(`mt_id`) FROM `clicks` where camp_id='".$camp_id."'");
				$stats['calls'] = getOne("SELECT count(`call_id`) FROM `calls` where camp_id='".$camp_id."'");
				
			}
			return $stats;
		}
		
		function get_reportdata($camp_id,$cond,$from,$for='call',$start_date='',$end_date='') // $for = call or click
		{
			/*$arr_data = array();
			/// to get all call data
			
			// get todays data
			$arr_cond1 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and and DATE(CallStartTime) = DATE(NOW()) group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['1'] = $arr_cond1;
			
			// get yesterdays data
			$arr_cond2 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and DATE(CallStartTime) = SUBDATE(CURDATE(),INTERVAL 1 DAY)  group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['2'] = $arr_cond2;
			
			// get last 7 days data
			$arr_cond3 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and DATE(CallStartTime) >= SUBDATE(CURDATE(),INTERVAL 7 DAY) and DATE(CallStartTime) <=  DATE(NOW()) group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['3'] = $arr_cond3;
			
			// get last 30 days data
			$arr_cond4 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and DATE(CallStartTime) >= SUBDATE(CURDATE(),INTERVAL 30 DAY) and DATE(CallStartTime) <=  DATE(NOW()) group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['4'] = $arr_cond4;
			
			// get last month data
			$arr_cond5 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and YEAR(CallStartTime) = YEAR(CURRENT_DATE - INTERVAL 1 MONTH) AND MONTH(CallStartTime) = MONTH(CURRENT_DATE - INTERVAL 1 MONTH) group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['5'] = $arr_cond5;
			
			// get this year data
			$arr_cond6 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and YEAR(CallStartTime) = YEAR(CURDATE()) group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['6'] = $arr_cond6;
			
			// get last quarter data
			$arr_cond7 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and QUARTER(CallStartTime) = QUARTER(NOW()) and YEAR(CallStartTime)  = YEAR(NOW()) group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['7'] = $arr_cond7;
			
			// get all years data
			$arr_cond8 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['8'] = $arr_cond8;
			
			// custom date data
			$arr_cond9 = getData("SELECT camp_id, call_id, count(call_id) as call_count, UNIX_TIMESTAMP(CallStartTime) AS MYTIMESTAMP FROM calls where camp_id ='".$camp_id."' and CallStartTime BETWEEN '".$start_date."' AND '".$end_date."' group by CallStartTime ORDER BY camp_id DESC");
			$arr_data['calls']['9'] = $arr_cond9;*/
			
				$duration=0;
				$condition="";
				if($for == 'call')
				{
					$table_name = 'calls';
					$coloum_name = 'CallStartTime';
					$count_name = 'call_id';
				}
				else
				{
					$table_name = 'clicks';
					$coloum_name = 'click_time';
					$count_name = 'mt_id';
				}
				switch($cond){
					case '1':
						$condition =" DATE(".$coloum_name.") = DATE(NOW()) ";		
					break;
					
					case '2':
						$condition =" DATE(".$coloum_name.") = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
					break;
					
					case '3':
						$condition=" DATE(".$coloum_name.") >= SUBDATE(CURDATE(),INTERVAL 7 DAY) and DATE(".$coloum_name.") <=  DATE(NOW()) ";
					break;
					
					case '4':
						$condition=" DATE(".$coloum_name.") >= SUBDATE(CURDATE(),INTERVAL 30 DAY) and DATE(".$coloum_name.") <=  DATE(NOW()) ";
					break;
					
					case '5':
						$condition=" MONTH(".$coloum_name.") = MONTH(CURDATE()) ";
					break;
					
					case '6':
						$condition=" YEAR(".$coloum_name.") = YEAR(CURDATE()) ";
					break;
					
					case '7':
						$condition=" QUARTER(".$coloum_name.") = QUARTER(NOW()) and YEAR(".$coloum_name.")  = YEAR(NOW()) ";
					break;
					
					case '8':
						$condition="1";
					break;
					
					case '9':
						$condition=" DATE(".$coloum_name.") >= '".$start_date."' AND DATE(".$coloum_name.") <= '".$end_date."' ";
					break;
					
				}
				if($from == -1) // for dashboard report
					$condition .= " and camp_id in (".((count($camp_id)>0)?implode(',',$camp_id):"''").") group by ".$coloum_name;
				elseif($from == 0) // for multi campaign report
					$condition .= " and camp_id in (".((count($camp_id)>0)?implode(',',$camp_id):"''").") group by camp_id,".$coloum_name;
				else // for campaignwise report 
					$condition .= " and camp_id ='".$camp_id."' group by ".$coloum_name;
				
				$report_data = getData("SELECT camp_id, count(".$count_name.") as call_count, UNIX_TIMESTAMP(".$coloum_name.")*1000 AS call_time FROM ".$table_name." where ".$condition." ORDER BY ".$coloum_name." ASC");
				//echo "SELECT camp_id, count(".$count_name.") as call_count, UNIX_TIMESTAMP(".$coloum_name.")*1000 AS call_time FROM ".$table_name." where ".$condition." ORDER BY ".$coloum_name." ASC"; exit;
				//echo "report_data"."<pre>"; print_r($report_data); 
				if($from == -1) 
				{
					$result_array1 ="";
					//foreach($report_data as $camp_data)
					for($i=0;$i<count($report_data); $i++)
					{	
						$str = '';				
						if($i!=0)
							$str = ',';				
						//$result_array .=",[{$camp_data['call_time']},{$camp_data['call_count']}]";					
						$result_array1 .= $str."[{$report_data[$i]['call_time']},{$report_data[$i]['call_count']}]";					
					}
					$result_array['name'] = $for;
					$result_array['data'] = $result_array1;
					
				}
				else
				{
					$result_array=array();
					foreach($report_data as $camp_data){
						if(isset($result_array[$camp_data['camp_id']])){
							$result_array[$camp_data['camp_id']].=",[{$camp_data['call_time']},{$camp_data['call_count']}]";
						}else{
							$result_array[$camp_data['camp_id']]="[{$camp_data['call_time']},{$camp_data['call_count']}]";
						}
					}				
					//echo "first array"."<pre>"; print_r($result_array); 
					//$result_array = $report_data
					if($from == 0)
					{
						foreach($camp_id as $camp){
							if(!array_key_exists($camp,$result_array)){
								$result_array[$camp]="[]";
							}
						}
					}
					foreach($result_array as $key=>$value){
						$camp_details = $this->get_campaign_details($key);
						$result_array[$key]=array('name'=>$camp_details['name'],'data'=>$value);
					}
				}
				//echo "<pre>"; print_r($result_array); 
				return $result_array;			
		}
		
		function get_reporttable_data($camp_id,$cond,$from,$for='call',$start_date='',$end_date='') // $for = call or click
		{
			$duration=0;
			$condition="";
			if($for == 'call')
			{
				$table_name = 'calls';
				$coloum_name = 'CallStartTime';
				$count_name = 'call_id';
			}
			else
			{
				$table_name = 'clicks';
				$coloum_name = 'click_time';
				$count_name = 'mt_id';
			}
			switch($cond){
				case '1':
					$condition =" DATE(".$coloum_name.") = DATE(NOW()) ";		
				break;
				
				case '2':
					$condition =" DATE(".$coloum_name.") = SUBDATE(CURDATE(),INTERVAL 1 DAY)";
				break;
				
				case '3':
					$condition=" DATE(".$coloum_name.") >= SUBDATE(CURDATE(),INTERVAL 7 DAY) and DATE(".$coloum_name.") <=  DATE(NOW()) ";
				break;
				
				case '4':
					$condition=" DATE(".$coloum_name.") >= SUBDATE(CURDATE(),INTERVAL 30 DAY) and DATE(".$coloum_name.") <=  DATE(NOW()) ";
				break;
				
				case '5':
					$condition=" MONTH(".$coloum_name.") = MONTH(CURDATE()) ";
				break;
				
				case '6':
					$condition=" YEAR(".$coloum_name.") = YEAR(CURDATE()) ";
				break;
				
				case '7':
					$condition=" QUARTER(".$coloum_name.") = QUARTER(NOW()) and YEAR(".$coloum_name.")  = YEAR(NOW()) ";
				break;
				
				case '8':
					$condition="1";
				break;
				
				case '9':
					$condition=" DATE(".$coloum_name.") >= '".$start_date."' AND DATE(".$coloum_name.") <= '".$end_date."' ";
				break;
				
			}
			if($from == 0)
				$condition .= " and camp_id in (".((count($camp_id)>0)?implode(',',$camp_id):"''").")";
			else
				$condition .= " and camp_id ='".$camp_id."'";
			
			$report_data = getData("SELECT * FROM ".$table_name." where ".$condition." ORDER BY ".$coloum_name." ASC");
			
			if($from == 0){
				$all_camps=$this->getAllActiveCampaigns($_SESSION['user_main_group'],$_SESSION['user_id']);
				$all_camps_names=array();
				foreach($all_camps as $camp){
					$all_camps_names[$camp['camp_id']]=$camp['name'];
				}
				for($i=0;$i<count($report_data);$i++){
					$report_data[$i]['campaign_name']=$all_camps_names[$report_data[$i]['camp_id']];
				}
//				echo "<pre>";print_r($report_data);
			}
			return $report_data;
		}
		
		function get_numbersdetails()
		{
			$arr_data = getData("SELECT CAMP_NUM.number_id, CAMP_NUM.camp_id, CAMP_NUM.friendly_name, CAMP_NUM.last_used, CAMP.name, NUM_CNT.total_no, DATEDIFF(NOW(),CAMP_NUM.last_used) AS day_diff FROM campaign_numbers AS CAMP_NUM LEFT JOIN (SELECT name, camp_id FROM campaign) AS CAMP  ON CAMP_NUM.camp_id = CAMP.camp_id LEFT JOIN (SELECT COUNT(number_id) AS total_no, camp_id FROM campaign_numbers WHERE 1 GROUP BY camp_id) AS NUM_CNT  ON NUM_CNT.camp_id = CAMP.camp_id WHERE CAMP_NUM.is_active = 1");
			return $arr_data;
		}
		
		function get_numbersid($number_id)
		{
			$num_sid = getOne("SELECT number_sid FROM campaign_numbers WHERE number_id = '".$number_id."'");
			return $num_sid;
		}
		
		function save_device($arr_details)
		{
			$insertQry = $this->getInsertDataString($arr_details, 'devices');
			updateData($insertQry);
		}
		
		function save_maxmind_details($arr_details)
		{
			$insertQry = $this->getInsertDataString($arr_details, 'click_locations');
			updateData($insertQry);
		}
		
		function get_available_number($campaign_id)
		{
			$arr_avail_num = getData("SELECT * FROM campaign_numbers WHERE camp_id ='".$campaign_id."' and is_allocated ='0' and is_active = '1' order by last_used ASC");
			return $arr_avail_num;
		}
		
}
?>