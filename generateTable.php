<?php
	session_start();
	
	require "connect.php";
	$userId = $_SESSION['id'];
	
	if (isset($_GET['mode']) && $_GET['mode'] == "mine")
	{
		//$moje = "SELECT klasa_id FROM nauczyciele_i_klasy WHERE nauczyciel_id = $userId";
		$sql = "SELECT id_uzytkownika, login, imie, nazwisko, klasy.nazwa FROM uzytkownicy, uczniowie_i_klasy, klasy WHERE czy_aktywne = 0 AND typ_konta='uczen' AND uzytkownicy.id_uzytkownika = uczniowie_i_klasy.uczen_id AND uczniowie_i_klasy.klasa_id = klasy.id_klasy AND klasy.id_klasy IN (SELECT klasa_id FROM nauczyciele_i_klasy WHERE nauczyciel_id = $userId)";
	}
	else
		$sql = "SELECT id_uzytkownika, login, imie, nazwisko, klasy.nazwa FROM uzytkownicy, uczniowie_i_klasy, klasy WHERE czy_aktywne = 0 AND typ_konta='uczen' AND uzytkownicy.id_uzytkownika = uczniowie_i_klasy.uczen_id AND uczniowie_i_klasy.klasa_id = klasy.id_klasy";
	
	$wynik = $conn -> query ($sql);
	
	if ($wynik -> num_rows > 0)
	{
echo<<<END
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
		echo "<br>" . "<p>Brak próśb o aktywację </p>";
	
	$wynik -> close();
	$conn -> close();
?>

