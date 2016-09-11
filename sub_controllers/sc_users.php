<?php
require_once('library/userManager.php');
$userObject= new UserManager();
require_once('library/groupManager.php');
$groupObject= new GroupManager();
require_once('library/pageManager.php');
$pageObject = new PageManager();
require_once('library/functionManager.php');
$functionObject = new FunctionManager();

switch($function){
	case "view":
	case "view_users":
		//echo $show_status; //if(!$show_status)
		$data['show_status'] = $show_status;		
		$data['allUsers'] = $userObject->getAllUsers($show_status,$function,$logArray['user_id']);		
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();
		$data['arr_manager'] = $userObject->get_managers();		
		$page = "view_manage_users.php";
		
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
	
	case"show_user_form":
		$edit_id = $_POST['edit_id'];	
		if( $edit_id != 0)
		{	
			$userDetails = $userObject->getRecordById('user_id',$edit_id);
			//$data['userDetails']['edit_id'] = $_POST['edit_id'];  
			$data['userDetails']  = $userDetails;
		}
		$data['arr_manager'] = $userObject->get_managers();
		$page="add_edit_user.php";
		
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
	break;
	
	case "save_user":
		$userObject->getUserVariables();
		$userVariables = $userObject->db_fields;
		$userVariables['user_manager'] = $_POST['user_manager'];
		if(($_POST['edit_id']) !== '0')
		{
			$userVariables['user_id']=$_POST['edit_id'];
			$userPassword=$userVariables['user_password'];
			unset($userVariables['user_password']);
			$previousPass=$userObject->getUserPassword($userVariables['user_id']);
			$userVariables['modified_by'] = $logArray['user_id'];
			$userVariables['modified_date']=date('Y-m-d H:i:s');
			$userVariables['user_group'] = 4;
			$userVariables['user_email'] = strtolower($userVariables['user_email']);	 				
			$userObject->update($userVariables,'user_id');
			$sha1_currentpass=sha1($userPassword);
			if(!($sha1_currentpass==$previousPass)  && !($sha1_currentpass==sha1('********'))){
				$userObject->setPassword($userPassword,$userVariables['user_id']);
			}
			
			$data['allUsers']=$userObject->getAllUsers(1,$function);
			$notificationArray['type'] = 'Success';
			$notificationArray['message'] =  showmsg('user','update');
			$is_edit = true;
			$page="view_manage_users.php"; 
		}
		else
		{	
			//print_r($_POST); exit;
			if(!$userObject->isUserExist($userVariables['user_name'])){
				$userPassword=$userVariables['user_password'];
				$userVariables['user_password']="";
				
				$userVariables['added_by']=$_SESSION['user_id'];
				$userVariables['added_date']=date('Y-m-d H:i:s');
				
				$userVariables['user_group'] = 4;
				$userVariables['user_email'] = strtolower($userVariables['user_email']);	 			
				$user_id = $userObject->insert($userVariables);
				$userObject->setPassword($userPassword,$user_id);
				
				//$data['allGroups']=$groupObject->getAll();
				$notificationArray['type'] = 'Success';
				$notificationArray['message'] = showmsg('user','add'); 
				$page="view_manage_users.php"; 
			}else{
				$is_exist = true;
				$notificationArray['type'] = 'Failed';
				$notificationArray['message'] = showmsg('user','dup','username'); 
				$page="add_edit_user.php";
			}
		}
		$data['show_status'] = 1;
		$data['userVariables'] = $userVariables;
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['arr_manager'] = $userObject->get_managers();
		$data['allGroups']=$groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		
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
	break;
	
	case "delete_user":
		$user_id=$_POST['edit_id'];
		$userObject->delete($user_id,'user_id');
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('user','delete');
		 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		//echo $data['show_status']; exit;
		$data['arr_manager'] = $userObject->get_managers();
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$page="view_manage_users.php";
		
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
	break;
	
	case "restore_user":
		$user_id=$_POST['edit_id'];
		$userObject->restoreEntry('user_id',$user_id);
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('user','restore'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		$data['arr_manager'] = $userObject->get_managers();
		$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$page="view_manage_users.php";
		
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
	break;
	
	/////////////// super admin
	case "view_super_user":
		$data['show_status'] = $show_status;
		$data['allUsers'] = $userObject->getAllUsers($show_status,$function,$logArray['user_id']);		
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();		
		
		$data['user_id'] = $logArray['user_id'];
		$data['group_id'] = $logArray['group_id'];
		$page = "view_super_admin.php";
		
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
	break;
	
	case "show_super_user_form":
		//echo "<pre>"; print_r($_POST);
		$edit_id = $_POST['edit_id'];
		$data['user_id'] = $logArray['user_id'];
		$data['group_id'] = $logArray['group_id'];	
		if( $edit_id != 0)
		{	
			if(isset($_POST['onlyview']) &&  $_POST['onlyview'] == 1)
			{ 
				$data['view'] = 1;				
			}
			$userDetails = $userObject->getRecordById('user_id',$edit_id);			
			$data['userDetails']  = $userDetails;
		}		
		$page="add_edit_superadmin.php";
		
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
	break;
	
	case "save_super_user":
		$userObject->getUserVariables();
		$userVariables = $userObject->db_fields;
		$data['user_id'] = $logArray['user_id'];
		$data['group_id'] = $logArray['group_id'];	
		if(($_POST['edit_id']) !== '0')
		{
			$userVariables['user_id']=$_POST['edit_id'];
			$userPassword=$userVariables['user_password'];
			unset($userVariables['user_password']);
			$previousPass=$userObject->getUserPassword($userVariables['user_id']);
			$userVariables['modified_by'] = $logArray['user_id'];
			$userVariables['modified_date']=date('Y-m-d H:i:s');
			$userVariables['user_group'] = 3;
			$userVariables['user_email'] = strtolower($userVariables['user_email']);	 				
			$userObject->update($userVariables,'user_id');
			$sha1_currentpass=sha1($userPassword);
			if(!($sha1_currentpass==$previousPass)  && !($sha1_currentpass==sha1('********'))){
				$userObject->setPassword($userPassword,$userVariables['user_id']);
			}
			
			$data['allUsers']=$userObject->getAllUsers(1,$function);
			$notificationArray['type'] = 'Success';
			$notificationArray['message'] =  showmsg('super admin','update');
			$is_edit = true;
			$page="view_super_admin.php"; 
		}
		else
		{	
			//print_r($userVariables); exit;
			if(!$userObject->isUserExist($userVariables['user_name'])){
				$userPassword=$userVariables['user_password'];
				$userVariables['user_password']="";
				
				$userVariables['added_by']=$_SESSION['user_id'];
				$userVariables['added_date']=date('Y-m-d H:i:s');
				
				$userVariables['user_group'] = 3;
				$userVariables['user_email'] = strtolower($userVariables['user_email']);	 			
				$user_id=$userObject->insert($userVariables);
				$userObject->setPassword($userPassword,$user_id);
				
				//$data['allGroups']=$groupObject->getAll();
				$notificationArray['type'] = 'Success';
				$notificationArray['message'] = showmsg('super admin','add'); 
				$page="view_super_admin.php"; 
			}else{
				$is_exist = true;
				$notificationArray['type'] = 'Failed';
				$notificationArray['message'] = showmsg('super admin','dup','username'); 
				$page="add_edit_superadmin.php";
			}
		}
		$data['show_status'] = 1;
		$data['userVariables'] = $userVariables;
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);		
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		//echo "<pre>"; print_r($data['allUsers']); exit;
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
	break;
	
	case "delete_super_user":
		$data['user_id'] = $logArray['user_id'];
		$data['group_id'] = $logArray['group_id'];	
		$user_id=$_POST['edit_id'];
		$userObject->delete($user_id,'user_id');
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('super admin','delete'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		//$data['arr_manager'] = $userObject->get_managers();
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		//$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$page="view_super_admin.php";
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
	break;
		
	case "restore_super_user":
		$data['user_id'] = $logArray['user_id'];
		$data['group_id'] = $logArray['group_id'];	
		$user_id=$_POST['edit_id'];
		$userObject->restoreEntry('user_id',$user_id);
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('super admin','restore'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		//$data['arr_manager'] = $userObject->get_managers();
		//$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$page="view_super_admin.php";
		
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
	break;
	///////////////////////////////////////
	
	case "view_custom_user":
		
		$data['show_status'] = $show_status;
		//echo "<pre>"; print_r($_SESSION);
		$data['allUsers'] = $userObject->getAllUsers($show_status,$function,$logArray['user_id']);
		//print_r($data['allUsers']); exit;
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();
		$data['arr_manager'] = $userObject->get_managers();
		$page = "view_custom_users.php";
		
		// for managing actions permissions
		if(isset($_POST['mainfunction']))
		{
			$data['mainfunction'] = $_POST['mainfunction'];
			$data['subfunction_name'] = $_POST['subfunction_name'];					
		}
		else
		{
			$data['mainfunction'] = $mainfunction;
			$data['subfunction_name'] = $subfunction_name;
		}
		
		$arr_permissions = get_action_permissions($logArray['user_id'],$current_pageid,$function,$function_type,$set_subfunction_id);
		$data['arr_permission'] = $arr_permissions;	
		///////////////////////////////////////
		
	break;
	
	//case"show_super_user_form":
	case"show_custom_user_form":
		$edit_id = $_POST['edit_id'];	
		if( $edit_id != 0)
		{	
			$userDetails = $userObject->getRecordById('user_id',$edit_id);
			//$data['userDetails']['edit_id'] = $_POST['edit_id'];  
			$data['userDetails']  = $userDetails;
		}
		$data['arr_manager'] = $userObject->get_managers();
		$data['show_status'] = 1;
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$page="add_edit_custom_user.php";
		
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
	break;
	
	//case "save_super_user":
	case "save_custom_user":
		$userObject->getUserVariables();
		$userVariables = $userObject->db_fields;
		$data['arr_manager'] = $userObject->get_managers();
		//echo strtolower($userVariables['user_email']); exit;
		if(($_POST['edit_id']) !== '0')
		{
				$userVariables['user_id']=$_POST['edit_id'];
				$userPassword=$userVariables['user_password'];
				unset($userVariables['user_password']);
				$previousPass=$userObject->getUserPassword($userVariables['user_id']);
				$userVariables['modified_by'] = $logArray['user_id'];
				$userVariables['modified_date']=date('Y-m-d H:i:s');
				if($function == "save_custom_user")
					$userVariables['user_group'] = $_POST['user_group'];
				else if($function == "save_manager")
					$userVariables['user_group'] = 2;
				else if($function == "save_super_user"){
					$userVariables['user_manager'] = $_POST['selmanager'];
					$userVariables['user_group'] = 3;
				}
				else if($function == "save_user")
				{
					$userVariables['user_manager'] = $_POST['selmanager'];
					$userVariables['user_group'] = 4;
				}
					 
				$userVariables['user_email'] = strtolower($userVariables['user_email']);	 				
				$userObject->update($userVariables,'user_id');
				$sha1_currentpass=sha1($userPassword);
				if(!($sha1_currentpass==$previousPass)  && !($sha1_currentpass==sha1('********'))){
					$userObject->setPassword($userPassword,$userVariables['user_id']);
				}
				$data['allUsers']=$userObject->getAllUsers(1,$function);
				
				$notificationArray['type'] = 'Success';
				$notificationArray['message'] =  showmsg('custom user','update');
					
			
				$is_edit = true;
				$page="view_custom_users.php";
				
		}
		else
		{	
					if(!$userObject->isUserExist($userVariables['user_name'])){
					
					$userPassword=$userVariables['user_password'];
					$userVariables['user_password']="";
					
					$userVariables['added_by']=$_SESSION['user_id'];
					$userVariables['added_date']=date('Y-m-d H:i:s');
					
					if($function == "save_custom_user")
						$userVariables['user_group'] = $_POST['user_group'];
					else if($function == "save_manager")
						$userVariables['user_group'] = 2;
					else if($function == "save_super_user"){
						$userVariables['user_manager'] = $_POST['selmanager'];
						$userVariables['user_group'] = 3;
					}
					else if($function == "save_user")
					{
						$userVariables['user_manager'] = $_POST['selmanager'];
						$userVariables['user_group'] = 4;
					}
						
					$userVariables['user_email'] = strtolower($userVariables['user_email']);	 			
					$user_id=$userObject->insert($userVariables);
					$userObject->setPassword($userPassword,$user_id);
					
					/*$selectedGroupArray=array();
					if(isset($_POST['groups'])){
						$selectedGroupArray=$_POST['groups'];
					}
					array_push($selectedGroupArray ,$_POST['user_group']);
			*/
					//$groupObject->setGroupPermissionsForUser($user_id,$selectedGroupArray);
					$data['allGroups']=$groupObject->getAll();
					$notificationArray['type'] = 'Success';
					$notificationArray['message'] = showmsg('custom user','add'); 
					$page="view_custom_users.php";
				}else{
					$userObject->getUserVariables();
					$userVariables = $userObject->db_fields;
					
					$is_exist = true;
					$data['allGroups']=$groupObject->getAll();
					$notificationArray['type'] = 'Failed';
					$notificationArray['message'] = showmsg('custom user','dup','username'); 
					$page = "add_edit_custom_view.php";
				}
		}
		$data['show_status'] = 1;
		$data['userVariables'] = $userVariables;
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']); 
		
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
	break;
	
	//case "delete_super_user":
	case "delete_custom_user":
		$user_id=$_POST['edit_id'];
		$userObject->delete($user_id,'user_id');
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('custom user','delete'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		$data['arr_manager'] = $userObject->get_managers();
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$page="view_custom_users.php";
		
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
	break;
		
	//case "restore_super_user":
	case "restore_custom_user":
		$user_id=$_POST['edit_id'];
		$userObject->restoreEntry('user_id',$user_id);
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('custom user','restore'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		$data['arr_manager'] = $userObject->get_managers();
		$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$page="view_custom_users.php";
		
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
	break;
	
	/************** cases of manager ************************/
	case "view_manager":
		$data['show_status'] = $show_status;
		$data['allUsers'] = $userObject->getAllUsers($show_status,$function,$logArray['user_id']);
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();
		$data['arr_manager'] = $userObject->get_managers();
		$page = "manage_managers.php";
		
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
	break;
	
	case"show_manager_form":
		$edit_id = $_POST['edit_id'];	
		if( $edit_id != 0)
		{	
			$userDetails = $userObject->getRecordById('user_id',$edit_id);
			//$data['userDetails']['edit_id'] = $_POST['edit_id'];  
			$data['userDetails']  = $userDetails;
			$data['assignedUsers'] = $userObject->getAllAssignedUsersToManager($edit_id);
		}else{
			$data['assignedUsers'] = $userObject->getAllAssignedUsersToManager();
		}
		$page="add_edit_manager.php";
		
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
	break;
	
	case "save_manager":
		$userObject->getUserVariables();
		$userVariables = $userObject->db_fields;
		if(($_POST['edit_id']) !== '0')
		{
			$userVariables['user_id']=$_POST['edit_id'];
			$userPassword=$userVariables['user_password'];
			unset($userVariables['user_password']);
			$previousPass=$userObject->getUserPassword($userVariables['user_id']);
			$userVariables['modified_by'] = $logArray['user_id'];
			$userVariables['modified_date']=date('Y-m-d H:i:s');
			$userVariables['user_group'] = 2;
			$userVariables['user_email'] = strtolower($userVariables['user_email']);	 				
			$userObject->update($userVariables,'user_id');
			$sha1_currentpass=sha1($userPassword);
			if(!($sha1_currentpass==$previousPass)  && !($sha1_currentpass==sha1('********'))){
				$userObject->setPassword($userPassword,$userVariables['user_id']);
			}
			
			if(isset($_POST['user_array'])){
				$userVariables['user_array'] = $_POST['user_array'];
				$userObject->assignManagerToUsers($userVariables['user_id'],$userVariables['user_array']);
			}else{
				$userObject->assignManagerToUsers($userVariables['user_id']);	
			}
			$data['allUsers']=$userObject->getAllUsers(1,$function);
			$notificationArray['type'] = 'Success';
			$notificationArray['message'] =  showmsg('manager','update');
			$is_edit = true;
			$page="manage_managers.php"; 
		}
		else
		{	
			if(!$userObject->isUserExist($userVariables['user_name'])){
				$userPassword=$userVariables['user_password'];
				$userVariables['user_password']="";
				
				$userVariables['added_by']=$_SESSION['user_id'];
				$userVariables['added_date']=date('Y-m-d H:i:s');
				
				$userVariables['user_group'] = 2;
				$userVariables['user_email'] = strtolower($userVariables['user_email']);	 			
				$user_id=$userObject->insert($userVariables);
				$userObject->setPassword($userPassword,$user_id);
				
				if(isset($_POST['user_array'])){
					$userVariables['user_array'] = $_POST['user_array'];
					$userObject->assignManagerToUsers($user_id,$userVariables['user_array']);
				}else{
					$userObject->assignManagerToUsers($user_id);	
				}
				//$userObject->assignManagerToUsers($user_id,$userVariables['user_array']);
				//$data['allGroups']=$groupObject->getAll();
				$notificationArray['type'] = 'Success';
				$notificationArray['message'] = showmsg('manager','add'); 
				$page="manage_managers.php"; 
			}else{
				//$userVariables['user_array'] = $_POST['user_array'];
				$data['assignedUsers'] = $userObject->getAllAssignedUsersToManager();
				$data['checkedUsers'] = array();
				if(isset($_POST['user_array'])){
				$data['checkedUsers'] = $_POST['user_array'];
				}
				//print_r($data['checkedUsers']); exit;
				$is_exist = true;
				//$data['allGroups']=$groupObject->getAll();
				$notificationArray['type'] = 'Failed';
				$notificationArray['message'] = showmsg('manager','dup','username'); 
				$page="add_edit_manager.php";
			}
		}
		$data['show_status'] = 1;
		$data['userVariables'] = $userVariables;
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['allGroups']=$groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		//$page="manage_managers.php";
		
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
	break;
	
	case "delete_manager":
		$user_id=$_POST['edit_id'];
		$userObject->delete($user_id,'user_id');
		$userObject->removeUsersUnderThisManager($user_id);
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('manager','delete'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$page="manage_managers.php";
		
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
	break;
	
	case "restore_manager":
		$user_id=$_POST['edit_id'];
		$userObject->restoreEntry('user_id',$user_id);
		$notificationArray['type'] = 'Success';
		$notificationArray['message'] = showmsg('manager','restore'); 
		if(isset($_POST['show_status']))
			$data['show_status'] = $_POST['show_status'];
		else
			$data['show_status'] = 1;
		$data['allGroups'] = $groupObject->getAll();
		$data['rec_counts'] = $userObject->get_allcounts($function,$logArray['user_id']);
		$data['allUsers'] = $userObject->getAllUsers($data['show_status'],$function,$logArray['user_id']);
		$page="manage_managers.php";
		
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
	break;
	/*****************END manager ******************************/
	/****************** cases of Groups **************************************/
	
	case "view_groups":       
				if(isset($_POST['show_status']))
					$data['show_status'] = $_POST['show_status'];
				else
					$data['show_status'] = 1;
				$data['allGroups'] = $groupObject->getAllGroup($data['show_status']);
				$data['rec_counts'] = $groupObject->get_allcounts();
				
				// for managing actions permissions
				if(isset($_POST['mainfunction']))
				{
					$data['mainfunction'] = $_POST['mainfunction'];
					$data['subfunction_name'] = $_POST['subfunction_name'];					
				}
				else
				{
					$data['mainfunction'] = $mainfunction;
					$data['subfunction_name'] = $subfunction_name;
				}
				
				$arr_permissions = get_action_permissions($logArray['user_id'],$current_pageid,$function,$function_type,$set_subfunction_id);
				$data['arr_permission'] = $arr_permissions;	
				///////////////////////////////////
				//$data['allGroups']=$groupObject->getAllGroups();
				//var_dump($data['allGroups']);exit;
				$page="manage_groups.php";
			break;
			
			case "show_add_group":
									
				$data['arr_perm_data'] = get_permission_data();
				//echo "<pre>"; print_r($data['arr_perm_data']); exit;
				$page = "edit_group.php";
			break;
			
			case "save_group" :			
					
				$groupVariables = $groupObject->getGroupVariablesNew();
				//print_r($groupVariables); exit;
				$groupVariables['modified_by']=$_SESSION['user_id'];
				$groupVariables['modified_date']=date('Y-m-d H:i:s');
				//print_r($_POST); exit;
				if($_POST['edit_id'] != 0)
				{	
					$groupVariables['group_id'] = $_POST['edit_id'];
					if(!$groupObject->isGroupExistNew($groupVariables['group_name'],$groupVariables['group_id']))
					{																			
						$groupObject->updateUsingId($groupVariables);					
						$groupObject->save_permission($_POST,$groupVariables['group_id']);						
						$notificationArray['type'] = 'Success';
						$notificationArray['message'] = showmsg('group','update');;
						$page="manage_groups.php";
					}
					
				}
				elseif(!$groupObject->isGroupExistNew($groupVariables['group_name']))
				{		
					
					$group_id=$groupObject->insertGroupNew($groupVariables);
					//echo "----".$group_id; exit;
					$groupObject->save_permission($_POST,$group_id);
					$notificationArray['type'] = 'Success';
					$notificationArray['message'] = showmsg('group','add');;
					$page="manage_groups.php";
				}
				else
				{
					$is_exist = true;
					$data['groupDetails'] = $groupVariables;
					$data['arr_perm_data'] = get_permission_data();
					$notificationArray['type'] = 'Failed';
					$notificationArray['message'] = showmsg('group','dup','group name');  
					$page = "edit_group.php";
				}
				$data['show_status'] = 1;
				$data['allGroups']=$groupObject->getAllGroups();
				$data['rec_counts'] = $groupObject->get_allcounts();
				
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
			break;	
					
				
			/*case "add_group":
					$groupVariables = $groupObject->getGroupVariablesNew();
					if(!$groupObject->isGroupExistNew($groupVariables['group_name']))
					{
						$groupVariables['added_by']=$_SESSION['user_id'];
						$groupVariables['added_date']=date('Y-m-d H:i:s');
						$group_id=$groupObject->insertGroupNew($groupVariables);
						//print_r($_REQUEST); exit;
						$selectedFunctionArray=array();
						if(isset($_REQUEST['functions'])){
							$selectedFunctionArray=$_REQUEST['functions'];
							$groupObject->setPagePermissionsForGroupNew($group_id,$selectedFunctionArray);
						}
						$notificationArray['type'] = 'Success';
						$notificationArray['message'] = showmsg('group','add');;
						$page="manage_groups.php";
					}
					else
					{
						$is_exist = true;
						$data['allPages'] = $pageObject->getAllPages();
						$data['allFunctions'] = $functionObject->getAll('functions');
						$data['allGroups']=$groupObject->getAllGroups();
						$data['groupDetails'] = $groupVariables;
						$data['groupPermissionDetails'] = $_REQUEST['functions'];
						$notificationArray['type'] = 'Failed';
						$notificationArray['message'] = showmsg('group','dup','group name');  
						$page = "edit_group.php";
					}
					$data['allGroups']=$groupObject->getAllGroups();
					$data['rec_counts'] = $groupObject->get_allcounts();
			break;*/
			
			case "edit_group":
				$data['allPages'] = $pageObject->getAllPages();
				$data['allFunctions'] = $functionObject->getAll('functions');
				$data['allGroups']=$groupObject->getAllGroups();
				 
				$group_Id = $_REQUEST['edit_id'];
				$groupDetails=$groupObject->getGroupDetailsNew($group_Id);
				$data['groupDetails'] = $groupDetails;
				
				$data['arr_perm_data'] = get_permission_data();
				$page = "edit_group.php";
				
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
			break;
			
			/*case "edit_group_entry":
					//echo "<pre>"; print_r($_REQUEST); exit;
					$groupVariables = $groupObject->getGroupVariablesNew();
					$groupVariables['group_id'] = $_REQUEST['edit_id'];
					
					if(!$groupObject->isGroupExistNew($groupVariables['group_name'],$groupVariables['group_id']))
					{
						
						$groupVariables['modified_by']=$_SESSION['user_id'];
						$groupVariables['modified_date']=date('Y-m-d H:i:s');
						
						$groupObject->updateUsingId($groupVariables);
						
						$selectedFunctionArray=array();
						if(isset($_REQUEST['functions'])){
							$selectedFunctionArray=$_REQUEST['functions'];
						}
						$groupObject->setPagePermissionsForGroupNew($groupVariables['group_id'],$selectedFunctionArray);
						$notificationArray['type'] = 'Success';
						$notificationArray['message'] = showmsg('group','update');;
						$page="manage_groups.php";
					}
					else
					{
						$is_edit = true;
						$is_exist = true;
						$data['allPages'] = $pageObject->getAllPages();
						$data['allFunctions'] = $functionObject->getAll('functions');
						$data['allGroups']=$groupObject->getAllGroups();
						$data['groupDetails'] = $groupVariables;
						if(isset($_REQUEST['functions']))
							$data['groupPermissionDetails'] = $_REQUEST['functions'];
						$notificationArray['type'] = 'Failed';
						$notificationArray['message'] = showmsg('group','dup','group name'); 
						$page = "edit_group.php";
					}
					$data['allGroups']=$groupObject->getAllGroups();
					$data['rec_counts'] = $groupObject->get_allcounts();
			break;*/
			
			case "delete_group":
				$group_id=$_REQUEST['edit_id'];
				if($group_id != 1){
					$groupObject->deleteUsingIdNew($group_id);
					$notificationArray['type'] = 'Success';
					$notificationArray['message'] = showmsg('group','delete');
					
				}else{
					$notificationArray['type'] = 'Failed';
					$notificationArray['message'] = $arr_msg['group']['delpermission'];
				}
				if(isset($_POST['show_status']))
					$data['show_status'] = $_POST['show_status'];
				else
					$data['show_status'] = 1;
				$data['allGroups']=$groupObject->getAllGroup($data['show_status']);
				$data['rec_counts'] = $groupObject->get_allcounts();
				$page="manage_groups.php";
			
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
			break;
			
			case "restore_group":
				if(isset($_POST['show_status']))
					$data['show_status'] = $_POST['show_status'];
				else
					$data['show_status'] = 1;
				$group_id=$_REQUEST['edit_id'];
				$groupObject->restoreGroup($group_id);
				$data['rec_counts'] = $groupObject->get_allcounts();
				$data['allGroups'] = $groupObject->getAllGroup($data['show_status']);
				$notificationArray['type'] = 'Success';
				$notificationArray['message'] = showmsg('group','restore');				
				$page="manage_groups.php";
				
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
			break;	
}
?>