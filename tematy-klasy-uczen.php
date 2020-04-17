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
	<script src="jquery-3.4.1.min.js"> </script>
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
<li class="nav-item pl-3 dropdown active">
<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
Panel ucznia
</a>
<div class="dropdown-menu">
END;
echo '<a class="dropdown-item" href="profil.php?id=' . $_SESSION['id'] . '">Mój profil</a>';
echo<<<END
<a class="dropdown-item active" href="tematy-klasy-uczen.php">Tematy dla mojej klasy</a>
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
		$userId = $_SESSION['id'];
		$sql = "SELECT klasa_id, nazwa FROM klasy, uczniowie_i_klasy WHERE id_klasy = klasa_id AND uczen_id = $userId";
		$wynik = $conn -> query($sql);
		$wiersz = $wynik -> fetch_assoc();
		
		echo '<h3 class="m-4"> Tematy dla klasy <strong style="color:#2A0E64">' . $wiersz['nazwa'] . '</strong></h3>';
			
	$id_klasy = $wiersz['klasa_id'];
	
	$sql = "SELECT id_tematu FROM dostepnosc WHERE grupa='$id_klasy'";
	$wynik = $conn -> query($sql);
	$tematy = array();
	
	while ($wiersz = $wynik -> fetch_assoc())
	{
		$temat = $wiersz['id_tematu'];
		array_push($tematy, $temat);
	}
	
	$tematyStr = implode(", ", $tematy);
	
	$sql = "SELECT tematy.id_tematu, tematy.nazwa AS nazwa_tematu, kategorie.nazwa AS nazwa_kategorii, kategorie.id_kategorii, COUNT(posty.id_posta) AS ilosc, uzytkownicy.id_uzytkownika, uzytkownicy.login FROM tematy, kategorie, posty, uzytkownicy WHERE kategorie.id_kategorii = tematy.kategoria_id AND posty.temat_id = tematy.id_tematu AND tematy.uzytkownik_id = uzytkownicy.id_uzytkownika AND tematy.id_tematu IN ($tematyStr) GROUP BY tematy.nazwa ORDER BY tematy.data_utworzenia DESC";
			
			$wynik = $conn -> query($sql);
			
			
			if ($wynik && $wynik -> num_rows > 0)
			{
echo<<<END
<div class="container table-responsive">
<table class="table table-bordered table-hover">
<thead class="thead-dark text-center">
<th> Temat </th>
<th> Kategoria </th>
<th> Liczba postów </th>
</thead>
<tbody>
END;
				
				while ($wiersz = $wynik -> fetch_assoc())
				{
					echo "<tr>";
						echo "<td>";
						echo '<strong><a href="' . "temat.php?id=" . $wiersz['id_tematu'] . '">' . $wiersz['nazwa_tematu'] . "</a> </strong>";
							
						echo '<p class="mb-0">założony przez: ' . '<a class="d-inline-block" href="' . "profil.php?id=" . $wiersz['id_uzytkownika'] . '">' . $wiersz['login'] . "</a> </p>";
						
						echo "</td>";
						
						echo '<td class="text-center">';
						echo '<a class="mt-2" href="' . "kategoria.php?id=" . $wiersz['id_kategorii'] . '">' . $wiersz['nazwa_kategorii'] . "</a>";
						echo "</td>";
						
						echo '<td class="text-center">' . '<p class="mt-2">' . $wiersz['ilosc'] .  '</p>' . "</td>";
						
					echo "</tr>";
				}
echo<<<END
</tbody>
</table>
</div>
END;

			$wynik -> close();
			$conn -> close();
			}
			
			else
				echo '<p class="m-4"> Dla tej klasy nie ma dostępnych żadnych tematów. </p>';
			
			
	?>

</body>
</html>