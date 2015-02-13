<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

$puser = $_SESSION['username'];

$dates = "SELECT Date FROM RequestsAppointment WHERE Pusername='$puser'";
$sqldates = mysql_query($dates) or die(mysql_error());

?>

<html>
<head>
	<title>GTMRS - View Visit History</title>
</head>
<body>
	<h1>View Visit History</h1>

	Dates of Visit
</body>
</html>