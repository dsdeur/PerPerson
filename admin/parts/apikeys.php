<?php
	
	// Check if the get are set else header to index
	if(isset($_GET["userid"]) && isset($_GET["mail"])) {
		$userid = test_input($_GET['userid']);
		$mail = test_input($_GET['mail']);
	} else {
		header("Location: index.php");
	}

	// Get the keys from the given user
	$keys = $db->getKeys($userid);
?>

	<section id="data" class="dataSec">
		<h1>API Keys for user: <?php echo $mail; ?></h1>

		<?php 
		// Loop through all keys
		foreach($keys as $key): ?>
		<div class="apikey">
			<div class="text">
				<h2><?php echo $key["url"]; ?>:</h2>
				<p><?php echo $key["key"]; ?></p>
			</div>

			<form action="../php/deletekey.php?userid=6&mail=admin@admin.com" method="post">
				<button type="submit" class="bttn red" name="keyid" value="<?php echo $key["keyid"] ?>">Delete key</button>
			</form>
		</div>
		<?php endforeach; ?>
	</section><!--/USERCONIG-->