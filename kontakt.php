<?php
	session_start();
	require "sesje.php";
	
	if (!isset ($_SESSION['zalogowany']))
	{
		$_SESSION['temat_err'] = "Zaloguj się, aby kontynuować";
		header ("Location: logowanie.php");
	}
	
	$tytulOK = false;
	$trescOK = false;
	$clean_temat;
	
	/*if (isset($_POST['nazwa']) && isset($_POST['tresc']))
	{
		$_SESSION['pom_nazwa'] = $_POST['nazwa'];
		$_SESSION['pom_tresc'] = $_POST['tresc'];
	}*/
	
	if (isset($_POST['nazwa']))
	{
		require_once "htmlpurifier/library/HTMLPurifier.auto.php";
		$tyt = $_POST['nazwa'];
	
		$tyt_clear = strip_tags($tyt);
		$tyt_clear = htmlentities($tyt_clear, ENT_QUOTES, "UTF-8");
		$tyt_clear = trim($tyt_clear);
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$tyt_clear = $purifier->purify($tyt_clear);
		
		$_SESSION['pom_nazwa'] = $tyt_clear;

		if (ctype_space($tyt_clear) || $tyt_clear == "")
		{
			$_SESSION['blad_tytul'] = "Tytuł nie może być pusty.";
			unset($_SESSION['pom_nazwa']);
		}
		else
		{
			$tytulOK = true;
			$clean_temat = $tyt_clear;
		}
			
	}
	
	if (isset($_POST['tresc']))
	{
		$tr = $_POST['tresc'];
	
		$tr_clear = strip_tags($tr);
		$tr_clear = str_replace("&nbsp;", "", $tr_clear);
		
		if (ctype_space($tr_clear) || $tr_clear == "")
		{
			$_SESSION['blad_tresc'] = "Treść nie może być pusta";
			unset ($_SESSION['pom_tresc']);
		}
		else
			$trescOK = true;
	}
	
	
	if ($trescOK && $tytulOK)
	{
		require "connect.php";
		//$nazwa = $_POST['nazwa'];
		$tresc = $_POST['tresc'];
		$uzytkownik = $_SESSION['id'];
	
		
		$sql = "SELECT id_kategorii FROM kategorie WHERE nazwa='Wiadomości od użytkowników'";
		$wyn = $conn -> query($sql);
		
		$wiersz = $wyn -> fetch_assoc();
		$id_kat = $wiersz['id_kategorii'];
		
		$tresc = $conn -> real_escape_string($tresc);
		
		$sql_add = "INSERT INTO tematy VALUES (NULL, '$clean_temat', NOW(), $id_kat, $uzytkownik)";
		$result = $conn -> query($sql_add);

		if ($result)
		{
			$sql_id = "SELECT id_tematu, data_utworzenia FROM tematy WHERE nazwa = '$clean_temat' AND kategoria_id = $id_kat AND uzytkownik_id = $uzytkownik ORDER BY data_utworzenia DESC LIMIT 1";
			$wynik = $conn -> query($sql_id);
			
			if (!$wynik)
				echo $conn -> error;
			
			$wiersz = $wynik -> fetch_assoc();
			
			$id = $wiersz['id_tematu'];
			$data = $wiersz['data_utworzenia'];
			
			$sql_post = "INSERT INTO posty VALUES (NULL, '$tresc', '$data', $id, $uzytkownik)";
			
			$wynik2 = $conn -> query($sql_post);
			
			if (!$wynik2)
				echo $conn -> error;
			
			$_SESSION['send'] = "Wiadomość została wysłana";
			
			
			$sql_last = "SELECT id_tematu FROM tematy ORDER BY id_tematu DESC LIMIT 1";
			$result2 = $conn -> query($sql_last);
			$wiersz = $result2 -> fetch_assoc();
			$lastId = $wiersz['id_tematu'];
			
			$sql_dost = "INSERT INTO dostepnosc VALUES ($lastId, 'administratorzy')";
			$conn -> query ($sql_dost);
			
			
			$result2 -> close();
		}
		
		else 
			echo $conn -> error;
		
		
		$wynik -> close();
		$conn -> close();
		
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
	<script src="ckeditor_full/ckeditor.js"></script>
	<script src="jquery-3.4.1.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
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
<li class="nav-item pl-3 dropdown active">
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
<a class="dropdown-item active" href="kontakt.php">Kontakt z administratorem</a>
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
echo '<a class="dropdown-item" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
echo<<<END
<a class="dropdown-item" href="tematy-klasy-uczen.php">Tematy dla mojej klasy</a>
<a class="dropdown-item" href="moje-tematy.php">Moje tematy</a>
<a class="dropdown-item active" href="kontakt.php">Kontakt z administratorem</a>
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
			if (isset ($_SESSION['send']))
			{
				echo '<div class="alert alert-success mt-4">' . $_SESSION['send'] . "</div>";
				unset ($_SESSION ['send']);
				
				if (isset($_SESSION['pom_nazwa']))
					unset($_SESSION['pom_nazwa']);
				
				if (isset($_SESSION['pom_tresc']))
					unset($_SESSION['pom_tresc']);
			}
		?>
		
		<h3 class="my-4">Napisz wiadomość do administratora</h3>
		<form method="POST">
			<div class="form-group">
				<label for="title"> Temat </label>
				<input type="text" class="form-control" id="title" name="nazwa" value="<?php if(isset($_SESSION['pom_nazwa'])){echo $_SESSION['pom_nazwa']; unset($_SESSION['pom_nazwa']);}?>" required>
			
				<?php
					if (isset($_SESSION['blad_tytul']))
					{
						echo '<p class="mt-2" style="color: red">'. $_SESSION['blad_tytul']. "</p>";
						unset ($_SESSION['blad_tytul']);
					}
				
				?>
			</div>
			<div class="form-group">
				<label for="tr">Treść:</label>
				<textarea class="form-control" rows="7" id="tr" name="tresc" required><?php if (isset($_SESSION['pom_tresc'])) {echo $_SESSION['pom_tresc']; unset ($_SESSION['pom_tresc']);} ?></textarea>
			</div>
			
			<?php
				if (isset($_SESSION['blad_tresc']))
				{
					echo '<p style="color: red">'. $_SESSION['blad_tresc']. "</p>";
					unset ($_SESSION['blad_tresc']);
				}
			
			?>
			
			<button type="submit" class="btn btn-primary mb-4"> Wyślij wiadomość </button>
		</form>
	</div>
	
</body>
</html>
