<?php
	require "connect.php";
	
	$id = $_POST['id'];
	
	$sql_up1 = "UPDATE posty SET uzytkownik_id = -1 WHERE uzytkownik_id = $id";
	$sql_up2 = "UPDATE tematy SET uzytkownik_id = -1 WHERE uzytkownik_id = $id";
	$sql_typ = "SELECT typ_konta FROM uzytkownicy WHERE id_uzytkownika = $id";
	
	$conn -> query($sql_up1);
	$conn -> query($sql_up2);
	
	$wynik = $conn -> query($sql_typ);
	$wiersz = $wynik -> fetch_assoc();
	$typ = $wiersz['typ_konta'];
	
	if ($typ == "uczen")
	{
		$sql_uczen = "DELETE FROM uczniowie_i_klasy WHERE uczen_id = $id";
		$conn -> query($sql_uczen);
	}
	else if ($typ == "nauczyciel");
	{
		$sql_nauczyciel = "DELETE FROM nauczyciele_i_klasy WHERE nauczyciel_id = $id";
		$conn -> query($sql_nauczyciel);
	}
	
	
	$sql = "DELETE FROM uzytkownicy WHERE id_uzytkownika = $id";
	
	$result = $conn -> query($sql);
?>