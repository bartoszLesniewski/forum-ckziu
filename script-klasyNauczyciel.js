function usun(nr, userID)
{
	var result = confirm ("Czy na pewno chcesz usunąć klasę?");
	
	if (result)
	{
		var row = document.getElementById(nr);
		row.parentNode.removeChild(row);
		
		$.ajax
		({
			url: "deleteKlasyNauczyciel.php",
			method: "POST",
			data: 
			{
				id_klasy: nr,
				id_nauczyciela: userID
			}
		});
	}
}