<?php
	require_once("../inc/config.php");

	// Check if the name is provided
	if(isset($_POST["url"])) {
		// Test the input
		$url = test_input($_POST["url"]);

		// Generate a key
		$key = substr(md5(rand()),0,32);
		// Add the key to the db
		$db->newKey($userid, $url, $key);
	}

	// Header back to the userconfig
	header("Location: ../" . ADMIN_PATH . "/userconfig.php");
?>