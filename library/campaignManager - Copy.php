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
		
		function getAllActiveCampaigns()
		{
			return getData("SELECT * FROM campaign WHERE campaign_status='active'");	
		}
		
		function getCampaignVariables()
		{
			$this->db_fields['name'] = $_POST['name'];
			$this->db_fields['offer_url'] = $_POST['offer_url'];
			$this->db_fields['description'] = $_POST['description'];
			$this->db_fields['status'] = $_POST['status'];
		}
		
		function saveivrs($arr_data)
		{
			echo "<pre>"; print_r($arr_data); 
			// insert campaign related data in campaign table
			if(!isset($arr_data['is_recordcall']) || $arr_data['is_recordcall'] != 1)
				$arr_data['is_recordcall'] = 0;
			$arr_campaign = array(
							'name' => trim($arr_data['name']),
							'offer_url' => trim($arr_data['offer_url']),
							'description' => trim($arr_data['description']),
							'status' => trim($arr_data['status']),
							'is_recordcall' => trim($arr_data['is_recordcall']),							
							'is_active' => 1
							);
			//echo "<pre>"; print_r($arr_campaign); exit;
			if(isset($arr_data['campaign_id']) && ($arr_data['campaign_id'] != ""))
			{
				$camp_id = $arr_data['campaign_id'];
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
			
			if(!isset($arr_data['details']['is_promt_recording']) || $arr_data['details']['is_promt_recording'] != 1)
					$arr_data['details']['is_promt_recording'] = 0;
			if($arr_data['action_type_id'] == 1 || $arr_data['action_type_id'] == 2 || $arr_data['action_type_id'] == 4)
			{				
				$arr_ivrs_action = array(
									'description' => trim($arr_data['details']['description']),									
									'is_promt_recording' => $arr_data['details']['is_promt_recording'],
									'action_type_id' => trim($arr_data['action_type_id'])									
									);
				//if($arr_data['details']['file_name'] != '')
				//	$arr_ivrs_action['file_name'] = trim($arr_data['details']['file_name']);
				if($arr_data['action_type_id'] == 2)
					$arr_ivrs_action['call_forword_no'] = trim($arr_data['details']['call_forword_no']);
				$insertQry = $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
				updateData($insertQry);
				$ivrs_action_id = mysql_insert_id();				
				updateData("UPDATE campaign SET ivrs_action_id='".$ivrs_action_id."' WHERE camp_id='".$camp_id."'");
			}
			elseif($arr_data['action_type_id'] == 3)
			{
				// insert ivrs data in ivrs table
				if(isset($arr_data['details']))
				{	//echo "<pre>"; print_r($arr_data['details']); 
					//var_dump($arr_data);
					$arr_ivrs = array(
							'camp_id' => $camp_id,
							'description' => $arr_data['details']['description'],
							'level' => 0,
							'is_promt_recording' => trim($arr_data['details']['is_promt_recording']),
							);
					//echo "<pre>"; print_r($arr_ivrs); exit; 
					//if($arr_data['details']['file_name'] != ''
					//	$arr_ivrs['file_name'] = trim($arr_data['details']['file_name']);
					$insertQry = $this->getInsertDataString($arr_ivrs, 'ivrs');
					updateData($insertQry);
					$ivrs_id = mysql_insert_id();
					//echo $ivrs_id; exit;
					// insert all keys data in ivrs_action table 
					if(isset($arr_data['details']['keys']))
					{
						$num_key = $arr_data['details']['keys'];
						for($i=1;$i<=$num_key; $i++)
						{
							if(isset($arr_data['key_'.$i]))
							{
								if(!isset($arr_data['key_'.$i]['details']['is_promt_recording']) || $arr_data['key_'.$i]['details']['is_promt_recording'] != 1)
									$arr_data['key_'.$i]['details']['is_promt_recording'] = 0;
								$arr_ivrs_action = array(
													'ivrs_id' => $ivrs_id, 
													'key_val' => $i,
													'description' => trim($arr_data['key_'.$i]['details']['description']),
													'action_type_id' => $arr_data['key_'.$i]['details']['action_type_id'],
													'is_promt_recording' => $arr_data['key_'.$i]['details']['is_promt_recording']													
													);
								//if($arr_data['key_'.$i]['details']['file_name'] != '')
								//	$arr_ivrs_action['file_name'] = trim($arr_data['key_'.$i]['details']['file_name']);
								
								// if action type is forword to call center
								if($arr_data['key_'.$i]['details']['action_type_id'] == 2)
									$arr_ivrs_action['call_forword_no'] = trim($arr_data['key_'.$i]['details']['call_forword_no']);
								
								$insertQry = $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
								updateData($insertQry);
								$ivrs_action_id = mysql_insert_id();
								
								// if action type is ask a question 
														
								if($arr_data['key_'.$i]['details']['action_type_id'] == 3)
								{
									//for($j=1; $j<=count($arr_data['key_'.$i]['ivrs']); $j++)
										$this->save_ivrs_tree($arr_data['key_'.$i]['ivrs'],$camp_id,$ivrs_action_id,1);
								}
								
							}
						}
					}
				}				
			}
			return $camp_id;			
		}
		
		function save_ivrs_tree($arr_data,$camp_id,$ivrs_action_id,$level)
		{
			echo "<pre>"; print_r($arr_data); echo $ivrs_action_id; exit;
			if(isset($arr_data['details']))
			{
				
				if(!isset($arr_data['details']['is_promt_recording']) || $arr_data['details']['is_promt_recording'] != 1)
					$arr_data['details']['is_promt_recording'] = 0;
									
				$arr_ivrs = array(
						'camp_id' => $camp_id,
						'description' => trim($arr_data['details']['description']),
						'level' => $level,
						'is_promt_recording' => trim($arr_data['details']['is_promt_recording']),
						//'num_keys' => $num_key,
						'ivrs_action_id' => $ivrs_action_id
						);
				//if($arr_data['details']['is_promt_recording'] == 1)
				//	$arr_ivrs['file_name'] = trim($arr_data['details']['file_name']);
				$insertQry = $this->getInsertDataString($arr_ivrs, 'ivrs');
				updateData($insertQry);
				$ivrs_id = mysql_insert_id();
				
				// insert all keys data in ivrs_action table
				/*if($arr_data['details']['action_type_id'] == 3)
				{*/
					$num_key = $arr_data['details']['keys'];				
					for($i=1;$i<=$num_key; $i++)
					{
						if(isset($arr_data['key_'.$i]))
						{
							if(!isset($arr_data['key_'.$i]['details']['is_promt_recording']) || $arr_data['key_'.$i]['details']['is_promt_recording'] != 1)
								$arr_data['key_'.$i]['details']['is_promt_recording'] = 0;
							$arr_ivrs_action = array(
												'ivrs_id' => $ivrs_id, 
												'key_val' => $i,
												'description' => trim($arr_data['key_'.$i]['details']['description']),
												'action_type_id' => $arr_data['key_'.$i]['details']['action_type_id'],
												'is_promt_recording' => $arr_data['key_'.$i]['details']['is_promt_recording'],
												'file_name' => trim($arr_data['key_'.$i]['details']['file_name'])
												);
							if($arr_data['key_'.$i]['details']['action_type_id'] == 2)
								$arr_ivrs_action['call_forword_no'] = trim($arr_data['key_'.$i]['details']['call_forword_no']);
							
							$insertQry =  $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
							updateData($insertQry);
							$ivrs_action_id = mysql_insert_id();
							
							if($arr_data['key_'.$i]['details']['action_type_id'] == 3)
							{
								$this->save_ivrs_tree($arr_data['key_'.$i]['ivrs'],$camp_id,$ivrs_action_id,$level++);
							}
						}
					}
				/*}
				else
				{
					$arr_ivrs_action = array(
												'ivrs_id' => $ivrs_id, 
												'key_val' => $i,
												'description' => trim($arr_data['key_'.$i]['details']['description']),
												'action_type_id' => $arr_data['key_'.$i]['details']['action_type_id'],
												'is_promt_recording' => $arr_data['key_'.$i]['details']['is_promt_recording'],
												'file_name' => trim($arr_data['key_'.$i]['details']['file_name'])
												);
							if($arr_data['key_'.$i]['details']['action_type_id'] == 2)
								$arr_ivrs_action['call_forword_no'] = trim($arr_data['key_'.$i]['details']['call_forword_no']);
							
							$insertQry =  $this->getInsertDataString($arr_ivrs_action, 'ivrs_action');
							updateData($insertQry);			
					//updateData("UPDATE campaign SET ivrs_action_id='".$ivrs_action_id."' WHERE camp_id='".$camp_id."'");
				}*/
				
			}
			////////////////////////////////////////////////////////
		}
		
		function get_ivrs($camp_id)
		{
			$arr_data = getRow("SELECT * FROM campaign WHERE camp_id='".$camp_id."'");	
			$check_action = getRow("SELECT * FROM ivrs_action WHERE ivrs_action_id='".$arr_data['ivrs_action_id']."'");	 
			if($check_action['action_type_id'] == 1 || $check_action['action_type_id'] == 2 || $check_action['action_type_id'] == 4)
			{
				$arr_data['details'] = $check_action;
			}
			elseif($check_action['action_type_id'] == 3)
			{			
				$arr_ivrs1 = getRow("SELECT * FROM ivrs WHERE camp_id='".$camp_id."' and level = 0");
				$arr_data['ivrs']['details'] = $arr_ivrs1;
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
								$arr_data = $this->get_ivrs_tree($arr_ivrs_action[$i-1]['ivrs_action_id'],$arr_data['ivrs']['key_'.$i]);
							}
						}
					}
				}
			}
			return $arr_data;
		}
		function get_ivrs_tree($ivrs_action_id,$arr_append)
		{
			$arr_ivrs_action = getRow("SELECT * FROM ivrs WHERE ivrs_action_id='".$ivrs_action_id."'");
			$arr_append['ivrs']['details'] = $arr_ivrs_action;
			
			$arr_append['details']['keys'] = count($arr_ivrs_action);
			if(count($arr_ivrs_action) > 0)
			{
				for($i=1; $i<=count($arr_ivrs_action); $i++)
				{
					$arr_append['key_'.$i]['details'] = $arr_ivrs_action[$i-1];
					if($arr_ivrs_action[$i-1]['action_type_id'] == 3)
					{
						$this->get_ivrs_tree($arr_ivrs_action[$i-1]['ivrs_action_id'],$arr_append);
					}
					else
					{
						return $arr_append;
					}
				}
			}
			else
			{
				return $arr_append;
			}			
		}
		
		function save_numbers($camp_id , $numbers)
		{
			foreach($numbers as $k=>$v)
			{
				$check_exist = getData("SELECT number_id FROM campaign_numbers WHERE camp_id='".$camp_id."' and number ='".$v."'");
				$arr_number = array(
									'camp_id' => $camp_id,
									'number' => $v
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
		function save_step3($arr_data)
		{
			$updateQry = $this->getUpdateDataString($arr_data,'camp_id', 'campaign');
			updateData($updateQry);
		}
		
}
?>