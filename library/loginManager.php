<?php 
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false)
{
	Header("location: index.php");
}
require_once('library/Config.php');
//require_once('library/LDAP/LDAP.php');

function checkLogin($user,$pass=FALSE) 
{
	if($pass===FALSE){
		$user = addslashes($user);
		$query="SELECT * FROM users WHERE user_name='".$user."'";
		$result= getData($query);
		$userCount = (int) sizeof($result);
		if($userCount > 0) return true;
		else return false;
	}else{
		$user = addslashes($user);
		$pass = addslashes($pass);
		$query="SELECT * FROM users WHERE user_name='".$user."' AND user_password = sha1('".$pass."')";
		$result= getData($query);
		$userCount = (int) sizeof($result);
		if($userCount > 0) return true;
		else return false;
	}
}

function setUserDetails($user)
{	
	if(is_array($user) && isset($user['user_name']) && isset($user['name'])){ // Used when updating info from my profile
		$_SESSION['user_name'] = $user['user_name'];
		$_SESSION['name'] = $user['name'];
	}else{
		$user = addslashes($user);
		//$pass = addslashes($pass);
		$query="SELECT * FROM users WHERE user_name='".$user."'"; 	
		$userDetails = getData($query);
		//print_r($userDetails);exit;
		if(sizeof($userDetails)>0)
		{
			 $_SESSION['user_id'] = $userDetails[0]['user_id'];
			 $_SESSION['user_name'] = $userDetails[0]['user_name'];
			 $_SESSION['user_main_group'] = $userDetails[0]['user_group'];
			 $_SESSION['name'] = $userDetails[0]['name'];
			 $_SESSION['user_login'] = true;
		}
	}
}
?>