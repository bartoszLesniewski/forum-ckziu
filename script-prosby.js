function checkAll(all)
{
	var checkboxes = document.getElementsByName("check[]");
	
	for (let i = 0; i < checkboxes.length; i++)
		checkboxes[i].checked = all.checked;
	
}
