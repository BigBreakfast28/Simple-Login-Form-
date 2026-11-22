<?php 
session_start();

include('includes/config.php');

if (!isset($_SESSION['username'])) {
    header("Location: login.php?unauthorized");
}

if (time() > $_SESSION['lastactive'] + $config['timedout']) {
    session_destroy();
    header('Location: login.php?timeout');
} else {
    $_SESSION['lastactive'] = time();
}

include('views/v_members.php');
