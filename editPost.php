<?php
	session_start();
	require "sesje.php";

	if (!isset ($_SESSION['zalogowany']))
	{
		$_SESSION['temat_err'] = "Zaloguj się, aby kontynuować";
		header ("Location: logowanie.php");
	}
	
	else if (isset($_SESSION['zalogowany']) && $_SESSION['typ_konta'] != "administrator")
	{
		require "connect.php";
		$id = $_GET['id'];
		$sql = "SELECT uzytkownik_id FROM posty WHERE id_posta = $id";
		$wynik = $conn -> query($sql);
		
		if ($wynik -> num_rows > 0)
		{
			$wiersz = $wynik -> fetch_assoc();
			$autor = $wiersz['uzytkownik_id'];
		}
		else
			$autor = -1;
	
		$zalogowany = $_SESSION['id'];
		
		if ($zalogowany != $autor)
			$_SESSION['blad_post'] = "Nie masz odpowiednich uprawnień do wykonania tej operacji";
		
		$sql_autor_typ = "SELECT typ_konta FROM uzytkownicy WHERE id_uzytkownika = $autor";
		$wyn = $conn -> query($sql_autor_typ);
		$wiersz = $wyn -> fetch_assoc();
		
		$typAutor = $wiersz['typ_konta'];
		$typZalogowany = $_SESSION['typ_konta'];
		
		if ($typAutor == "uczen" && $typZalogowany == "nauczyciel")
			unset($_SESSION['blad_post']);
	}
	
	/*else if (isset ($_SESSION['zalogowany']))
	{
		require "dostepneTematy.php";
		require "connect.php";
		$topic_id = $_GET['id'];
		$topics = $_SESSION['dostepneTematy'];
		
		if (strpos($topics, $topic_id) == false)
			$_SESSION['brak_uprawnien'] = "Nie masz odpowiednich uprawnień, aby wyświetlić ten temat";
	}*/

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
	<script src="ckeditor_full/ckeditor.js"></script>
	<script src="editor.js"></script>
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
			if (isset($_SESSION['blad_post']))
			{
				echo '<div class="alert alert-danger m-4">';
				echo $_SESSION['blad_post'];
				echo '</div>';
				
				unset ($_SESSION['blad_post']);
				exit();
			}
		?>
		<h4 class="mt-4"> Edycja posta </h3>
		<form method="POST" action="edytuj-post.php?id=<?php echo $_GET['id'];?>" class="mt-4">
			<div class="form-group">
				<textarea class="form-control" rows="5" id="tr" name="edit" placeholder="Wpisz swoją odpowiedź" required><?php 
					require "connect.php";
					$post = $_GET['id'];
					
					$sql = "SELECT tresc FROM posty WHERE id_posta = $post";
					$wynik = $conn -> query($sql);
					$wiersz = $wynik -> fetch_assoc();
					
					echo $wiersz['tresc'];
					
					$wynik -> close();
					$conn -> close();
				?></textarea>
			</div>
			
			<?php
				if (isset($_SESSION['blad_edit']))
				{
					echo '<p style="color: red">'. $_SESSION['blad_edit']. "</p>";
					unset ($_SESSION['blad_edit']);
				}
			
			?>
			<button type="submit" class="btn btn-primary mb-4">Edytuj post</button>

		</form>
	</div>
	
</body>
</html>
