<?php

	require_once('library/campaignManager.php');
	$campaignObject= new CampaignManager();
	
switch($function){
	case "view":
		$page = "manage_dashboard.php";
		$all_camps=$campaignObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id'],$show_status);
		//echo "<pre>";print_r($all_camps);exit;
		$all_camps_ids=array();
		foreach($all_camps as $camp){
			array_push($all_camps_ids,$camp['camp_id']);
		}
		$data['arr_details'][0] = $campaignObject->get_reportdata($all_camps_ids,1,-1,'call');
		$data['arr_details_clicks'][0] = $campaignObject->get_reportdata($all_camps_ids,1,-1,'click');
		//echo "<pre>"; print_r($data['arr_details']); 
		//echo "<pre>"; print_r($data['arr_details_clicks']); 
		//exit;
	break;	
}
//$selected_day = $settingObject->getDefaultFinancialMonth();
/*$selected_day = 4;
if(isset($_POST['year']))
{	$year = $_POST['year'];
	$data['year'] = $year;
	for($i=1;$i<=12;$i++)
	{	$data['income_statistics'][$i] = $accountsObject->getTotalAmountOfIncome($year,$i,$selected_day);
		$data['expense_statistics'][$i] = $accountsObject->getTotalAmountOfExpense($year,$i,$selected_day);
		$data['income_expense_statistics'][$i] = $accountsObject->getTotalAmountOfIncomeAndExpense($year,$i,$selected_day);
	}
	return $data['income_statistics'];
}
else
{	$curYear = date('Y');
	$data['year'] = $curYear;
	for($i=1;$i<=12;$i++)
	{	//$data['income_statistics'][$i] = $accountsObject->getTotalAmountOfIncome($curYear,$i,$selected_day);
		//$data['expense_statistics'][$i] = $accountsObject->getTotalAmountOfExpense($curYear,$i,$selected_day);
		//$data['income_expense_statistics'][$i] = $accountsObject->getTotalAmountOfIncomeAndExpense($curYear,$i,$selected_day);
	}
	return $data; 	
} */
?>