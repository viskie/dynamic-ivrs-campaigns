<?php
require_once('library/leadManager.php');
$leadObject= new leadManager();

switch($function){
	case "view_leads":
		if(isset($_POST['show_status']))
			$data['show_status'] = $show_status;
		$data['arr_campaigns'] = $leadObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id'],$show_status);
		$data['get_coloums'] = $leadObject->get_coloums();
		//echo "<pre>"; print_r($data['get_coloums']); exit;		
		$page = "manage_leads.php";		
		
	break;
	
	case "show_data" :
		$data = $leadObject->get_data($_POST);	//echo "<pre>"; print_r($data); exit;
		extract($_POST);
		if(!isset($chk_click))
			$chk_click = array();
		if(!isset($chk_locations))
			$chk_locations = array();
		if(!isset($chk_devices))
			$chk_devices = array();
		if(!isset($chk_calls))
			$chk_calls = array();
		$arr_coloum = array_merge($chk_click,$chk_locations,$chk_devices,$chk_calls);	
		$download_data = array();
		for($i=0; $i<count($data); $i++)
		{
			foreach($data[$i] as $k=>$v)
			{
				if(in_array($k,$arr_coloum))
					$download_data[$i][$k] = $v;
			}
		}
		
		$data['old_data'] = $_POST;				
		$data['arr_coloum'] = $arr_coloum;
		$data['download_data'] = $download_data;
		//echo "<pre>"; print_r($arr_coloum);
		//echo "<pre>"; print_r($download_data); exit;
		if(isset($_POST['show_status']))
			$data['show_status'] = $show_status;
		$data['arr_campaigns'] = $leadObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id'],$show_status);
		$data['get_coloums'] = $leadObject->get_coloums();
		$page = "manage_leads.php";		
	break;
	
}
?>