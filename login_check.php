<?php
	require_once('library/loginManager.php');
	if(isset($_COOKIE[$cookie_name])){
		$uname=base64_decode($_COOKIE[$cookie_name]);
		$uname=substr($uname,0,strlen($salt)*(-1));
//		echo $uname;exit;
		if(checkLogin($uname))
		{
			session_name("mtrack");
			session_start();
			setUserDetails($uname);
			header("Location: home.php");
			exit;
		}
	}
	if((isset($_POST['wl-username'])) && (trim($_POST['wl-password']) != ""))
	{	
		$inpUsername = $_POST['wl-username'];
		$inpPassword = $_POST['wl-password'];	
		
		if(checkLogin($inpUsername,$inpPassword))
		{
			session_name("mtrack");
			session_start();
			setUserDetails($inpUsername);
			if(isset($_POST['remember_me'])){
				setcookie($cookie_name,base64_encode($inpUsername.$salt),time()+60*60*24*30);
			}
			header("Location: home.php");
			exit;
		}
		else
		{
			$notification = "Username or Password Incorrect";
		}
	}
?>
