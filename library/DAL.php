<?
	if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
	{
		header("location: index.php");
		exit();
	}
	
	function Handle_Mysql_Error($sql,$err,$errno)
	{
		echo($sql . "<br>" . $err . "       on   " . $errno);
		exit();
	}

	/*function getInsertDataString($dataArray, $tableName)
	{
		$subQryArr = array();
		foreach($dataArray as $key => $value)
		{
			$subQryArr[] = $key." = '".addslashes($value)."'";
		}
		$subQry = implode(", ",$subQryArr);
		$insertQry = "insert into ".$tableName." set ".$subQry;
		return $insertQry;	
	}*/
//function to retrive data from the database
		function getData($query) 
		{
			$query=trim($query); 
			log_history('Get Data',$query);
			$result = mysql_query($query) or Handle_Mysql_Error($query,mysql_error(),mysql_errno());

			$resArr = array();
			while($res = mysql_fetch_array($result,MYSQL_ASSOC)) 
			{
				if(is_array($res))
				{
					foreach($res as $key => $value)
					{
						$res[$key] = stripslashes($value);
					}
				}
				$resArr[] = $res;
			}
			return $resArr;
		}
		
		
//function to retrive 1 row from the database
		function getRow($query) 
		{
			$query=trim($query);
			if(strpos(strtoupper($query),'LIMIT')===FALSE){
				$query.=" LIMIT 0,1";
			}
			log_history('Get Row',$query);
			$result = mysql_query($query) or Handle_Mysql_Error($query,mysql_error(),mysql_errno());
			$res = mysql_fetch_array($result,MYSQL_ASSOC);
			if(is_array($res))
			{
				foreach($res as $key => $value)
				{
					$res[$key] = stripslashes($value);
				}
			}
			return $res;
		}
		
//function update database
		function updateData($query)
		{	$query=trim($query);
			log_history('Update Data',$query); 
			$result = mysql_query($query) or die(mysql_error());
		}
		
		/*function getUpdateDataString($dataArray,$tableName,$idFieldName){
			$subQryArr = array();
			foreach($dataArray as $key => $value)
			{
				if(is_array($idFieldName))
				{
					if(!in_array($key,$idFieldName)){
						$subQryArr[] = $key." = '".addslashes($value)."'";	
					}
				}
				else{
					if(!($key==$idFieldName))
						$subQryArr[] = $key." = '".addslashes($value)."'";	
				}
			}
			$subQry = implode(", ",$subQryArr);
			$updateQry = "Update ".$tableName." set ".$subQry." where";//.$idFieldName."='".$dataArray[$idFieldName]."'";
			if(!is_array($idFieldName))
				$updateQry.=" ".$idFieldName."='".$dataArray[$idFieldName]."'";
			else{
				foreach($idFieldName as $val){
					$updateQry.=" ".$val."='".$dataArray[$val]."' and";	
				}
			}
			return $updateQry;
		}*/
		
//Function to Get single value from the database
		function getOne($query)
		{	
			$query=trim($query);
			if(strpos(strtoupper($query),'LIMIT')===FALSE){
				$query.=" LIMIT 0,1";
			}
			log_history('Get One',$query);
			$result = mysql_query($query) or Handle_Mysql_Error($query,mysql_error(),mysql_errno());
			$resArr = '';
			$res = mysql_fetch_array($result);
			$resArr = stripslashes($res[0]);	
			return $resArr;
		}
		
		
/**** Function ot insert bulk of rows to the database.
	* $data_array : 2D array contaning rows to be inserted 
	* $feild_array : array of table attributes
*****/
		function insertBulk($data_array, $feild_array, $table_name)
		{
			$feild_array_explod = implode(',',$feild_array);
			$first=true;
			$first1=true;
			$query="INSERT INTO ".$table_name." (".$feild_array_explod.") VALUES ";
			foreach($data_array as $singel_row){
				if(!$first){
					$query.=", ";
				}else{
					$first=false;
				}
				$query.=" (";
				$first1=true;
				foreach($singel_row as $val){
					if(!$first1){
						$query.=", ";
					}else{
						$first1=false;
					}	
					$query.="'".$val."'";
				}
				$query.=")";
			}
			//echo $query;exit;
			log_history('Insert Bulk',$query);
			updateData($query);	
		}
		
		function log_history($type,$query){
			$query=addslashes($query);
			$user_id="0";
			if(isset($_SESSION['user_id'])){
				$user_id=$_SESSION['user_id'];
			}
			//mysql_query("INSERT INTO `user_history`(`user_id`, `type`, `Description`) VALUES ('".$user_id."','".$type."','".$query."')") or die(mysql_error());
		}
?>