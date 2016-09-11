<?php 
	error_reporting(E_ALL); ini_set('display_errors', 'On'); 
	$notification = "";
	$salt="HG@#GG"; // If you make change in this, Apply change in index.php also
	$cookie_name=md5('remember_'.$salt);// If you make change in this, Apply change in sc_home.php -> logout section also
	include_once("login_check.php");
?>
<!doctype html>  
<html lang="en-us">

<head>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge;chrome=1" >
	<meta charset="utf-8">
	<title>mTrack Admin Panel | Login</title>

	<meta name="viewport" content="width=device-width, initial-scale=1.0"> 

	<!-- The Columnal Grid and mobile stylesheet -->
	<link rel="stylesheet" href="css/columnal/columnal.css" type="text/css" media="screen" />

	<!-- Fixes for IE -->
	<!--[if lt IE 9]>
            <link rel="stylesheet" href="css/columnal/ie.css" type="text/css" media="screen" />
            <link rel="stylesheet" href="css/ie8.css" type="text/css" media="screen" />
            <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
	<![endif]-->

	<!-- Now that all the grids are loaded, we can move on to the actual styles. --> 
        <link rel="stylesheet" href="js/jqueryui/jqueryui.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="css/global.css" type="text/css" media="screen" />
		<link rel="stylesheet" href="css/config.css" type="text/css" media="screen" />
        
</head>
<body id="login">
    <div class="container">
        <div class="row">
            
            <a href="index.php" class="center block"><img src="img/logo.png" style="width:18%;box-shadow:0 0 5px 2px #CCC;-moz-box-shadow:0 0 5px 2px #CCC;-webkit-box-shadow:0 0 5px 2px #CCC;"/></a>
            
            <div class="col_6 pre_3 padding_top_60">
                <div class="widget clearfix">
                    <h2>Login</h2>
                    <span style="margin-left: 107px;color: #F00;"><?php echo $notification; ?></span>
                    <div class="widget_inside">
                    	<form action="index.php" method="post" id="wl-form" name="wl-form">	
                        <div class="form">
                            <div class="clearfix no_border">
                                <label>Username</label>
                                <div class="input">
                                    <input type="text" class="xlarge" id="wl-username" name="wl-username" value=""/>
                                </div>
                            </div>
                            <div class="clearfix no_border">
                                <label>Password</label>
                                <div class="input">
                                    <input type="password" class="xlarge" id="wl-password" name="wl-password" value=""/>
                                </div>
                            </div>
                            <div class="clearfix">
                                <div class="input" style="text-align:center;float:none;">
                                    <input type="checkbox" name="remember_me" value="remember"/> Remember me?
                                </div>
                            </div>
                          
                            <div class="clearfix grey-highlight">
                                <div class="input" style="text-align:center;float:none;">
                                    <input type="submit"  class="button blue" value="Login" />
                                    <a href="index.php" class="button"><span>Reset</span></a>
                                </div>
                            </div>
                             </form> 
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
 </body>
</html>