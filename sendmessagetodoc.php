<?php

include 'dbinfo.php';
ob_start();
session_start();

mysql_connect($host, $dbuser, $dbpw, $dbname) or die("Unable to connect.");
mysql_select_db($dbname) or die("Unable to connect to database.");

$puser = $_SESSION['username'];

$unread = "SELECT SendMsgtoPatient.Dusername, SendMsgtoPatient.Content, SendMsgtoPatient.Status, SendMsgtoPatient.DateTime, Doctor.First_Name, Doctor.Last_Name FROM SendMsgtoPatient INNER JOIN Doctor ON SendMsgtoPatient.Dusername = Doctor.Username WHERE SendMsgtoPatient.Pusername = '$puser' ORDER BY SendMsgtoPatient.DateTime";
$messages = mysql_query($unread) or die(mysql_error());

$doctors = "SELECT First_Name, Last_Name, Username FROM Doctor";
$docs = mysql_query($doctors) or die(mysql_error());

//send command after send is clicked
if(isset($_POST['send'])){
	$d = $_POST['doctor'];
	$content = $_POST['content'];
	echo $d." ".$puser." ".$content;
	$update = "INSERT INTO SendsMsgtoDoctor (Pusername, Dusername, Content, Status) VALUES ('$puser', '$d', '$content', '0')"; 
	mysql_query($update) or die(mysql_error());
}

?>


<html>
<head>
	<title>Communication</title>
</head>
	
<body>
	<a href="patienthomepage.php">Home</a>
	<h1>Inbox</h1>
	<?php 
		if(mysql_num_rows($messages) > 0){
			echo "<br><form action = '' method = POST ''><table border = '1'><tr>
					<th>Status</th>
					<th>Date</th>
					<th>From</th>
					<th>Message</th>
					<th>Read?</th>";
			while ($row = mysql_fetch_array($messages)){	
				if ($row[2] == 0){
					$stat = "Unread";
				}
				else{
					$stat = "Read";
				}
				$duser = $row[0];
				$date = $row[3];
				$from = "Dr. ".$row[4]." ".$row[5];
				$message = $row[1];
				echo "<tr><td>"; echo $stat; 
				echo "</td><td>";
				echo $date; echo "</td><td>";
				echo $from; echo "</td><td>";
				echo $message; echo "</td><td>";
				echo "<input type = 'checkbox' name = 'checked[]' value = '$duser,$date,$message'/></td></tr>";

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
		 			mysql_query("UPDATE SendMsgtoPatient SET Status = '1' WHERE Pusername = '$puser' AND Dusername = '$sender' AND Content = '$msg'") or die(mysql_error());
				}
			}
		}

	?>
	<br>
	<input type = "submit" name = 'update' value = 'Update'/>
	<input type = "submit" name= "new" value="New Message"/>
	</form> <br>
	<?php
		if(isset($_POST['new'])){
			echo "<form action ='' method = 'POST'>";
				echo "Send Message To: <select name = 'doctor'>";
				if (mysql_num_rows($docs) > 0){
					while($row = mysql_fetch_array($docs)) {
						$name = "Dr. ".$row[0]." ".$row[1];
						$duser = $row[2];
						echo "<option value=\"".$duser."\">".$name."</option>";
					}
				}
			echo "</select><br>";
			echo "<textarea cols= '40' rows= '5' name= 'content'> Message Content </textarea><br>";
			echo "<input type = 'submit' name = 'send' value ='Send Message'/>";
			echo "</form>";
		}
	?>
</body>
<html>