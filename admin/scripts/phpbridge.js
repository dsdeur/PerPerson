$(document).ready(function(){
	// Function to delete an entry from the database
	$(".delete").click(function(){

		// Get the table data and id from row to delete
		var table = $("#tablename").text();
		var idname = $("#idname").text();
		var id = $(this).parent().parent().find("td").eq(0).text();

		var self = $(this);

		// Post the data to the delete script
		$.post('../php/delete.php', { table: table, idname: idname, id: parseInt(id, 10)}, function(result) {
			// If successful remove the row
			self.parent().parent().remove();
		});
	});

});