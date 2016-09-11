<?php
	//echo "---".$function; exit;
	require_once('library/userManager.php');
	$userObject= new UserManager();
	
	switch($function)
		{
			case "view":
			break;
			
			case "logout":
				//echo md5('remember_'.SALT);exit;
				setcookie(md5('remember_'.SALT),'',time()-60*60*24);
				 $_SESSION['user_id'] = "";
				 $_SESSION['user_name'] = "";
				 $_SESSION['user_group'] = "";
				 $_SESSION['preferred_language'] = "";
				 $_SESSION['name'] = "";
				 $_SESSION['user_login'] = false;
				 session_destroy();
				 header("Location: index.php");
				 echo "
				 		<script type='text/javascript'>
							window.location = 'index.php';
						</script>
				 ";
			break;
			case "language_change":
				require_once('library/userManager.php');
				$userObject = new UserManager();
				$language=$_REQUEST['language'];
				$userObject->setLanguage($language,$_SESSION['user_id']);
				
				$_SESSION['preferred_language'] = $language;
				
				//$page = getOne("SELECT `page_name` FROM `pages` WHERE `is_active`=1 AND `page_id`=(select landing_page from user_groups where group_id = '".$_SESSION['user_main_group']."')");
				
				$page = "manage_settings.php";
			break;
			
			case "view_profile":
				$userDetails=$userObject->getProfileInfo($_SESSION['user_id']);
				$data['userDetails'] = $userDetails;
				$data['user_main_group'] = $_SESSION['user_main_group']; 
				//$data['profile_info']=$userObject->getProfileInfo($_SESSION['user_id']);
				$page="manage_my_profile.php";
			break;
			
			case "edit_profile":
				$data['user_main_group'] = $_POST['user_main_group']; 
				
				$userObject->getProfileVariables();
				$userVariables = $userObject->db_fields;
				$userVariables['user_id']=$_SESSION['user_id'];
				
					
				$userPassword=$userVariables['user_password'];
				//echo "<pre>"; print_r($userPassword);
				unset($userVariables['user_password']);
				$previousPass=$userObject->getUserPassword($userVariables['user_id']);
				$userObject->update($userVariables,'user_id');
				$sha1_currentpass=sha1($userPassword);
				if(!($sha1_currentpass==$previousPass)  && !($sha1_currentpass==sha1('********'))){
					$userObject->setPassword($userPassword,$userVariables['user_id']);
				}
				$userDetails=$userObject->getProfileInfo($_SESSION['user_id']);
				$data['userDetails'] = $userDetails;
				$notificationArray['type'] = 'Success';
				$notificationArray['message'] = 'Profile information updated successfully!';
				
				$page="manage_my_profile.php";
				
			break;
		}
?>
