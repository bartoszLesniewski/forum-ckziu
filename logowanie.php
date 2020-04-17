<?php
	session_start();
	require "sesje.php";
	
	if (isset($_SESSION['zalogowany']))
	{
		header("Location: index.php");
		exit();
	}
	
	if (isset($_POST['login']))
	{
		$login = $_POST['login'];
		$haslo = $_POST['haslo'];

		require "connect.php";
		
		$login = $conn -> real_escape_string($login);
		
		$sql = "SELECT * FROM uzytkownicy WHERE login='$login'";
		
		$result = $conn -> query($sql);
		
		if ($result -> num_rows > 0)
		{
			$row = $result -> fetch_assoc();
			
			if ($row['czy_aktywne'] == 0)
				$_SESSION['nieaktywne'] = "Twoje konto nie zostało jeszcze aktywowane. Skontaktuj się z administratorem.";
			
			else
			{
				if (password_verify ($haslo, $row['haslo']))
				{
					/*$_SESSION['zalogowany'] = true; 
					$_SESSION['id'] = $row['id_uzytkownika'];
					$_SESSION['uzytkownik'] = $row['login'];
					$_SESSION['typ_konta'] = $row['typ_konta'];*/
					
					if ($row['typ_konta'] == "uczen")
					{
						$userId = $row['id_uzytkownika'];
						$sql_klasa = "SELECT nazwa FROM klasy, uczniowie_i_klasy WHERE id_klasy = klasa_id AND uczen_id = $userId";
						$klasaResult = $conn -> query($sql_klasa);
						$klasaRow = $klasaResult -> fetch_assoc();
						$klasaNazwa = $klasaRow['nazwa'];
						
						if ($klasaNazwa != "absolwenci")
						{
							$sqlDate = "SELECT CURDATE() AS data";
							$dataResult = $conn -> query($sqlDate);
							$dataRow = $dataResult -> fetch_assoc();
							$dataStr = $dataRow['data'];
							$data_utworzenia = $row['data_zalozenia'];//porownanie dat zrobic!!!
							
							if ($dataStr == "2020-06-27" && strtotime($data_utworzenia) < strtotime($dataStr))
							{
								$_SESSION['aktualizacja'] = "Zaktualizuj swoje dane";
								$_SESSION['aktualizacjaId'] = $userId;
								$url = "aktualizacja.php?id=" . $userId;
								header ("Location: $url");
							}
							
							else
							{
								$_SESSION['zalogowany'] = true; 
								$_SESSION['id'] = $row['id_uzytkownika'];
								$_SESSION['uzytkownik'] = $row['login'];
								$_SESSION['typ_konta'] = $row['typ_konta'];
								
								if (isset($_SESSION['aktualizacja']))
									unset ($_SESSION['aktualizacja']);
								
								header ("Location: index.php");
							}
						}
						
						else
						{
							$_SESSION['zalogowany'] = true; 
							$_SESSION['id'] = $row['id_uzytkownika'];
							$_SESSION['uzytkownik'] = $row['login'];
							$_SESSION['typ_konta'] = $row['typ_konta'];
							
							if (isset($_SESSION['aktualizacja']))
								unset ($_SESSION['aktualizacja']);
							
							header ("Location: index.php");
						}
					}
					
					else
					{
						$_SESSION['zalogowany'] = true; 
						$_SESSION['id'] = $row['id_uzytkownika'];
						$_SESSION['uzytkownik'] = $row['login'];
						$_SESSION['typ_konta'] = $row['typ_konta'];
						
						if (isset($_SESSION['aktualizacja']))
							unset ($_SESSION['aktualizacja']);
						
						header ("Location: index.php");
					}
				}
				
				else
				{
					$_SESSION['blad_logowania'] = "Nieprawidłowy login lub hasło";
				}
				
			}
		}
		
		else
			$_SESSION['blad_logowania'] = "Nieprawidłowy login lub hasło";
		
		$conn -> close();
		$result -> close();
		
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
							echo '<a class="nav-link active" href="logowanie.php"> <i class="icon-login"></i> Logowanie </a>';
					?>
				</li> 
			</ul> 
		</div>
	</nav>
	
	<div class="container mt-5">
		<h3 class="mb-4 text-center"> Panel logowania </h3>
		<?php
			if (isset ($_SESSION['temat_err']))
			{
				echo '<div class="alert alert-info">' . $_SESSION['temat_err'] . "</div>";
				unset ($_SESSION ['temat_err']);
			}
			
			if (isset ($_SESSION['nieaktywne']))
			{
				echo '<div class="alert alert-info">' . $_SESSION['nieaktywne'] . "</div>";
				unset ($_SESSION ['nieaktywne']);
			}
		?>
		<form method="POST">
			<div class="form-group">
				<label for="log">Login:</label>
				<input type="text" class="form-control" placeholder="Login" id="log" name="login">
			</div>
			
			<div class="form-group">
				<label for="pass">Hasło:</label>
				<input type="password" class="form-control" placeholder="Hasło" id="pass" name="haslo">
			</div>
			
			<div class="form-group form-check">
				<label class="form-check-label">
				  <input class="form-check-input" type="checkbox"> Zapamiętaj mnie
				</label>
			</div>
			<?php
				if (isset($_SESSION['blad_logowania']))
				{
					echo '<p style="color: red">' . $_SESSION['blad_logowania'] . "</p>";
					unset ($_SESSION['blad_logowania']);
				}
			?>
			<center> <button type="submit" class="btn btn-primary text-center">Zaloguj się</button> </center>
		</form>
	
	</div>
  
</body>
</html>
