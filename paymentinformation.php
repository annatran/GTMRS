<?php 

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

$puser = $_SESSION['username'];
$name = $_POST['name'];
$cardno = $_POST['cardno'];
$type = $_POST['type'];
$cvv = $_POST['cvv'];
$expiration = $_POST['expiration'];
$sqldate = date("Y-m-d", strtotime($expiration));

if (isset($_POST['order'])) {

	echo $puser." ".$name." ".$cardno." ".$type." ".$cvv." ".$expiration;

	$sqlquery = "INSERT INTO PaymentInformation (CardNo, CVV, ExpirationDate, Type, CardholderName) VALUES ('$cardno', '$cvv', '$sqldate', '$type', '$name')";
	mysql_query($sqlquery) or die(mysql_error());

	$updatecard = "UPDATE Patient SET Card_No='$cardno' WHERE Username='$puser'";
	mysql_query($updatecard) or die(mysql_error());
}

?>

<html>
<head>
	<title>Payment Information</title>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/jquery-1.9.1.js"></script>
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script>
		$(function() {
			$( "#datepicker" ).datepicker();
		});
	</script>
</head>

<body>
	<a href="patienthomepage.php">Home</a>
	<h1>Payment Information</h1>

	<?php
		$checkcard = "SELECT Card_No FROM Patient WHERE Username='$puser'";
		$result = mysql_query($checkcard) or die(mysql_error());
		$checkzero = mysql_fetch_array($result);
		if ($checkzero['Card_No'] != 0) {
			$patientinfo = "SELECT * FROM PaymentInformation INNER JOIN Patient ON PaymentInformation.CardNo=Patient.Card_No WHERE Username='$puser'";
			$presult = mysql_query($patientinfo) or die(mysql_error());
			$info = mysql_fetch_array($presult);
			echo "Found!<br>";
			echo "Cardholder's Name: ".$info['CardholderName']."<br>";
			echo "Card Number: ".$info['CardNo']."<br>";
			echo "Type: ".$info['Type']."<br>";
			echo "CVV: ".$info['CVV']."<br>";
			echo "Date of Expiry: ".$info['ExpirationDate'];
		}
		else {
			echo "<form action='' method='POST'>";
			echo "Cardholder's Name: <input type='text' name='name' /><br>";
			echo "Card Number: <input type='number' name='cardno' /><br>";
			echo "Type of Card: <select name='type'>";
			echo "<option>Visa</option>";
			echo "<option>Mastercard</option>";
			echo "<option>Discover</option>";
			echo "<option>American Express</option>";
			echo "</select><br>";
			echo "CVV: <input type='number' name='cvv' /><br>";
			echo "Date of Expiry: <input type='text' name='expiration' id='datepicker' /><br>";
			echo "<input type='submit' name='order' value='Order' />";
			echo "</form>";
		}
	?>

<!-- 	<form action="" method="POST">
		Cardholder's Name: <input type="text" name="name" /><br>
		Card Number: <input type="number" name="cardno" /><br>
		Type of Card: <select name="type">
			<option>Visa</option>
			<option>Mastercard</option>
			<option>Discover</option>
			<option>American Express</option>
		</select><br>
		CVV: <input type="number" name="cvv" /><br>
		Date of Expiry: <input type="text" name="expiration" id="datepicker" /><br>
		<input type="submit" name="order" value="Order" />
	</form> -->
</body>
</html>