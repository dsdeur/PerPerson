<?php
    // Make loggin not required
    $loginRequired = false;
    require_once("../inc/config.php");
    require_once("../inc/passwordhashing.php");

    // Check if allready loggedin
    if($loggedin && $is_admin) {
        header("Location: index.php");
    } else if($loggedin) {
        header("Location: userconfig.php");
    }

    // Check if the attempts session var isset else set it to 0
    if(!isset($_SESSION["attempts"])) {
        $_SESSION["attempts"] = 0;
    }

    // Get the attemps
    $attempts = $_SESSION["attempts"];
            
    // If a wait time isset get the time
    if(isset($_SESSION["waittime"])) {
        $wait = time()-$_SESSION["waittime"];

        // If the wait is done, reset the session vars
        if($wait > 30) {
            $_SESSION["attempts"] = 0;
            $attempts = 0;
            unset($_SESSION["waittime"]);
            unset($wait);

            session_destroy();
        }
    }


    // Check if email and password id posted, also not to many attempts or a wait
    if(isset($_POST["email"]) && isset($_POST["password"]) && $attempts <= 5 && !isset($_SESSION["waittime"])) {
        // Test the email and password
        $email = test_input($_POST["email"]);
        $password = test_input($_POST["password"]);

        // Check the login, get the user data
        $user = $db->checkLogin($email);

        // Verify the password
        if(password_verify($password , $user["password"])){
            // Set the session vars
            $_SESSION["email"] = $email;
            $_SESSION["userid"] = $user['id'];
            $_SESSION["is_admin"] = $user["admin"];

            // Reset the attempts
            unset($_SESSION["attempts"]);

            // Header to user of admin page
            if($user["admin"]) {
               header("Location: ../". ADMIN_PATH ."/index.php");  
               exit(); 
            } else {
               header("Location: ../". ADMIN_PATH ."/userconfig.php"); 
               exit();  
            } 
        }

        // If attempts is 5 set the wait time
        if($attempts == 5) {
            $_SESSION["waittime"] = time();

            header("Location: ../". ADMIN_PATH ."/login.php?wait=". 30 ."&failed=" . $attempts); 
            exit();  
        }    
    // If the waittime is set return to the login with the time
    } else if(isset($_SESSION["waittime"])) {
        $wait = (30 - $wait);

        header("Location: ../". ADMIN_PATH ."/login.php?failed=" . $attempts . "&wait=" . $wait); 
        exit();
    }
    
    // Add a failed login attempt
    if($attempts < 5){
        $_SESSION["attempts"]++;
    }

    // Header back with leftover attempts
    $attempts = 5 - $attempts;
    header("Location: ../" . ADMIN_PATH . "/login.php?failed=$attempts");  
    exit();
?>