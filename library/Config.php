<?php
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
}
require_once('DAL.php');

$host = "";
$user= "";
$pass= "";
$database = "";

mysql_connect($host,$user,$pass,TRUE) or die("could not connect");
mysql_select_db($database) or die("could not select database".$database);
define("PATH",$path);
mysql_set_charset("utf8");
?>