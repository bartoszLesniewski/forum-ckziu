function showList()
{
	listaU.style.display = "none";
	komunikat.innerHTML = "Wybierz klasy, w których uczysz";
	listaN.style.display = "block";
}

function chooseClass()
{
	listaN.style.display = "none";
	komunikat.innerHTML = "Wybierz swoją klasę";
	listaU.style.display = "block";
}