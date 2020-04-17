<?php
	$id = $_POST['id'];
	$nazwa = $_POST['nazwa'];
	
	require_once "htmlpurifier/library/HTMLPurifier.auto.php";
	
	$tyt_clear = strip_tags($nazwa);
	$tyt_clear = htmlentities($tyt_clear, ENT_QUOTES, "UTF-8");
	$tyt_clear = trim($tyt_clear);
	
	$config = HTMLPurifier_Config::createDefault();
	$purifier = new HTMLPurifier($config);
	$tyt_clear = $purifier->purify($tyt_clear);

	if (ctype_space($tyt_clear) || $tyt_clear == "")
	{
		echo "Tytuł nie może być pusty!";
	}
	
	else
	{
		require "connect.php";
		
		$sql = "UPDATE tematy SET nazwa='$tyt_clear' WHERE id_tematu=$id";
		echo $tyt_clear;	
		
		$result = $conn -> query($sql);
	
		$conn -> close();
	}
	

?>