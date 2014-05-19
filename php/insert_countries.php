<?php 
	// Require admin
	$adminRequired = true;
	require_once("../inc/config.php");

	// Check if the files exist and dont have errors
	if(isset($_FILES['alpha2']) && isset($_FILES['alpha3']) && $_FILES['alpha2']['error'] == 0 && $_FILES['alpha3']['error'] == 0){
		// Get the first file
	    $name = $_FILES['alpha2']['name'];
	    $type = $_FILES['alpha2']['type'];
	    $tmpName = $_FILES['alpha2']['tmp_name'];
	    $ext = pathinfo($name, PATHINFO_EXTENSION);

		// Get the second file
	    $name3 = $_FILES['alpha3']['name'];
	    $type3 = $_FILES['alpha3']['type'];
	    $tmpName3 = $_FILES['alpha3']['tmp_name'];
	    $ext3 = pathinfo($name3, PATHINFO_EXTENSION);

	    // Check the extensions
	    if($ext == "json" && $ext3 == "json") {
	    	// Decode the json
			$jsonAlpha2 = json_decode(file_get_contents($tmpName), true);
			$jsonAlpha3 = json_decode(file_get_contents($tmpName3), true);

			// Add the countries to the db
			updateCountries($jsonAlpha2, $jsonAlpha3);
		}
	}

	// Header back to the admin
	header("Location: ../admin/index.php?page=countries")
?>