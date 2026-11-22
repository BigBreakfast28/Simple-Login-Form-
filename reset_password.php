<?php 
session_start();

include "includes/config.php";
include "includes/db.php";

$success['alert'] = '';

$error['alert'] = '';
$error['email'] = '';
$error['pass'] = '';
$error['pass2'] = '';

$input['email'] = '';
$input['pass'] = '';
$input['pass2'] = '';

if (isset($_GET['key'])) {
    //user is entering a new password
    if (isset($_POST['submit']))
	{
		// process form
		$input['email'] = htmlentities($_POST['email'], ENT_QUOTES);
		$input['pass'] = htmlentities($_POST['password'], ENT_QUOTES);
		$input['pass2'] = htmlentities($_POST['password2'], ENT_QUOTES);
		$key = htmlentities($_GET['key'], ENT_QUOTES);
		
		// check input
		if ($input['email'] == '' || $input['pass'] == '' || $input['pass2'] == '')
		{
			// all fields need to be filled in
			if ($input['email'] == '') { $error['email'] = 'required!'; }
			if ($input['pass'] == '') { $error['pass'] = 'required!'; }
			if ($input['pass2'] == '') { $error['pass2'] = 'required!'; }
			$error['alert'] = 'Please fill in required fields!';
			
			// show form
			include('views/v_reset_pw2.php');
		}
		else if (!preg_match('/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*[0-9a-zA-Z]\.)+[a-zA-Z]{2,9})$/', $input['email']))
		{
			// email is invalid
			$error['email'] = 'Please enter a valid email!';
			
			// show form
			include('views/v_reset_pw2.php');
		}
		else
		{
			// make sure email and key match
			$check = $mysqli->prepare("SELECT email FROM members WHERE email = ? AND pw_reset = ?");
			$check->bind_param("ss", $input['email'], $key);
			$check->execute();
			$check->store_result();
			if($check->num_rows == 0)
			{
				// error
				$error['alert'] = "Unfortunately the reset key and the email you have entered do not match, or the password reset key is invalid. Please double check your email address, or try <a href='reset_password.php'>resetting your password again</a>.";
				include('views/v_reset_pw2.php');
			}
			else
			{
                $input['hash'] = md5($input['pass'] . $config['salt']);
				// update password/clearing reset key
				$check = $mysqli->prepare("UPDATE members SET password = ?, pw_reset = '' WHERE email = ?");
				$check->bind_param("ss", $input['hash'], $input['email']);
				$check->execute();
				$check->close();
				
				// add alert and clear form values
				$success['alert'] = 'Password updated successfully.';
				$input['email'] = '';
				$input['pass'] = '';
				$input['pass2'] = '';
				
				include('views/v_reset_pw2.php');
			}
		
		}
	}
	else
	{
		// show form
		include("views/v_reset_pw2.php");
	}
 
} else {
    //user is requesting a new password
    if (isset($_POST['submit'])) {

        $input['email'] = htmlentities($_POST['email'], ENT_QUOTES);

        if ($_POST['email'] == '') {
            $error['email'] = "Please enter your email address!";

            include 'views/v_reset_pw.php';
        } else if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',$input['email'])) {
            $error['email'] = "Email address in invalid!";
    
            include 'views/v_reset_pw.php';
        } else {
            //check the email address is in the database
            $verify = $mysqli->prepare("SELECT email FROM members WHERE email=?");
            $verify->bind_param("s", $input['email']);
            $verify->execute();
            $verify->store_result();
            if ($verify->num_rows == 0) {
                $verify->close();
                $error['alert'] = "This email doesn't exist in this database";
                
                include 'views/v_reset_pw.php';
            } else {
                //Prompt the user to click a link and so they can reset their password. 
                $verify->close();

                $key = randomkey(16);

                $subject = $config['site_name'] . " Password Reset";

                $message = "<html><body>";
				$message .= "<p>Hello,</p>";
				$message .= "<p>You (or someone claiming to be you) recently asked that your " . $config['site_name'] . " password be reset. If so, please click the link below to reset your password. If you do not want to reset your password, or if the request was in error, please ignore this message.</p>";
				$message .= "<a href='" . $config['site_url'] . "/reset_password.php?key=" . $key . "'>" . $config['site_url'] . "/reset_password.php?key=" . $key . "</a></p>";
				$message .= "<p>Thanks, <br/>The Administrator, " . $config['site_name'] . "</p>";
				$message .= "</body></html>";

                $headers = "MIME-Version: 1.0 \r\n";
				$headers .= "Content-type: text/html; charset=utf-8 \r\n";
				$headers .= "From: " . $config['site_name'] . " <noreply@" . $config['site_domain'] . ">\r\n";
				$headers .= "X-Sender: <noreply@" . $config['site_domain'] . ">\r\n";
				$headers .= "Reply-To: <noreply@" . $config['site_domain'] . ">\r\n";

                mail($input['email'], $subject, $message, $headers);

                //Update pw_reset key
                $stmt = $mysqli->prepare("UPDATE members SET pw_reset = ? WHERE email = ?");
                $stmt->bind_param("ss", $key, $input['email']);
                $stmt->execute();
                $stmt->close();

                
                //Add alert message and clear the file
                $success['alert'] = "Please check your email for the password reset message";
                $input['email'] = '';
                include 'views/v_reset_pw.php';

            }
        }
    } else {
       include 'views/v_reset_pw.php';
    } 
}




$mysqli->close();
function randomKey($length) {
    $characters = "0123456789abcdefghijklmnopqrstuxwxyzABCDEFGHIJKLMNOPQRTUVWXYZ";
    $string = '';
    for ($p = 0; $p < $length; $p++) {
        $string .= $characters[mt_rand(0, strlen($characters)-1)];
    }
    return $string;
}