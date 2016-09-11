<?
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

require_once('Config.php');

class FunctionManager extends commonObject
{
		public $db_fields;
		function FunctionManager()
		{
			$this->db_fields = array();	
		}
		
		function isFunctionExists($function_name,$function_id=0)
		{
			$query="select * from functions where function_name='".trim($function_name)."'";
			if($function_id!=0){
				$query.=" AND function_id!=".$function_id;
			}
			$resultSet = getData($query); 
			if(sizeof($resultSet)>0){
				return true;
			}else{
				return false;
			}
		}
		function isOrderExists($pageid, $menu_order,$function_id=0)
		{
			$query="select * from functions where page_id ='".$pageid."' and menu_order='".trim($menu_order)."'";
			if($function_id!=0){
				$query.=" AND function_id!=".$function_id;
			} 
			$resultSet = getData($query);
			if(sizeof($resultSet)>0){
				return true;
			}else{
				return false;
			}
		}
		
		function getFunctionDetailsById($function_id)
		{
			return getRow("SELECT * FROM `functions` WHERE function_id=".$function_id);	
		}
		
		function getFunctionVariables()
		{
			$this->db_fields['function_name'] = $_REQUEST['function_name'];	
			$this->db_fields['friendly_name'] = $_REQUEST['friendly_name'];	
			$this->db_fields['menu_order'] = $_REQUEST['menu_order'];
				
			$this->db_fields['page_id'] = $_REQUEST['page_id'];	
		}
		function getAllPages()
		{
			return getData("select * from pages WHERE is_active=1");
		}
		function get_all_funcounts()
		{
			$arr_counts = array();
			$all = getOne("SELECT COUNT(function_id) AS CNT From functions WHERE 1");
			$arr_counts['all'] = $all;
			$active = getOne("SELECT COUNT(function_id) AS CNT From functions WHERE is_active = 1");
			$arr_counts['active'] = $active;
			$trash = getOne("SELECT COUNT(function_id) AS CNT From functions WHERE is_active = 0");
			$arr_counts['trash'] = $trash;
			return $arr_counts;
			
		}
		function get_functions($show='1')
		{
			if($show == 0)
				$arr_functions = getData("select * from functions WHERE 1  ORDER BY page_id,menu_order ASC");
			elseif($show == 1)
				$arr_functions = getData("select * from functions WHERE is_active=1  ORDER BY page_id,menu_order ASC");
			elseif($show == 2)
				$arr_functions = getData("select * from functions WHERE is_active=0  ORDER BY page_id,menu_order ASC"); //echo "<pre>"; print_r($arr_status); exit;
			return($arr_functions);
		}
		function restore_function($id)
		{
			$change_data = array (
								'is_active' => 1,							
								); 
			$change_data['function_id'] = $id;
			$updateQry=$this->getUpdateDataString($change_data,"functions","function_id");
			updateData($updateQry);	
		}
		// subfunction crud related functions
		function get_subfunctions($show='1')
		{
			if($show == 0)
				$arr_status = getData("select * from sub_functions where 1 ORDER BY page_id,main_function_id,menu_order ASC");
			elseif($show == 1)
				$arr_status = getData("select * from sub_functions WHERE is_active=1 ORDER BY page_id,main_function_id,menu_order ASC");
			elseif($show == 2)
				$arr_status = getData("select * from sub_functions WHERE is_active=0 ORDER BY page_id,main_function_id,menu_order ASC");
			return($arr_status);
		}
		function getAllFunctions()
		{
			$arr_functions = getData("select * from functions  WHERE is_active = 1");
			return ($arr_functions);			
		}
		function get_subfunction($function_id)
		{
			return getRow("SELECT * FROM sub_functions WHERE function_id=".$function_id);	
		}
		function restore_subfunction($id)
		{
			$change_data = array (
								'is_active' => 1,							
								); 
			$change_data['function_id'] = $id;
			$updateQry=$this->getUpdateDataString($change_data,"sub_functions","function_id");
			updateData($updateQry);	
		}
		function get_all_subfuncounts()
		{
			$arr_counts = array();
			$all = getOne("SELECT COUNT(function_id) AS CNT From sub_functions WHERE 1");
			$arr_counts['all'] = $all;
			$active = getOne("SELECT COUNT(function_id) AS CNT From sub_functions WHERE is_active = 1");
			$arr_counts['active'] = $active;
			$trash = getOne("SELECT COUNT(function_id) AS CNT From sub_functions WHERE is_active = 0");
			$arr_counts['trash'] = $trash;
			return $arr_counts;
			
		}
		function save_function($arr_data)
		{	
			$insert_data = array(
							'function_name' => $arr_data['function_name'],
							'friendly_name' => $arr_data['friendly_name'],
							'main_function_id' => $arr_data['main_function_id'],
							'menu_order' => $arr_data['menu_order'],
							'page_id' => $arr_data['page_id'],
							'is_crud' => $arr_data['is_crud'],														
							'is_active' => 1
							);
			if($arr_data['edit_id'] != "")
			{
				$chk_exist =  getData("SELECT function_id From sub_functions WHERE function_name ='".$arr_data['function_name']."' and main_function_id='". $arr_data['main_function_id']."' and page_id='".$arr_data['page_id']."' and function_id NOT IN ('".$arr_data['edit_id']."')");
				if(count($chk_exist) > 0)
				{
					return("exist");
				}
				else
				{
					$insert_data['function_id'] = $arr_data['edit_id'];
					$updateQry=$this->getUpdateDataString($insert_data,"sub_functions","function_id");
					updateData($updateQry);
				}
			}
			else
			{
				$chk_exist =  getData("SELECT function_id From sub_functions WHERE function_name ='".$arr_data['function_name']."' and main_function_id='". $arr_data['main_function_id']."' and page_id='".$arr_data['page_id']."'");
				if(count($chk_exist) > 0)
				{
					return("exist");
				}
				else
				{
					$insert_data['added_by'] = $arr_data['added_by'];
					$insert_data['added_date'] = $arr_data['added_date'];
					$insertQry = getInsertDataString($insert_data, 'sub_functions');					
					updateData($insertQry);
				}
			}			
		}
		function get_mainfunctions($pageid)
		{
			$arr_functions = getData("select * from functions  WHERE page_id = '".$pageid."' and is_active = 1");
			return ($arr_functions);			
		}
		function is_subfun_OrderExists($pageid,$mainfunctionid,$menuorder,$function_id='')
		{
			$query="select * from sub_functions where page_id='".$pageid."' and main_function_id = '".$mainfunctionid."' and menu_order = '".$menuorder."'";
			if($function_id!=''){
				$query.=" AND function_id!=".$function_id;
			}
			$resultSet = getData($query); 
			if(sizeof($resultSet)>0){
				return true;
			}else{
				return false;
			}
		}
		/////////////////////////////////////
}
?>
        