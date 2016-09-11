<?php
if(strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false){
	Header("location: index.php");
}

//Define the Data Array 
$data = array();
$is_exist = false;
$is_edit = false;

$show_status = isset($_POST['show_status']) ? $_POST['show_status'] : 1;

//Define the Notification Array 
$notificationArray = array();

	// following code is done for managing view boxes on all pages and for managing permissions of functions and subfunctions
	$include_subcon = false;
	$function_type = 'subfunction';
	$is_internal_subfunction = 1;
	$set_subfunction_id = '';
	$subfunction_name = '';
	if($function == 'show_box_view')
	{	
		$is_internal_subfunction = 0;			
		$arr_allfunctions = get_allsubmenu($logArray['user_id'], $current_pageid);
		
		if(count($arr_allfunctions) > 1)
		{	
			$data['check_from'] = 'function';
			$data['module'] = $module;
			$data['arr_alldata'] = $arr_allfunctions;
			$include_subcon = true;
			$function_type = 'function';
		}
		elseif(count($arr_allfunctions) == 1)
		{
			$mainfunction = $function;			
			$arr_subfunctions = get_menudata($logArray['user_id'], $arr_allfunctions[0]['function_id']);
			if(count($arr_subfunctions) > 1)
			{	
				$data['arr_alldata'] = $arr_subfunctions;
				$data['module'] = $module;
				$include_subcon = true;				
			}
			elseif(count($arr_subfunctions) == 1)
			{	
				$subfunction_name = $arr_subfunctions[0]['function_name'];				
				$function = $arr_subfunctions[0]['function_name'];				
				$set_subfunction_id = $arr_subfunctions[0]['function_id'];
			}
		}	
	}
	elseif(isset($_POST['from_menu']) && ($_POST['from_menu'] == 'function'))
	{	
		$is_internal_subfunction = 0;
		$mainfunction = $function;				
		$arr_subfunctions = get_menudata($logArray['user_id'], $function,'function_name',$current_pageid);
		
		if(count($arr_subfunctions) > 1)
		{
			$data['arr_alldata'] = $arr_subfunctions;
			$include_subcon = true;			
		}
		elseif(count($arr_subfunctions) == 1)
		{	
			$subfunction_name = $arr_subfunctions[0]['function_name'];				
			$function = $arr_subfunctions[0]['function_name'];
			$set_subfunction_id = $arr_subfunctions[0]['function_id'];
		}		
	}
	
	if($is_internal_subfunction == 1)
	{	
		if(isset($_POST['mainfunction']))
		{	
			$main_functionid = get_current_functionid($_POST['mainfunction'],$current_pageid); 
			$set_subfunction_id =  getOne("select function_id from sub_functions WHERE  page_id = '".$current_pageid."' and main_function_id= '".$main_functionid."' and function_name = '".$_POST['subfunction_name']."'");
		}	
	}		
	//echo $module.'###'.$page.'###'.$function.'mainfun'.$mainfunction.'subfunid'.$set_subfunction_id;
	//echo 'end'; //exit;
	if($include_subcon == true)
	{
		$page="view.php";
	}
	else
	{
		include('sub_controllers/sc_'.$module.'.php');
	}
?>
<input type="hidden" name="from_menu" id="from_menu" value="<?php if(isset($check_from)) echo $check_from; ?>">
<?php 	
if(!is_file("views/".$page)){
	include_once('views/uc.php');
}else{
	include_once("views/".$page);
}

?>
