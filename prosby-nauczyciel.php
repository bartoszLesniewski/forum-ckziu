<?php
	session_start();
	require "sesje.php";
	
	if (!isset($_SESSION['zalogowany']) || isset($_SESSION['zalogowany']) && $_SESSION['typ_konta'] == "uczen")
	{
		header ("Location: index.php");
		exit();
	}
	
	if (isset ($_POST['check']))
	{
		if ($_POST['sub'] == 1)
		{
			require "connect.php";
			
			$checkboxes = $_POST['check'];
			
			for ($i = 0; $i < count($checkboxes); $i++)
			{
				$id = $checkboxes[$i];
				$sql = "UPDATE uzytkownicy SET czy_aktywne = 1 WHERE id_uzytkownika=$id";
				$result = $conn -> query ($sql);
			}
			
			$_SESSION['zaakceptowane'] = "Operacja wykonana pomyślnie!";
			
			$conn -> close();
		}
		else
		{
			require "connect.php";
			
			$checkboxes = $_POST['check'];
			
			for ($i = 0; $i < count($checkboxes); $i++)
			{
				$id = $checkboxes[$i];
			
				$sql_typ = "SELECT typ_konta FROM uzytkownicy WHERE id_uzytkownika = $id";
				$wynik = $conn -> query($sql_typ);
				$wiersz = $wynik -> fetch_assoc();
				$typ = $wiersz['typ_konta'];
				
				if ($typ == "uczen")
				{
					$sql_uczen = "DELETE FROM uczniowie_i_klasy WHERE uczen_id = $id";
					$conn -> query($sql_uczen);
				}
				else if ($typ == "nauczyciel");
				{
					$sql_nauczyciel = "DELETE FROM nauczyciele_i_klasy WHERE nauczyciel_id = $id";
					$conn -> query($sql_nauczyciel);
				}
				
				$sql = "DELETE FROM uzytkownicy WHERE id_uzytkownika = $id";
				
				$result = $conn -> query($sql);
			}
			
			$_SESSION['zaakceptowane'] = "Operacja wykonana pomyślnie!";
			
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
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/jquery.dataTables.css">
	<script src="jquery-3.4.1.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>

	<script src="script-prosby.js"> </script>
	<script src="script-prosbyTable.js"></script>
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
<a class="dropdown-item active" href="prosby-nauczyciel.php">Aktywacja kont uczniów</a>
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
			
	
		<div class="card mx-4 my-4">
			<div class="card-header"><h3>Prośby o aktywację</h3></div>
			<div class="card-body">
			
			<?php
				if (isset ($_SESSION['zaakceptowane']))
				{
					echo '<div class="alert alert-success">' . $_SESSION['zaakceptowane'] . '</div>';
					unset ($_SESSION['zaakceptowane']);
				}
			?>
			
			<div id="tab">
				<form class="ml-4" method="POST">
				<?php
					require "connect.php";
					$sql = "SELECT id_uzytkownika, login, imie, nazwisko, klasy.nazwa FROM uzytkownicy, uczniowie_i_klasy, klasy WHERE czy_aktywne = 0 AND typ_konta='uczen' AND uzytkownicy.id_uzytkownika = uczniowie_i_klasy.uczen_id AND uczniowie_i_klasy.klasa_id = klasy.id_klasy";
					
					$wynik = $conn -> query ($sql);
					
					if ($wynik -> num_rows > 0)
					{
echo<<<END
<button type="submit" name="sub" class="btn btn-primary mb-4" value="1"> Zaakceptuj </button>
<button type="submit" name="sub" class="btn btn-danger mb-4 ml-4" value="2"> Odrzuć </button>
<div class="custom-control custom-checkbox mb-4">
<input type="checkbox" class="custom-control-input" id="moje" onchange="tylkoMoje()">
<label class="custom-control-label text-center align-middle" for="moje"> Pokazuj uczniów tylko z moich klas</label>
</div>
<div class="table-responsive">
<table id="prosby" class="table table-bordered text-center mb-4" cellspacing="0">


<thead>
<tr>
<th style="width: 20%">
<div class="custom-control custom-checkbox">
<input type="checkbox" class="custom-control-input" id="all" onclick="checkAll(this)">
<label class="custom-control-label text-center align-middle" for="all"> <strong> Zaznacz wszystkie </strong> </label>
</div>
</th>
<th>Login</th>
<th>Imię</th>
<th>Nazwisko</th>
<th>Klasa</th>
</tr>
</thead>

<tbody>
END;
					while ($wiersz = $wynik -> fetch_assoc())
						{
							//echo '<tr id="' . $wiersz['id_uzytkownika'] . '">';
							//echo "<td>" . '<input type="checkbox" class="form-check-input">' . "</td>";
							//echo "<td>" . $wiersz['login'] . "</td>";
							//echo "</tr>";
							echo "<tr>";
							echo "<td>";
							echo '<div class="custom-control custom-checkbox mb-3">';
							echo '<input type="checkbox" name="check[]" class="custom-control-input" id="' . $wiersz['id_uzytkownika'] . '" value="' . $wiersz['id_uzytkownika'] . '">';
							echo '<label class="custom-control-label" for="' . $wiersz['id_uzytkownika'] . '"></label>';
							echo "</div></td>";
							
							echo "<td>" . $wiersz['login'] . "</td>";
							echo "<td>" . $wiersz['imie'] . "</td>";
							echo "<td>" . $wiersz['nazwisko'] . "</td>";
							echo "<td>" . $wiersz['nazwa'] . "</td>";
	
						}
					}
					else
						echo "<p>Brak próśb o aktywację </p>";
					
					$wynik -> close();
					$conn -> close();
				?>
				</div>
				</form>
						</tbody>
					</table>
				</div>
			</div>
		</div>
</body>
</html>
