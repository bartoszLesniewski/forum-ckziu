$(document).ready(function() {
    $('#kl').DataTable( {
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
var names = ["klasa"];

function edit(nr)
{
	tabGlobal = new Array();
	editTable = new Array();
	
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	for (let i = 0; i < 1; i++)
	{
		wiersz = tab[i].innerHTML;
		tabGlobal[i] = wiersz;
	}
	
	/*for (let i = 0; i < tabGlobal.length; i++)
		console.log (tabGlobal[i]);*/
	
	for (let i = 0; i < 1; i++)
	{
		tab[i].innerHTML = '<input type="text" class="form-control" name="' + names[i] + '" id="' + names[i] + '"value="' + tab[i].innerHTML + '" onchange="editInput()">';	
	}
	
	tab[1].innerHTML = '<button type="button" class="btn btn-success btn-sm ml-2 mr-2 my-1 text-center" onclick="zatwierdz(' + nr + ')"> Zatwierdź </button> <button type="button" class="btn btn-danger btn-sm mr-2 my-1 text-center" onclick="odrzuc(' + nr + ')"> Odrzuć </button>';

}

function editInput()
{
	//editTable = new Array();
	editTable[0] = klasa.value;
}

function odrzuc(nr)
{
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	for (let i = 0; i < 1; i++)
		tab[i].innerHTML = tabGlobal[i];
	
	tab[1].innerHTML = '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="edit(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>' + '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="usun(' + nr + ')"><i class="icon-delete"> </i>Usuń</button>';
	
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
			url: "updateKlasy.php",
			method: "POST",
			data: 
			{
				id: nr,
				nazwa: editTable[0]
			}
		});
	}
	
	else
		editInput();
					
					
	for (let i = 0; i < 1; i++)
		tab[i].innerHTML = editTable[i];
				
	tab[1].innerHTML = '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="edit(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>' + '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="usun(' + nr + ')"><i class="icon-delete"> </i>Usuń</button>';
	
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
			url: "deleteKlasy.php",
			method: "POST",
			data: 
			{
				id: nr
			}
		});
	}
}
