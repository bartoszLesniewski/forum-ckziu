$(document).ready(function() {
	var session;
	
	$.ajax
	({
		url: "getsession.php",
		method: "GET",
		data: 
		{
			typ: 'typ_konta'
		},
		success: function (data) {
			session = data;
			//console.log(session);
			if (session == "administrator" || session == "nauczyciel")
			{
				CKEDITOR.replace( 'tr',
				{
					//extraPlugins: 'attach',
					//toolbar: this.customToolbar,
					filebrowserBrowseUrl: 'ckfinder/ckfinder.html',
					filebrowserImageBrowseUrl: 'ckfinder/ckfinder.html?type=Images',
					filebrowserUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
					filebrowserImageUploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Images'
				});
			}
			else
			{
				CKEDITOR.replace('tr');
			}
				
		}
	});
	
});
