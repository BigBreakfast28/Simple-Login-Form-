<?php 
session_start();

include "includes/config.php";
include "includes/db.php";

if (!isset($_SESSION['username'])) {

    header("Location:login.php?unauthorized");
    $error['alert'] = 'You do not have access to this page';
}

if (time() > $_SESSION['lastactive'] + $config['timedout']) {
    session_destroy();
    header('Location: login.php?timeout');
} else {
    $_SESSION['lastactive'] = time();
}

$success['alert'] = '';

$error['alert'] = '';
$error['user'] = '';
$error['email'] = '';
$error['pass'] = '';
$error['pass2'] = '';

$input['user'] = '';
$input['email'] = '';
$input['pass'] = '';
$input['pass2'] = '';

if (isset($_POST['submit'])) {
    $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
    $input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
    $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
    $input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);


    if ($_POST['username'] == '' || $_POST['password'] == '' || $_POST['password2'] == '' || $_POST['email'] == '') {
       if ($_POST['username'] == '') { $error['user'] = "REQUIRED!";}
       if ($_POST['email'] == '') { $error['email'] = "REQUIRED!";}
       if ($_POST['password'] == '') {$error['pass'] = "REQUIRED!";}
       if ($_POST['password2'] == '') {$error['pass2'] = "REQUIRED!";}

       $error['alert'] = "Please fill in all required fields!";

     
       include 'views/v_register.php';

       
    } else if ($_POST['password'] != $_POST['password2']) {
            $error['alert'] = "Passwords do not match!";

            include 'views/v_register.php';


    } else if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$input['email'])) {
        //Insert the preg match for emails above and then insert an $error['email'] alert if the enter an invalid email address then return the v_register form.
        $error['email'] = "This is an invalid email address";
        
        include 'views/v_register.php';

           //check to make sure this code works later Lesson 11. Make it to lesson 14 today. Must be finished by the weekend

    } else  {

        //verify email address is not already taken
        $verify = $mysqli->prepare("SELECT email FROM members WHERE email=?");
        $verify->bind_param("s", $input['email']);
        $verify->execute();
        $verify->store_result();

        if ($verify->num_rows != 0) {
            $error['alert'] = "That email address is already in use!";

            $error['email'] = "Please select a different email";

            include 'views/v_register.php';
            exit;
        }

        $verify = $mysqli->prepare("SELECT username FROM members WHERE username=?");
        $verify->bind_param("s", $input['user']);
        $verify->execute();
        $verify->store_result();

        if ($verify->num_rows != 0) {
            $error['alert'] = "Username is already taken. Please select another one.";

            $error['user'] = "Unavaliable";

            include 'views/v_register.php';
            exit;
        }

        
        
        
        $input['hash'] = md5($input['pass'] . $config['salt']);

        if ($stmt = $mysqli->prepare("INSERT INTO members (username, email, password) VALUES (?, ?, ?)")) {
            $stmt->bind_param("sss", $input['user'], $input['email'], $input['hash']);

            $stmt->execute();
            $stmt->close();

            $success['alert'] = "New member has been successfully created!";

            $input['user'] = '';
            $input['email'] = '';
            $input['pass'] = '';
            $input['pass2'] = '';

            include "views/v_register.php";
            

           
        } else {
            echo "Could not prepare SQLi statement.";

        }
    }


} else {
    include 'views/v_register.php';
}

$mysqli->close();