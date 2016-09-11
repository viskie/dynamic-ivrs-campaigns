<?
session_name("mtrack");
session_start();
date_default_timezone_set('Asia/Kolkata');
//Get all the includes
require_once('main_includes.php');

if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    include('controller.php');
}
?>