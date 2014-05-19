<?php
	// Make only available for admins
	$adminRequired = true;
	require_once("../inc/config.php");

	// All pages, for navigation
	$pages = ["dash", "countries", "datasets", "data", "users", "apikeys"];

	// Check if the page get is provided
	if(isset($_GET["page"])) {
		// Get the page and test it
		$page = test_input($_GET["page"]);

		// Search if page is in array
		if (false !== $key = array_search($page, $pages)) {
		    // Set the correct page key
		    $pageKey = $key;
		} else {
			// if not right display dash
			$pageKey = 0;
		}
	} else {
		// if not provided display dash
		$pageKey = 0;
	}
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Admin | Per Person</title>

		<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
  		
		<meta charset="UTF-8">
  		<meta name="viewport" content="width=device-width">
		<meta name="description" content="" />  
		<meta name="keywords" content="" /> 

		<link type="text/css" rel="stylesheet" href="css/style.css" />
		
		<script type="text/javascript" src="scripts/jquery-1.10.2.min.js"></script>
		<script type="text/javascript" src="scripts/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="scripts/jquery.easing.1.3.js" type="text/javascript"></script>
		<script src="scripts/phpbridge.js" type="text/javascript"></script>
	</head>

	<body>
		<header>
			<div id="title">
				<h1>Per Person Admin</h1>
				<a href="../public/">Visit site >></a>
			</div>

			<div id="profile">
				<p>Logged in as: <?php echo $email ?> | 
				<a href="../php/logout.php">Logout</a> | 
				<a href="userconfig.php">Manage personal api keys</a>
				</p>
			</div>
		</header>

		<aside id="menu">
			<nav>
				<ul>
					<li><a href="?page=dash">Dash</a></li>
					<li><a href="?page=countries">Countries</a></li>
					<li><a href="?page=datasets">Datasets</a></li>
					<li><a href="?page=users">Users</a></li>
				</ul>
			</nav>
		</aside><!--/LOGINCONTAINER-->

		<?php 
		// Include the page requested
		include("parts/". $pages[$pageKey] .".php"); ?> 
	</body>
</html>