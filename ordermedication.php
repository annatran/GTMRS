<?php 

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $username, $password, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

$puser = $_SESSION['username'];

if (isset($_POST['checkout'])) {
	echo $puser." ";
	$medicine = $_POST['medicine'];
	$dosage = $_POST['dosage'];
	$duration = $_POST['months']."months".$_POST['days']."days";
	$consult = $_POST['consultdr'];
	$prescripdate = $_POST['prescripdate']; 
	// $sqldate = date("Y-m-d", strtotime($prescripdate))
	echo $medicine." ".$dosage." ".$duration." ".$consult." ".$prescripdate;
	$_SESSION['medicine'] = $medicine;
	header('Location: paymentinformation.php');
}

?>

<html>
<head>
	<title>GTMRS - Order Medication from Pharmacy</title>
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
	<a href = "patienthomepage.php">Home</a>
	<h1>Order Medication from Pharmacy</h1>

	<form action="" method="POST">
		Medicine Name: <input type="text" name="medicine" required/><br>
		Dosage: <select name="dosage">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select> per day<br>
		Duration: <select name="months">
			<?php for($i=0; $i<13; $i++): ?>
				<option><?= $i ?></option>
			<?php endfor ?>
		</select> months  <select name="days">
			<?php for($j=0; $j<31; $j++): ?>
				<option><?= $j ?></option>
			<?php endfor ?>
		</select> days<br>
		Consulting Doctor: <input type="text" name="consultdr" /><br>
		Date of Prescription: <input type="text" name="prescripdate" id="datepicker" /><br>
		<input type="submit" name="checkout" value="Checkout" />
	</form>
</body>
</html>

