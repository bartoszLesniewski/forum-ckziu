$(document).ready(function() {
    $('#users').DataTable( {
        "language": {
            "lengthMenu": "Wyświetlaj _MENU_ rekordów na stronę",
            "zeroRecords": "Nie znaleziono żadnych rekordów",
            "info": "Strona _PAGE_ z _PAGES_",
            "infoEmpty": "Brak rekordów",
            "infoFiltered": "(filtered from _MAX_ total records)",
			"search": "Szukaj:",
			"paginate": {
			"next": "Następna",
			"previous": "Poprzednia"
			}
        }
    } );
} );


var tabGlobal;
var editTable;
var names = ["login", "imie", "nazwisko", "email", "typ_konta"];

function edit(nr)
{
	tabGlobal = new Array();
	editTable = new Array();
	
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	for (let i = 0; i < 5; i++)
	{
		wiersz = tab[i].innerHTML;
		tabGlobal[i] = wiersz;
	}
	
	/*for (let i = 0; i < tabGlobal.length; i++)
		console.log (tabGlobal[i]);*/
	
	for (let i = 0; i < 5; i++)
	{
		if (i == 4)
		{
			if (tab[i].innerHTML == "administrator")
				index = "administrator";
			else if (tab[i].innerHTML == "nauczyciel")
				index = "nauczyciel";
			else if (tab[i].innerHTML == "uczen")
				index = "uczen";
			
			tab[i].innerHTML = '<select class="form-control" id="lista" onchange="editInput()"> <option value="administrator">administrator</option> <option value="nauczyciel">nauczyciel</option> <option value="uczen">uczeń</option> </select>';
			
			if (index == "administrator")
				lista.selectedIndex = "0";
			else if (index == "nauczyciel")
				lista.selectedIndex = "1";
			else if (index == "uczen")
				lista.selectedIndex = "2";
		}
		else
		{
			tab[i].innerHTML = '<input type="text" class="form-control" name="' + names[i] + '" id="' + names[i] + '"value="' + tab[i].innerHTML + '" onchange="editInput()">';
		}
		
	}
	
	tab[5].innerHTML = '<button type="button" class="btn btn-success btn-sm ml-2 mr-2 my-1 text-center" onclick="zatwierdz(' + nr + ')"> Zatwierdź </button> <button type="button" class="btn btn-danger btn-sm mr-2 my-1 text-center" onclick="odrzuc(' + nr + ')"> Odrzuć </button>';

}

function editInput()
{
	//editTable = new Array();
	editTable[0] = login.value;
	editTable[1] = imie.value;
	editTable[2] = nazwisko.value;
	editTable[3] = email.value;
	editTable[4] = lista.value;
}

function odrzuc(nr)
{
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	for (let i = 0; i < 5; i++)
		tab[i].innerHTML = tabGlobal[i];
	
	tab[5].innerHTML = '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="edit(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>' + '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="usun(' + nr + ')"><i class="icon-delete"> </i>Usuń</button>';
	
}

function zatwierdz(nr)
{
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	if (editTable.length > 0)
	{
		/*for (let i = 0; i < editTable.length; i++)
			console.log(editTable[i]);*/
		
		$.ajax
		({
			url: "update.php",
			method: "POST",
			data: 
			{
				id: nr,
				login: editTable[0],
				imie: editTable[1],
				nazwisko: editTable[2],
				email: editTable[3],
				typ_konta: editTable[4]
			}
		});
	}
	
	else
		editInput();
					
					
	for (let i = 0; i < 5; i++)
		tab[i].innerHTML = editTable[i];
				
	tab[5].innerHTML = '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="edit(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>' + '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="usun(' + nr + ')"><i class="icon-delete"> </i>Usuń</button>';
	
}

function usun(nr)
{
	var result = confirm ("Czy na pewno chcesz usunąć tego użytkownika?");
	
	if (result)
	{
		var row = document.getElementById(nr);
		row.parentNode.removeChild(row);
		
		$.ajax
		({
			url: "delete.php",
			method: "POST",
			data: 
			{
				id: nr
			}
		});
	}
}
