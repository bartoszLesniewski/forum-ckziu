<?php

	if (!isset($_SESSION['inicjuj']))
	{
		session_regenerate_id();
		$_SESSION['inicjuj'] = true;
		$_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
	}
	
	
	if($_SESSION['ip'] != $_SERVER['REMOTE_ADDR'])
	{
		session_unset();
		session_destroy();
		header ("Location: index.php");
	}
?>