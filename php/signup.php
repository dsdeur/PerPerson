<?php
	// Set the login to not required
	$loginRequired = false;
	require_once("../inc/config.php");
	require_once("../inc/passwordhashing.php");

	// Check if logged in and header if so.
	if($loggedin && $is_admin) {
		header("Location: index.php");
	} else if($loggedin) {
		header("Location: userconfig.php");
	}

	// Check if email and password are provided
	if(isset($_POST["email"]) && isset($_POST["password"])) {
	    // Test the email
	    $email = test_input($_POST["email"]);

	    // Check if email is valid
	    if(validate_email($email)) {
	    	// Test the password
		    $password = test_input($_POST["password"]);

		    // Encrypt the password
		    $password = password_hash($password, PASSWORD_BCRYPT);
		    // Check if the mail adress is valid
		    $emailUnique = $db->checkEmailExists($email);

		    if($emailUnique) {
		    	// Add the user to the database
			    $userid = $db->addUser($email, $password);
			    
			    // Set the SESSION vars
			    if($userid) {
			    	$_SESSION["email"] = $email;
			    	$_SESSION["userid"] = $userid;
			    	$_SESSION["is_admin"] = false;

			    	// Header to the userconfig
		    	    header("Location: ../". ADMIN_PATH ."/userconfig.php");  
			    }
		    }
		}
	}
	
	// if failed send back to the login/signup
	header("Location: ../". ADMIN_PATH ."/login.php?signupfailed=true");  

?>