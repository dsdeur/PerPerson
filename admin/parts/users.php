<?php
	// Get all users from db
	$users = $db->getFromDB("user");
?>

	<div id='jqueryData'>
		<p id="idname">userid</p>
		<p id="tablename">user</p>
	</div>

	<section id="users" class="dataSec">
		<h1>Users</h1>
		<table>
			<tr>
				<th>ID</th>
				<th>Email</th>
				<th>Admin</th>
				<!-- <th></th> -->
				<th></th>
			</tr>
		<?php 
		// Display all users
		foreach($users as $user): ?>
			<tr>
				<td><?php echo $user["userid"] ?></td>
				<td><a href="?page=apikeys&userid=<?php echo $user["userid"] ?>&mail=<?php echo $user["email"] ?>">
					<?php echo $user["email"] ?>
				</a></td>
				<td><?php echo $user["admin"] ?></td>
				<!-- <td><button class="bttn edit">Edit</button></td> -->
				<td><button class="bttn red delete">Delete</button></td>
			</tr>
		<?php endforeach; ?>
	</section>