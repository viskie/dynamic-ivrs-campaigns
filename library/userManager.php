<?
//Vishak Nair - 25/08/2012
//for user management
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

require_once('Config.php');

class UserManager extends commonObject
{
	//to get all the users.
	/*function getAllUsers()
	{o
		$userGroup = array();
		$userGroup = $_SESSION['user_group'];
		//print_r($userGroup);exit;
		$first = true;
		$user_groups = "";
		foreach($userGroup as $key=>$value)
		{
			if(!$first)
				$user_groups.= ", ";
			else
				$first = false;
				
			$user_groups.= $value;	
			
		}
		//var_dump($user_groups);exit;
		return $resultSet = getData("SELECT * FROM `users` WHERE is_active =1 AND `user_id` in (SELECT `user_id` FROM `user_group_permissions` WHERE `group_id` IN (".$user_groups.") AND is_active=1) group by users.user_id");
	}*/
	
	public $db_fields;
	function UserManager()
	{
		$this->table_name = "users";
		$this->db_fields = array();	
	}
	
	
	function getAllUsers($value=1,$function='',$userid='')
	{	
		if($value==0){
			if($function == "view_users" || $function== "show_user_form" || $function=="delete_user" || $function=="restore_user")
			{	
				$check_group = getData("select user_group from `".$this->table_name."` where user_id ='".$userid."' ");
				if(count($check_group > 0) && $check_group[0]['user_group'] == 2){
					return getData("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `".$this->table_name."` WHERE user_group =4 and user_manager = ".$userid." ORDER BY is_active DESC ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id` ");
					//return getData("select * from `".$this->table_name."` where user_group =4 and user_manager = ".$userid."  order by is_active desc "); 
				}else{
					return getData("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `".$this->table_name."` WHERE user_group =4 ORDER BY is_active DESC ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id` ");
		  			//return getData("select * from `".$this->table_name."` where user_group =4 order by is_active desc "); 
				}
			}
			else if($function == "view_manager" || $function== "show_manager_form" || $function=="delete_manager" || $function=="restore_manager")
			{	
		  		return getData("select * from `".$this->table_name."` where user_group=2 order by is_active desc ");
			}
			else if($function == "view_super_user" || $function== "show_super_user_form" || $function=="delete_super_user" || $function=="restore_super_user")
			{
				return getData("select * from `".$this->table_name."` where user_group=3 order by is_active desc ");
			}
			else if($function == "view_custom_user" || $function== "show_custom_user_form" || $function=="delete_custom_user_user" || $function=="restore_custom_user")
		  		return getData("select * from `".$this->table_name."` where user_group not in(1,2,3,4) order by is_active desc ");		
		}
		 else if($value==1){
			if($function == "view_users" || $function== "show_user_form" || $function=="save_user" || $function=="delete_user" || $function=="restore_user")
			{
				$check_group = getData("select user_group from `".$this->table_name."` where user_id ='".$userid."' ");
				if(count($check_group) > 0 && $check_group[0]['user_group'] == 2){
					return getData("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `".$this->table_name."` WHERE user_group =4 and user_manager = ".$userid." and  is_active=1 ORDER BY is_active DESC ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id` ");
					//return getData("select * from `".$this->table_name."` where user_group =4 and user_manager = ".$userid." and  is_active=1 order by is_active desc "); 
				}else{
					return getData("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `".$this->table_name."` WHERE user_group =4  and is_active=1 ORDER BY is_active DESC ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id` ");
		  			return getData("select * from `".$this->table_name."` where user_group =4 and is_active=1");
				}
			}
			else if($function == "view_manager" || $function== "show_manager_form" || $function=="save_manager" || $function=="delete_manager" || $function=="restore_manager")
		  		return getData("select * from `".$this->table_name."` where user_group=2 and is_active=1");
			else if($function == "view_super_user" || $function== "show_super_user_form" || $function=="save_super_user" || $function=="delete_super_user" || $function=="restore_super_user")
		  		return getData("select * from `".$this->table_name."` where user_group=3 and is_active=1");	
			else if($function == "view_custom_user" || $function== "show_custom_user_form" || $function=="save_custom_user" || $function=="delete_custom_user" || $function=="restore_custom_user")		 
				return getData("select * from `".$this->table_name."` where user_group not in(1,2,3,4) and is_active=1");	
		}
		else if($value==2){
			if($function == "view_users" || $function== "show_user_form" || $function=="delete_user" || $function=="restore_user")
			{
				$check_group = getData("select user_group from `".$this->table_name."` where user_id ='".$userid."' ");
				if(count($check_group > 0) && $check_group[0]['user_group'] == 2){
					return getData("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `".$this->table_name."` WHERE user_group =4 and user_manager = ".$userid." and  is_active=0 ORDER BY is_active DESC ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id` ");	
					//return getData("select * from `".$this->table_name."` where user_group =4 and user_manager = ".$userid." and is_active=0 order by is_active desc "); 
				}else{
		  			//return getData("select * from `".$this->table_name."` where user_group=4 and is_active=0");
					return getData("SELECT USER . * , MANAGER.manager_name FROM ( SELECT * FROM `".$this->table_name."` WHERE user_group =4  and is_active=0 ORDER BY is_active DESC ) AS USER LEFT JOIN ( SELECT `user_id` , `user_name` AS manager_name FROM `users` ) AS MANAGER ON USER.`user_manager` = MANAGER.`user_id` ");
				}
			}
			else if($function == "view_manager" || $function== "show_manager_form" || $function=="delete_manager" || $function=="restore_manager")
		  		return getData("select * from `".$this->table_name."` where user_group=2 and is_active=0");
			else if($function == "view_super_user" || $function== "show_super_user_form" || $function=="delete_super_user" || $function=="restore_super_user")
		  		return getData("select * from `".$this->table_name."` where user_group=3 and is_active=0");
			else if($function == "view_custom_user" || $function== "show_custom_user_form" || $function=="delete_custom_user" || $function=="restore_custom_user")
				return getData("select * from `".$this->table_name."` where user_group not in(1,2,3,4) and is_active=0");
		}
	}
	
	function get_allcounts($function='',$userid='')
	{
		$arr_counts = array();
		if($function == "view_users" || $function=="show_user_form" || $function=="save_user" || $function=="delete_user" || $function=="restore_user")
		{	
			$check_group = getData("select user_group from `".$this->table_name."` where user_id ='".$userid."' ");
			if(count($check_group > 0) && $check_group[0]['user_group'] == 2)
			{
				$all = getOne("SELECT COUNT(user_id) AS CNT From users WHERE user_group=4 and user_manager = ".$userid."");
				$arr_counts['all'] = $all;
				$active = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 1 AND user_group =4 and user_manager = ".$userid."");
				$arr_counts['active'] = $active;
				$trash = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 0 AND user_group=4 and user_manager = ".$userid."");
				$arr_counts['deleted'] = $trash;
			}
			else
			{
				$all = getOne("SELECT COUNT(user_id) AS CNT From users WHERE user_group=4");
				$arr_counts['all'] = $all;
				$active = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 1 AND user_group =4");
				$arr_counts['active'] = $active;
				$trash = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 0 AND user_group=4");
				$arr_counts['deleted'] = $trash;
			}
		}
		else if($function == "view_manager" || $function=="show_manager_form" || $function=="save_manager" || $function=="delete_manager" || $function=="restore_manager"){
			$all = getOne("SELECT COUNT(user_id) AS CNT From users WHERE user_group=2");
			$arr_counts['all'] = $all;
			$active = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 1 AND user_group=2");
			$arr_counts['active'] = $active;
			$trash = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 0 AND user_group=2");
			$arr_counts['deleted'] = $trash;
		}
		else if($function == "view_super_user" || $function=="show_super_user_form" || $function=="save_super_user" || $function=="delete_super_user" || $function=="restore_super_user"){
			$all = getOne("SELECT COUNT(user_id) AS CNT From users WHERE user_group=3");
			$arr_counts['all'] = $all;
			$active = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 1 AND user_group=3");
			$arr_counts['active'] = $active;
			$trash = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 0 AND user_group=3");
			$arr_counts['deleted'] = $trash;
		}
		else if($function == "view_custom_user" || $function=="show_custom_user_form" || $function=="save_custom_user" || $function=="delete_custom_user" || $function=="restore_custom_user"){
			$all = getOne("SELECT COUNT(user_id) AS CNT From users WHERE user_group not in(1,2,3,4)");
			$arr_counts['all'] = $all;
			$active = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 1 AND user_group not in(1,2,3,4)");
			$arr_counts['active'] = $active;
			$trash = getOne("SELECT COUNT(user_id) AS CNT From users WHERE is_active = 0 AND user_group not in(1,2,3,4)");
			$arr_counts['deleted'] = $trash;
		}
		return $arr_counts;
		
	}
	
	function getAllAssignedUsersToManager($user_id=0){
		if($user_id == 0){
			return getData("SELECT * FROM users WHERE user_group = 4 and user_manager=0 and is_active=1");	
		}else{
			return getData("SELECT * FROM users WHERE user_group = 4 and is_active=1 and  user_manager in ('".$user_id."',0)");	
		}
	}
	
	
	function assignManagerToUsers($user_id,$userArray=array()){
		
		$resultSet = getData("SELECT user_id FROM users WHERE user_manager='".$user_id."'");
		if(count($resultSet)>0){
			foreach($resultSet as $result)
				$present_user[] = $result['user_id'];
			$intersect = array_intersect($userArray,$present_user);
			$new_user = array_diff($userArray,$intersect);
			$old_user = array_diff($present_user,$intersect);
			
			//print_r($new_user); exit;
			foreach($new_user as $key=>$value){
				updateData("UPDATE users SET user_manager='".$user_id."' WHERE user_id='".$value."'");
			}
			foreach($old_user as $key=>$value){
				updateData("UPDATE users SET user_manager=0 WHERE user_id='".$value."'");
			}
		}else{
			foreach($userArray as $key=>$value){
				updateData("UPDATE users SET user_manager='".$user_id."' WHERE user_id='".$value."'");
			}	
		}
	}
	
	function removeUsersUnderThisManager($user_id){
		updateData("UPDATE users SET user_manager=0 WHERE user_manager='".$user_id."'");
	}
	//
	function restoreUsers($user_id){
		updateData("UPDATE users SET is_active=1 WHERE user_id=".$user_id);
	}
	
	//To get all the details of perticular user using user_id
	function getUserDetails($user_id)
	{
		return $resultSet = getRow("select * from  users where is_active =1 AND user_id='".$user_id."'");
	}
	
	//To get all the details of perticular user using user_id
	function getUserPassword($user_id)
	{
		return $resultSet = getOne("select `user_password` from  users where is_active =1 AND user_id='".$user_id."'");
	}

	//To get name of perticular user using user_id
	function getUserNameUsingId($user_id){
		return $resultSet = getOne("select user_name from users where is_active =1 AND user_id='".$user_id."'");
	}

	//To get name of perticular user using user_id
	function getUserIdUsingName($user_name){
		return $resultSet = getOne("select user_id from users where is_active =1 AND user_name='".$user_name."'");
	}

	
	function getProfileInfo($user_id)
	{
		return $resultSet = getRow("select * from  users where user_id='".$user_id."'");
	}
	
	function getAllPermissionedGroupsOfGroup($user_id){
		$resultSet=getData("SELECT `group_id` FROM `user_group_permissions` WHERE `user_id`=".$user_id);
		//echo ("SELECT `group_id` FROM `user_group_permissions` WHERE `user_id`=".$user_id);exit;
		$permissionedGroups=array();
		for($i=0;$i<count($resultSet);$i++){
			$permissionedGroups[]=$resultSet[$i]['group_id'];
		}
		return $permissionedGroups;	
	}
		
	//To restrict duplicate in users.
	function isUserExist($user_name,$user_id=0){
		$query="select * from users where user_name='".$user_name."'";
		if($user_id!=0){
			$query.=" AND user_id!=".$user_id;
		}
		$resultSet = getData($query);
		if(sizeof($resultSet)>0){
			return true;
		}else{
			return false;
		}
	}
	
	//To update a row in users table using user_id. 
	function updateUsingId($dataArray){
		$updateQry=getUpdateDataString($dataArray,"users","user_id");
		updateData($updateQry);
	}
	
	//vishak
	function updateEmployeeInUser($employeeVariables)
	{
		updateData("UPDATE users SET user_name='".$employeeVariables['employee_user_name']."', name='".$employeeVariables['employee_name']."', user_email='".$employeeVariables['personal_email']."', user_phone=".$employeeVariables['phone_number'].",modified_by=".$employeeVariables['modified_by'].",modified_date='".$employeeVariables['modified_date']."' where user_id = ".$employeeVariables['user_id']."");	
		
		//exit;
	}
	
	function setPassword($newPassword,$user_id){
		updateData("UPDATE `users` SET `user_password`=sha1('".$newPassword."') WHERE is_active =1 AND `user_id`=".$user_id);
	}
	
	function setLanguage($newLanguage,$user_id){
		updateData("UPDATE `users` SET `preferred_language`='".$newLanguage."' WHERE is_active =1 AND `user_id`=".$user_id);
	}
	
	//To delete a row in users table using user_id. 
	function deleteUsingId($user_id){
		updateData("UPDATE `users` SET `is_active`=false WHERE `user_id`='".$user_id."'");
	}
		
	function insertUser($varArray)
	{
		$insertQry = getInsertDataString($varArray, 'users');
		updateData($insertQry);
		return mysql_insert_id();
	}
	
	//vishak
	function insertEmployeeInUsers($employeeVariables)
	{	
		$insertId = updateData("INSERT INTO `users` (user_group,user_name,user_password,name,user_email,user_phone,added_by,added_date)values (31,'".$employeeVariables['employee_user_name']."','".sha1($employeeVariables['employee_password'])."','".$employeeVariables['employee_name']."','".$employeeVariables['personal_email']."',".$employeeVariables['phone_number'].",".$employeeVariables['added_by'].",'".$employeeVariables['added_date']."');");
		return mysql_insert_id();
	}
	
	function getUserVariables()
	{
		//$this->db_fields['user_group'] = $_REQUEST['user_group'];
		if(isset($_REQUEST['user_name']))
			$this->db_fields['user_name'] = $_POST['user_name'];
		$this->db_fields['user_password'] = $_POST['user_password'];
		$this->db_fields['name'] = $_POST['name'];
    	$this->db_fields['user_email'] = $_POST['user_email'];
				
		$this->db_fields['company_name'] = $_REQUEST['company_name'];
		$this->db_fields['user_phone'] = $_REQUEST['user_phone'];
		$this->db_fields['address1'] = $_REQUEST['address1'];
		$this->db_fields['address2'] = $_REQUEST['address2'];
		$this->db_fields['city'] = $_REQUEST['city'];
		$this->db_fields['state'] = $_REQUEST['state'];
		$this->db_fields['zip_code'] = $_REQUEST['zip_code'];
		$this->db_fields['country'] = $_REQUEST['country'];							
	}
	
	function getProfileVariables()
	{
		$this->db_fields['user_group'] = $_SESSION['user_main_group'];
		$this->db_fields['user_name'] = $_SESSION['user_name'];
		$this->db_fields['user_password'] = $_REQUEST['user_password'];
		$this->db_fields['name'] = $_REQUEST['name'];
    	$this->db_fields['user_email'] = $_REQUEST['user_email'];	
		
		$this->db_fields['company_name'] = $_REQUEST['company_name'];
		$this->db_fields['user_phone'] = $_REQUEST['user_phone'];
		$this->db_fields['address1'] = $_REQUEST['address1'];
		$this->db_fields['address2'] = $_REQUEST['address2'];
		$this->db_fields['city'] = $_REQUEST['city'];
		$this->db_fields['state'] = $_REQUEST['state'];
		$this->db_fields['zip_code'] = $_REQUEST['zip_code'];
		$this->db_fields['country'] = $_REQUEST['country'];		
		
	}
	
	function get_managers()
	{
		$resultSet = getData("SELECT * FROM `users` WHERE user_group ='2' and  is_active = '1'");
		return $resultSet;
	}
	//Vishak Nair - 04/09/2012
	//To get the branch permission for user.
/*	function getAllPermissionedBranchesOfUser($user_id){
		$resultSet=getData("SELECT `branch_id` FROM `user_permissions_on_company` WHERE `user_id`=".$user_id);
		$permissionedBranches=array();
		for($i=0;$i<count($resultSet);$i++){
			$permissionedBranches[]=$resultSet[$i]['branch_id'];
		}
		return $permissionedBranches;
	}*/
	
	
	//
	//Vishak Nair - 04/09/2012
	//To set the branch permission for user.
/*	function setBranchPermissionsForUser($user_id,$branchArray){
		$first=true;
		updateData("DELETE FROM `user_permissions_on_company` WHERE `user_id`='".$user_id."'");
		updateData("DELETE FROM `user_permissions_on_original_company` WHERE `user_id`='".$user_id."'");
		if(count($branchArray)>0){
			$companyArray=getData("Select Distinct(`company_id`) from company_details where branch_id in (".implode(',',$branchArray).")");
			$query="INSERT INTO `user_permissions_on_original_company`(`user_id`, `company_id`) VALUES ";
			foreach($companyArray as $company){
				if(!$first){
					$query.=", ";
				}else{
					$first=false;
				}
				$query.="('".$user_id."','".$company['company_id']."')";				
			}
			updateData($query);
			
			$first=TRUE;
			$query="INSERT INTO `user_permissions_on_company`(`user_id`, `branch_id`) VALUES ";
			foreach($branchArray as $branchToAdd){
				if(!$first){
					$query.=", ";
				}else{
					$first=false;
				}
				$query.="('".$user_id."','".$branchToAdd."')";
			}
			updateData($query);
		}
		updateData("OPTIMIZE TABLE `user_permissions_on_original_company`");//To delete the data files related to the deleted records.
		updateData("OPTIMIZE TABLE `user_permissions_on_company`");//To delete the data files related to the deleted records.
	}*/
	
} 
?>
