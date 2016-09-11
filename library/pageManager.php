<?

if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	header("location: index.php");
	exit();
}

//require_once('Config.php');

class PageManager extends commonObject
{
	function getAllPages($show='1')
		{
			if($show == 0)
				$arr_orders =  getData("SELECT * From pages WHERE 1 order by tab_order ASC");
			elseif($show == 1)
				$arr_orders =  getData("SELECT * From pages WHERE is_active = 1 order by tab_order ASC");
			elseif($show == 2)
				$arr_orders =  getData("SELECT * From pages WHERE is_active = 0 order by tab_order ASC");
			return $arr_orders;
		}
		
		function get_allcounts()
		{
			$arr_counts = array();
			$all = getOne("SELECT COUNT(page_id) AS CNT From pages WHERE page_id != 1");  //page_id =1 for home page
			$arr_counts['all'] = $all;
			$active = getOne("SELECT COUNT(page_id) AS CNT From pages WHERE is_active = 1 and page_id != 1");
			$arr_counts['active'] = $active;
			$trash = getOne("SELECT COUNT(page_id) AS CNT From pages WHERE is_active = 0");
			$arr_counts['trash'] = $trash;
			return $arr_counts;
			
		}
		
		function restore_page($id)
		{
			$change_data = array (
									'is_active' => 1,							
									); 
			$change_data['page_id'] = $id;
			$updateQry=$this->getUpdateDataString($change_data,"pages","page_id");
			updateData($updateQry);
			
		}
	
	/*function getAllPages(){	
		return $resultSet = getData("SELECT * FROM `pages` WHERE is_active =1");
	}*/
	
	function getParentPages($level){
		$query = "select * from `pages` where is_active=1 and level='".$level."'";
		//echo $query;exit;
		return getData($query);
	}
	
	
	function getPageVariables()
	{	global $function;
		$varArray['description'] = $_REQUEST['description'];
		//$varArray['page_name'] = $_REQUEST['page_name'];	
		$varArray['module_name'] = strtolower($_REQUEST['module_name']);
		$varArray['module_name'] = preg_replace('/\s+/','',$varArray['module_name']);
		$varArray['title'] = $_REQUEST['title'];
		
		
		
		if($function=='add_page')	
		{
			 $_REQUEST['level']=1;
		}
		$varArray['level'] = $_REQUEST['level'];
		if(!isset($_REQUEST['parent_page_id']))
		{
			$_REQUEST['parent_page_id']=0;
		}	
		$varArray['parent_page_id'] = $_REQUEST['parent_page_id'];
		$varArray['tab_order'] = $_REQUEST['tab_order'];
		if(!isset($_REQUEST['is_crud']))
		{
			$_REQUEST['is_crud']='0';
		}
		else
		{	$_REQUEST['is_crud']='1';
		}
		$varArray['is_crud'] = $_REQUEST['is_crud'];
		
		if(($_FILES['file']['name'])!='')
		{
			$varArray['img_url'] = "img/".$_FILES['file']['name'];
		}
		
		//var_dump($varArray);exit;
		return $varArray;
	}
	
	function isPageExist($module_name,$page_id=0){
		$query="select * from pages where module_name='".$module_name."' and is_active=1";
		if($page_id!=0){
			$query.=" AND page_id!=".$page_id;
		}
		$resultSet = getData($query);
		if(sizeof($resultSet)>0){
			return true;
		}else{
			return false;
		}
	}
	
	function insertPage($varArray)
	{
		$insertQry = $this->getInsertDataString($varArray, 'pages');
		updateData($insertQry);
		return mysql_insert_id();
	}
	
	function getPageDetails($page_id)
	{
		return $resultSet = getRow("select * from  `pages` where is_active =1 AND page_id='".$page_id."'");
	}
	
	
	function updateUsingId($dataArray){
		$updateQry=$this->getUpdateDataString($dataArray,"pages","page_id");
		updateData($updateQry);
	}
	
	function deleteUsingId($page_id){
		updateData("UPDATE `pages` SET `is_active`=0 WHERE `page_id`='".$page_id."'");
	}
	
	function getMaxTabOrder()
	{
		return getOne("SELECT max(`tab_order`) FROM `pages` ");
	}
	
} 
?>
