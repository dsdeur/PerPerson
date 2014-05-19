<?php 

	if(true) //true == debug
	{
	    ini_set('error_reporting', E_ALL);
	    error_reporting(-1);
	    ini_set('display_errors','1');
	}
	else
	{
	    ini_set('error_reporting', E_ALL);
	    error_reporting(E_ALL);
	    ini_set('display_errors','0');
	}

	// Database config
	$config = array(  
		"db" => array(  
			"dbname" => "perperson",  
			"username" => "root",  
			"password" => "",  
			"host" => "localhost"   
		)
	);  

	// Constants
	defined("INC_PATH")  
	    or define("INC_PATH", 'inc');  

	defined("ADMIN_PATH")  
	    or define("ADMIN_PATH", 'admin'); 
    defined("COUNTER_PATH")  
        or define("COUNTER_PATH", 'php/teller.txt'); 

   /*
	* Include common files
    */

    // Check login
	require_once("checklogin.php");
	// include functions
	require_once("functions.php");
	// include the database class
	require_once("database.php");

	// Create the database object and connect
	$db = new Database();
	$db->connect($config);
?>