function kategorie()
{
	var x = lista.value;
	komunikat.innerHTML = "";
	listaN.style.display = "none";
	
	$.ajax
	({
		url: "dlaKogo.php",
		method: "POST",
		data: 
		{
			id: x
		},
		success: function (result)
		{
			var returnedValue = result;	
			var session = se;
			var pom = p;
			var add, sel;
			
			//console.log (returnedValue);
			//console.log(session);
			//console.log(pom);
			
			if (session == "administrator")
				add = '<option value="administratorzy"> Administratorzy </option>';
			else
				add = "";
			
			if (returnedValue == "wszyscy")
			{
				if (pom != "null")
					sel = "selected";
				else 
					sel = "";
				
				dost.innerHTML = '<div class="form-group">' + 
				'<label for="dostepnosc"> Dla kogo ma być dostępny temat? </label>' + 
				'<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">' + 
				'<option value="wszyscy"> Wszyscy </option>' + 
				'<option value="nauczyciele"> Nauczyciele </option>' + 
				'<option value="uczniowie"> Uczniowie </option>' + 
				'<option value="klasa" ' + sel + '> Wybrana klasa / klasy </option>' + 
				add + 
				'</select>' + 
				'</div>';
			}
			
			else if (returnedValue == "nauczyciele")
			{
				
				dost.innerHTML = '<div class="form-group">' + 
				'<label for="dostepnosc"> Dla kogo ma być dostępny temat? </label>' + 
				'<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">' + 
				'<option value="nauczyciele"> Nauczyciele </option>' + 
				add +
				'</select>' + 
				'</div>';
				
			}
			
			else if (returnedValue == "uczniowie")
			{
				if (pom != "null")
					sel = "selected";
				else 
					sel = "";
				
				dost.innerHTML = '<div class="form-group">' + 
				'<label for="dostepnosc"> Dla kogo ma być dostępny temat? </label>' + 
				'<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">' + 
				'<option value="nauczyciele"> Nauczyciele </option>' + 
				'<option value="uczniowie"> Uczniowie </option>' + 
				'<option value="klasa" ' + sel + '> Wybrana klasa / klasy </option>' + 
				add +
				'</select>' + 
				'</div>';
			}
			
			else if (returnedValue == "administratorzy")
			{
				if (pom != "null")
					sel = "selected";
				else 
					sel = "";
				
				dost.innerHTML = '<div class="form-group">' + 
				'<label for="dostepnosc"> Dla kogo ma być dostępny temat? </label>' + 
				'<select class="form-control" id="dostepnosc" name="dostepnosc" onchange="showClasses()">' + 
				'<option value="administratorzy"> Administratorzy </option>' + 
				'</select>' + 
				'</div>';
			}
			
			if (sel != "")
				showClasses();
			
		}
		
	});
	
}


function showClasses()
{
	if (dostepnosc.value == "klasa")
	{
		komunikat.innerHTML = "Wybierz klasy, które będą miały dostęp do tematu";
		listaN.style.display = "block";
	}
	else
	{
		komunikat.innerHTML = "";
		listaN.style.display = "none";
	}
	
}
