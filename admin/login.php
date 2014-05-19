<?php 
	// Disable login required
    $loginRequired = false;
    require_once("../inc/checklogin.php");
    require_once("../inc/functions.php");


    // Check if allready logged in, redirect to index (id admin) or userconfig if user
    if($loggedin && $is_admin) {
    	header("Location: index.php");
    } else if($loggedin) {
    	header("Location: userconfig.php");
    }
?>

<!DOCTYPE html>

<html>
	<head>
		<title>Login | Per Person</title>

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
			// Show hide signup form
			$(document).ready(function(){
				$("#signup").hide(0);

				$("#signupBttn").click(function(event){
					event.preventDefault();

					$("#login").fadeOut(200, function(){
						$("#signup").fadeIn(200);
					});
				});

				$("#loginBttn").click(function(event){
					event.preventDefault();

					$("#signup").fadeOut(200, function(){
						$("#login").fadeIn(200);
					});
				});
			});
		</script>
	</head>

	<body>
		<section id="loginContainer">

			<?php 
			// Display wait message if waiting time is set
			if(isset($_GET["wait"])) {
				echo "<p>To many attemps, wait " . test_input($_GET["wait"]) . " seconds..</p>";
			} else if(isset($_GET["failed"])) {
				echo "<p>Login failed check email and password, you've got " . test_input($_GET["failed"]) . " attemps left..</p>";
			} ?>

			<?php 
			// Display login faild on faild login
			if(isset($_GET["signupfailed"])) {
				echo "<p>Signup failed, check you email allready in use or not a valid email</p>";
			} ?>
			<div id="login">
				<h1>Login</h1>

				<form action="../php/login.php" id="loginForm" method="post">
					<label for="email">Email:</label>
					<input id="email" type="text" name="email"><br>
					
					<label for="password">Password:</label>
					<input id="password" type="password" name="password"><br />				
					<input type="submit" class="big" value="Login">
				</form>
				<a id="signupBttn" class="bttn red" href="">Signup</a>
			</div>

			<div id="signup">
				<h1>Signup</h1>

				<form action="../php/signup.php" id="signupForm" method="post">
					<label for="emailSign">Email:</label>
					<input id="emailSign" type="text" name="email"><br>
					
					<label for="passwordSign">Password:</label>
					<input id="passwordSign" type="password" name="password"><br />				
					<input class="big" type="submit" value="Signup">
				</form>
				<a id="loginBttn" class="bttn red" href="">Login</a>
			</div>
		</section><!--/LOGINCONTAINER-->
	</body>
</html>