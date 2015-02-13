<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

?>

<html>
<head>
	<title>Homepage for Patients</title>
</head>

<body>
	<h1>Homepage for Patients</h1>

	<a href="makeappointments.php">Make Appointments</a><br>
	<a href="visithistory.php">View Visit History</a><br>
	<a href="ordermedication.php">Order Medication</a><br>
	<a href="communicate.php">Communicate</a><br>
	<a href="rate.php">Rate a doctor</a><br>
	<a href="editprofile.php">Edit Profile</a>
	<br><br>
	<a href="logout.php">Logout</a>

</body>

</html>
