<?php
	require "connect.php";
	if (isset($_SESSION['typ_konta']))
	{	
		if ($_SESSION['typ_konta'] == "nauczyciel")
		{
			$id = $_SESSION['id'];
			
			/*$sql_klasy = "SELECT klasa_id FROM nauczyciele_i_klasy WHERE nauczyciel_id = $id";
			$result = $conn -> query($sql_klasy);
			$klasy = array();
			
			if ($result && $result -> num_rows > 0)
			{
				while ($wiersz = $result -> fetch_assoc())
				{
					$kl = $wiersz['klasa_id'];
					array_push($klasy, $kl);
				}
			}
			
			
			if (count($klasy) > 0)
				$klasyStr = implode (", ", $klasy);
			else
				$klasyStr = "null";*/
			
			$sql = "SELECT id_tematu FROM dostepnosc WHERE grupa='nauczyciele' OR grupa='wszyscy' OR grupa='uczniowie'";
			
			$result = $conn -> query($sql);
			$tematy = array();
			
			if ($result && $result -> num_rows > 0)
			{
				while ($wiersz = $result -> fetch_assoc())
				{
					$temat = $wiersz['id_tematu'];
					if (!in_array($temat, $tematy))
						array_push($tematy, $temat);
				}
			}
			
			$sql = "SELECT id_tematu FROM tematy WHERE uzytkownik_id = $id AND id_tematu NOT IN (SELECT id_tematu FROM dostepnosc WHERE grupa='nauczyciele' OR grupa='wszyscy' OR grupa='uczniowie')";
			
		}
		
		else if ($_SESSION['typ_konta'] == "uczen")
		{
			$id = $_SESSION['id'];
			$sql_klasy = "SELECT klasa_id FROM uczniowie_i_klasy WHERE uczen_id = $id";
			$result = $conn -> query($sql_klasy);
			
			if ($result && $result -> num_rows > 0)
			{
				$wiersz = $result -> fetch_assoc();
				$klasyStr = $wiersz['klasa_id'];
			}
			else
				$klasyStr = "null";
			
			$sql = "SELECT id_tematu FROM dostepnosc WHERE grupa='uczniowie' OR grupa='wszyscy' OR grupa IN ($klasyStr)";
			
			$result = $conn -> query($sql);
			$tematy = array();
			
			if ($result && $result -> num_rows > 0)
			{
				while ($wiersz = $result -> fetch_assoc())
				{
					$temat = $wiersz['id_tematu'];
					if (!in_array($temat, $tematy))
						array_push($tematy, $temat);
				}
			}
			
			$sql = "SELECT id_tematu FROM tematy WHERE uzytkownik_id = $id AND id_tematu NOT IN (SELECT id_tematu FROM dostepnosc WHERE grupa='uczniowie' OR grupa='wszyscy' OR grupa IN ($klasyStr))";	
		}
		
		else if ($_SESSION['typ_konta'] == "administrator")
		{
			$sql = "SELECT id_tematu FROM tematy";
			$tematy = array();
		}
		
		$result = $conn -> query($sql);
			
		if ($result && $result -> num_rows > 0)
		{
			while ($wiersz = $result -> fetch_assoc())
			{
				$temat = $wiersz['id_tematu'];
				if (!in_array($temat, $tematy))
					array_push($tematy, $temat);
			}
		}
		
		if (count($tematy) > 0)
			$dostepneTematy = implode(", ", $tematy);
		else
			$dostepneTematy = "null";
		
		$_SESSION['dostepneTematy'] = $dostepneTematy;
	}
	else
	{
		$sql = "SELECT id_tematu FROM dostepnosc WHERE grupa='wszyscy'";
		$result = $conn -> query($sql);
		
		if ($result && $result -> num_rows > 0)
		{
			$tematy = array();
			
			while ($wiersz = $result -> fetch_assoc())
			{
				$temat = $wiersz['id_tematu'];
				array_push($tematy, $temat);
			}
			
			if (count($tematy) > 0)
				$dostepneTematy = implode(", ", $tematy);
			else
				$dostepneTematy = "null";
			
			
			$result -> close();
			$conn -> close();
		}
		else
			$dostepneTematy = "null";
		
		$_SESSION['dostepneTematy'] = $dostepneTematy;

	}
	
	//echo $_SESSION['dostepneTematy'];
?>