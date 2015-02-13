<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to select database.");

if (isset($_SESSION['username'])) {
	$user = $_SESSION['username'];

	if (isset($_POST['license'])) {
		$license = $_POST['license'];
		$first = $_POST['first'];
		$last = $_POST['last'];
		$dob = $_POST['dob'];
		$homeaddress = $_POST['homeaddress'];
		$workphone = $_POST['work'];
		$specialty = $_POST['specialty'];
		$room_no = $_POST['room'];
		$day = $_POST['day'];
		$from_time = $_POST['from'];
		$to_time = $_POST['to'];

	 	$sql_query = "INSERT INTO Doctor (Username, License_No, First_Name, Last_Name, DOB, Home_Address, Work_phone, Specialty, Room_No)
	 		VALUES ('$user', '$license', '$first', '$last', '$dob', '$homeaddress', '$workphone', '$specialty', '$room_no')";
	 	mysql_query($sql_query) or die(mysql_error());


	 	for($x = 0; $x < count($day); $x++) {
	 		$d = $day[$x];
	 		$f = $from_time[$x];
	 		$t = $to_time[$x];
	 		$combo = $user.$d.$f.$t;
	 		$avail_query = "INSERT INTO Availability (Availability, Dusername, Day, From_Time, To_Time) VALUES ('$combo', '$user', '$d', '$f', '$t')";
	 		mysql_query($avail_query) or die(mysql_error());
	 		echo "$combo <br>";
	 	}
	 	// echo "Yay!";
 	}
}

?>

<html>
<head>
	<title>Doctor Profile</title>

	<script type="text/javascript">
		var days=[];
		days[0]="Sunday";
		days[1]="Monday";
		days[2]="Tuesday";
		days[3]="Wednesday";
		days[4]="Thursday";
		days[5]="Friday";
		days[6]="Saturday";

		var times=[];
		for(hours=0; hours<24; hours=hours+1) {
			for(mins=0; mins<60; mins=mins+30) {
				
			}
		}

		function addInput(divName){
		    var newDiv=document.createElement('div');
		    var selectHTML = "";
		    selectHTML="<select name='day[]'>";
		    for(i=0; i<days.length; i=i+1){
		        selectHTML+= "<option>"+days[i]+"</option>";
		    }
		    selectHTML += "</select>";
		    selectHTML += " From: <select name='from[]'>";
		    for(hours=0; hours<24; hours=hours+1) {
		    	for(mins=0; mins<60; mins=mins+30) {
		    		selectHTML += "<option>"+(hours%12 != 0 ? hours%12 : 12)+":"+(mins==0 ? "00" : "30")+(hours>=12 ? " pm" : " am")+"</option>";
		    	}
		    }
		    selectHTML += "</select>";
		    selectHTML += " To: <select name='to[]'>";
		    for(hours=0; hours<24; hours=hours+1) {
		    	for(mins=0; mins<60; mins=mins+30) {
		    		selectHTML += "<option>"+(hours%12 != 0 ? hours%12 : 12)+":"+(mins==0 ? "00" : "30")+(hours>=12 ? " pm" : " am")+"</option>";
		    	}
		    }
		    selectHTML += "</select>";		    
		    newDiv.innerHTML= selectHTML;
		    document.getElementById(divName).appendChild(newDiv);
		}
 	</script>

</head>

<body>
	<h1>Doctor Profile</h1>

	<form action="" method="POST">
		License Number: <input type="number" name="license" required/><br>
		First Name: <input type="text" name="first" /><br>
		Last Name: <input type="text" name="last" /><br>
		Date of Birth: <input type="date" name="dob"
			pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))"
			title="YYYY-MM-DD and valid year, month, and/or day" /> Format: YYYY-MM-DD<br>
		Work Phone: <input type="tel" name="work" maxlength="10" pattern="[0-9]{10}" title="Format: 0001112222" /><br>
		Specialty: <select name="specialty">
			<option>General Physician</option>
			<option>Heart Specialist</option>
			<option>Eye Physician</option>
			<option>Orthopedics</option>
			<option>Psychiatry</option>
			<option>Gynecologist</option>
		</select><br>
		Room Number: <input type="number" name="room" /><br>
		Home Address: <input type="text" name="homeaddress" />
		<div id="available">
			Availability: <select name="day[]">
				<option>Sunday</option>
				<option>Monday</option>
				<option>Tuesday</option>
				<option>Wednesday</option>
				<option>Thursday</option>
				<option>Friday</option>
				<option>Saturday</option>
			</select>
			From: <select name="from[]">
				<?php for($i = 0; $i < 24; $i++): ?>
					<?php for($j = 0; $j < 60; $j += 30): ?>
  						<option><?= $i % 12 ? $i % 12 : 12 ?>:<?= $j == 0 ? '00' : '30' ?> <?= $i >= 12 ? 'pm' : 'am' ?></option>
  					<?php endfor ?>
				<?php endfor ?>
			</select>
			To: <select name="to[]">
				<?php for($i = 0; $i < 24; $i++): ?>
					<?php for($j = 0; $j < 60; $j += 30): ?>
  						<option><?= $i % 12 ? $i % 12 : 12 ?>:<?= $j == 0 ? '00' : '30' ?> <?= $i >= 12 ? 'pm' : 'am' ?></option>
  					<?php endfor ?>
				<?php endfor ?>
			</select>
			<input type="button" value="Add" onClick="addInput('available');" />
		</div> <br>
		<input type="submit" name="submit" value="Submit" />
	</form>
</body>

</html>