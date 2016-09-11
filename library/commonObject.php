<?php
class commonObject
{	
	protected $table_name;
	
	function getInsertDataString($dataArray, $tableName='')
	{
		$subQryArr = array();
		foreach($dataArray as $key => $value)
		{
			$subQryArr[] = $key." = '".addslashes($value)."'";
		}
		$subQry = implode(", ",$subQryArr);
		if($tableName != ''){
			$insertQry = "insert into ".$tableName." set ".$subQry;
		}
		else{
			$insertQry = "insert into ".$this->table_name." set ".$subQry;
		}
		return $insertQry;	
	}
	
	//Vishak Nair - 09-08-2012
	//To generate string for update query form array.
	//Note: $dataArray must contain the id. $idFieldName is the name of the field which contains the id.
	function getUpdateDataString($dataArray,$idFieldName,$tableName=''){
		$subQryArr = array();
		foreach($dataArray as $key => $value)
		{
			if(!($key==$idFieldName))
				$subQryArr[] = $key." = '".addslashes($value)."'";
		}
		$subQry = implode(", ",$subQryArr);
		if($tableName != ''){
			$updateQry = "Update ".$tableName." set ".$subQry." where ".$idFieldName."='".$dataArray[$idFieldName]."'";
		}else{
			$updateQry = "Update ".$this->table_name." set ".$subQry." where ".$idFieldName."='".$dataArray[$idFieldName]."'";	
		}
		return $updateQry;
	}
	
	function insert($varArray,$tableName='')
	{
		if($tableName != '')
			$insertQry = $this->getInsertDataString($varArray,$tableName);
		else
			$insertQry = $this->getInsertDataString($varArray,$tableName='');	
		updateData($insertQry);
		return mysql_insert_id();
	}
	
	function update($varArray,$field_name)
	{
		$updateQry = $this->getUpdateDataString($varArray,$field_name,$tableName='');
		updateData($updateQry);
	}
	
	function delete($id,$field_name)
	{
		updateData("UPDATE `".$this->table_name."` SET `is_active`=0 WHERE `".$field_name."`='".$id."'");	
	}
	
	function getAll($tableName='')
	{
		if($tableName != ''){	
			return $resultSet = getData("SELECT * FROM `".$tableName."` WHERE is_active =1");
		}else{
			return $resultSet = getData("SELECT * FROM `".$this->table_name."` WHERE is_active =1");
		}
	}
	
	function getRecordById($field_name,$field_value)
	{
		return getRow("SELECT * FROM `".$this->table_name."` WHERE `".$field_name."`= '".$field_value."'");	
	}
	
	function restoreEntry($field_name,$field_value)
	{
		updateData("UPDATE `".$this->table_name."` SET is_active=1 WHERE `".$field_name."`= '".$field_value."'");		
	}
	
	function countRows($field_name,$tableName='')
	{
		if($tableName != ''){
			$count = getOne("SELECT COUNT('".$field_name."') AS CNT From `".$tableName."` WHERE 1");	
		}else{
			$count = getOne("SELECT COUNT('".$field_name."') AS CNT From `".$this->table_name."` WHERE 1");	
		}
		return $count;
	}
	
	function countWhere($dataArr,$field_name,$tableName='')
	{
		$whereConditions = implode(", ",$dataArr);
		if($tabelName != ''){
			$count = getOne("SELECT COUNT('".$field_name."') AS CNT From `".$tableName."` WHERE '".$whereConditions."'");	
		}else{
			$count = getOne("SELECT COUNT('".$field_name."') AS CNT From `".$this->table_name."` WHERE '".$whereConditions."'");	
		}
		return $count;	
	}
	
	function getRowsWhere($dataArr,$tableName='')
	{
		$whereConditions = implode(", ",$dataArr);
		if($tableName != ''){
			$resultSet = getData("SELECT * FROM '".$tableName."' WHERE '".$whereConditions."'");
		}else{
			$resultSet = getData("SELECT * FROM '".$this->table_name."' WHERE '".$whereConditions."'");
		}	
		return $resultSet;
	}
	
	
}
?>