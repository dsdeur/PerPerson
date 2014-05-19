<?php
	// Get all countries from db
	$data = $db->getFromDB("country");
?>
	<div id='jqueryData'>
		<p id="idname">country_code</p>
		<p id="tablename">country</p>
	</div>

	<script>
	// Show and hide upload form
	$(document).ready(function(){
		$("#countryform").hide(0);

		$("#showForm").click(function(event){
			event.preventDefault();

			$("#countrydata").fadeOut(200, function(){
				$("#countryform").fadeIn(200);
			});
		});

		$("#cancel").click(function(event){
			event.preventDefault();

			$("#countryform").fadeOut(200, function(){
				$("#countrydata").fadeIn(200);
			});
		});
	});
	</script>

	<section id="countries" class="dataSec">
		<div id="countryform">
			<h1>Update country data</h1>
			<form action="../php/insert_countries.php" method="post" enctype="multipart/form-data">
				<label for="alpha2">Alpha2 json:</label>
				<input type="file" id="alpha2" name="alpha2" />

				<label for="alpha2">Alpha3 json:</label>
				<input type="file" id="alpha3" name="alpha3" />
		
				<input type="submit" class="big" name="submit" value="Upload" />
			</form>
			<button class="bttn red" id="cancel">Cancel</button>
		</div>

		<div id="countrydata">
			<h1>Countries</h1>

			<button class="bttn big" id="showForm">Update country data</button>
			
			<table>
				<tr>
					<th>Country Code</th>
					<th>Name</th>
					<th>Alpha2</th>
					<th>Alpha3</th>
					<th></th>
					<!--<th></th>-->
				</tr>
			<?php 
			// Loop through countries
			foreach($data as $line): ?>
				<tr>
					<td><?php echo $line["country_code"] ?></td>
					<td><?php echo $line["name"] ?></td>
					<td><?php echo $line["alpha_2"] ?></td>
					<td><?php echo $line["alpha_3"] ?></td>
					<!--<td><button class="bttn edit">Edit</button></td>-->
					<td><button class="bttn red delete">Delete</button></td>
				</tr>
			<?php endforeach; ?>
		</table>
		</div>
	</section>