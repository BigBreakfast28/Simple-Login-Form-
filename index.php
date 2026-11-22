<?php
session_start();
include('includes/config.php');
include('includes/db.php');



$error['alert'] = '';
$error['user'] = '';
$error['pass'] = '';
$input['user'] = '';
$input['pass'] = '';

if (isset($_POST['submit'])) {

    
    if ($_POST['username'] == '' || $_POST['password'] == '') {
       if ($_POST['username'] == '') { $error['user'] = "REQUIRED!";}
       if ($_POST['password'] == '') {$error['pass'] = "REQUIRED!";}

       $error['alert'] = "Please fill in all required fields!";

       $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
       $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);

       include('views/v_login.php');
    } else {
        
        $input['user'] = htmlentities($_POST['username'], ENT_QUOTES);
        $input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
        $input['hash'] = md5($input['pass'] . $config['salt']);

        if ($stmt = $mysqli->prepare("SELECT id FROM members WHERE username=? AND password=?")) {
            $stmt->bind_param("ss", $input['user'], $input['hash']);
            $stmt->execute();
            $stmt->bind_result($id);
            $stmt->fetch();

            if ($id) {
                $stmt->close();
                
                $_SESSION['id'] = $id;
                $_SESSION['username'] = $input['user'];
                $_SESSION['lastactive'] = time();

                header("Location:members.php");
            } else {
                $stmt->close();
                $error['alert'] = "Username or password is incorrect!";
                
                include('views/v_login.php');
            }
        } else {
            echo "Could not prepare SQLi statement.";

        }
    }

} else {
    if (isset($_GET['unauthorized'])) {
        $error['alert'] = "You must be logged in to view this page.";
    }

    if (isset($_GET['timeout'])) {
        $error['alert'] = "Your session has expired. Please log in again.";
    }
    include('views/v_login.php');
}

$mysqli->close();