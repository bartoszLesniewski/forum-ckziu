<?php
	require "connect.php";
	
	$id = $_POST['id'];
	$nazwa = $_POST['nazwa'];
	
	$sql = "UPDATE klasy SET nazwa='$nazwa' WHERE id_klasy=$id";
	
	$result = $conn -> query($sql);
	
	$result -> close();
	$conn -> close();
?>