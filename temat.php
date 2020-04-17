<?php
	session_start();
	require "sesje.php";

	/*if (!isset ($_SESSION['zalogowany']))
	{
		$_SESSION['temat_err'] = "Zaloguj się, aby kontynuować";
		header ("Location: logowanie.php");
	}*/
	
	/*else if (isset ($_SESSION['zalogowany']))
	{*/
		require "dostepneTematy.php";
		require "connect.php";
		$topic_id = $_GET['id'];
		$topics = $_SESSION['dostepneTematy'];
		
		if (strpos($topics, $topic_id) === false)
			$_SESSION['brak_uprawnien'] = "Nie masz odpowiednich uprawnień, aby wyświetlić ten temat";
	//}
	
	if (isset ($_POST['odpowiedz']))
	{
		
		$id_tematu = $_GET['id'];
		$id_uzytkownika = $_SESSION['id'];
		$odpowiedz = $_POST['odpowiedz'];
		
		$odpowiedz_clear = strip_tags($odpowiedz);
		$odpowiedz_clear = str_replace("&nbsp;", "", $odpowiedz_clear);
		
		if (ctype_space($odpowiedz_clear) || $odpowiedz_clear == "")
		{
			$_SESSION['blad_tresc'] = "Treść nie może być pusta";
		}
		
		else
		{
			require "connect.php";
			
			$sql_dodaj = "INSERT INTO posty VALUES (NULL, '$odpowiedz', NOW(), $id_tematu, $id_uzytkownika)";
			
			$result = $conn -> query($sql_dodaj);
			
			if (!$result)
				echo $conn -> error;
			
			$_SESSION['odp_add'] = "Odpowiedź została dodana";
			
			header ("Location: temat.php?id=$id_tematu");
			
			$result -> close();
			$conn -> close();
		}
	}
	
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Bartek Leśniewski">
    <title>Forum CKZiU w Brodnicy</title>
    <link rel="stylesheet" href="bootstrap.css">
    <link rel="stylesheet" href="style.css">
	<link rel="stylesheet" href="fontello/css/fontello.css">
	<script src="jquery-3.4.1.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script src="editor.js"></script>
	<script src="ckeditor_full/ckeditor.js"></script>
	<script src="script-posty.js"></script>

</head>

<body>
  <header class="container">
	<div class="row">
		<div class="col-4 m-auto">
			<img style="width: 110px; height: 100px" class="my-3 align-right" src="logo2.png" alt="logo">
		</div>
		
		<div class="col-md-8 col-sm-12 m-auto">
			<h1> Forum CKZiU w Brodnicy </h1>
		</div>
	</div>
   </header>
	<nav class="navbar navbar-dark bg-dark navbar-expand-md">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#menu">
			<span class="navbar-toggler-icon"></span>
		</button>
		
		<div class="collapse navbar-collapse" id="menu">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a class="nav-link" href="index.php"> Strona główna</a>
				</li>
				
				<li class="nav-item pl-3">
					<a class="nav-link" href="kategorie.php"> Kategorie </a>
				</li>
				
				<li class="nav-item pl-3">
					<a class="nav-link" href="dodaj-temat.php"> Dodaj temat </a>
				</li>
				
<?php
if (isset ($_SESSION['typ_konta']) && ($_SESSION['typ_konta'] == 'administrator' || $_SESSION['typ_konta'] == 'nauczyciel'))
{
echo<<<END
<li class="nav-item pl-3">
<a class="nav-link" href="dodaj-kategorie.php"> Dodaj kategorię </a>
</li>
END;
}
?>
				
<?php
if (isset ($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'administrator')
{
echo<<<END
<li class="nav-item pl-3 dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel administratora
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
echo<<<END
<a class="dropdown-item" href="powiadomienia.php">Powiadomienia</a>
<a class="dropdown-item" href="zarzadzanie.php">Zarządzanie użytkownikami</a>
<a class="dropdown-item" href="klasy.php">Klasy</a>
<a class="dropdown-item" href="lista-kategorii.php">Lista kategorii</a>
<a class="dropdown-item" href="prosby2.php">Prośby o aktywację</a>
<a class="dropdown-item" href="ckfinder/ckfinder.php">Pliki z serwera</a>
</div>
</li>
END;
}
?>

<?php
if (isset ($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'nauczyciel')
{
echo<<<END
<li class="nav-item pl-3 dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel nauczyciela
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
echo<<<END
<a class="dropdown-item" href="moje-klasy.php">Moje klasy</a>
<a class="dropdown-item" href="moje-tematy.php">Moje tematy</a>
<a class="dropdown-item" href="tematy-klasy.php">Tematy klasami</a>
<a class="dropdown-item" href="prosby-nauczyciel.php">Aktywacja kont uczniów</a>
<a class="dropdown-item" href="kontakt.php">Kontakt z administratorem</a>
<a class="dropdown-item" href="ckfinder/ckfinder.php">Pliki z serwera</a>
</div>
</li>
END;
}
?>

<?php
if (isset ($_SESSION['typ_konta']) && $_SESSION['typ_konta'] == 'uczen')
{
echo<<<END
<li class="nav-item pl-3 dropdown">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel ucznia
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
echo<<<END
<a class="dropdown-item" href="tematy-klasy-uczen.php">Tematy dla mojej klasy</a>
<a class="dropdown-item" href="moje-tematy.php">Moje tematy</a>
<a class="dropdown-item" href="kontakt.php">Kontakt z administratorem</a>
</div>
</li>
END;
}
?>

			</ul>
			
			<ul class="navbar-nav ml-auto">
				<li class="nav-item pr-2">
				<?php
					if (isset ($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true)
						echo '<span style="color: white" class="nav-link "> Jesteś zalogowany jako <a href="profil.php?id=' . $_SESSION['id'] . '">' . $_SESSION['uzytkownik'] . '</a> </span>';
					else
						echo '<a class="nav-link" href="rejestracja.php"> <i class="icon-user"></i>Zarejestruj się </a>';
				?>
				</li>
				
				<li class="nav-item pr-2">
					<?php
						if (isset ($_SESSION['zalogowany']) && $_SESSION['zalogowany'] == true)
							echo '<a class="nav-link" href="wyloguj.php"> <i class="icon-logout"></i> Wyloguj się </a>';
						else
							echo '<a class="nav-link" href="logowanie.php"> <i class="icon-login"></i> Logowanie </a>';
					?>
				</li> 
			</ul> 
		</div>
	</nav>
	
	<div class="container">
		<?php
			if (isset($_SESSION['brak_uprawnien']))
			{
				echo '<div class="alert alert-danger mt-4">';
				echo $_SESSION['brak_uprawnien'];
				echo '</div>';
				
				unset ($_SESSION['brak_uprawnien']);
				exit();
			}
		?>
	
	
		<?php
			if (isset ($_SESSION['temat_add']))
			{
				echo '<div class="alert alert-success mt-4">' . $_SESSION['temat_add'] . "</div>";
				unset ($_SESSION ['temat_add']);
			}
			
			if (isset ($_SESSION['odp_add']))
			{
				echo '<div class="alert alert-success mt-4">' . $_SESSION['odp_add'] . "</div>";
				unset ($_SESSION ['odp_add']);	
			}
			
						
			if (isset ($_SESSION['post_edit']))
			{
				echo '<div class="alert alert-success mt-4">' . $_SESSION['post_edit'] . "</div>";
				unset ($_SESSION ['post_edit']);	
			}
			
		?>
			
		<?php
		if (isset($_SESSION['zalogowany']))
		{
			require "connect.php";
			$logged = $_SESSION['id'];
			$logged_typ = $_SESSION['typ_konta'];
			
			$id_tematu = $_GET['id'];
			$sql_temat = "SELECT nazwa, uzytkownik_id FROM tematy WHERE id_tematu = $id_tematu";
			$wynik_tematy = $conn -> query($sql_temat);
			$wiersz_tematy = $wynik_tematy -> fetch_assoc();
			
			$nazwa_tematu = $wiersz_tematy['nazwa'];
			$zalozycielId = $wiersz_tematy['uzytkownik_id'];
			
			$sql_temat = "SELECT typ_konta FROM uzytkownicy WHERE id_uzytkownika=$zalozycielId";
			$wynik_tematy = $conn -> query($sql_temat);
			$wiersz_tematy = $wynik_tematy -> fetch_assoc();
			
			$zalozycielTyp = $wiersz_tematy['typ_konta'];
			
			echo '<div id="topic"><h3 id="topicName" class="mt-4">' . $nazwa_tematu . '</h3></div>';
			
			if ($logged_typ == "administrator" || ($logged_typ == "nauczyciel" && $logged == $zalozycielId) || ($zalozycielTyp == "uczen" && $logged_typ != "uczen"))
			{
				echo '<div id="przycisk"><button class="btn btn-primary btn-sm mr-2 mb-4 text-center" onclick="edytujTemat(' . $id_tematu . ')">' . '<i class="icon-pencil"> </i>Edytuj</button>';
				echo '<button class="btn btn-primary btn-sm mr-2 mb-4 ml-2 text-center" onclick="usunTemat(' . $id_tematu . ')">' . '<i class="icon-delete"> </i>Usuń</button></div>';
			}
			
			$sql_posty = "SELECT id_posta, tresc, data_publikacji, uzytkownik_id FROM posty WHERE temat_id = $id_tematu";
			$wynik_posty = $conn -> query($sql_posty);
			
			if ($wynik_posty -> num_rows > 0)
			{
			while ($wiersz = $wynik_posty -> fetch_assoc())
			{
				$tresc = $wiersz['tresc'];
				$data = $wiersz['data_publikacji'];
				$id_uzytkownika = $wiersz['uzytkownik_id'];
				
				$sql_user = "SELECT login, plec, typ_konta FROM uzytkownicy WHERE id_uzytkownika = $id_uzytkownika";
				$wynik_user = $conn -> query($sql_user);
				$wiersz_user = $wynik_user -> fetch_assoc();
				
				if ($wiersz_user['plec'] == "m")
					$plec = "man";
				else
					$plec = "woman";
				
				$login = $wiersz_user['login'];
				$typ = $wiersz_user['typ_konta'];
				
echo '<div id="' . $wiersz['id_posta'] . '" class="media mt-2 mb-4 p-3">';			
echo<<<END
<img src="avatar-$plec.png" alt="avatar" class="mr-4 mt-4 rounded-circle" style="width:70px;">
<div class="media-body">
END;

if ($id_uzytkownika == -1)
	echo '<h5 class="mr-4 d-inline-block"> Konto usunięte </h5> <small><p class="d-inline-block text-muted">' . $data . '</p></small>';
else
	echo '<h5 class="mr-4 d-inline-block"><a href="profil.php?id=$id_uzytkownika">' . $login . '</a></h5> <small><p class="d-inline-block text-muted">' . $data . '</p></small>';

if ($id_uzytkownika == $logged || $logged_typ == "administrator" || ($typ == "uczen" && ($logged_typ == "administrator" || $logged_typ == "nauczyciel")))
{
	echo '<button type="button" class="btn btn-primary btn-sm float-right ml-2 mr-2 text-center" onclick="usunPost(' . $wiersz['id_posta'] . ')"><i class="icon-delete"> </i>Usuń</button>';
	
	echo '<a id="hrefPost" class="btn btn-primary btn-sm float-right mr-2 text-center" role="button" href="editPost.php?id=' . $wiersz['id_posta'] . '"><i class="icon-pencil"> </i>Edytuj</a>';
}

echo<<<END
<hr class="mt-0">
<p> $tresc </p> 
</div>		
</div>
END;
			}
			
			$wynik_user -> close();
			$wynik_posty -> close();
			$conn -> close();
			}
			
			else 
			{
				echo '<div class="alert alert-info mb-4">';
				echo 'W tym temacie nie ma dostępnych żadnych postów';
				echo '</div>';
				
			}		
		}
			
		else
		{
			require "connect.php";
			
			$id_tematu = $_GET['id'];
			$sql_temat = "SELECT nazwa FROM tematy WHERE id_tematu = $id_tematu";
			$wynik_tematy = $conn -> query($sql_temat);
			$wiersz_tematy = $wynik_tematy -> fetch_assoc();
			
			$nazwa_tematu = $wiersz_tematy['nazwa'];
			
			echo '<div id="topic"><h3 id="topicName" class="my-4">' . $nazwa_tematu . '</h3></div>';
			
			$sql_posty = "SELECT id_posta, tresc, data_publikacji, uzytkownik_id FROM posty WHERE temat_id = $id_tematu";
			$wynik_posty = $conn -> query($sql_posty);
			
			if ($wynik_posty -> num_rows > 0)
			{
			while ($wiersz = $wynik_posty -> fetch_assoc())
			{
				$tresc = $wiersz['tresc'];
				$data = $wiersz['data_publikacji'];
				$id_uzytkownika = $wiersz['uzytkownik_id'];
				
				$sql_user = "SELECT login, plec, typ_konta FROM uzytkownicy WHERE id_uzytkownika = $id_uzytkownika";
				$wynik_user = $conn -> query($sql_user);
				$wiersz_user = $wynik_user -> fetch_assoc();
				
				if ($wiersz_user['plec'] == "m")
					$plec = "man";
				else
					$plec = "woman";
				
				$login = $wiersz_user['login'];
				$typ = $wiersz_user['typ_konta'];

echo '<div id="' . $wiersz['id_posta'] . '" class="media mt-2 mb-4 p-3">';			
echo<<<END
<img src="avatar-$plec.png" alt="avatar" class="mr-4 mt-4 rounded-circle" style="width:70px;">
<div class="media-body w-100">
END;

if ($id_uzytkownika == -1)
	echo '<h5 class="mr-4 d-inline-block"> Konto usunięte </h5> <small><p class="d-inline-block text-muted">' . $data . '</p></small>';
else
	echo '<h5 class="mr-4 d-inline-block"><a href="profil.php?id=$id_uzytkownika">' . $login . '</a></h5> <small><p class="d-inline-block text-muted">' . $data . '</p></small>';

echo<<<END
<hr class="mt-0">
<p> $tresc </p> 
</div>		
</div>
END;
			}
			
			$wynik_user -> close();
			$wynik_posty -> close();
			$conn -> close();
			}
			
			else 
			{
				echo '<div class="alert alert-info mb-4">';
				echo 'W tym temacie nie ma dostępnych żadnych postów';
				echo '</div>';
				
			}		
				
		}
		?>
		
		<?php
		if (isset($_SESSION['zalogowany']))
		{
echo<<<END
<form method="POST">
<div class="form-group">
<textarea class="form-control" rows="5" id="tr" name="odpowiedz" placeholder="Wpisz swoją odpowiedź" required></textarea>
</div>
END;
		
if (isset($_SESSION['blad_tresc']))
{
	echo '<p style="color: red">'. $_SESSION['blad_tresc']. "</p>";
	unset ($_SESSION['blad_tresc']);
}
					
echo<<<END
<button type="submit" class="btn btn-primary mb-4">Dodaj odpowiedź</button>
</form>
</div>
END;
		}
		
		else
		{
echo<<<END
<div class="alert alert-info">
Zaloguj się, aby dodać post.
</div>
END;
		}
		?>

</body>
</html>
