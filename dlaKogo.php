<?php
	if (isset($_POST['id']))
	{
		require "connect.php";
		
		$id = $_POST['id'];
		$sql = "SELECT dostep FROM kategorie WHERE id_kategorii = $id";
		$result = $conn -> query($sql);
		
		if ($result && $result -> num_rows > 0)
		{
			$wynik = $result -> fetch_assoc();
			$grupa = $wynik['dostep'];
			
			echo $grupa;
		}
	}
?>