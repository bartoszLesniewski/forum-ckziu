<?php
	require "connect.php";
	
	$id = $_POST['id'];
	$nazwa = $_POST['nazwa'];
	$dostep = $_POST['dostep'];
	
	$sql = "UPDATE kategorie SET nazwa='$nazwa', dostep='$dostep' WHERE id_kategorii=$id";
	
	$result = $conn -> query($sql);
	
	$result -> close();
	$conn -> close();
?>