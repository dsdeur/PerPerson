<?php
	// Get all datasets from db
	$datafromdb = $db->getFromDB("dataset");
?>
	<script>
	// Show and hide contact form
	$(document).ready(function(){
		$("#datasetform").hide(0);

		$("#showForm").click(function(event){
			event.preventDefault();

			$("#datasetdata").fadeOut(200, function(){
				$("#datasetform").fadeIn(200);
			});
		});

		$("#cancel").click(function(event){
			event.preventDefault();

			$("#datasetform").fadeOut(200, function(){
				$("#datasetdata").fadeIn(200);
			});
		});
	});
	</script>

	<div id='jqueryData'>
		<p id="idname">id</p>
		<p id="tablename">dataset</p>
	</div>

	<section id="data" class="dataSec">

		<div id="datasetform">
			<h1>Add dataset</h1>
			<form action="../php/insert_dataset.php" method="post" enctype="multipart/form-data">
				<label for="csv">Dataset CSV:</label>
				<input type="file" id="csv" name="csv" />
				
				<label for="name">Name:</label>
				<input id="name" type="text" name="name"><br>
				
				<label for="unit">Unit:</label>
				<input id="unit" type="text" name="unit"><br>
				
				<input type="submit" class="big" name="submit" value="Upload" />
			</form>
			<button class="bttn red" id="cancel">Cancel</button>
		</div>

		<div id="datasetdata">
			<h1>Datasets</h1>
			<button id="showForm" class="bttn big">Upload dataset</button>
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Unit</th>
					<!-- <th></th> -->
					<th></th>
				</tr>
			<?php 
			// Display datasets
			foreach($datafromdb as $data): ?>
				<tr>
					<td><?php echo $data["id"] ?></td>
					<td><?php echo $data["name"] ?></td>
					<td><?php echo $data["unit"] ?></td>
					<!-- <td><button class="bttn edit">Edit</button></td> -->
					<td><button class="bttn red delete">Delete</button></td>
				</tr>
			<?php endforeach; ?>
			</table>
		</div>
	</section>