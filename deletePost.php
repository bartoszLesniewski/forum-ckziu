<?php
	require "connect.php";
	
	$id = $_POST['id'];
	
	$sql = "DELETE FROM posty WHERE id_posta = $id";
	
	$result = $conn -> query($sql);
?>