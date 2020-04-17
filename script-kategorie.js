var tabGlobal;
var editTable;
var names = ["kategoria", "dostep"];

function edit(nr)
{
	tabGlobal = new Array();
	editTable = new Array();
	
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	for (let i = 0; i < 2; i++)
	{
		wiersz = tab[i].innerHTML;
		tabGlobal[i] = wiersz;
	}
	
	/*for (let i = 0; i < tabGlobal.length; i++)
		console.log (tabGlobal[i]);*/
	
	for (let i = 0; i < 2; i++)
	{	
		if (i == 1)
		{
			if (tab[i].innerHTML == "wszyscy")
				index = "wszyscy";
			else if (tab[i].innerHTML == "administratorzy")
				index = "administratorzy";
			else if (tab[i].innerHTML == "nauczyciele")
				index = "nauczyciele";
			else if (tab[i].innerHTML == "uczniowie")
				index = "uczniowie";
			
			tab[i].innerHTML = '<select class="form-control" id="lista" onchange="editInput()"> <option value="wszyscy">wszyscy</option> <option value="administratorzy">administratorzy</option> <option value="nauczyciele">nauczyciele</option> <option value="uczniowie">uczniowie</option> </select>';
			
			if (index == "wszyscy")
				lista.selectedIndex = "0";
			else if (index == "administratorzy")
				lista.selectedIndex = "1";
			else if (index == "nauczyciele")
				lista.selectedIndex = "2";
			else if (index == "uczniowie")
				lista.selectedIndex = "3";
		}
		else
		{
			tab[i].innerHTML = '<input type="text" class="form-control" name="' + names[i] + '" id="' + names[i] + '"value="' + tab[i].innerHTML + '" onchange="editInput()">';	
		}
	}
	
	tab[2].innerHTML = '<button type="button" class="btn btn-success btn-sm ml-2 mr-2 my-1 text-center" onclick="zatwierdz(' + nr + ')"> Zatwierdź </button> <button type="button" class="btn btn-danger btn-sm mr-2 my-1 text-center" onclick="odrzuc(' + nr + ')"> Odrzuć </button>';

}

function editInput()
{
	//editTable = new Array();
	editTable[0] = kategoria.value;
	editTable[1] = lista.value;
}

function odrzuc(nr)
{
	var tab = document.getElementById(nr).getElementsByTagName("td");
	
	for (let i = 0; i < 2; i++)
		tab[i].innerHTML = tabGlobal[i];
	
	tab[2].innerHTML = '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="edit(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>';
	
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
			url: "updateKategorie.php",
			method: "POST",
			data: 
			{
				id: nr,
				nazwa: editTable[0],
				dostep: editTable[1]
			}
		});
	}
	
	else
		editInput();
					
					
	for (let i = 0; i < 2; i++)
		tab[i].innerHTML = editTable[i];
				
	tab[2].innerHTML = '<button type="button" class="btn btn-primary btn-sm ml-2 mr-2 my-1 text-center" onclick="edit(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>';
	
}

