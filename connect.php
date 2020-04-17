<?php

	$conn = new Mysqli ("localhost", "root", "", "forum_edit");
	
	if ($conn -> connect_errno)
	{
		echo "Błąd przy łączeniu z bazą: " . $conn->connect_errno . "<br>";
		echo $conn -> connect_error;
		exit();
	}
	
	else
	{
		//echo "Połączono";
		$conn -> set_charset('utf8');
	}
?>