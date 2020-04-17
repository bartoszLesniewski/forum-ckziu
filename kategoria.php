<?php
	session_start();
	require "sesje.php";
	require "dostepneKategorie.php";
	require "connect.php";
	$topic_id = $_GET['id'];
	$topics = $_SESSION['dostepneKategorie'];
	
	if (strpos($topics, $topic_id) === false)
		$_SESSION['brak_uprawnien'] = "Nie masz odpowiednich uprawnień, aby wyświetlić tę kategorię";
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
							echo '<a class="nav-link" href="logowanie.php"> <i class="icon-login"></i> Logowanie </a>';
					?>
				</li> 
			</ul> 
		</div>
	</nav>
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
			require "dostepneTematy.php";
			require "connect.php";
			$id_kat = $_GET['id'];
			$sql_nazwa = "SELECT nazwa FROM kategorie WHERE id_kategorii = $id_kat";
			$wynik = $conn -> query($sql_nazwa);
			$wiersz = $wynik -> fetch_assoc();
			
			$nazwa_kat = $wiersz['nazwa'];
			
			echo '<h3 class="m-4"> Tematy w kategorii ' . '<i style="color: #2A0E64">' . $nazwa_kat . '</i>' . '</h3>';
			
			$topics = $_SESSION['dostepneTematy'];
			
			$sql = "SELECT tematy.id_tematu, tematy.nazwa, tematy.data_utworzenia, uzytkownicy.id_uzytkownika, uzytkownicy.login FROM tematy, kategorie, uzytkownicy WHERE tematy.kategoria_id = kategorie.id_kategorii AND tematy.uzytkownik_id = uzytkownicy.id_uzytkownika AND kategorie.id_kategorii = $id_kat AND tematy.id_tematu IN ($topics) ORDER BY tematy.data_utworzenia DESC";
			
			$wynik = $conn -> query($sql);
			
			
			if ($wynik -> num_rows > 0)
			{
echo<<<END
<div class="container table-responsive">
<table class="table table-bordered table-hover">
<thead class="thead-dark text-center">
<th> Temat </th>
<th> Liczba postów </th>
<th> Ostatni post </th>
</thead>
<tbody>
END;
				
				while ($wiersz = $wynik -> fetch_assoc())
				{
					$t_id = $wiersz['id_tematu'];
					$ilosc = 0;
					$sql2 = "SELECT COUNT(posty.id_posta) AS ilosc FROM posty WHERE posty.temat_id = $t_id";
					$wynik2 = $conn -> query($sql2);
					
					if ($wynik2 && $wynik2 -> num_rows > 0)
					{
						$wiersz2 = $wynik2 -> fetch_assoc();
						$ilosc = $wiersz2['ilosc'];
					}
						
					echo "<tr>";
						echo "<td>";
						echo '<strong><a href="' . "temat.php?id=" . $wiersz['id_tematu'] . '">' . $wiersz['nazwa'] . "</a> </strong>";
							
						echo '<p class="mb-0">założony przez: ';
						if ($wiersz['id_uzytkownika'] == -1)
							echo "konto usunięte </p>";
						else
						{
							echo '<a class="d-inline-block" href="' . "profil.php?id=" . $wiersz['id_uzytkownika'] . '">' . $wiersz['login'] . "</a> </p>";
						}
						
						echo '<p class="text-muted mb-0">' . $wiersz['data_utworzenia'] . "</p>";
						
						echo "</td>";
						
						$id_tem = $wiersz['id_tematu'];
						$sql_ostatni = "SELECT uzytkownicy.id_uzytkownika, uzytkownicy.login, posty.data_publikacji FROM tematy, uzytkownicy, posty WHERE posty.uzytkownik_id = uzytkownicy.id_uzytkownika AND posty.temat_id = tematy.id_tematu AND tematy.id_tematu = $id_tem ORDER BY posty.data_publikacji DESC LIMIT 1";
						
						$wynik2 = $conn -> query($sql_ostatni);
						$wiersz2 = $wynik2 -> fetch_assoc();
						
						echo '<td class="text-center">' . '<p class="mt-4">' . $ilosc . "</p>" . "</td>";
						
						echo '<td class="text-center">';
						
						if ($wynik2 && $wynik2 -> num_rows > 0)
						{
							echo '<p class="mb-0 mt-3">autor: ' . '<a class="d-inline-block" href="' . "profil.php?id=" . $wiersz2['id_uzytkownika'] . '">' . $wiersz2['login'] . "</a> </p>";										
							echo '<p class="text-muted mb-0">' . $wiersz2['data_publikacji'] . "</p>";
						}
						else
						{
							echo '<p class="mb-0 mt-3"> Brak postów </p>';
						}
						
						echo "</td>";
						
					echo "</tr>";
					
					$wynik2 -> close();
				}
echo<<<END
</tbody>
</table>
</div>
END;
			}
			
			else
				echo '<p class="m-4"> W tej kategorii nie ma jeszcze żadnych tematów lub nie masz do nich dostępu </p>';
			
			$wynik -> close();
			$conn -> close();
		?>
</body>
</html>
