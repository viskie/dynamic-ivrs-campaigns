<?php
require_once('library/campaignManager.php');
$campaignObject= new CampaignManager();

include("library/twilioManager.php");
$twilioObject= new twilioManager();

include("library/hasofferManager.php");
$hasofferObject= new hasofferManager();

switch($function)
{
	case"view":
	case"view_campaign":
		
		if(isset($_POST['show_status']))
				$data['show_status'] = $show_status;
		$data['active_campaigns'] = $campaignObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id'],$show_status);	
		$data['rec_counts'] = $campaignObject->get_allcounts($logArray['group_id'],$logArray['user_id']);
		if(count($data['active_campaigns']) > 0){
			foreach($data['active_campaigns'] as $campaign)
			{
				$camp_ids[] = $campaign['camp_id'];
			}
			
			$data['getCompoundStatsCampaigns'] = $campaignObject->getCompoundStatsCampaigns($camp_ids);
			if($data['getCompoundStatsCampaigns']['transactions'] != 0)
				$data['getCompoundStatsCampaigns']['conversion_ratio'] = ($data['getCompoundStatsCampaigns']['calls']/$data['getCompoundStatsCampaigns']['transactions'])*100;
			else
				$data['getCompoundStatsCampaigns']['conversion_ratio'] = 0;
		}
		
		// for managing actions permissions
		if(isset($mainfunction))
		{	
			$data['mainfunction'] = $mainfunction;
			$data['subfunction_name'] = $subfunction_name;							
		}
		else
		{
			$data['mainfunction'] = $_POST['mainfunction'];
			$data['subfunction_name'] = $_POST['subfunction_name'];	
		}		
		$arr_permissions = get_action_permissions($logArray['user_id'],$current_pageid,$function,$function_type,$set_subfunction_id);
		$data['arr_permission'] = $arr_permissions;
		//echo "<pre>"; print_r($data['arr_permission']);				
		///////////////////////////////////
			
	break;
	
	case"show_campaign_form":
		if($_POST['edit_id'] != 0)
		{
			$data['arr_ivrs'] = $campaignObject->get_campaign($_POST['edit_id']);
			$data['arr_ivrs']['manager_id'] = $campaignObject->getManagerOfUser($data['arr_ivrs']['user_id']);
			$data['arr_numbers'] = $campaignObject->get_numbers($_POST['edit_id']);
			$data['user_of_manager'] = $campaignObject->getAllUserOfManager($data['arr_ivrs']['manager_id']['user_manager']);
			$data['arr_subid'] = $campaignObject->get_subids($_POST['edit_id']);
			//echo "<pre>"; print_r($data['arr_subid']); exit;				
		}		
		$data['all_managers'] = $campaignObject->getAllManager();
		$data['all_user_of_manager'] = $campaignObject->getAllUserOfManager($logArray['user_id']);
		// for managing actions permissions
		if(isset($mainfunction))
		{	
			$data['mainfunction'] = $mainfunction;
			$data['subfunction_name'] = $subfunction_name;							
		}
		else
		{
			$data['mainfunction'] = $_POST['mainfunction'];
			$data['subfunction_name'] = $_POST['subfunction_name'];	
		}		
		//$arr_permissions = get_action_permissions($logArray['user_id'],$current_pageid,$function,$function_type,$set_subfunction_id);
		//$data['arr_permission'] = $arr_permissions;
		//echo "<pre>"; print_r($data['arr_permission']);				
		///////////////////////////////////
		$page = "add_edit_campaign.php";
	break;
	
	case"save_campaign_step2": 
		$camp_id = $campaignObject->saveivrs($_POST);			
		$messages = array();
		//echo "<pre>"; print_r($_FILES); //echo "<pre>"; print_r($_POST); exit;
		if(isset($_FILES['file_name']['tmp_name']))
		{
			$num_files = count($_FILES['file_name']['tmp_name']);
			$incorrect_file = array();
			for($i =0; $i<$num_files; $i++)
			{
				$campaign_name = $_POST['name'];				
				$file = $_FILES['file_name']['name'][$i];
				$file_type = $_FILES["file_name"]["type"][$i];
				$allowed_type = array('audio/mpeg','audio/wav','audio/wave','audio/x-wav','audio/aiff','audio/x-aifc','audio/x-aiff','audio/x-gsm','audio/gsm','audio/ulaw');				
				//if(in_array($file_type,$allowed_type))
				//{				
					$make_dir = DOC_ROOT."uploads/".$camp_id;
					if (!file_exists($make_dir))
					{
						if(!@mkdir($make_dir))					
						{
							$error = error_get_last();
							$messages['mkdir'] = $error;							
						}
					}
						
					$dest_path = DOC_ROOT."uploads/".$camp_id."/$file";
					$temp_name = $_FILES['file_name']['tmp_name'][$i];
					
					if(!move_uploaded_file($temp_name, $dest_path))
					{
						$messages[] = $file;
					}				
				/*}
				else
				{
					$incorrect_file[] = $file;  
				}*/
			}
		}
		$incorrect_file = array_filter($incorrect_file);	
		if(count($incorrect_file) > 0)
		{
			$campaignObject->remove_ivrsfile($incorrect_file);
			$inc_files = implode(',',$incorrect_file);
			$notificationArray['type'] = 'Success';
			$notificationArray['message'] = $inc_files." are not allowed audio format files!";
		}
		//echo "<pre>"; print_r($incorrect_file); exit;	
		
		$data['arr_ivrs'] = $campaignObject->get_campaign($camp_id);
		$data['arr_numbers'] = $campaignObject->get_numbers($camp_id);
		$data['arr_ivrs']['manager_id'] = $campaignObject->getManagerOfUser($data['arr_ivrs']['user_id']);
		$data['all_managers'] = $campaignObject->getAllManager();
		$data['all_user_of_manager'] = $campaignObject->getAllUserOfManager($logArray['user_id']);
		$data['user_of_manager'] = $campaignObject->getAllUserOfManager($data['arr_ivrs']['manager_id']['user_manager']);
		$page = "add_edit_campaign.php";
		// for managing actions permissions
		if(isset($mainfunction))
		{	
			$data['mainfunction'] = $mainfunction;
			$data['subfunction_name'] = $subfunction_name;							
		}
		else
		{
			$data['mainfunction'] = $_POST['mainfunction'];
			$data['subfunction_name'] = $_POST['subfunction_name'];	
		}		
		//$arr_permissions = get_action_permissions($logArray['user_id'],$current_pageid,$function,$function_type,$set_subfunction_id);
		//$data['arr_permission'] = $arr_permissions;
		//echo "<pre>"; print_r($data['arr_permission']);				
		///////////////////////////////////		
	break;
	
	case "save_campaign" : 
		if(isset($_POST['step']) && ($_POST['step'] == 1))
		{
            $check_offer_id = $campaignObject->check_offer_id_exist($_POST['offer_id'],$_POST['campaign_id']);
            if($check_offer_id)
            {
                echo "exist";
                exit;
            }
            else
            {
                $campaignVariables['name'] = $_POST['name'];
                $campaignVariables['offer_url'] = $_POST['offer_url'];
                $campaignVariables['description'] = $_POST['description'];
                //$campaignVariables['call_center_number'] = preg_replace("/[^0-9]/", '',  trim($_POST['call_center_number']));
                $campaignVariables['offer_id'] = $_POST['offer_id'];
                $campaignVariables['status'] = $_POST['status'];
                $campaignVariables['extra_number'] = $_POST['extra_number'];
                $campaignVariables['deallocation_time'] = $_POST['deallocation_time'];
                $campaignVariables['call_conversion_criteria'] = $_POST['call_conversion_criteria'];
				$campaignVariables['call_conversion_days'] = $_POST['call_conversion_days']; 
                if((isset($_POST['campaign_id']) && $_POST['campaign_id'] != '') || (isset($_POST['campaign_id']) && $_POST['campaign_id'] != ''))
                {
                    if(!isset($_POST['user_id']) ||  trim($_POST['user_id']) == "" || $_POST['user_id'] == "undefined")
                    {
                        $campaignVariables['user_id'] = $logArray['user_id'];
                    }else{
                        $campaignVariables['user_id'] = $_POST['user_id'];
                    }

                    if(isset($_POST['campaign_id']) && $_POST['campaign_id'] != '')
                        $campaignVariables['camp_id'] = $_POST['campaign_id'];
                    if (isset($_POST['edit_id']) && $_POST['edit_id'] != '')
                        $campaignVariables['camp_id'] = $_POST['edit_id'];

                    $campaignVariables['user_id'] = $_POST['user_id'];


                    $campaign_id=$campaignObject->update($campaignVariables,'camp_id');
                    echo $_POST['campaign_id'];
                }else{
                    if(!isset($_POST['user_id']) || trim($_POST['user_id']) == "" || $_POST['user_id'] == "undefined")
                    {
                        $campaignVariables['user_id'] = $logArray['user_id'];
                    }else{
                        $campaignVariables['user_id'] = $_POST['user_id'];
                    }
                    $campaignVariables['added_by'] = $_SESSION['user_id'];
                    //print_r($campaignVariables); exit;
                    $campaign_id=$campaignObject->insert($campaignVariables);
                    echo $campaign_id;
                }
            }
		}
		if(isset($_POST['step']) && ($_POST['step'] == 3))
		{
			/*$check_subid_exist = $campaignObject->check_subid_exist($_POST['campaign_id'],$_POST['subId']);
			if($check_subid_exist)
			{
				echo 'subid_exist';
			}
			else
			{*/
				/*$check_exist = $hasofferObject->getCampaign($_POST['hasoffer_camp_id']);
				//echo "<pre>"; print_r($check_exist); exit;
				if(count($check_exist['response']['data']) > 0 && (count($check_exist['response']['errors']) == 0))
				{
					$arr_data = array(
								'camp_id' => $_POST['campaign_id'],
								'hasoffer_camp_id' => $_POST['hasoffer_camp_id'],
								'subId' => $_POST['subId'],
								);					
					$campaignObject->save_step3($arr_data);
					echo true;
				}
				else
					echo  'hasofferid_exist';*/
				$campaignObject->save_step3($_POST);				
				/*$arr_data = array(
							'camp_id' => $_POST['campaign_id'],
							//'hasoffer_camp_id' => $_POST['hasoffer_camp_id'],
							'subid1' => $_POST['subid1'],							
							);					
				$campaignObject->save_step3($arr_data);*/
				echo true;
			//}
		}
		exit;			
	break;
	
	case "get_users_of_manager":
		$manager_id=$_POST['manager_id'];
		header('content-type:application/json');
		$data['all_user_of_manager'] = $campaignObject->getAllUserOfManager($manager_id);
		echo json_encode($data['all_user_of_manager']);
		exit;
	break;
	
	case "buy_number_ajax" :
		//echo "<pre>"; print_r($_POST); exit;
		$arr_numbers = $twilioObject->buy_tollfree_numbers($_POST['cnt_number'],$_POST['num_type']);
		//echo "<pre>"; print_r($arr_numbers); exit;
		if(count($arr_numbers) != $_POST['cnt_number'])
		{
			$get_mail_admin = $campaignObject->get_mail_admin();
			foreach($get_mail_admin as $k=>$v)
			{
				$to      = $v['user_email'];
				$subject = 'M-Track - Inform about number buy problem';
				$message .= '<table cellpadding="0" cellspacing="0" align="center" width="100%">
								<tr><td>Dear '.$v['user_name'].'</td></tr>';
				
				$message .= '<tr><td>This is to inform about number buy problem.</td></tr>';
				$message .= '<tr><td>Thanks,</td></tr>';
				$message .= '<tr><td>MTrack Team</td></tr>';
				$message .= '</table>';
				$headers = 'From: admin@example.com' . "\r\n" .
					'Reply-To: admin@example.com' . "\r\n" .
					'X-Mailer: PHP/' . phpversion();
									
				mail($to, $subject, $message, $headers);
			}
		}
		$arr_old_numbers = $campaignObject->get_numbers($_POST['campaign_id']);
		$campaignObject->save_numbers($_POST['campaign_id'] , $arr_numbers);
		
		$str = '<div id="number_list_lable">We purchased the following numbers :</div>
				<div><input type="checkbox" name="chk_all_numbers" id="chk_all_numbers" value="1" onchange="check_numbers(this)" /><span id="chk_all_txt">Check All</span></div>
                  <div class="number_list">';
		for($i=0; $i<count($arr_numbers); $i++)
		{
		  $str .= '<div><input type="checkbox" name="number[]" id="number" value="'.$arr_numbers[$i]['number'].'" onchange="add_div(this)"/>'.$arr_numbers[$i]['friendly_name'].'</div>';  
		}
		if(count($arr_old_numbers) > 0)
		{
			for($j=0; $j<count($arr_old_numbers); $j++)
			{
			  $str .= '<div><input type="checkbox" name="number[]" id="number" value="'.$arr_old_numbers[$j]['number'].'" onchange="add_div(this)"/>'.$arr_old_numbers[$j]['friendly_name'].'</div>';  
			}
		}
		$str .= '</div>
				 <div> <input type="button" class="button blue" name="release_number" id="release_number" value="Release Numbers" onclick="release_numbers()" /></div>
			</div>  ';
		echo $str;
		exit;
		
		/*$str1 = '<div id="number_list_lable">We buy followings numbers :</div>
					<div class="number_list">
						<div>
							<input type="checkbox" name="number[]" id="number" value="+18888818057" />
							(888) 881-8057							
							<input type="hidden" name="numbers[]" id="numbers" value="" />
						</div>
						<div><input type="checkbox" name="number[]" id="number" value="+18888801609" />+18888801609</div>
						<div><input type="checkbox" name="number[]" id="number" value="+18888801609" />+18888801609</div>
						<div> <input type="button" class="button blue" name="release_number" id="release_number" value="Release Numbers" onclick="release_numbers()" /></div>
					</div>';
		echo $str1;*/
		exit;
	break;
	
	case 'release_number_ajax' :						
			$arr_numbers = $campaignObject->get_numbers_details($_POST);
			$arr_numbers = array_filter($arr_numbers);
			$campaignObject->release_number($arr_numbers);
			foreach($arr_numbers as $k=>$v)
			{
				$twilioObject->deleteNumber($v['number_sid']);
			}			
			exit;
	break;
	
	case "delete_campaign" :			
			$campaignObject->delete_campaign($_POST['edit_id']);
			$data['active_campaigns'] = $campaignObject->getCampaignsDetails($logArray['group_id'],$logArray['user_id'],$show_status);
			$data['rec_counts'] = $campaignObject->get_allcounts($logArray['group_id'],$logArray['user_id']);
			if(isset($_POST['show_status']))
				$data['show_status'] = $show_status;
			
			//echo "<pre>"; print_r($data); exit;
			$notificationArray['type'] = 'Success';
			$notificationArray['message'] = showmsg('campaign','delete');
			 
			// for managing actions permissions
			if(isset($mainfunction))
			{	
				$data['mainfunction'] = $mainfunction;
				$data['subfunction_name'] = $subfunction_name;							
			}
			else
			{
				$data['mainfunction'] = $_POST['mainfunction'];
				$data['subfunction_name'] = $_POST['subfunction_name'];	
			}		
			$arr_permissions = get_action_permissions($logArray['user_id'],$current_pageid,$function,$function_type,$set_subfunction_id);
			$data['arr_permission'] = $arr_permissions;			
			///////////////////////////////////
			$page = "manage_campaign.php";
	break;

	case "unlink_file" : 
			$file = DOC_ROOT."uploads/".$_POST['camp_id']."/".$_POST['file_name'];			
			if (file_exists($file))
			{
				unlink($file);
			}
			exit;			
	break;
	
	case "show_campaign_details" :			
			$data['calls'] = $campaignObject->get_calls($_POST['edit_id']);
			$data['clicks'] = $campaignObject->get_clicks($_POST['edit_id']);
			$data['campaign'] = $campaignObject->get_campaign_details($_POST['edit_id']);
			$data['getCompoundStatsCampaigns'] = $campaignObject->getCompoundStatsCampaigns($_POST['edit_id']);
			if($data['getCompoundStatsCampaigns']['transactions'] != 0)
				$data['getCompoundStatsCampaigns']['conversion_ratio'] = ($data['getCompoundStatsCampaigns']['calls']/$data['getCompoundStatsCampaigns']['transactions'])*100;
			else
				$data['getCompoundStatsCampaigns']['conversion_ratio'] = 0;
			//echo "<pre>"; print_r($data['calls']); exit;
			
			if(isset($mainfunction))
			{	
				$data['mainfunction'] = $mainfunction;
				$data['subfunction_name'] = $subfunction_name;							
			}
			else
			{
				$data['mainfunction'] = $_POST['mainfunction'];
				$data['subfunction_name'] = $_POST['subfunction_name'];	
			}	
					
			$page = "view_campaign.php";
	break; 
	
	case "show_call_details" :
			$data['call'] = $campaignObject->get_call_details($_POST['edit_id']);
			$data['click_location'] = $campaignObject->get_click_location_details($data['call']['mt_id']);
			$data['survey_details'] = $campaignObject->get_survey_details($data['call']['mt_id']);
			$data['survey_info'] = $campaignObject->get_survey_info($data['call']['mt_id']);
			$data['campaign'] = $campaignObject->get_campaign_details($data['call']['camp_id']);
			$data['click'] = $campaignObject->get_click_details($data['call']['mt_id']);
			$data['device_details'] = $campaignObject->get_device_details($data['call']['mt_id']);
			//echo $data['call']['mt_id']; echo "<pre>"; print_r($data['click']); echo "<pre>"; print_r($data['device_details']); exit;
			if(isset($mainfunction))
			{	
				$data['mainfunction'] = $mainfunction;
				$data['subfunction_name'] = $subfunction_name;							
			}
			else
			{
				$data['mainfunction'] = $_POST['mainfunction'];
				$data['subfunction_name'] = $_POST['subfunction_name'];	
			}	
			$page = "view_call.php";
	break;
	
	case "show_click_details" :
			$data['click'] = $campaignObject->get_click_details($_POST['edit_id']);
			$data['click_location'] = $campaignObject->get_click_location_details($data['click']['mt_id']);
			$data['campaign'] = $campaignObject->get_campaign_details($data['click']['camp_id']);
			$data['survey_details'] = $campaignObject->get_survey_details($data['click']['mt_id']);
			$data['survey_info'] = $campaignObject->get_survey_info($data['click']['mt_id']);
			$data['device_details'] = $campaignObject->get_device_details($data['click']['mt_id']);
			if(isset($mainfunction))
			{	
				$data['mainfunction'] = $mainfunction;
				$data['subfunction_name'] = $subfunction_name;							
			}
			else
			{
				$data['mainfunction'] = $_POST['mainfunction'];
				$data['subfunction_name'] = $_POST['subfunction_name'];	
			}	
			//echo "<pre>"; print_r($data['survey_info']); exit;
			$page = "view_click.php";
	break;
	
	case "get_device_data" : // this method is used to get device data from wurfl api
		
			include("library/wurfl/examples/demo/inc/wurfl_config_standard.php");
			//wurflInfo = $wurflManager->getWURFLInfo();
			//var_dump($wurflInfo);
			
			//$user_agent = "Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 800)";
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			$requestingDevice = $wurflManager->getDeviceForUserAgent($user_agent);
			//var_dump($requestingDevice);
			
			$arr_details = array(
							'wurfl_id' => $requestingDevice->id,
							'brand_name' => $requestingDevice->getCapability('brand_name'),
							'model_name' => $requestingDevice->getCapability('model_name'),
							'marketing_name' => $requestingDevice->getCapability('marketing_name'),
							);
			$campaignObject->save_device($arr_details);
			?>
            <!--<ul>
                <li style="padding:5px;">ID: < ?php echo $requestingDevice->id; ?> </li>
                <li style="padding:5px;">Brand Name: < ?php echo $requestingDevice->getCapability('brand_name'); ?> </li>
                <li style="padding:5px;">Model Name: < ?php echo $requestingDevice->getCapability('model_name'); ?> </li>
                <li style="padding:5px;">Marketing Name: < ?php echo $requestingDevice->getCapability('marketing_name'); ?> </li>
                <li style="padding:5px;">Preferred Markup: < ?php echo $requestingDevice->getCapability('preferred_markup'); ?> </li>
                <li style="padding:5px;">Resolution Width: < ?php echo $requestingDevice->getCapability('resolution_width'); ?> </li>
                <li style="padding:5px;">Resolution Height: < ?php echo $requestingDevice->getCapability('resolution_height'); ?> </li>   
            </ul>-->
            <?php
			/*	$requestingDevice->getCapability('preferred_markup')
			$requestingDevice->getCapability('resolution_width')
			echo $requestingDevice->getCapability('resolution_height')
			$is_wireless = ($requestingDevice->getCapability('is_wireless_device') == 'true');
			$is_smarttv = ($requestingDevice->getCapability('is_smarttv') == 'true');
			$is_tablet = ($requestingDevice->getCapability('is_tablet') == 'true');
			$is_phone = ($requestingDevice->getCapability('can_assign_phone_number') == 'true');*/
			
	break;
	
	case "get_click_data" : // This method is used to get all click data using MaxMind api
			
			$params = getopt('l:i:');	
			if (!isset($params['l'])) $params['l'] = MaxMind_licensekey; //'DxdKyleQ6k90';
			if (!isset($params['i'])) $params['i'] = $_SERVER['REMOTE_ADDR'];
			//if (!isset($params['i'])) $params['i'] = '44.55.66.77';
			//if (!isset($params['i'])) $params['i'] = '123.236.177.171';
			
			$query = 'https://geoip.maxmind.com/e?' . http_build_query($params);
			
			$omni_keys =
			  array(
				'country_code',
				'country_name',
				'region_code',
				'region_name',
				'city_name',
				'latitude',
				'longitude',
				'metro_code',
				'area_code',
				'time_zone',
				'continent_code',
				'postal_code',
				'isp_name',
				'organization_name',
				'domain',
				'as_number',
				'netspeed',
				'user_type',
				'accuracy_radius',
				'country_confidence',
				'city_confidence',
				'region_confidence',
				'postal_confidence',
				'error'
				);
			
			$curl = curl_init();
			curl_setopt_array(
				$curl,
				array(
					CURLOPT_URL => $query,
					CURLOPT_USERAGENT => 'MaxMind PHP Sample',
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_SSL_VERIFYPEER => false,
					CURLOPT_SSL_VERIFYHOST => false
				)
			);
			
			$resp = curl_exec($curl);		
			if (curl_errno($curl)) {
				throw new Exception(
					'GeoIP request failed with a curl_errno of '
					. curl_errno($curl)
				);
			}		
			$omni_values = str_getcsv($resp);
			$omni_values = array_pad($omni_values, sizeof($omni_keys), '');
			$omni = array_combine($omni_keys, $omni_values);
			
			echo "<pre>"; print_r($omni);
			$campaignObject->save_maxmind_details($omni);
			exit;			
	break;
		
}
?>