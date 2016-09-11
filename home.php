<?php  error_reporting(E_ALL); ini_set('display_errors', 'On'); 

// Start the session
session_name("mtrack");
session_start();
ob_start();
date_default_timezone_set('Asia/Kolkata');
$page = "";
$function = "";
$pageTitle = "";
//echo "---".$function; exit;
unset($_GET);	
//Get all the includes
require_once('main_includes.php');	

//Build the HTML Document Header
require_once('document_header.php');

//Build the Application Header
require_once('application_header.php');

//Build the User Menu
require_once('user_menu.php');

//Include the Main Controller
require_once('controller.php');

//Include the Footer
require_once('footer.php');

?>
