<?php
	session_start();
	require "sesje.php";
	
	if (!isset ($_SESSION['zalogowany']))
	{
		$_SESSION['temat_err'] = "Zaloguj się, aby kontynuować";
		header ("Location: logowanie.php");
	}
	
	/*if (isset ($_POST['tytul']) && isset($_POST['tresc']))
	{
		$_SESSION['pom_tytul'] = $_POST['tytul'];
		$_SESSION['pom_tresc'] = $_POST['tresc'];
	}*/
	
	if (isset($_POST['tytul']) && $_POST['dostepnosc'] == "klasa" && !isset($_POST['check']))
	{
		/*$_SESSION['pom_tytul'] = $_POST['tytul'];
		$_SESSION['pom_tresc'] = $_POST['tresc'];*/
		$_SESSION['pom_lista'] = $_POST['lista'];
		$_SESSION['pom_dostepnosc'] = $_POST['dostepnosc'];
		$_SESSION['blad_check'] = "Musisz wybrać co najmniej jedną klasę";
	}
	
	if (isset($_SESSION['pom_dostepnosc']))
	{
		$pomDost = $_SESSION['pom_dostepnosc'];
		unset($_SESSION['pom_dostepnosc']);
	}
	
	else
		$pomDost = "null";
	
	/*if (isset($_POST['tytul']) && ctype_space(strip_tags($_POST['tytul'])))
		$_SESSION['blad_tytul'] = "Tytuł nie może być pusty!";
	
	if (isset($_POST['tresc']) && ctype_space(strip_tags($_POST['tresc'])))
		$_SESSION['blad_tresc'] = "Treść nie może być pusta!";
	
	echo "tresc:" . ctype_space(strip_tags($_POST['tresc']));*/
	
	$tytulOK = false;
	$trescOK = false;
	$clean_temat;
	
	if (isset($_POST['tytul']))
	{
		require_once "htmlpurifier/library/HTMLPurifier.auto.php";
		$tyt = $_POST['tytul'];
	
		/*$tyt_clear = strip_tags($tyt);
		$tyt_clear = html_entity_decode($tyt_clear);
		$tyt_clear = urldecode($tyt_clear);
		$tyt_clear = preg_replace('/[^A-Za-z0-9]/', ' ', $tyt_clear);
		$tyt_clear = preg_replace('/ +/', ' ', $tyt_clear);
		$tyt_clear = trim($tyt_clear);*/
		
		$tyt_clear = strip_tags($tyt);
		$tyt_clear = htmlentities($tyt_clear, ENT_QUOTES, "UTF-8");
		$tyt_clear = trim($tyt_clear);
		
		$config = HTMLPurifier_Config::createDefault();
		$purifier = new HTMLPurifier($config);
		$tyt_clear = $purifier->purify($tyt_clear);
		
		$_SESSION['pom_tytul'] = $tyt_clear;
		
		//echo "tyt_clear: " . $tyt_clear;

		if (ctype_space($tyt_clear) || $tyt_clear == "")
		{
			$_SESSION['blad_tytul'] = "Tytuł nie może być pusty.";
			unset($_SESSION['pom_tytul']);
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
		
		$_SESSION['pom_tresc'] = $tr_clear;
		
		if (ctype_space($tr_clear) || $tr_clear == "")
		{
			$_SESSION['blad_tresc'] = "Treść nie może być pusta";
			unset ($_SESSION['pom_tresc']);
		}
		else
			$trescOK = true;
	}
	
	
	if (isset($_POST['tytul']) && ($_POST['dostepnosc'] != "klasa" || $_POST['dostepnosc'] == "klasa" && isset($_POST['check'])) && $trescOK && $tytulOK)
	{
		require "connect.php";
		
		//$tytul = $_POST['tytul'];
		$tresc = $_POST['tresc'];
		$kategoria = $_POST['lista'];
		$uzytkownik = $_SESSION['id'];
		$dost = $_POST['dostepnosc'];
		
		$clean_temat = $conn -> real_escape_string($clean_temat);
		$tresc = $conn -> real_escape_string($tresc);
		
		$sql_add = "INSERT INTO tematy VALUES (NULL, '$clean_temat', NOW(), $kategoria, $uzytkownik)";
		$result = $conn -> query($sql_add);

		if ($result)
		{
			$sql_id = "SELECT id_tematu, data_utworzenia FROM tematy WHERE nazwa = '$clean_temat' AND kategoria_id = $kategoria AND uzytkownik_id = $uzytkownik ORDER BY data_utworzenia DESC LIMIT 1";
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
			
			$_SESSION['temat_add'] = "Temat został dodany";
			
			
			$sql_last = "SELECT id_tematu FROM tematy ORDER BY id_tematu DESC LIMIT 1";
			$result2 = $conn -> query($sql_last);
			$wiersz = $result2 -> fetch_assoc();
			$lastId = $wiersz['id_tematu'];
			
			/*$dostepneTematy = $_SESSION['dostepneTematy'];
			$dostepneTematy = $dostepneTematy . ", " . $lastId;
			$_SESSION['dostepneTematy'] = $dostepneTematy;*/
			
			if ($dost == "klasa")
			{
				$klasy = $_POST['check'];
				
				for ($i = 0; $i < count($klasy); $i++)
				{
					$idKlasy = $klasy[$i];
					$sql_dost = "INSERT INTO dostepnosc VALUES ($lastId, '$idKlasy')";
					
					$conn -> query($sql_dost);
				}
			}
			
			else if ($dost == "moja")
			{
				$user = $_SESSION['id'];
				$sql = "SELECT klasa_id FROM uczniowie_i_klasy WHERE uczen_id = $user";
				$res = $conn -> query($sql);
				$wiersz = $res -> fetch_assoc();
				
				$idKlasy = $wiersz['klasa_id'];
				
				$sql_dost = "INSERT INTO dostepnosc VALUES ($lastId, '$idKlasy')";
				$conn -> query($sql_dost);
			}
			
			else
			{
				$sql_dost = "INSERT INTO dostepnosc VALUES ($lastId, '$dost')";
				$conn -> query ($sql_dost);
			}
			
			$result2 -> close();
			
			if (isset($_SESSION['pom_tytul']))
				unset($_SESSION['pom_tytul']);
			
			if (isset($_SESSION['pom_tresc']))
				unset($_SESSION['pom_tresc']);
			
			if (isset($_SESSION['pom_lista']))
				unset($_SESSION['pom_lista']);
			
			if (isset($_SESSION['pom_dostepnosc']))
				unset($_SESSION['pom_dostepnosc']);
			
			header ("Location: temat.php?id=$id");
		}
		
		else 
			echo $conn -> error;
		
		$wynik2 -> close();
		$wynik -> close();
		$result -> close();
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
	<script>
		var se = "<?php echo $_SESSION['typ_konta'];?>";
    </script>
	<script>
		var p ='<?php echo $pomDost;?>';
	</script>
	<script src="script-temat.js"> </script>
	<script src="editor.js"></script>
</head>

<body onload="kategorie()">
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
					<a class="nav-link active" href="dodaj-temat.php"> Dodaj temat </a>
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
		<h3 class="my-4">Dodawanie nowego tematu</h3>
		<form method="POST">
			<div class="form-group">
				<label for="title"> Tytuł </label>
				<input type="text" class="form-control" id="title" name="tytul" value="<?php if(isset($_SESSION['pom_tytul'])) {echo $_SESSION['pom_tytul']; unset ($_SESSION['pom_tytul']);} ?>" required>
			</div>
			<?php
				if (isset($_SESSION['blad_tytul']))
				{
					echo '<p style="color: red">'. $_SESSION['blad_tytul']. "</p>";
					unset ($_SESSION['blad_tytul']);
				}
			
			?>
			
			<div class="form-group">
				<label for="lista"> Wybierz kategorię </label>
				<select class="form-control" id="lista" name="lista" onchange="kategorie()">
					<?php
						require "dostepneKategorie.php";
						require "connect.php";
						$dostepneKategorie = $_SESSION['dostepneKategorie'];
						
						$sql_kategorie = "SELECT id_kategorii, nazwa FROM kategorie WHERE id_kategorii IN($dostepneKategorie)";
						$wynik = $conn -> query($sql_kategorie);
						
						while ($wiersz = $wynik -> fetch_assoc())
						{
							echo '<option value="' . $wiersz['id_kategorii'] . '" ';
							if (isset($_SESSION['pom_lista']) && $_SESSION['pom_lista'] == $wiersz['id_kategorii'])
							{
								echo "selected";
								unset ($_SESSION['pom_lista']);
							}
							echo '>' . $wiersz['nazwa'] . "</option>";
						}
						
						$wynik -> close();
						$conn -> close();
						
					?>
				</select>
			</div>
			<div id="dost">
			
				
<?php
/*$typ = $_SESSION['typ_konta'];

echo $_POST['grupa'];

if ($typ != "uczen")
{
echo<<<END
<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">
<option value="wszyscy"> Wszyscy </option>
<option value="nauczyciele"> Nauczyciele </option>
<option value="uczniowie"> Uczniowie </option>
<option value="administratorzy"> Administratorzy </option>
<option value="klasa"
END;
if (isset($_SESSION['pom_dostepnosc']))
{
	echo "selected";
	unset ($_SESSION['pom_dostepnosc']);
}
echo<<<END
> Wybrana klasa / klasy </option>
</select>
END;
}

else
{
echo<<<END
<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">
<option value="wszyscy"> Wszyscy </option>
<option value="uczniowie"> Uczniowie </option>
<option value="moja"> Moja klasa </option>
</select>
END;
}*/
?>
				
			</div>
			
			<p id="komunikat"> </p>
			<div id="listaN" class="checkList mb-4">	
				<?php
				require "connect.php";
				
				$sql = "SELECT id_klasy, nazwa FROM klasy";
				
				$result = $conn -> query($sql);
				
				while ($wiersz = $result -> fetch_assoc())
				{
					echo '<label> <input class="ml-2" type="checkbox" name="check[]" value="' . $wiersz['id_klasy'] . '">' . " " . $wiersz['nazwa'] . '</label> <br>';
				}
				
				$result -> close();
				$conn -> close();
				?>
			</div>
			
			<?php
				if (isset($_SESSION['blad_check']))
				{
					echo '<p style="color: red">'. $_SESSION['blad_check']. "</p>";
					unset ($_SESSION['blad_check']);
				}
			?>
			
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
			<button type="submit" class="btn btn-primary mb-4">Dodaj temat</button>
		</form>
	</div>

</body>
</html>
