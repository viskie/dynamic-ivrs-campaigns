<?php
session_start();
include('library/commonObject.php');
include('library/constants.php');
require_once('library/leadManager.php');
$leadObject= new leadManager();

extract($_POST);
//echo "<pre>"; print_r($_POST); exit;
$timestamp = date('Ymd-His');
$data = $leadObject->get_data($_POST);
if(!isset($chk_click))
	$chk_click = array();
if(!isset($chk_locations))
	$chk_locations = array();
if(!isset($chk_devices))
	$chk_devices = array();
if(!isset($chk_calls))
	$chk_calls = array();
$arr_coloum = array_merge($chk_click,$chk_locations,$chk_devices,$chk_calls);	//echo "<pre>"; print_r($arr_coloum); exit;
$download_data = array();
for($i=0; $i<count($data); $i++)
{
	foreach($data[$i] as $k=>$v)
	{
		if(in_array($k,$arr_coloum))
			$download_data[$i][$k] = $v;
	}
}	


if(isset($download_type) && ($download_type == 'csv'))
{
	ob_start();
	$df = fopen("php://output", 'w');
	fputcsv($df, $arr_coloum);	
	//echo "<pre>"; print_r($csv_data); exit;
	if(count($download_data) != 0)
	{
		foreach ($download_data as $row) {
		  fputcsv($df, $row);
		}
	}	
	fclose($df);
	$csv_content = ob_get_clean();
	
	header('Content-disposition: inline;filename=LeadData-'.$timestamp.'.csv');
	header('Content-Type: text/csv;charset=UTF-8');
	echo $csv_content;
}
elseif(isset($download_type) && ($download_type == 'xml'))
{
	$sxe = new SimpleXMLElement('<lead_info></lead_info>');
	//$sxe->addAttribute('type', 'documentary');
	
	foreach($download_data as $arr_data)
	{
		$lead = $sxe->addChild('lead');		
		foreach($arr_data as $k=>$v)
		{
			$lead->addChild($k, $v);			
		}		
	}
	
	header("Content-type: text/xml");
	header('Content-disposition: attachment;filename=LeadData-'.$timestamp.'.xml');	 
	echo ($sxe->asXML());	
}
exit;
?>
