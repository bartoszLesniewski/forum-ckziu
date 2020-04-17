<?php
	session_start(); 
	
	if (isset($_POST['edit']))
	{
		$tresc = $_POST['edit'];
		$id = $_GET['id'];
		
		$tyt_clear = strip_tags($tresc);
		$tyt_clear = html_entity_decode($tyt_clear);
		$tyt_clear = urldecode($tyt_clear);
		$tyt_clear = preg_replace('/[^A-Za-z0-9]/', ' ', $tyt_clear);
		$tyt_clear = preg_replace('/ +/', ' ', $tyt_clear);
		$tyt_clear = trim($tyt_clear);

		if (ctype_space($tyt_clear) || $tyt_clear == "")
		{
			$_SESSION['blad_edit'] = "Treść posta nie może być pusta.";
			$url = "editPost.php?id=" . $id;
			header ("Location: $url");
		}
		
		else
		{
			require "connect.php";
			
			$sql = "UPDATE posty SET tresc='$tresc' WHERE id_posta = $id";
			
			$result = $conn -> query($sql);
			
			if ($result)
			{
				$_SESSION['post_edit'] = "Post został edytowany";
				
				$sql_topic = "SELECT temat_id FROM posty WHERE id_posta = $id";
				$wyn = $conn -> query($sql_topic);
				$wiersz = $wyn -> fetch_assoc();
				
				$temat = $wiersz['temat_id'];
				$url = 'temat.php?id=' . $temat;
				
				header ("Location: $url");
				
				$wyn -> close();
				$conn -> close();
			}
		}
	}

?>