<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect");
mysql_select_db($dbname) or die("Unable to select database.");

if (isset($_SESSION['username'])) {
	$user = $_SESSION['username'];

	if (isset($_POST['p_name']) && isset($_POST['homephone'])) {	
		$p_name = $_POST['p_name'];
		$dob = $_POST['dob'];
		$gender = $_POST['gender'];
		$address = $_POST['address'];
		$homephone = $_POST['homephone'];
		$workphone = $_POST['workphone'];
		$weight = $_POST['weight'];
		$height = $_POST['height'];
		$income = $_POST['income'];
		$allergies = $_POST['allergies'];

		if ($income == 'zero') {
			$income = 0;			
		}
		else if ($income == 'twentyfive') {
			$income = 25000;
		}
		else {
			$income = 50000;
		}

		$sql_query = "INSERT INTO Patient (Username, Annual_Income, Name, Home_phone, Weight, Height, Work_Phone, Address, Gender, DOB) 
			VALUES ('$user', '$income', '$p_name', '$homephone', '$weight', '$height', '$workphone', '$address', '$gender', '$dob')";
		mysql_query($sql_query) or die(mysql_error());

		for ($i = 0; $i < count($allergies); $i++) {
			$a = $allergies[$i];
			// Add only non-empty fields.
			if ($a != "") {
				$allergy_query = "INSERT INTO Allergies (Allergies, Username) VALUES ('$a', '$user')";
				mysql_query($allergy_query) or die(mysql_error());	
				echo "$a <br>";
			}
		}
		header ('Location: patienthomepage.php');
		echo "You did it!";
	}
}

else {
	echo "Session time out :(";
}

?>

<html>
<head>
	<title>Patient Profile</title>

	<!--<script type="text/javascript">
    var datefield=document.createElement("input")
    datefield.setAttribute("type", "date")
    if (datefield.type!="date"){ //if browser doesn't support input type="date", load files for jQuery UI Date Picker
        document.write('<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/themes/base/jquery-ui.css" rel="stylesheet" type="text/css" />\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"><\/script>\n')
        document.write('<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8/jquery-ui.min.js"><\/script>\n')
    }
	</script>
 
	<script>
		if (datefield.type!="date"){ //if browser doesn't support input type="date", initialize date picker widget:
    		jQuery(function($){ //on document.ready
        	$('#dob').datepicker();
    		})
		}
	</script>-->
	<script type="text/javascript">
		function addInput(divName) {
			var newdiv = document.createElement('div');
			newdiv.innerHTML = "<input type='text' name='allergies[]'>";
			document.getElementById(divName).appendChild(newdiv);
		}
	</script>

</head>

<body>
	<h1>Patient Profile</h1>

	<form action="" method="POST">
		Patient Name: <input type="text" name="p_name" required/> *<br>
		Date of Birth: <input type="date" id="dob" name="dob" maxlength="10" 
			pattern="(?:19|20)[0-9]{2}-(?:(?:0[1-9]|1[0-2])-(?:0[1-9]|1[0-9]|2[0-9])|(?:(?!02)(?:0[1-9]|1[0-2])-(?:30))|(?:(?:0[13578]|1[02])-31))" /> 
			Format: YYYY-MM-DD<br>
		Gender: <select name="gender">
			<option value="male">Male</option>
			<option value="female">Female</option>
		</select><br>
		Address: <input type="text" name="address" /> Street Address, City, State<br>
		Home Phone: <input type="tel" name="homephone" maxlength="10" pattern="[0-9]{10}" title="10-digit phone number. Example: 4041234567" required/> * #########<br>
		Work Phone: <input type="tel" name="workphone" maxlength="10" pattern="[0-9]{10}" title="10-digit phone number. Example: 0123456789" /> <br>
		Weight: <input type="number" name="weight" maxlength="3"/> lbs<br>
		Height: <input type="text" name="height" /> inches<br>
		Annual Income($): <select name="income">
			<option value="zero">0 - 25,000</option>
			<option value="twentyfive">25,000 - 50,000</option>
			<option value="fifty">50,000 or greater</option>
		</select><br>
		<div id="allergy">
			Allergies: <input type="text" name="allergies[]" />
			<input type="button" value="+" onClick="addInput('allergy');">
		</div>
		<input type="submit" name="submit" value="Submit" />
	</form>

</body>
</html>