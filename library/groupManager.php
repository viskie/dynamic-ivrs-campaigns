<?
//Vishak Nair - 23/08/2012
//for group management
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

require_once('Config.php');

class GroupManager extends commonObject
{
	//to get all the groups.
	/*function getAllGroups()
	{
		$userGroup = array();
		$userGroup = $_SESSION['user_group'];
		//print_r($userGroup);
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
		
		return $resultSet = getData("select * from  user_groups where is_active =1 AND group_id in (SELECT `permissioned_group_id` as `user_group` FROM `user_permissions_on_group` WHERE `group_id`IN (".$user_groups."))");
	}*/
	
	public $db_fields;
	function GroupManager()
	{
		$this->table_name = "user_groups";
		$this->db_fields = array();
	}
	
	function getAll($tableName=''){
		return $resultSet = getData("select * from  user_groups where is_active =1 and group_id not in (1,2,3,4)");	
	}
	
	function getAllGroups(){
		return $resultSet = getData("select * from  user_groups where is_active =1");
	}
	
	//To get all the details of perticular user group using group_id
	function getGroupDetails($group_id)
	{
		return $resultSet = getRow("select * from  user_groups where is_active =1 AND group_id='".$group_id."'");
	}

	//To get name of perticular group using group_id
	function getGroupNameUsingId($group_id){
		return $resultSet = getOne("select group_name from user_groups where is_active =1 AND group_id='".$group_id."'");
	}
	
	function getCrudPermissionsForCurrentPage($group_id,$current_page_id){
			$result_set = getData("SELECT `add`,`edit`,`delete` FROM `user_crud_permissions` WHERE `is_active` =1 AND `group_id`=".$group_id." AND `page_id`=".$current_page_id."");
			return $result_set;
	}
	
	
	//get all details wether perticuler group has permission to add a page (vishak)
	function getAllPermissionedPagesToAdd($groupId){
		
			$result_set = getData("SELECT `page_id` FROM `user_crud_permissions` WHERE `add` =1 AND `is_active` =1 AND `group_id`=".$groupId);
			$add_permission = array();
			for($i=0;$i<count($result_set);$i++){
				$add_permission[] = $result_set[$i]['page_id'];
			}
			//print_r($add_permission);exit;
			return $add_permission;
	}
	
	//get all details wether perticuler group has permission to edit a page (vishak)
	function getAllPermissionedPagesToEdit($groupId){
		
			$result_set = getData("SELECT `page_id` FROM `user_crud_permissions` WHERE `edit` =1 AND `is_active` =1 AND `group_id`=".$groupId);
			$edit_permission = array();
			for($i=0;$i<count($result_set);$i++){
				$edit_permission[] = $result_set[$i]['page_id'];
			}
			//print_r($add_permission);exit;
			return $edit_permission;
	}
	
	//get all details wether perticuler group has permission to delete a page (vishak)
	function getAllPermissionedPagesToDelete($groupId){
		
			$result_set = getData("SELECT `page_id` FROM `user_crud_permissions` WHERE `delete` =1 AND `is_active` =1 AND `group_id`=".$groupId);
			$delete_permission = array();
			for($i=0;$i<count($result_set);$i++){
				$delete_permission[] = $result_set[$i]['page_id'];
			}
			//print_r($add_permission);exit;
			return $delete_permission;
	}
	//To restrict duplicate in groups.
	function isGroupExist($group_name,$landing_page,$group_id=0){
		$query="select * from user_groups where group_name='".$group_name."' AND landing_page='".$landing_page."'";
		if($group_id!=0){
			$query="select * from user_groups where group_name='".$group_name."' AND landing_page='".$landing_page."' AND group_id!='".$group_id."'";
		}
		$resultSet = getData($query);
		if(sizeof($resultSet)>0){
			return true;
		}else{
			return false;
		}
	}
	
	//To update a row in user_group table using group_id. 
	function updateUsingId($dataArray){
		$updateQry=$this->getUpdateDataString($dataArray,"group_id");
		updateData($updateQry);
	}
	
	//To get the page permission for group.
	function getAllPermissionedPagesOfGroup($group_id){
		$resultSet=getData("SELECT `page_id` FROM `user_permissions` WHERE is_active =1 AND `group_id`=".$group_id);
		$permissionedPages=array();
		for($i=0;$i<count($resultSet);$i++){
			$permissionedPages[]=$resultSet[$i]['page_id'];
		}
		return $permissionedPages;
	}
	
	//To set the page permission for group.
	function setPagePermissionsForGroup($group_id,$pageArray){
		$first=true;
		updateData("DELETE FROM `user_permissions` WHERE  page_id!=1 AND `group_id`=".$group_id);
		if(count($pageArray)>0){
			$query="INSERT INTO `user_permissions`(`group_id`, `page_id`) VALUES ";
			foreach($pageArray as $pageToAdd){
				if(!$first){
					$query.=", ";
				}else{
					$first=false;
				}
				$query.="('".$group_id."','".$pageToAdd."')";
			}
			updateData($query);
		}
		updateData("OPTIMIZE TABLE `user_permissions`");//To delete the data files related to the deleted records.
	}
	
	function setOnePagePermissionsForGroup($varArray){
		$insertQry = getInsertDataString($varArray, 'user_permissions');
		updateData($insertQry);
		return mysql_insert_id();
	}
	
	//To get the group permission for group.
	function getAllPermissionedGroupsOfGroup($group_id){
		$resultSet=getData("SELECT `permissioned_group_id` FROM `user_permissions_on_group` WHERE is_active =1 AND `group_id`=".$group_id);
		$permissionedGroups=array();
		for($i=0;$i<count($resultSet);$i++){
			$permissionedGroups[]=$resultSet[$i]['permissioned_group_id'];
		}
		return $permissionedGroups;
	}
	
	//To make the HTML list of checkboxes for permission of group.
	function makeGroupPermissionList($permissionedGroups=array()){
		$permissionedGroupsStr="<ul class=\"groupsCheckboxes\">";
		$allGroups=$this->getAllGroups();
		for($i=0;$i<count($allGroups);$i++){
			$permissionedGroupsStr.="<li><input type=\"checkbox\" name=\"groups[]\" value=\"".$allGroups[$i]['group_id']."\"";
			if(in_array($allGroups[$i]['group_id'],$permissionedGroups)){
				$permissionedGroupsStr.="checked=\"checked\"";
			}
			$permissionedGroupsStr.=">".$allGroups[$i]['group_name']."</li>";
		}
		return $permissionedGroupsStr."</ul>";
	}
	
	//To set the group permission for group.
	function setGroupPermissionsForGroup($group_id,$groupArray){
		$first=true;
		updateData("DELETE FROM `user_permissions_on_group` WHERE `group_id`=".$group_id);
		if(count($groupArray)>0){
			$query="INSERT INTO `user_permissions_on_group`(`group_id`, `permissioned_group_id`) VALUES ";
			foreach($groupArray as $groupToAdd){
				if(!$first){
					$query.=", ";
				}else{
					$first=false;
				}
				$query.="('".$group_id."','".$groupToAdd."')";
			}
			updateData($query);
		}
		updateData("OPTIMIZE TABLE `user_permissions_on_group`");//To delete the data files related to the deleted records.
	}
	
	//To set user belonging to multiple groups (vishak)
	/*function setGroupPermissionsForUser($user_id,$groupArray=array())
	{
		//echo("DELETE FROM `user_group_permissions` WHERE `user_id`=".$user_id);
		updateData("DELETE FROM `user_group_permissions` WHERE `user_id`=".$user_id);
		$first = true;
		//var_dump($groupArray);
		if(count($groupArray)>0)
		{
			$query = "INSERT INTO user_group_permissions (`user_id`,`group_id`) VALUES";
			foreach($groupArray as $groupToSet){
				if(!$first)
					$query.=", ";
				else
					$first = false;
				
				$query.="('".$user_id."','".$groupToSet."')";	
			}
			updateData($query);
		}
	}*/
	
	function deleteCRUDPermissionForGroup($group_id){
		updateData("DELETE FROM `user_crud_permissions` WHERE `group_id`='".$group_id."'");
		updateData("OPTIMIZE TABLE `user_crud_permissions`");
	}
	
	//function to set add/edit/delete perminsions of page to group (vishak)
	function setCRUDPermissionForGroup($crud_permission = array(),$group_id,$pages_array=array())
	{
		updateData("DELETE FROM `user_crud_permissions` WHERE `group_id`=".$group_id);
		//echo "done delete";exit;
		
		if(count($pages_array)!==0){
			$page_permission_array = array();
			//print_r($crud_permission);print_r($pages_array);
			foreach($crud_permission as $crud_value)
			{
				$pageId_permission = explode("_",$crud_value);
				$page_id = $pageId_permission[0];
				$permission = $pageId_permission[1];
				
				if(in_array($page_id,$pages_array)){
					//echo "tru for page_id".$page_id."<br>";
					//print_r($pages_array);
					if(array_key_exists($page_id,$page_permission_array)){
						$page_permission_array[$page_id][$permission] = 1;
					}
					else{
						$page_permission_array[$page_id][0] = 0;
						$page_permission_array[$page_id][1] = 0;
						$page_permission_array[$page_id][2] = 0;	
						$page_permission_array[$page_id][$permission] = 1;
					}
				}else{
					//echo "else executed";exit;
				}
			}
			//print_r($page_permission_array);
			foreach($page_permission_array as $key=>$value)
			{
				
				$page_id = $key;
				$first = true;
				$permission = "";
				foreach($value as $key=>$value)
				{
					if(!$first)
						$permission.= ", ";
					else
						$first = false;		
					$permission.= $value;	
				}
						
				$query = "INSERT INTO `user_crud_permissions` (`group_id`,`page_id`,`add`,`edit`,`delete`) VALUES (".$group_id.",".$page_id.",".$permission.")";
				//echo $query;
				updateData($query);
			}
		}
		updateData("OPTIMIZE TABLE `user_crud_permissions`");
	}
	
	//To delete a row in user_group table using group_id. 
	function deleteUsingId($group_id){
		updateData("UPDATE `user_groups` SET `is_active`=false WHERE `group_id`='".$group_id."'");
		updateData("UPDATE `user_permissions_on_group` SET `is_active`=false WHERE `group_id`='".$group_id."'");
		$user_groups =  implode(",", $_SESSION['user_group']);
		updateData("UPDATE `user_group_permissions` SET `is_active`=false WHERE `group_id`='".$group_id."'");
		updateData("UPDATE `user_crud_permissions` SET `is_active`=false WHERE `group_id`='".$group_id."'");
	}
		
	function insertGroup($varArray)
	{
		//echo $_SESSION['user_main_group']; exit;
		$insertQry = getInsertDataString($varArray, 'user_groups');
		updateData($insertQry);
		$insertedGroupId=mysql_insert_id();
		updateData("INSERT INTO `user_permissions_on_group`(`group_id`, `permissioned_group_id`) VALUES ('".$_SESSION['user_main_group']."','".$insertedGroupId."')");
		return $insertedGroupId;
	}
	
	function getGroupVariables()
	{
		$this->db_fields['group_name'] = $_REQUEST['group_name'];
		$this->db_fields['comments'] = $_REQUEST['comments'];
		$this->db_fields['landing_page'] = $_REQUEST['landing_page'];
		//return $varArray;
	}
	
	//************************************************************************************************
	
	function restoreGroup($group_id){
		updateData("UPDATE user_groups SET is_active=1 WHERE group_id=".$group_id);
		updateData("UPDATE user_permissions SET is_active=1 WHERE group_id=".$group_id);
	}
	
	function getAllGroup($status)
	{
		if($status == 0)
			return $resultSet = getData("SELECT * FROM `user_groups` WHERE 1 order by is_active desc");
		if($status == 1)
			return $resultSet = getData("SELECT * FROM `user_groups` WHERE is_active = 1 order by is_active desc");
		else		
			return $resultSet = getData("SELECT * FROM `user_groups` WHERE is_active = 0");
	}
	
	function get_allcounts()
	{
		$arr_counts = array();
		$all = getOne("SELECT COUNT(group_id) AS CNT From user_groups WHERE group_id!='".developer_grpid."'");
		$arr_counts['all'] = $all;
		$active = getOne("SELECT COUNT(group_id) AS CNT From user_groups WHERE is_active = 1 and group_id!='".developer_grpid."'");
		$arr_counts['active'] = $active;
		$trash = getOne("SELECT COUNT(group_id) AS CNT From user_groups WHERE is_active = 0 and group_id!='".developer_grpid."'");
		$arr_counts['deleted'] = $trash;
		return $arr_counts;
		
	}
	
	function getGroupVariablesNew()
	{
		$varArray['group_name'] = $_REQUEST['group_name'];
		$varArray['comments'] = $_REQUEST['comments'];
		//$this->db_fields['landing_page'] = $_REQUEST['landing_page'];
		return $varArray;
	}
	function isGroupExistNew($group_name,$group_id=0){
		
		$query="select * from user_groups where group_name='".$group_name."' AND is_active=1";
		
		if($group_id!=0)
		{
			$query.=" AND group_id!=".$group_id;
		}
		$resultSet = getData($query);
		if(sizeof($resultSet)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function insertGroupNew($varArray)
	{
		//echo $_SESSION['user_main_group']; exit;
		$insertQry = $this->getInsertDataString($varArray);
		updateData($insertQry);
		$insertedGroupId=mysql_insert_id();
		return $insertedGroupId;
	}
	
	function getGroupDetailsNew($group_id)
	{
		return $resultSet = getRow("select * from  user_groups where is_active =1 AND group_id='".$group_id."'");
	}
	
	function deleteUsingIdNew($group_id){
		updateData("UPDATE `user_groups` SET `is_active`=0 WHERE `group_id`='".$group_id."'");
		updateData("UPDATE `user_permissions` SET `is_active`=0 WHERE `group_id`='".$group_id."'");
	}
	
	//To set the page permission for group.
	function setPagePermissionsForGroupNew($group_id,$functionArray){
		//echo $group_id;
		//print_r($functionArray);exit;
		$first=true;
		updateData("DELETE FROM `user_permissions` WHERE  page_id!=1 AND `group_id`=".$group_id);
		if(count($functionArray)>0){
			$query="INSERT INTO `user_permissions`(`group_id`,`function_id`) VALUES ";
			foreach($functionArray as $funToAdd){
				if(!$first){
					$query.=", ";
				}else{
					$first=false;
				}
				$query.="('".$group_id."','".$funToAdd."')";
			}
			updateData($query);
		}
		updateData("OPTIMIZE TABLE `user_permissions`");//To delete the data files related to the deleted records.
	}
	
	function getAllPermissionedPagesOfGroupNew($group_id){
		$resultSet=getData("SELECT `function_id` FROM `user_permissions` WHERE is_active =1 AND `group_id`=".$group_id);
		$permissionedPages=array();
		for($i=0;$i<count($resultSet);$i++){
			$permissionedPages[]=$resultSet[$i]['function_id'];
		}
		return $permissionedPages;
	}
	
	function get_allgroups()
	{
		$arr_groups =  getData("SELECT group_id, group_name From user_groups WHERE is_active = 1");
		return ($arr_groups);
	}
	
	function save_permission($arr_data,$groupid)
	{
		$insert_data['group_id'] = $groupid;
		updateData("DELETE FROM user_permissions WHERE group_id = ".$groupid);
		if(isset($arr_data["page_perm"]))
		{
			foreach($arr_data["page_perm"] as $k=>$v)
			{	
				if(isset($arr_data["function_".$v]))
				{
					$insert_data['page_id'] = $v;
					foreach($arr_data["function_".$v] as $k1=>$v1)
					{
						if(isset($arr_data["subfunction_".$v.'_'.$v1]))
						{
							$insert_data['function_id'] = $v1;
							foreach($arr_data["subfunction_".$v.'_'.$v1] as $k3=>$v3)
							{
								$insert_data['sub_function_id'] = $v3;
								$insert_data['view_perm'] = 1;
								$insert_data['add_perm'] = 0;
								$insert_data['edit_perm'] = 0;
								$insert_data['delete_perm'] = 0;
								$insert_data['restore_perm'] = 0;
								if(isset($arr_data["subfunction_".$v.'_'.$v1.'_'.$v3]))
								{											
									foreach($arr_data["subfunction_".$v.'_'.$v1.'_'.$v3] as $k4=>$v4)
									{
										if($v4 == 'add')
											$insert_data['add_perm'] = 1;
										elseif($v4 == 'edit')
											$insert_data['edit_perm'] = 1;
										elseif($v4 == 'delete')
											$insert_data['delete_perm'] = 1;
										elseif($v4 == 'restore')
											$insert_data['restore_perm'] = 1;												
									}
								}
								$insert_data['is_active'] = 1;		
								/*$check_exist = getData("select permission_id from user_permissions WHERE group_id = '".$insert_data['group_id']."' and page_id = '".$insert_data['page_id']."' and function_id = '".$insert_data['function_id']."' and sub_function_id = '".$insert_data['sub_function_id']."'");
								
								if(count($check_exist) > 0)
								{
									echo "here".$check_exist[0]["permission_id"].'</br>';
									//echo "here".count($check_exist).$check_exist[0]["permission_id"];	exit;								
									$insert_data['permission_id'] = $check_exist[0]["permission_id"];
									$updateQry=$this->getUpdateDataString($insert_data,"user_permissions",'permission_id');
									updateData($updateQry);
								}
								else
								{*/
									//echo "<pre>"; print_r($insert_data);
									$insertQry = $this->getInsertDataString($insert_data, 'user_permissions');
									updateData($insertQry);		
								//}
							}
							
						}
					}
				}
			}
		}		
	}
	
}
?>