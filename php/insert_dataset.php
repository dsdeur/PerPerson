<?php 
	require_once("../inc/config.php");

	// Check if the file is upload and 
	if(isset($_FILES['csv']) && $_FILES['csv']['error'] == 0 && isset($_POST["name"]) && isset($_POST["unit"])){
	    // Get the csv info
	    $name = $_FILES['csv']['name'];
	    $type = $_FILES['csv']['type'];
	    $tmpName = $_FILES['csv']['tmp_name'];
	    $ext = pathinfo($name, PATHINFO_EXTENSION);

	    // Check the extension
	    if($ext == "csv"){
	    	// Test the input
	    	$datasetname = test_input($_POST["name"]);
	   	 	$datasetunit = test_input($_POST["unit"]);
	    		
	    	// Insert in the database
			insertDataset($tmpName, $datasetname, $datasetunit);
	    }
	} else {
		//echo "erororor";
	}

	// Header back to the admin
	header("Location: ../admin/index.php?page=datasets")
?>
