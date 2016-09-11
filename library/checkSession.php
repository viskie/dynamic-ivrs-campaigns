<?php
if($_SESSION['user_login']!=true)
{	
	header("location: index.php");
	exit;
}

if(!($_SESSION['user_login'] == true))
{
		 echo "
					<script type='text/javascript'>
						alert(\"Please Log into the application to use this feature\");
						window.location = 'index.php';
					</script>
			 ";
			exit;
}
?>
