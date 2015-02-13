<?php
include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");
?>

<html>
<head>
	<title>GTMRS - Appointments Calendar</title>
</head>

<body>
	<h1>Appointments Calendar</h1>

	<form action="" method="POST">
		<b>Select Date: </b> <select name="day">
			<?php for($i = 1; $i < 32; $i++): ?>
				<option><?= $i ?></option>
			<?php endfor ?>
		</select> <select name="month">
			<?php for($i = 1; $i < 13; $i++): ?>
				<option><?= date("F", mktime(0, 0, 0, $i+1, 0, 0, 0)) ?></option>
			<?php endfor ?>
		</select> <select name="year">
			<option>2013</option>
			<option>2014</option>
		</select>
	</form>
</body>

</html>