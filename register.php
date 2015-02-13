<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect");
mysql_select_db($dbname) or die("Unable to select database.");

if(isset($_POST['username'])) {
	$user = $_POST['username'];
	$pw = $_POST['password'];
	$confirm_pw = $_POST['confirm_pw'];
	$user_type = $_POST['user_type'];

	$user_query = "SELECT Username FROM User WHERE (Username = '$user')";
	$user_result = mysql_query($user_query) or die(mysql_error());

	if (mysql_num_rows($user_result) == 1) {
		echo "Username already exists";
	}

	else {
		if ($pw == $confirm_pw) {
			echo "Yay!";
			$sql_query = "INSERT INTO User (Username, Password) VALUES ('$user', '$pw')";
			mysql_query($sql_query) or die(mysql_error());
			if ($user_type == 'patient') {
				//echo "You're a patient.";
				$_SESSION['username'] = $user; 
				header('Location: patientprofile.php');
			}
			else if ($user_type == 'doctor') {
				$_SESSION['username'] = $user;
				header('Location: doctorprofile.php');
				// echo "You a doctaaaa.";
			}
		}
		else {
			echo "Passwords do not match.";
		}
	}
}

?>

<html>
<head>
	<title>New User Registration!</title>
</head>
<body>
	<h1>New User Registration</h1>

	<form action="" method="POST">
		Username: <input type="text" name="username" /><br>
		Password: <input type="password" name="password" /><br>
		Confirm Password: <input type="password" name="confirm_pw" /><br>
		Type of User: <select name="user_type"> 
			<option value="doctor">Doctor</option>
			<option value="admin">Administrative Personnel</option>
			<option value="patient">Patient</option>
		</select><br>
		<input type="submit" name="register" value="Register" />
	</form>
</body>
</html>