<?php
	session_start();
	
	if (isset($_GET['typ'])) 
		echo $_SESSION[$_GET['typ']];

?>