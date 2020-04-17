var nazwa;
var previous;

function htmlEntities(str) 
{
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

function edytujTemat (nr)
{
	nazwa = topicName.innerHTML;
	previous = nazwa;
	topic.innerHTML = '<input type="text" class="form-control mt-4 mb-2" name="tem" id="tem" value="' + nazwa + '">';
	
	przycisk.innerHTML = '<button type="button" class="btn btn-success btn-sm mr-2 mb-4 text-center" onclick="zatwierdz(' + nr + ')"> Zatwierdź </button> <button type="button" class="btn btn-danger btn-sm mr-2 mb-4 text-center" onclick="odrzuc(' + nr + ')"> Odrzuć </button>';
}


function zatwierdz (nr)
{
	var newValue = tem.value;
	
	if (newValue.trim() == "")
	{
		alert ("Tytuł nie może być pusty!");
	}
	
	else if (newValue != previous)
	{
		/*topic.innerHTML = '<h3 id="topicName" class="mt-4"></h3>';
		topicName.innerHTML = newValue;
		
		przycisk.innerHTML = '<button class="btn btn-primary btn-sm mr-2 text-center" onclick="edytujTemat(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button>';*/
		
		$.ajax
		({
			url: "editTopic.php",
			method: "POST",
			data: 
			{
				id: nr,
				nazwa: newValue
			},
			success: function (result)
			{
				var returnedValue = result;
				
				if (returnedValue == "Tytuł nie może być pusty!")
					alert (returnedValue);
				else
				{
					topic.innerHTML = '<h3 id="topicName" class="mt-4"></h3>';
					topicName.innerHTML = returnedValue;
					
					przycisk.innerHTML = '<button class="btn btn-primary btn-sm mr-2 mb-4 text-center" onclick="edytujTemat(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button> <button class="btn btn-primary btn-sm mr-2 mb-4 ml-1 text-center" onclick="usunTemat(' + nr + ')"><i class="icon-delete"> </i>Usuń</button>';
				}
			}
		});
	}
	else
		odrzuc(nr);
}

function odrzuc (nr)
{
	topic.innerHTML = '<h3 id="topicName" class="mt-4"></h3>';
	topicName.innerHTML = nazwa;
	
	przycisk.innerHTML = '<button class="btn btn-primary btn-sm mr-2 mb-4 text-center" onclick="edytujTemat(' + nr + ')"><i class="icon-pencil"> </i>Edytuj</button> <button class="btn btn-primary btn-sm mr-2 mb-4 ml-1 text-center" onclick="usunTemat(' + nr + ')"><i class="icon-delete"> </i>Usuń</button>';
}

function usunPost (nr)
{
	var result = confirm ("Czy na pewno chcesz usunąć post?");
	
	if (result)
	{
		var post = document.getElementById(nr);
		post.style.display = "none";
		
		$.ajax
		({
			url: "deletePost.php",
			method: "POST",
			data: 
			{
				id: nr
			}
		});
		
		
	}
}

function usunTemat (idTematu)
{
	var result = confirm ("Czy na pewno chcesz usunąć temat?");
	
	if (result)
	{
		window.location.href = "usunTemat.php?id=" + idTematu;
	}
	
}