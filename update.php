<?php
	require "connect.php";
	
	$id = $_POST['id'];
	$login = $_POST['login'];
	$imie = $_POST['imie'];
	$nazwisko = $_POST['nazwisko'];
	$email = $_POST['email'];
	$typ_konta = $_POST['typ_konta'];
	
	$sql = "UPDATE uzytkownicy SET login='$login', imie='$imie', nazwisko='$nazwisko', email='$email', typ_konta='$typ_konta' WHERE id_uzytkownika=$id";
	
	$result = $conn -> query($sql);
	
	$result -> close();
	$conn -> close();
?>