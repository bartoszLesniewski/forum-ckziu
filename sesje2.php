<?php

	if (!isset($_SESSION['inicjuj']))
	{
		session_regenerate_id();
		$_SESSION['inicjuj'] = true;
		$_SESSION['user'] = $_SERVER['HTTP_USER_AGENT'];
	}
	
	
	if($_SESSION['user'] != $_SERVER['HTTP_USER_AGENT'])
	{
		session_unset();
		session_destroy();
		header ("Location: index.php");
	}
?>