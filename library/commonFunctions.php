<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
}

function formatDate($date)
{
	if($date=="0000-00-00"){
		return "-";
	}else{
		return date("d-M-Y", strtotime($date));
	}

}

function has_access($current_page_id,$which_access){
	$user_groups = implode(",",$_SESSION['user_group']);
	$result_set = getData("select * from `user_crud_permissions` where `page_id`='".$current_page_id."' and `group_id` IN (".$user_groups.") and `".$which_access."`='1'");
	//print_r($result_set);exit;
	if(count($result_set) >0)
		return true;
	else
		return false;	
}

function getUserGroupPermissions()
{	
			$userGroup = array();
			//$userGroup = $_SESSION['user_group'];
			$userMainGroup = (int)$_SESSION['user_main_group'];
			$pageArray = array();
			$retArray = array();
			
			if($userMainGroup != 1)
			{
				$first = true;
				$user_groups = $userMainGroup;
				$pageArray = getData("select * from pages where page_id in ( select page_id from functions where function_id in ( select function_id from user_permissions where group_id='".$user_groups."')) order by tab_order ASC");
			}
			else
			{
					$pageArray = getData("select * from pages where is_active = 1 and level = 1 order by `tab_order` ASC");
			}
			
			foreach($pageArray as $value)
			{
					$retArray[$value['description']] = $value;
			}
			
			
			return $retArray;
}


function getPageTitle($pageName)
{
	return $page_title = getOne("select title from pages where page_name = '".$pageName."'");
}

function buildSidebar()
{
	
}

/*function purifyInputs()
{
	foreach($_POST as $key=>$value)
	{
		if(is_array($value)){	//Change: Msnthan Tripathi:25-Aug-2012. The function is giving error when passed array of checkboxes in query string.
			foreach($value as $keySub => $valueSub){
				if(is_array($valueSub)){
					foreach($valueSub as $key_sub_sub => $val_sub_sub){
						$value[$key_sub_sub] = addslashes($val_sub_sub);	
					}
				}
				else{
					$value[$keySub] = addslashes($valueSub);
				}
			}
		}else{
			$_POST[$key] = addslashes($value);
		}
	}
}*/

function purifyInputs($arr=array())
{
	if(empty($arr))
		$arr = $_POST;
		
	foreach($arr as $key=>$value)
	{
		if(is_array($value)){
			purifyInputs($value);	
		}else{
			$arr[$key] = addslashes($value);
		}
	}
}

function setPage()
{
	$page = "";
	if(isset($_POST['page']) && trim($_POST['page'])!=='')
	{
		$page = trim(addslashes($_POST['page']));
		if($page == 'home')
			$page = "home.php";
		else	
			$page = "manage_".$page.".php";	
	}
	else
	{
		$page = "manage_dashboard.php";
	}
	//echo "---".$page; exit;
	return $page;
}

function setFunction()
{
	$function = "";
	if(isset($_POST['function']) && trim($_POST['function'])!=='')
	{ 
		$function = trim(addslashes($_POST['function']));	
	}
	else{
		$function = "view";
	}
	return $function;
}

function setModule()
{
	$module = "";
	if(isset($_POST['page']) && trim($_POST['page'])!==''){ 
		$module = trim(addslashes($_POST['page']));	
	}else{
		$module = "dashboard";
	}
	return $module;
}


function setActiveInactive($value)
{
	
}

function createComboBox($name,$value,$display, $data, $blankField=false, $selectedValue="",$display2="",$firstFieldValue='Please Select', $otherParameters = "")
{	
	echo "<select id='".$name."' name = '".$name."' ".addslashes($otherParameters)." >";
	if($blankField){
		echo "<option value='0'>".$firstFieldValue."</option>";
	}
	for($d=0;$d<sizeof($data);$d++)
	{
		$selectedString = "";
		$selectedValue = trim($selectedValue);
		if($data[$d][$value] == $selectedValue)
		{
			$selectedString = " selected = 'selected' ";
		}
		
		echo "<option value='".$data[$d][$value]."' ".$selectedString.">".$data[$d][$display];
		if($display2!=""){
			echo " (".$data[$d][$display2].")";
		}
		echo "</option>";
	}
	echo "</select>";
}

/*function checkPagePermissions($fileName,$selectedFunction=1)
{
	$fileName = addslashes($fileName);

	$userGroup = array();
	$userGroup = $_SESSION['user_main_group'];

	if((int)$userGroup != 1)
	{
		$pageId = getOne("Select page_id from pages where page_name = '".$fileName."'");

		$pagePermission= getData("Select * from user_permissions where group_id IN(".$userGroup.") and page_id = '".$pageId."' and `".$selectedFunction."` = 1 and is_active = '1'");
		
		if(sizeof($pagePermission)>=1)
		{
			//Page-User Validated
		}
		else
		{	//echo $fileName;exit;
			
			 echo "
					<script type='text/javascript'>
						alert(\"You do not have permission to view this page.".$pageId." Please Contact administrator\");
						window.location = 'index.php';
					</script>
			 ";
			exit;
		}
	}
}*/

/*function checkPagePermissions($function)
{
	//$moduleName = addslashes($moduleName);

	$userGroup = array();
	$userGroup = $_SESSION['user_main_group'];

	if((int)$userGroup != 1)
	{
		$functionId = getOne("Select function_id from functions where function_name = '".$function."'");

		$pagePermission= getData("Select * from user_permissions where group_id IN(".$userGroup.") and function_id = '".$functionId."' and is_active = '1'");
		
		if(sizeof($pagePermission)>=1)
		{
			//Page-User Validated
		}
		else
		{	//echo $fileName;exit;
			
			 echo "
					<script type='text/javascript'>
						alert(\"You do not have permission to view this page.".$function." Please Contact administrator\");
						window.location = 'index.php';
					</script>
			 ";
			exit;
		}
	}
}*/

function insertLog($logArray)
{
	$insertQry = "INSERT INTO requestlog (`user_id`,`page`,`function`,`request_variables`,`session_variables`,`request_time`,`ip_address`) VALUES ('".$logArray['user_id']."','".$logArray['page']."','".$logArray['function']."','".$logArray['request_variables']."','".$logArray['session_variables']."','".$logArray['request_time']."','".$logArray['ip_address']."')";
	updateData($insertQry);
	return mysql_insert_id();
}

/* This function works as in_array() function for multi dimension array */
function multi_in_array($needle, $haystack, $strict = false) 
{
	foreach ($haystack as $item) {
		if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && multi_in_array($needle, $item, $strict))) {
			return true;
		}
	}
	return false; 	
} 	

function secondsToTime($seconds,$is_hms_padded=false)
{
	// extract hours
	$hours = floor($seconds / (60 * 60));
 
	// extract minutes
	$divisor_for_minutes = $seconds % (60 * 60);
	$minutes = floor($divisor_for_minutes / 60);
 
	// extract the remaining seconds
	$divisor_for_seconds = $divisor_for_minutes % 60;
	$seconds = ceil($divisor_for_seconds);
	
	// return the final array
	if($is_hms_padded==true)
	{	$obj = array(
			"h" => str_pad((int) $hours,2,"0",STR_PAD_LEFT),
			"m" => str_pad((int) $minutes,2,"0",STR_PAD_LEFT),
			"s" => str_pad((int) $seconds,2,"0",STR_PAD_LEFT)
		);
	}
	else
		{$obj = array(
			"h" => (int) $hours,
			"m" => (int) $minutes,
			"s" => (int) $seconds,
		);
	}
	return $obj;
}
	
	
function getDateTimeDiff($date1,$date2,$is_hms=false,$is_hms_padded=false)
{
	$seconds = strtotime($date1) - strtotime($date2); 
	if($is_hms==true)	
	{
		if($is_hms_padded==true)
		{	
			$diff = secondsToTime($seconds,true);
		}
		else
		{	$diff = secondsToTime($seconds);
		}
		
		return $diff;
	}
	else
	{
		return ($seconds);   // returns diff in seconds
	}
}

function showmsg($field, $action, $matchwith='')
{
	if($action == 'add')
		$msg = ucfirst(strtolower($field))." added successfully !";
	elseif($action == 'update')
		$msg = ucfirst(strtolower($field))." updated successfully!";
	if($action == 'delete')
		$msg = ucfirst(strtolower($field))." deleted successfully!";
	if($action == 'dup')
		$msg = "Duplicate ".strtolower($field)." with same ".$matchwith." found!";
	if($action == 'restore')
		$msg = ucfirst(strtolower($field))." restored successfully!";
	return $msg;
}

function populateNotification($notificationArray=array()){
	
	if(array_key_exists('type',$notificationArray)) {
		if($notificationArray['type'] == "Success") {
			echo "<span class='notification done'>".$notificationArray['message']."</span>";
		} else if($notificationArray['type'] == "Failed") {
			echo "<span class='notification undone'>".$notificationArray['message']."</span>";
		}
	}
}

function get_permission_data()
{
	$arr_permission = array();
	$arr_pages = getData("select * from pages WHERE is_active = 1");
	$i=0;
	foreach($arr_pages as $k=>$v)
	{
		$arr_permission[$i]['page_id'] = $v['page_id'];
		$arr_permission[$i]['module_name'] = $v['module_name'];
		$arr_functions = getData("select * from functions WHERE page_id = '".$v['page_id']."' and is_active=1 ORDER BY menu_order ASC");
		$j=0;
		foreach($arr_functions as $k1=>$v1)
		{
			$arr_permission[$i]['functions'][$j] = $v1;
			$arr_subfunctions = getData("select * from sub_functions WHERE main_function_id = '".$v1['function_id']."' and is_active=1 ORDER BY menu_order ASC");
			foreach($arr_subfunctions as $k2=>$v2)
			{
				$arr_permission[$i]['functions'][$j]['subfunction'][] = $v2;
			}
			$j++;
		}
		$i++;
	}
	//echo "<pre>"; print_r($arr_permission); //exit;
	return ($arr_permission);
}

function check_permission($check='',$groupid='',$pageid='',$functionid='',$subfunctionid='',$action='')
{
	if($check == 'all')
	{
		$perm_pages = getData("select DISTINCT page_id from user_permissions WHERE group_id = ".$groupid." and is_active=1");
		$pages 	= getData("select page_id from pages WHERE is_active = 1");
		if(count($perm_pages) == count($pages))
			return true;
		else
			return false;
	}
	else
	{
		$str_condition = ' group_id='.$groupid.' and is_active=1';
		if($pageid != "")
			$str_condition .= ' and page_id='.$pageid;
		if($functionid != "")
			$str_condition .= ' and function_id='.$functionid;
		if($subfunctionid != "")
			$str_condition .= ' and sub_function_id='.$subfunctionid;
		if($check == 'subfunction')
		{
			if($action == 'view')
				$str_condition .= ' and view_perm = 1';
			if($action == 'add')
				$str_condition .= ' and add_perm = 1';
			if($action == 'edit')
				$str_condition .= ' and edit_perm = 1';
			if($action == 'delete')
				$str_condition .= ' and delete_perm = 1';
			if($action == 'restore')
				$str_condition .= ' and restore_perm = 1';			
		}
		$arr_permission = getData("select permission_id from user_permissions WHERE ".$str_condition);
		//echo "<pre>"; print_r($arr_permission); exit;	
		if(count($arr_permission)> 0)
		 	return true;
		else
			return false;
	}	
}

function get_allmenu($userid)
{
	$groupid = getOne("select user_group from users WHERE user_id = '".$userid."'");
	$arr_allmenu = getData("select * from  pages WHERE is_active = 1");
	$arr_menu = array();
	foreach($arr_allmenu as $k=>$v)
	{
		if($groupid == developer_grpid)
		{
			$arr_menu[] = $v;
		}
		else
		{
			$check_permission = getData("select permission_id from user_permissions WHERE group_id = '".$groupid."' and page_id = '".$v['page_id']."' and is_active=1");
			if(count($check_permission) > 0)
			{
				$arr_menu[] = $v;
			}
		}
	} 
	return $arr_menu;
}

function get_allsubmenu($userid,$pageid)
{
	$groupid = getOne("select user_group  from users WHERE user_id = '".$userid."'"); 
	$arr_submenu = array();
	$arr_allsubmenu = getData("select * from  functions WHERE page_id = '".$pageid."' and is_active=1"); //echo 'here'.$groupid; //echo "<pre>"; print_r($arr_allsubmenu); exit;
	foreach($arr_allsubmenu as $k=>$v)
	{
		if($groupid == developer_grpid)
		{
			$arr_submenu[] = $v;
		}
		else
		{
			$check_permission = getData("select permission_id from  user_permissions WHERE group_id = '".$groupid."' and page_id = '".$pageid."' and function_id ='".$v['function_id']."' and is_active=1");
			if(count($check_permission) > 0)
			{
				$arr_submenu[] = $v;
			}
		}
	}
	//echo "<pre>"; print_r($arr_submenu); exit;
	return($arr_submenu);		
	
}

function get_currentpageid($module)
{
	$pageid = getOne('select page_id from pages where module_name = "'.$module.'"');
	return $pageid;
}
function get_current_functionid($name, $pageid)
{
	$functionid = getOne('select function_id from functions where function_name = "'.$name.'" and page_id="'.$pageid.'"');
	return $functionid;
}

function get_menudata($userid,$id,$byfield='',$pageid='')
{	
	if($byfield == 'function_name')
	{
		$functionid = get_current_functionid($id,$pageid);
		$cond = "main_function_id = '".$functionid."'";
	}
	else
		$cond = "main_function_id = '".$id."'";
	$arr_allfunctions = getData("select function_id, page_id, function_name, friendly_name, main_function_id, is_crud from sub_functions WHERE ".$cond." and is_active=1 ORDER BY menu_order ASC");
	
	$groupid = getOne("select user_group  from users WHERE user_id = '".$userid."'"); 
	//echo $groupid;
	$all_subfunction = array();
	foreach($arr_allfunctions as $k=>$v)
	{	
		if($groupid == developer_grpid)
		{
			$all_subfunction[] = $v;
		}
		else
		{
			$check_permission = getData("select * from  user_permissions WHERE group_id = '".$groupid."' and page_id = '".$v['page_id']."' and function_id  ='".$v['main_function_id']."' and sub_function_id = '".$v['function_id']."' and is_active=1");
			if(count($check_permission) > 0)
			{
				$all_subfunction[] = $v;
			}
		}
	}	
	
	return $all_subfunction;
}

function get_action_permissions($userid,$pageid,$function,$function_type,$set_subfunction_id)
{	//echo $groupid;//echo "here"; exit;
	$groupid = getOne("select user_group  from users WHERE user_id = '".$userid."'");
	if($groupid == developer_grpid)
	{
			$check_permission[0] = array(
				'permission_id' => 0,
				'view_perm' => 1,
				'add_perm' => 1,
				'edit_perm' => 1,
				'delete_perm' => 1,
				'restore_perm' => 1,
				);
	}
	else
	{	
		$cond='';
		if($function_type == '')
		{
			$functionid = get_current_functionid($function,$pageid);
			$cond .= ' function_id = "'.$functionid.'"';
		}
		elseif($function_type == 'subfunction')
		{
			$cond = " sub_function_id = '".$set_subfunction_id."'";
		}
		$check_permission = getData("select  permission_id, view_perm, add_perm, edit_perm, delete_perm, restore_perm from user_permissions WHERE group_id = '".$groupid."' and page_id = '".$pageid."' and ".$cond);
		//echo "select  permission_id, view_perm, add_perm, edit_perm, delete_perm, restore_perm from user_permissions WHERE group_id = '".$groupid."' and page_id = '".$pageid."' and ".$cond;		
		
	}
	//echo "<pre>"; print_r($check_permission); exit;
	return $check_permission;
	 
}

function checkUserPemission($arr_all,$group_id)
{	
	
	//$group_id = $_SESSION['user_main_group']; //echo "<pre>"; print_r($arr_all);	echo "here".$group_id; 
	$cond = " group_id = '".$group_id."'";	
	//echo "<pre>"; print_r($arr_all); echo '***'.$group_id; exit;
	if($group_id != developer_grpid)
	{
		if(isset($arr_all['page']))
		{
			$page_id 	= getOne("select page_id from pages WHERE page_name = '".$arr_all['page']."'");	
			if($page_id != "")
				$cond .= " and page_id = '".$page_id."'";
		}
		if((isset($arr_all['mainfunction']) && ($arr_all['mainfunction'] != 'show_box_view') ) || (isset($arr_all['function']) && ($arr_all['function'] != 'show_box_view') ))
		{	
			if(isset($arr_all['mainfunction'])  && (trim($arr_all['mainfunction']) != ''))
			{
				$function_name = $arr_all['mainfunction'];
			}
			else
			{	
				$function_name = $arr_all['function'];
			}
			
			$function_id = getOne("select function_id from functions WHERE page_id = '".$page_id."' and function_name = '".$function_name."'");
			if($function_id != "")
				$cond .= " and function_id = '".$function_id."'";			
			
			if(isset($arr_all['subfunction_name']))
			{	
				$subfunction_id = getOne("select function_id from sub_functions WHERE  main_function_id  = '".$function_id."' and function_name = '".$arr_all['subfunction_name']."'");
				if($subfunction_id != "")
					$cond .= " and sub_function_id = '".$subfunction_id."'";
			}
		}
		
		$check_permission= getData("Select permission_id from user_permissions where ".$cond);
		//echo "Select permission_id from user_permissions where ".$cond; echo count($check_permission); exit;
		if(count($check_permission) == 0)
		{
			 echo "<script type='text/javascript'>
						alert(\"You do not have permission to view this page.".$function." Please Contact administrator\");
						window.location = 'index.php';
				   </script>";
			exit;
		}
	}
	
}




?>