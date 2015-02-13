<?php
include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");
?>

<html>
<head>
	<title>GTMRS - Homepage for Doctors</title>
</head>

<body>
	<h1>Homepage for Doctors</h1>

	<a href="apptscalendar.php">View Appointments Calendar</a><br>
	<a href="">Patient Visits</a><br>
	<a href="">Record a surgery</a><br>
	<a href="">Communicate</a><br>
	<a href="">Edit Profile</a>

</body>
</html>