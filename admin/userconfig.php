<?php
	require_once("../inc/config.php");

	// Get all user keys from db
	$keys = $db->getKeys($userid);
?>

<!DOCTYPE html>

<html>
	<head>
		<title>User | Per Person</title>

		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  		
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width">
		<meta name="description" content="" />  
		<meta name="keywords" content="" /> 

		<link type="text/css" rel="stylesheet" href="css/style.css" />
		
		<script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="scripts/jquery.easing.1.3.js" type="text/javascript"></script>
	
		<script>
			// Show hide new key form
			$(document).ready(function(){
				$("#newKey").hide(0);

				$("#newKeyBttn").click(function(event){
					event.preventDefault();

					$("#userconfig").fadeOut(200, function(){
						$("#newKey").fadeIn(200);
					});
				});

				$("#cancel").click(function(event){
					event.preventDefault();

					$("#newKey").fadeOut(200, function(){
						$("#userconfig").fadeIn(200);
					});
				});
			});
		</script>
	</head>

	<body>
		<header>
			<div id="title">
				<h1>Per Person API</h1>
				<a href="../public/">Visit site >></a>
			</div>
			
			<div id="profile">
				<p>Logged in as: <?php echo $email ?> | 
				<a href="../php/logout.php">Logout</a><p>
				<?php if($is_admin){
					echo " | <a href='index.php'>Back to admindash</a>";
				}?>
			</div>
		</header>

		<div id="userconfigContainer">
			<section id="userconfig">
				<h1>API Keys</h1>

				<?php 
				// Loop through keys
				foreach($keys as $key): ?>
				<div class="apikey">
					<div class="text">
						<h2><?php echo $key["url"]; ?>:</h2>
						<p><?php echo $key["key"]; ?></p>
					</div>

					<form action="../php/deletekey.php" method="post">
						<button type="submit" class="bttn red" name="keyid" value="<?php echo $key["keyid"] ?>">Delete key</button>
					</form>
				</div>
				<?php endforeach; ?>

				<button id="newKeyBttn" class="bttn big">New Key</button>
			</section><!--/USERCONIG-->

			<section id="newKey">
				<form action="../php/newkey.php" id="login" method="post">
					<label for="url">Name:</label>
					<input id="url" type="text" name="url"><br>
					
					<input type="submit" class="big" value="Generate key">
				</form>
				<button id="cancel" class="bttn red">Cancel</button>
			</section><!--/newKey-->
		</div>
	</body>
</html>