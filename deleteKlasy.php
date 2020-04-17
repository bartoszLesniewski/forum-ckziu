<?php
	require "connect.php";
	
	$id = $_POST['id'];
	
	$sql = "DELETE FROM klasy WHERE id_klasy = $id";
	
	$result = $conn -> query($sql);
?>