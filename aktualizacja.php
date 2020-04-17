<?php
	session_start();
	require "sesje.php";
	
	if (!isset($_SESSION['aktualizacja']))
		header ("Location: index.php");
	
	if (isset($_GET['id']) && isset($_SESSION['aktualizacjaId']) && $_GET['id'] != $_SESSION['aktualizacjaId'])
		header ("Location: index.php");
	
	else
	{
		if (isset($_POST['haslo1']))
		{
			require "connect.php";
			$ok = true;
			
			$haslo1 = $_POST['haslo1'];
			$haslo2 = $_POST['haslo2'];
			$userId = $_GET['id'];
			
			if (isset($_POST['rodzaj']))
			{
				$rodzaj = $_POST['rodzaj'];
				
				if ($rodzaj == "uczen")
				{
					if (isset($_POST['listaKlas']))
						$klasaUcznia = $_POST['listaKlas'];
					else
					{
						$_SESSION['err_klasa'] = "Musisz wybrać klasę";
						$_SESSION['aktualizacja'] = "Zaktualizuj swoje dane";
					}
				}
				else if ($rodzaj == "absolwent")
				{
					$sql = "SELECT id_klasy FROM klasy WHERE nazwa = 'absolwenci'";
					$wyn = $conn -> query($sql);
					
					if ($wyn && $wyn -> num_rows > 0)
					{
						$wiersz = $wyn -> fetch_assoc();
						$klasaUcznia = $wiersz['id_klasy'];
					}
					
				}
			}
			
			else
			{
				$ok = false;
				$_SESSION['err_rodzaj'] = "Musisz wybrać jedną z opcji";
				$_SESSION['aktualizacja'] = "Zaktualizuj swoje dane";
			}	
				
			if (strlen($haslo1) < 8)
			{
				$ok = false;
				$_SESSION['err_haslo'] = "Hasło musi zawierać co najmniej 8 znaków";
				$_SESSION['aktualizacja'] = "Zaktualizuj swoje dane";
			}
			
			if ($haslo1 != $haslo2)
			{
				$ok = false;
				$_SESSION['err_haslo'] = "Podane hasła muszą być takie same";
				$_SESSION['aktualizacja'] = "Zaktualizuj swoje dane";
			}
			
			if ($ok)
			{
				
				$haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);
				
				$sql_update = "UPDATE uzytkownicy SET haslo = '$haslo_hash', czy_aktywne = 0, data_zalozenia = NOW() WHERE id_uzytkownika = $userId";
				
				$result = $conn -> query($sql_update);
				
				if ($result)
				{
					$_SESSION['zaktualizowany'] = true;
					
					$sql_klasy = "UPDATE uczniowie_i_klasy SET klasa_id = $klasaUcznia WHERE uczen_id = $userId";
					$conn -> query($sql_klasy);
					
					header ("Location: zaktualizowany.php");
				}
				else
					echo $conn -> error;
				
				$result -> close();
				
				$conn -> close();

			}
			
			
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
	<script src="script-aktualizacja.js"> </script>
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
	
		<div class="container mt-5">
		<h3 class="mb-4 text-center"> Twoje konto wygasło. Zaktualizuj swoje dane. </h3>
		
		<form method="POST">			
			<div class="form-group">
				<label for="pass1">Nowe hasło:</label>
				<input type="password" class="form-control" placeholder="Hasło" id="pass1" name="haslo1" required>
			<?php
				if (isset ($_SESSION['err_haslo']))
				{
					echo '<p style="color: red">'. $_SESSION['err_haslo']. "</p>";
					unset ($_SESSION['err_haslo']);
				}
			?>
			</div>
			
			<div class="form-group">
				<label for="pass2">Powtórz hasło:</label>
				<input type="password" class="form-control" placeholder="Powtórz hasło" id="pass2" name="haslo2" required>
			</div>
			
			<div class="form-check-inline">
				<span class="mr-4"> Jestem: </span> 
				<label class="form-check-label">
					<input id="uczen" type="radio" name="rodzaj" value="uczen" class="form-check-input" onclick="chooseClass()"> uczniem
					
				</label>
			</div>
			
			<div class="form-check-inline pl-5 pb-3">
				<label class="form-check-label">
					<input id="absolwent" type="radio" name="rodzaj" value="absolwent" class="form-check-input" onclick="hideClass()"> absolwentem
				</label>
			</div>
			<?php
				if (isset ($_SESSION['err_rodzaj']))
				{
					echo '<p style="color: red">'. $_SESSION['err_rodzaj']. "</p>";
					unset ($_SESSION['err_rodzaj']);
				}
			?>
			
			<p id="komunikat"> </p>
			
			<div id="listaU" class="form-group">
			<select class="form-control" id="sel" name="listaKlas">
				<?php
					require "connect.php";
					
					$sql = "SELECT id_klasy, nazwa FROM klasy";
					
					$result = $conn -> query($sql);
					
					while ($wiersz = $result -> fetch_assoc())
					{
						echo '<option value="' . $wiersz['id_klasy'] . '">' . $wiersz['nazwa'] . "</option>";
					}
					
					$result -> close();
					$conn -> close();
				?>
			</select>
			</div>
			
			<?php
				if (isset ($_SESSION['err_klasa']))
				{
					echo '<p style="color: red">'. $_SESSION['err_klasa']. "</p>";
					unset ($_SESSION['err_klasa']);
				}
			?>
			
			<center> <button type="submit" class="btn btn-primary text-center mb-4">Wyślij prośbę o aktywację</button> </center>

		</form>
	</div>
	
</body>
</html>
