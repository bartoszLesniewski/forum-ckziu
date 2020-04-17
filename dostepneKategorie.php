<?php
	require "connect.php";
	if (isset($_SESSION['typ_konta']))
	{	
		if ($_SESSION['typ_konta'] == "nauczyciel")
		{
			$id = $_SESSION['id'];
			$sql = "SELECT id_kategorii FROM kategorie WHERE dostep='nauczyciele' OR dostep='wszyscy' OR dostep='uczniowie'";
			
		}
		
		else if ($_SESSION['typ_konta'] == "uczen")
		{
			$id = $_SESSION['id'];
			$sql = "SELECT id_kategorii FROM kategorie WHERE dostep='wszyscy' OR dostep='uczniowie'";
			
		}
		
		else if ($_SESSION['typ_konta'] == "administrator")
		{
			$sql = "SELECT id_kategorii FROM kategorie";
		}
		
		$result = $conn -> query($sql);
		$kategorie = array();
		
		if ($result && $result -> num_rows > 0)
		{
			while ($wiersz = $result -> fetch_assoc())
			{
				$kategoria = $wiersz['id_kategorii'];
				array_push($kategorie, $kategoria);
			}
		}
		
		if (count($kategorie) > 0)
			$dostepneKategorie = implode(", ", $kategorie);
		else
			$dostepneKategorie = "null";
		
		$_SESSION['dostepneKategorie'] = $dostepneKategorie;
	}
	else
	{
		$sql = "SELECT id_kategorii FROM kategorie WHERE dostep='wszyscy'";
		$result = $conn -> query($sql);
		
		if ($result && $result -> num_rows > 0)
		{
			$kategorie = array();
			
			while ($wiersz = $result -> fetch_assoc())
			{
				$kategoria = $wiersz['id_kategorii'];
				array_push($kategorie, $kategoria);
			}
			
			if (count($kategorie) > 0)
				$dostepneKategorie = implode(", ", $kategorie);
			else
				$dostepneKategorie = "null";
			
			
			$result -> close();
			$conn -> close();
		}
		else
			$dostepneKategorie = "null";
		
		$_SESSION['dostepneKategorie'] = $dostepneKategorie;

	}
	
	//echo "<br>Kategorie: ". $_SESSION['dostepneKategorie'];
?>