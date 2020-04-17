<?php
	session_start();
	
	require "connect.php";
	
	$id = $_GET['id'];
	
	$sql_posty = "DELETE FROM posty WHERE temat_id = $id";
	$conn -> query ($sql_posty);
	
	$sql_temat = "DELETE FROM tematy WHERE id_tematu = $id";
	$conn -> query($sql_temat);
	
	$_SESSION['usuniety_temat'] = "Temat został usunięty";
	header ("Location: index.php");
?>