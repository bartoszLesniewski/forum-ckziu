$(document).ready(function() {
	//$('#prosby').load("generateTable.php");
	
    $('#prosby').DataTable( {
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

function tylkoMoje()
{
	if (moje.checked)
		$('#prosby').load("generateTable.php?mode=mine");
	else
		$('#prosby').load("generateTable.php?mode=all");
}

