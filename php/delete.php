<?php 
	// Set admin required
	$adminRequired = true;
	require_once("../inc/config.php");

	// Test input and delete from db
	if(isset($_POST["idname"]) && isset($_POST["id"]) && isset($_POST["table"])) {
		$idname = test_input($_POST["idname"]);
		$id = test_input($_POST["id"]);
		$table = test_input($_POST["table"]);

		$db->delete($table, "$idname = $id");
	}
?>