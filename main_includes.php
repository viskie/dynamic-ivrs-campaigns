<?php
require_once('library/checkSession.php');
require_once('library/constants.php');
require_once('library/commonFunctions.php');
require_once('library/commonObject.php');
require_once('library/Config.php');

//Purify all request vaiables
purifyInputs();

$request_string = http_build_query($_POST);
$session_string = http_build_query($_SESSION);

if(!(isset($_POST['page'])))
{
	$_POST['page']='';
}

if(!(isset($_POST['function'])))
{
	$_POST['function']='';
}

$logArray = array(
'user_id'=>$_SESSION['user_id'],
'page'=>$_POST['page'],
'function'=>$_POST['function'],
'request_variables'=>$request_string,
'session_variables'=>$session_string,
'request_time'=>date('Y-m-d H:i:s'),
'group_id' => $_SESSION['user_main_group'],
'ip_address'=>$_SERVER['REMOTE_ADDR']);

//$log_id = insertLog($logArray);


//Set Page anf Function Variables
$page = setPage();
$function = setFunction();
$module = setModule();
$pageTitle = getPageTitle($page);  

$current_pageid = get_currentpageid($module); 	//echo $current_pageid;

//echo $module."---".$function; 
//echo $module;exit;
log_history("Page-Function","Page=>".$page." :: Function=>".$function);	

//Check Page & Function Permissions
if($function!='logout')
{
	//echo "<pre>"; print_r($_REQUEST); exit; 
	$arr_all = array(
				'page' => $page,
				'function' => $function				
				);	
	if(isset($_REQUEST['mainfunction']))
		$arr_all['mainfunction'] = $_REQUEST['mainfunction'];
	if(isset($_REQUEST['subfunction_name']))
		$arr_all['subfunction_name'] = $_REQUEST['subfunction_name'];
	
	checkUserPemission($arr_all,$logArray['group_id']);
	//checkPagePermissions($page,$function);
	//checkPagePermissions($function);
}	


unset($_POST['page']);
unset($_POST['function']);
unset($_POST['page']);
unset($_POST['function']);
//Check Page & Function Permissions
/*if($function!='logout')
{
	//checkPagePermissions($page,$function);
	checkPagePermissions($function);
}*/
?>