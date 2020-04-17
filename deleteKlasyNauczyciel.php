<?php
	require "connect.php";
	
	$id_klasy = $_POST['id_klasy'];
	$id_nauczyciela = $_POST['id_nauczyciela'];
	
	$sql = "DELETE FROM nauczyciele_i_klasy WHERE nauczyciel_id = $id_nauczyciela AND klasa_id = $id_klasy";
	
	$result = $conn -> query($sql);
?>