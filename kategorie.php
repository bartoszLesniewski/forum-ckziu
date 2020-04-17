<?php
	session_start();
	require "sesje.php";
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
					<a class="nav-link active" href="kategorie.php"> Kategorie </a>
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
		if (isset($_SESSION['kategoria_add']))
		{
			echo '<div class="alert alert-success m-4">' . $_SESSION['kategoria_add'] . "</div>";
			unset ($_SESSION['kategoria_add']);
		}

	?>
	<h3 class="m-4"> Lista dostępnych kategorii </h3>
	
	<div class="container table-responsive">
		<table class="table table-bordered table-hover">
			<thead class="thead-dark text-center">
				<th> Nazwa kategorii </th>
				<th> Liczba tematów </th>
				<th> Ostatnia aktywność </th>
			</thead>
			
			<tbody>
				<?php
					require "dostepneTematy.php";
					require "dostepneKategorie.php";
					require "connect.php";
					
					$dostepneKategorie = $_SESSION['dostepneKategorie'];
					
					$sql_kat = "SELECT id_kategorii, nazwa, opis FROM kategorie WHERE id_kategorii IN($dostepneKategorie)";
					
					$kategorie = $conn -> query ($sql_kat);
					
					if ($kategorie && $kategorie -> num_rows > 0)
					{
						while ($wiersz = $kategorie -> fetch_assoc())
						{
							echo "<tr>";
							echo '<td style="width: 50%"> <h5 class="text-center mt-4"><a href="' . "kategoria.php?id=" . $wiersz['id_kategorii'] . '">' . $wiersz['nazwa']  . "</a> </h5> <p style='color: #515050'><i>" . $wiersz['opis'] . "</i><p></td>";
							
							$id_kat = $wiersz['id_kategorii'];
							
							$topics = $_SESSION['dostepneTematy'];
							
							$sql_tematy = "SELECT COUNT(id_tematu) AS ile FROM tematy WHERE kategoria_id = $id_kat AND id_tematu IN($topics)";
							$dane = $conn -> query ($sql_tematy);
							
							$wiersz2 = $dane -> fetch_assoc();
							
							echo '<td class="text-center">' . '<p class="mt-4">' . $wiersz2['ile'] . '</p>' . '</td>';
							
							if ($wiersz2['ile'] > 0)
							{
								$sql_ostatni = "SELECT tematy.id_tematu, tematy.nazwa, uzytkownicy.login, uzytkownicy.id_uzytkownika, posty.data_publikacji FROM tematy, kategorie, uzytkownicy, posty WHERE tematy.kategoria_id = kategorie.id_kategorii AND posty.uzytkownik_id = uzytkownicy.id_uzytkownika AND posty.temat_id = tematy.id_tematu AND kategorie.id_kategorii = $id_kat AND tematy.id_tematu IN ($topics) ORDER BY posty.data_publikacji DESC LIMIT 1";
								
								$dane = $conn -> query($sql_ostatni);
								
								$wiersz2 = $dane -> fetch_assoc();
								
								echo "<td>"; 
								echo '<strong> <a href="' . "temat.php?id=" . $wiersz2['id_tematu'] . '">' . $wiersz2['nazwa'] . "</a> </strong>";
								
								echo '<p class="mb-0">autor ostatniego posta: ';
								if ($wiersz2['id_uzytkownika'] == -1)
									echo "konto usunięte </p>";
								else
								{
									echo '<a class="d-inline-block" href="' . "profil.php?id=" . $wiersz2['id_uzytkownika'] . '">' . $wiersz2['login'] . "</a> </p>";
								}
																
								echo '<p class="text-muted mb-0">' . $wiersz2['data_publikacji'] . "</p>";
								echo "</td>";
							}
							
							else
								echo '<td> <p class="text-center mt-4"> Brak aktywności w ostatnim czasie </p> </td>';
							
							echo "</tr>";
							
							$dane -> close();
						}
					}
					else
						"<p> Brak dostępnych kategorii </p>";
					
					$kategorie -> close();
					$conn -> close();
				
				?>
			</tbody>
		</table>
	</div>
	
</body>
</html>
