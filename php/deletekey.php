<?php
	require_once("../inc/config.php");

	// Get the keyid and delete it from the db
	if(isset($_POST["keyid"])) {
		$keyid = test_input($_POST["keyid"]);

		$db->deleteKey($userid, $keyid, $is_admin);
	}

	// Header to the right page
	if($is_admin && isset($_GET["userid"])) {
		$userid = $_GET["userid"];
		$email = $_GET["mail"];
		header("Location: ../" . ADMIN_PATH . "/index.php?page=apikeys&userid=$userid&mail=$email");	
	} else {
		header("Location: ../" . ADMIN_PATH . "/userconfig.php");
	}
?>