<?php
	require_once('library/campaignManager.php');
	$campaignObject= new CampaignManager();
	
	include("library/twilioManager.php");
	$twilioObject= new twilioManager();

	switch($function)
		{
			case "view":
			case "view_reports":
				//var_dump($_REQUEST['is_post_back']);exit;
				//echo "<pre>"; print_r($_POST);
				if(isset($_REQUEST['is_post_back']) && $_REQUEST['is_post_back']=="TRUE"){
					$camp_ids=array();
					if(isset($_REQUEST['camp_id'])){
						$camp_ids=$_REQUEST['camp_id'];
					}
					//var_dump($camp_ids);
					if(count($camp_ids)>0){
						$data['arr_details'] = $campaignObject->get_reportdata($camp_ids,$_POST['sel_condition'],0,'call',$_POST['custom_from_alt'],$_POST['custom_to_alt']);
						$data['arr_details_clicks'] = $campaignObject->get_reportdata($camp_ids,$_POST['sel_condition'],0,'click',$_POST['custom_from_alt'],$_POST['custom_to_alt']);
						$data['arr_multi_clicks'] = $campaignObject->get_reporttable_data($camp_ids,$_POST['sel_condition'],0,'click',$_POST['custom_from_alt'],$_POST['custom_to_alt']);
						$data['arr_multi_calls'] = $campaignObject->get_reporttable_data($camp_ids,$_POST['sel_condition'],0,'call',$_POST['custom_from_alt'],$_POST['custom_to_alt']);
					}
					$data['sel_options'] = array(
											'selected_campaigns' => $camp_ids,
											'sel_condition' => $_POST['sel_condition'],
											'custom_from_alt' => $_POST['custom_from_alt'],
											'custom_to_alt' => $_POST['custom_to_alt'],
											);

				}else{
					$data['arr_details'] = array();
					$data['arr_details_clicks'] = array();
				}
				$data['campaigns'] = $campaignObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id']);
				$data['arr_numbers'] = $campaignObject->get_numbersdetails();
				//echo "<pre>"; print_r($data['numbers']); exit;
				$data['is_single_campaign']=FALSE;
				//echo "<pre>"; print_r($data);exit;
			break;
			
			case "campaign_report" :
					//echo "<pre>"; print_r($_POST); exit;	
					$data['table_calls']=$campaignObject->get_reporttable_data($_POST['sel_campaign'],$_POST['camp_sel_condition'],1,'call',$_POST['camp_custom_from_alt'],$_POST['camp_custom_to_alt']);
					$data['table_clicks']=$campaignObject->get_reporttable_data($_POST['sel_campaign'],$_POST['camp_sel_condition'],1,'click',$_POST['camp_custom_from_alt'],$_POST['camp_custom_to_alt']);								
					$data['arr_details'] = $campaignObject->get_reportdata($_POST['sel_campaign'],$_POST['camp_sel_condition'],1,'call',$_POST['camp_custom_from_alt'],$_POST['camp_custom_to_alt']);
					$data['arr_details_clicks'] = $campaignObject->get_reportdata($_POST['sel_campaign'],$_POST['camp_sel_condition'],1,'click',$_POST['camp_custom_from_alt'],$_POST['camp_custom_to_alt']);
					$data['campaigns'] = $campaignObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id']);
					$data['arr_numbers'] = $campaignObject->get_numbersdetails();
					$data['sel_options'] = array(
											'sel_campaign' => $_POST['sel_campaign'],
											'camp_sel_condition' => $_POST['camp_sel_condition'],
											'camp_custom_from_alt' => $_POST['camp_custom_from_alt'],
											'camp_custom_to_alt' => $_POST['camp_custom_to_alt'],
											);
					$data['is_single_campaign'] = TRUE;
					//echo "<pre>"; print_r($data['arr_details_clicks']); exit;	
			break;
			
			case "ajax_number_release" :
					$number[0]['number_id'] = $_POST['number_id'];
					$campaignObject->release_number($number);
					$no_sid = $campaignObject->get_numbersid($_POST['number_id']);
					$twilioObject->deleteNumber($no_sid);
			break;
		}