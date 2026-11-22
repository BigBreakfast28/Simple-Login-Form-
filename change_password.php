<?php 
session_start();

include "includes/config.php";
include "includes/db.php";

if (!isset($_SESSION['username'])) {
    header("Location:login.php?unauthorized");
}

if (time() > $_SESSION['lastactive'] + $config['timedout']) {
    session_destroy();
    header('Location: login.php?timeout');
} else {
    $_SESSION['lastactive'] = time();
}

$success['alert'] = '';

$error['alert'] = '';
$error['current_password'] = '';
$error['pass'] = '';
$error['pass2'] = '';

$input['current_password'] = '';
$input['pass'] = '';
$input['pass2'] = '';

if (isset($_POST['submit'])) {
    if ($_POST['current_password'] == '' || $_POST['password'] == '' || $_POST['password2'] == '') {
       if ($_POST['current_password'] == '') { $error['current_password'] = "REQUIRED!";}
       if ($_POST['password'] == '') {$error['pass'] = "REQUIRED!";}
       if ($_POST['password2'] == '') {$error['pass2'] = "REQUIRED!";}

       $error['alert'] = "Please fill in all required fields!";

       $input['current_password'] = $_POST['current_password'];
       $input['pass'] = $_POST['password'];
       $input['pass2'] = $_POST['password2'];

       include 'views/v_changepassword.php';

       
    } elseif ($_POST['password'] != $_POST['password2']) {
            $error['alert'] = "Passwords do not match!";

            $input['current_password'] = htmlentities($_POST['current_password'], ENT_QUOTES);
            $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
            $input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);

            include 'views/v_changepassword.php';


    } else {
        $input['current_password'] =$_POST['current_password'];
        $input['pass'] = $_POST['password'];
        $input['pass2'] = $_POST['password2'];

        if ($check = $mysqli->prepare("SELECT password FROM members WHERE id=?")) {
            $check->bind_param("s", $_SESSION['id']);
            $check->execute();
            $check->bind_result($current_password);
            $check->fetch();
            $check->close();
        }

        if (md5($input['current_password']. $config['salt']) != $current_password) {
            $error['alert'] = "Your current password is incorrect!";
            $error['current_password'] = "incorrect!";

            include 'views/v_changepassword.php';
        } else {
            $input['hash'] = md5($input['pass'] . $config['salt']);

            if ($stmt = $mysqli->prepare("UPDATE members SET password =? WHERE id=?")) {
                $stmt->bind_param("ss", $input['hash'], $_SESSION['id']);

                $stmt->execute();
                $stmt->close();

                $success['alert'] = "New password has been successfully created!";

                $input['current_password'] = '';
                $input['pass'] = '';
                $input['pass2'] = '';

                include "views/v_changepassword.php";
                

            
            } else {
                echo "Could not prepare SQLi statement.";

            }

            }

        
    }


} else {
    include 'views/v_changepassword.php';
}

$mysqli->close();