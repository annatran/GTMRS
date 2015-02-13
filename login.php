<?php

include 'dbinfo.php';
session_start();
ob_start();

if (isset($_POST['username']) && isset($_POST['password'])) {
	$user = $_POST['username'];
	$pw = $_POST['password'];

	mysql_connect($host, $username, $password, $dbname) or die("Unable to connect");
	mysql_select_db($dbname) or die("Unable to select database");

	$sql_query = "SELECT Username FROM User WHERE (Username = '$user' AND Password = '$pw')";
	$result = mysql_query($sql_query) or die(mysql_error());

	$patient = "SELECT User.Username FROM User INNER JOIN Patient ON User.Username=Patient.Username WHERE User.Username = '$user'";
	$p_result = mysql_query($patient) or die(mysql_error());

	$doctor = "SELECT User.Username FROM User INNER JOIN Doctor ON User.Username=Doctor.Username WHERE User.Username='$user'";
	$d_result = mysql_query($doctor) or die(mysql_error());

	if(mysql_num_rows($result) == 1) {
		$_SESSION['username'] = $user;
		$_SESSION['password'] = $pw;
		if(mysql_num_rows($p_result) == 1) {
			header ('Location: patienthomepage.php');
		}
		else if(mysql_num_rows($d_result) == 1) {
			header ('Location: doctorhomepage.php');
		}
		else {
			echo "Uh-oh. $p_result";
		}
	} 
	else {
		$err = 'Wrong username and/or password :(';
		echo "$err";
	}
}

?>


<html>
<head>
<title>Login</title>
</head>
<body>


<h1>Login</h1>

<form action="" method="POST">
	Username: <input type="text" name="username" /><br>
	Password: <input type="password" name="password" /><br>

	<input type="submit" name="login" value="Login" />
</form>

<form action="register.php" method="POST">
	<input type="submit" name="create_account" value="Create Account" />
</form>

</body>
</html>