<?php
include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");
?>

<html>
<head>
	<title>GTMRS - Rate a Doctor</title>
</head>

<body>
	<h1>Rate a Doctor</h1>
	<form action="" method="POST">
		<b>Select Doctors:</b> <br>
		<b>Rating:</b> <br>
		<input type="submit" name="submit" value="Submit Rating" />
	</form>
</body>
</html>