<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

$puser = $_SESSION['username'];
$specialty = $_POST['specialty'];
$result = null;

if (isset($_POST['submit'])) {
	if (isset($_POST['specialty'])) {
		$sql_query = "SELECT Doctor.Username, Doctor.First_Name, Doctor.Last_Name, Doctor.Work_phone, Doctor.Room_No, Availability.Day, Availability.From_Time, Availability.To_Time 
			FROM Doctor INNER JOIN Availability ON Doctor.Username=Availability.Dusername WHERE Doctor.Specialty = '$specialty'";
		$result = mysql_query($sql_query) or die(mysql_error());
	}
}

$date = getdate(); 
$requests = array();

if (isset($_POST['request'])) {
	if (isset($_POST['check'])) {
		// // $request_query = "INSERT INTO RequestsAppointment (Dusername, Pusername, Date, Scheduled_Time) VALUES ('$duser', '$puser', '$date', '$sched')";
		// // mysql_query($request_query) or die(mysql_error());
		$d = $_POST['check'];
		// echo $d;
		$requests = explode(",", $d);
		$duser = $requests[0];
		$date = $requests[1]; 
		$datenum = date('N', strtotime($date));
		$sched = $requests[2];

		$i = 0;
		if (date("N") < $datenum) {
			$i = $datenum - date("N");
		}
		else if (date("N") > $datenum) {
			$i = 7 - date("N") + $datenum;
		}
		else {
			$i = 0;
		}

		$appttime = date('Y-m-d', strtotime("+".$i." days"));

		echo $duser;
		echo $date;
		echo $datenum;
		echo $sched;
		echo $puser;
		echo $appttime;

		$request_query = "INSERT INTO RequestsAppointment (Dusername, Pusername, Date, Scheduled_Time) VALUES ('$duser', '$puser', '$appttime', '$sched')";
		mysql_query($request_query) or die(mysql_error());
		echo "Check!<br>";	
	}
	else {
		echo "No checks.";
	}
}

?> 

<html>
<head>
	<title>GTMRS - Schedule appointments with Doctors</title>

	<script type="text/javascript">
		var box = document.querySelectorAll("input[name=")
	</script>
</head>

<body>
	<a href="patienthomepage.php">Home</a>
	<h1>Schedule appointments with Doctors</h1>

	<form action="" method="POST">
		Specialty: <select name="specialty">
			<option label="physician">General Physician</option>
			<option label="heart">Heart Specialist</option>
			<option label="eye">Eye Physician</option>
			<option label="ortho">Orthopedics</option>
			<option label="psychiatry">Psychiatry</option>
			<option label="obgyn">Gynecologist</option>
		</select>
		<input type="submit" name="submit" value="Search" />
	</form>

	<?php
		if (mysql_num_rows($result) > 0) {
			echo "<form action='' method='POST'>";
			echo "<table border='1'>
				<tr>
				<th>Doctor Name</th>
				<th>Phone Number</th>
				<th>Room Number</th>
				<th>Availability</th>
				<th>Average Rating</th>
				</tr>";
			while ($row = mysql_fetch_array($result)) {
				$convertfrom = date('h:i a', strtotime($row['From_Time']));
				$convertto = date('h:i a', strtotime($row['To_Time']));
				$duser = $row['Username'];
				$sched = $row['Day'];
				echo "<tr>";
				echo "<td> Dr. {$row['First_Name']} {$row['Last_Name']}</td>";
				echo "<td> {$row['Work_phone']} </td>";
				echo "<td> #{$row['Room_No']} </td>";
				echo "<td>{$row['Day']} : $convertfrom - $convertto <input type='checkbox' name='check[]' value='$duser,$sched,$convertfrom' /></td>";
				echo "</tr>";
			}
			echo "</table>";
			echo "<input type='submit' name='request' value='Request Appointment' />";
			echo "</form>";
		}
		else {
			echo "No doctors found.";
		}
	?>
</body>
</html>