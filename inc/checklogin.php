<?php 
   /*
	*	This is include in the config it runs on every page
	*	It check if a user is logged in, and sets the email, userid, and is_admin
	*/

	// Start the session
	session_start();
	// Default not logged in
	$loggedin = false;

	// Check if email and userid are set (check if logged in)
	if(!isset($_SESSION["email"]) && !isset($_SESSION["userid"])) {
		// If loggin is required for this page redirect to login
		if(!isset($loginRequired) || $loginRequired != false){
			header("Location: ../". ADMIN_PATH ."/login.php");	
		}
	} else {
		// User is logged in, set vars
		$loggedin = true;
		$email = $_SESSION["email"];
		$userid = $_SESSION["userid"];
		$is_admin = $_SESSION["is_admin"];

		// Check if admin required else redirect to user page
		if(isset($adminRequired) && $adminRequired != false && !$is_admin){
			header("Location: ../". ADMIN_PATH ."/userconfig.php");	
		} 
	}
?>