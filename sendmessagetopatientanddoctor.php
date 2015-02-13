<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $dbuser, $dbpw, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

$duser = $_SESSION['username'];

$unread = "SELECT Status, Patient.Name AS 'From', DateTime, Content, Patient.Username FROM SendsMsgtoDoctor INNER JOIN Patient on SendsMsgtoDoctor.Pusername = Patient.Username WHERE SendsMsgtoDoctor.Dusername = '$duser' UNION SELECT Communicates.Status, Doctor.Last_Name AS 'From', Communicates.DateTime, Content, Communicates.Dusername1 FROM Communicates INNER JOIN Doctor ON Communicates.Dusername1 = Doctor.Username WHERE Communicates.Dusername2 = '$duser'";
$messages = mysql_query($unread) or die(mysql_error());

$doctors = "SELECT First_Name, Last_Name, Username FROM Doctor";
$docs = mysql_query($doctors) or die(mysql_error());

$patients = "SELECT Name, Home_phone, Username FROM Patient";
$pats = mysql_query($patients) or die(mysql_error());



// send command after send is clicked
if(isset($_POST['sendtodoc'])){
	$d = $_POST['doctor'];
	$content = $_POST['content'];
	echo $d." ".$puser." ".$content;
	$update = "INSERT INTO Communicates (Dusername1, Dusername2, Content, Status) VALUES ('$duser', '$d', '$content', '0')"; 
	//mysql_query($update) or die(mysql_error());
}

?>


<html>
<head>
	<title>Communication</title>
</head>
	
<body>
	<a href="doctorhomepage.php">Home</a>
	<h1>Inbox</h1>
	<form action = "" method ='POST'>
	<?php
		if(mysql_num_rows($messages)>0){
			echo "<br><table border = '1'><tr>
				<th>Status</th>
				<th>Date</th>
				<th>From</th>
				<th>Message</th>
				<th>Read</th>";
			while ($row = mysql_fetch_array($messages)){
				if ($row[0] == 0){
					$stat = "Unread";
				}
				else{
					$stat = "Read";
				}
				$date = $row[2];
				$from = $row[1];
				$message = $row[3];
				$senter = $row[4];
				echo "<tr><td>"; echo $stat; 
				echo "</td><td>";
				echo $date; echo "</td><td>";
				echo $from; echo "</td><td>";
				echo $message; echo "</td><td>";
				if ($row[0] == 0) {
					echo "<input type='checkbox' name='checked[]' value='$senter,$date,$message' /></td></tr>";
				}
			}
			echo "</table>";
		}
		else{
			echo "NO MESSAGES :(";
		}
		
		if (isset($_POST['update'])){
			if (isset($_POST['checked'])){
		 		$read = $_POST['checked'];
		 		for ($i = 0; $i < count($read); $i++) {
		 			$requests = explode(",",$read[$i]);
		 			$sender = $requests[0];
		 			$thedate = $requests[1];
		 			$msg = $requests[2];
		 			echo $sender." ".$thedate." ".$msg;
		 			$frompatient = mysql_query("SELECT Username FROM Patient WHERE Username='$sender'");
		 			if (mysql_num_rows($frompatient) > 0) {
		 				mysql_query("UPDATE SendsMsgtoDoctor SET Status='1' WHERE Dusername = '$duser' AND Pusername = '$sender' AND Content = '$msg'");
		 				echo "From a patient!";
		 			}
		 			$fromdoc = mysql_query("SELECT Username FROM Doctor WHERE Username='$sender'");
		 			if (mysql_num_rows($fromdoc) > 0) {
		 				mysql_query("UPDATE Communicates SET Status='1' WHERE Dusername1 = '$sender' AND Dusername2 = '$duser' AND Content = '$msg'");
		 			}
		 		}
			}
		}


	?>

	<br>
		<input type = "submit" name ='update' value ='Update'/>
		<input type = "submit" name= "new" value="New Message"/>
	</form> <br>
	<?php
		if(isset($_POST['new'])){
			echo "<form action ='' method = 'POST'>";
				echo "Send Message To Doctor: <select name = 'doctor'>";
				if (mysql_num_rows($docs) > 0){
					while($row = mysql_fetch_array($docs)) {
						$name = "Dr. ".$row[0]." ".$row[1];
						$dusern = $row[2];
						echo "<option value=\"".$dusern."\">".$name."</option>";
					}
				}
			echo "</select><br>";
			echo "<textarea cols= '40' rows= '5' name= 'content'> Message Content </textarea><br>";
			echo "<input type = 'submit' name = 'sendtodoc' value ='Send Message to Doctor'/>";

			echo "Send Message To Patient: <select name = 'patient'>";
			if (mysql_num_rows($pats)>0){
				while ($row = mysql_fetch_array($pats)){
					$pname = $row[0];
					$phone = $row[1];
					$pusern = $row[2];
					echo "<option value =\"".$pusern."\">".$pname." ".$phone."</option>";


				}
			}
			echo "</select><br>";
			echo "<textarea cols= '40' rows= '5' name= 'content2'> Message Content </textarea><br>";
			echo "<input type = 'submit' name = 'sendtopat' value ='Send Message to Patient'/>";
			echo "</form>";
		}
	?> 
</body>
</html>





