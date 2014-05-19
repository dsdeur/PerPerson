<?php 
	require_once("../inc/config.php");

	// Delete de sessie
	if(isset($_SESSION["email"])) {
		unset($_SESSION["email"]);
		unset($_SESSION["userid"]);
		unset($_SESSION["is_admin"]);

		session_destroy();
	}

	// Header back to the login
	header("Location: ../" . ADMIN_PATH . "/login.php");
?>
