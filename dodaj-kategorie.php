<?php
	session_start();
	require "sesje.php";
	
	if (!isset ($_SESSION['zalogowany']))
	{
		$_SESSION['temat_err'] = "Zaloguj się, aby kontynuować";
		header ("Location: logowanie.php");
	}
	
	
	else if (isset($_SESSION['zalogowany']) && $_SESSION['typ_konta'] == "uczen")
	{
		$_SESSION['brak_uprawnien'] = "Nie masz odpowiednich uprawnień do wykonania tej operacji";
	}
	
	if (isset($_POST['nazwa']))
	{
		require "connect.php";
		require_once "htmlpurifier/library/HTMLPurifier.auto.php";
		
		$nazwa = $_POST['nazwa'];
		$dost = $_POST['dostepnosc'];
		
		if (isset($_POST['tresc']))
			$tresc = $_POST['tresc'];
		else
			$tresc = "";
		
		$sql_spr = "SELECT id_kategorii FROM kategorie WHERE nazwa='$nazwa'";
		$wyn = $conn -> query($sql_spr);
		
		if ($wyn -> num_rows > 0)
		{
			$_SESSION['blad_kategorii'] = "Kategoria o podanej nazwie już istnieje";
			$_SESSION['pom_tresc'] = $tresc;
		}
		
		/*$nazwa_clear = strip_tags($nazwa);
		$nazwa_clear = html_entity_decode($nazwa_clear);
		$nazwa_clear = urldecode($nazwa_clear);
		$nazwa_clear = preg_replace('/[^A-Za-z0-9]/', ' ', $nazwa_clear);
		$nazwa_clear = preg_replace('/ +/', ' ', $nazwa_clear);
		$nazwa_clear = trim($nazwa_clear);*/
		
		$nazwa_clear = strip_tags($nazwa);
		$nazwa_clear = htmlentities($nazwa_clear, ENT_QUOTES, "UTF-8");
		$nazwa_clear = trim($nazwa_clear);
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$nazwa_clear = $purifier->purify($nazwa_clear);

		if (ctype_space($nazwa_clear) || $nazwa_clear == "")
		{
			$_SESSION['blad_kategorii'] = "Nazwa kategorii nie może być pusta.";
			$_SESSION['pom_tresc'] = $tresc;
		}

		
		else
		{
			$nazwa_clear = $conn -> real_escape_string($nazwa_clear);
			
			$sql_add = "INSERT INTO kategorie VALUES (NULL, '$nazwa_clear', '$dost', '$tresc')";
			$result = $conn -> query($sql_add);

			if ($result)
			{	
				$_SESSION['kategoria_add'] = "Kategoria została dodana";
				
				header ("Location: kategorie.php");
			}
			
			else 
				echo $conn -> error;
		
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
<a class="nav-link active" href="dodaj-kategorie.php"> Dodaj kategorię </a>
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
				echo '<div class="alert alert-danger m-4">';
				echo $_SESSION['brak_uprawnien'];
				echo '</div>';
				
				unset ($_SESSION['brak_uprawnien']);
				exit();
			}
		?>
		
		<?php
			if (isset($_SESSION['blad_kategorii']))
			{
				echo '<div class="alert alert-danger mt-4">';
				echo $_SESSION['blad_kategorii'];
				echo '</div>';
				
				unset ($_SESSION['blad_kategorii']);
			}
		?>
		<h3 class="my-4">Dodawanie nowej kategorii</h3>
		<form method="POST">
			<div class="form-group">
				<label for="title"> Nazwa kategorii </label>
				<input type="text" class="form-control" id="title" name="nazwa" required>
			</div>
			
<div class="form-group">
<label for="dostepnosc"> Dla kogo ma być dostępna kategoria? </label>
<?php
$typ = $_SESSION['typ_konta'];

if ($typ == "nauczyciel")
{
echo<<<END
<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">
<option value="wszyscy"> Wszyscy </option>
<option value="nauczyciele"> Nauczyciele </option>
<option value="uczniowie"> Uczniowie </option>
</select>
END;
}

else
{
echo<<<END
<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">
<option value="wszyscy"> Wszyscy </option>
<option value="nauczyciele"> Nauczyciele </option>
<option value="uczniowie"> Uczniowie </option>
<option value="administratorzy"> Administratorzy </option>
</select>
END;
}
?>
</div>

			<div class="form-group">
				<label for="tr">Krótki opis kategorii (maksymalnie 200 znaków) - opcjonalnie</label>
				<textarea maxlength="200" class="form-control" rows="2" id="tr" name="tresc"><?php if (isset($_SESSION['pom_tresc'])) {echo $_SESSION['pom_tresc']; unset ($_SESSION['pom_tresc']);} ?></textarea>
			</div>
			
			<button type="submit" class="btn btn-primary mb-4">Dodaj kategorię </button>
		</form>
	</div>
	
</body>
</html>
