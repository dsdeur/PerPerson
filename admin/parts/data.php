<?php
	// Get all data from db
	$datafromdb = $db->getFromDB("data");
?>
	
	<section id="data" class="dataSec">
		<h1>Countries</h1>
		<table>
			<tr>
				<th>Country Code</th>
				<th>Dataset id</th>
				<th>Data</th>
				<th></th>
<!-- 				<th></th>
 -->			</tr>
		<?php foreach($datafromdb as $data): ?>
			<tr>
				<td><?php echo $data["country_code"] ?></td>
				<td><?php echo $data["dataset_id"] ?></td>
				<td><?php echo $data["data"] ?></td>
<!-- 				<td><button class="bttn edit">Edit</button></td>
 -->				<td><button class="bttn red delete">Delete</button></td>
			</tr>
		<?php endforeach; ?>
	</table>
	</section>