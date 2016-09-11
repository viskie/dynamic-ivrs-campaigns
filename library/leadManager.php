<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

require_once('Config.php');

class LeadManager extends commonObject
{
	public $db_fields;
	function CampaignManager()
	{
		$this->db_fields = array();	
		//$this->table_name = "campaign";
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
	function get_coloums()
	{
		$arr_coloums = array();
		// to get all call table coloums
		$call_coloums = getData("SHOW columns FROM calls");
		$except = array('call_id','CallSid','AccountSid','camp_id','mt_id');
		$arr_coloums['calls'] = $this->get_fields($call_coloums,$except);
		// to get all call table coloums
		$click_coloums = getData("SHOW columns FROM clicks");
		$except = array('camp_id','click_time');
		$arr_coloums['clicks'] = $this->get_fields($click_coloums,$except);
		// to get all location table coloums
		$click_coloums = getData("SHOW columns FROM click_locations");
		$except = array('click_location_id','mt_id','error','given_by_user','old_postal_code');
		$arr_coloums['locations'] = $this->get_fields($click_coloums,$except);
		// to get all device table coloums
		$click_coloums = getData("SHOW columns FROM devices");
		$except = array('device_id','mt_id','wurfl_id');
		$arr_coloums['devices'] = $this->get_fields($click_coloums,$except);
		return $arr_coloums;
		//echo "<pre>"; print_r($arr_coloums);		
		//exit;
	}
	function get_fields($arr,$except_arr)
	{
		$arr_fields = array();
		foreach($arr as $k=>$v)
		{
			if(!in_array($v['Field'],$except_arr))
				$arr_fields[] = $v['Field'];
		}
		return $arr_fields;
	}
	function get_data($arr_data)
	{
		//echo "<pre>"; print_r($arr_data); exit;	
		extract($arr_data);
		$call_str = '';
		$click_str = '';
		$arr_coloums = $this->get_coloums();
		if($selcamp != "")
		{	
			$click_str = " camp_id = ".$selcamp;			
		}		
		if($from_dur_alt != "" && $to_dur_alt != "")
		{
			$click_str .= " and date(click_time) BETWEEN '".$from_dur_alt."' AND '".$to_dur_alt."'";			
		}
		elseif($from_dur_alt != "" && $to_dur_alt == "")
		{
			$click_str .= " and date(click_time) BETWEEN '".$from_dur_alt."' AND now()";
		}
		elseif($from_dur_alt == "" && $to_dur_alt != "")
		{
			$click_str .= " and date(click_time) < '".$to_dur_alt."'";
		}
		if($txttid != "")
		{
			$click_str .= " and ".$this->get_in($txttid,'tid');
		}
		if($txtmid != "")
		{
			$click_str .= " and ".$this->get_in($txtmid,'mt_id'); 
		}
		if($txtsubid2 != "")
		{
			$click_str .= " and ".$this->get_in($txtsubid2,'subid2'); 
		}			
		if($txtname != "")
		{
			$like = " name LIKE '%".$txtname."%'"; 
			$click_str .= " and ".$like;			
		}
		if($txtaddress != "")
		{
			$like = " address LIKE '%".$txtaddress."%'"; 
			$click_str .= " and ".$like;			
		}
		if($txtemail != "")
		{
			$like = $this->get_like($txtemail,'email');			
			$click_str .= " and ".$like;			
		}
		if($txtcallto != "")
		{
			$like = $this->get_like($txtcallto,'CallTo');			
			$call_str .= " and ".$like;			
		}	
		if($txtcallfrom != "")
		{
			$like = $this->get_like($txtcallfrom,'CallFrom');			
			$call_str .= " and ".$like;			
		}
		if($chkconverted == "Yes")
		{
			$call_str .= " and is_postback = '1'";
		}
		$location_conditions = '';
		
		$loc_colm = implode(',',$arr_coloums['locations']);
		$dev_colm = implode(',',$arr_coloums['devices']);
		
		if($location_conditions == "")
			$location_conditions = '1';
		$location = " LEFT JOIN ( SELECT mt_id as MTID, ".$loc_colm." FROM click_locations WHERE ".$location_conditions.") AS LOCATION ON clicks.mt_id = LOCATION.MTID LEFT JOIN ( SELECT mt_id as dev_mt_id, ".$dev_colm." from devices) AS DEVICE ON clicks.mt_id = DEVICE.dev_mt_id ";
		$call_str = trim(trim($call_str),'and');
		
		if($call_str == '')
			$call_str = '1';
		$call_colm = implode(',',$arr_coloums['calls']);		
		$call_sql = "SELECT  mt_id AS call_mt_id, ".$call_colm." FROM calls WHERE ".$call_str; //is_postback = '1' and
		//echo $call_sql; exit;
		$click_str = trim(trim($click_str),'and');
		if($click_str == '')
			$click_str = '1';		 
		$click_colm = implode(',',$arr_coloums['clicks']);
		if($location_conditions == "1")
			$location_conditions = '';
		else
			$location_conditions = " and ".$location_conditions;
		$click_sql = "SELECT ".$click_colm.",click_time,".$loc_colm.", ".$dev_colm." FROM clicks ".$location." WHERE ".$click_str.$location_conditions;
		if(isset($chk_calls) && count($chk_calls) != 0)
			$main_query = "SELECT * FROM ( ".$click_sql." ) AS CLICK_DET LEFT JOIN ( ".$call_sql." ) AS CALL_DET on CLICK_DET.mt_id = CALL_DET.call_mt_id WHERE ".$call_str;
		else
			$main_query = "SELECT * FROM ( ".$click_sql." ) AS CLICK_DET "; //echo $main_query; exit;
		$arr_data = getData($main_query);
		//echo $main_query;  
		//exit;
		return $arr_data;		
	}
	function get_like($str,$name)
	{		
		if(strpos($str, ',') !== false)
		{
			$arr= explode(',',$str); 
			$arr = array_filter($arr);
		}
		else
			$arr[0] = $str;
		$like = '';
		foreach($arr as $k=>$v)
		{
			$like .= " ".$name." LIKE '%".$v."%' or"; 				
		}
		$like = trim($like,'or');
		$like = " (".$like." ) ";
		return $like;
	}
	function get_in($str,$name)
	{
		$arr= explode(',',$str);
		$arr = array_filter($arr);
		$in = '';
		foreach($arr as $a)
		{
			$in .= "'".$a."',"; 				
		}		
		$in = trim(trim($in),',');
		$str = $name." IN ( ".$in." )";
		return $str;
	}
	function get_surveydata($mt_id)
	{
		$arr_data = getData("SELECT question, answer from survey_data where mt_id='".$mt_id."'");
		return $arr_data;
	}
}
?>