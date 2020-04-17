<?php
	session_start();
	require "sesje.php";
	
	if (!isset ($_SESSION['zalogowany']))
	{
		$_SESSION['temat_err'] = "Zaloguj się, aby kontynuować";
		header ("Location: logowanie.php");
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
	<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
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
<li class="nav-item pl-3 dropdown active">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel administratora
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item active" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
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
<li class="nav-item pl-3 dropdown active">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel nauczyciela
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item active" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
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
<li class="nav-item pl-3 dropdown active">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel ucznia
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item active" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
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
	
	<?php
		require "connect.php";
		$id = $_GET['id'];
		
		$sql = "SELECT login, plec FROM uzytkownicy WHERE id_uzytkownika = $id";
		$wynik = $conn -> query($sql);
		
		if ($wynik && $wynik -> num_rows > 0 && $id > 0)
		{
			$wiersz = $wynik -> fetch_assoc();
			$nazwa = $wiersz['login'];
			$plec = $wiersz['plec'];
			
			echo '<h3 class="m-4"> Profil użytkownika ' . '<strong style="color: #2A0E64">' . $nazwa . "</strong> </h3>";
			
			$conn -> close();
			$wynik -> close();
		}
		else
		{
			echo '<div class="alert alert-danger m-4"> Nie ma takiego użytkownika </div>';
			exit();
		}
		
	?>	
	
	<section class="container">
		<div class="row">
			<div class="col-lg-4 col-md-6">
				<img style="width: 250px; height: 275px" src=<?php if ($plec == "m") echo '"avatar-man.png"'; else echo '"avatar-woman.png"';?> alt="avatar">
			</div>
			<div class="col-lg-8 col-md-6">
				<?php
					require "connect.php";
					$sql_dane = "SELECT imie, nazwisko FROM uzytkownicy WHERE id_uzytkownika = $id";
					$wynik = $conn -> query($sql_dane);
					$wiersz = $wynik -> fetch_assoc();
					
					echo '<h5 class="mt-md-0 mt-sm-5">Imię: ' . '<strong style="color: #2A0E64">'. $wiersz['imie'] . '</strong> </h5>';
					echo "<hr>";
					echo '<h5>Nazwisko: ' . '<strong style="color: #2A0E64">'. $wiersz['nazwisko'] . '</strong> </h5>';
					echo "<hr>";
					
					$wynik -> close();
					
					$sql_tematy = "SELECT COUNT(id_tematu) AS ile_tematow FROM tematy, uzytkownicy WHERE tematy.uzytkownik_id = uzytkownicy.id_uzytkownika AND uzytkownicy.id_uzytkownika = $id";
					
					$wynik = $conn -> query($sql_tematy);
					$wiersz = $wynik -> fetch_assoc();
					
					echo '<h5>Liczba założonych tematów: ' . '<strong style="color: #2A0E64">' . $wiersz['ile_tematow'] . '</strong> </h5>';
					echo "<hr>";
					
					$wynik -> close();
					
					$sql_posty = "SELECT COUNT(id_posta) AS ile_postow FROM posty, uzytkownicy WHERE posty.uzytkownik_id = uzytkownicy.id_uzytkownika AND uzytkownicy.id_uzytkownika = $id";
					
					$wynik = $conn -> query($sql_posty);
					$wiersz = $wynik -> fetch_assoc();
					
					echo '<h5>Liczba dodanych postów: ' . '<strong style="color: #2A0E64">' . $wiersz['ile_postow'] . '</strong> </h5>';
					echo "<hr>";
					
					$wynik -> close();
					$conn -> close();
					
				?>
			</div>
		</div>
	</section>
	
</body>
</html>
