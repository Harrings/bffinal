<?php
ob_start(); //from stack overflow
include 'pass.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
$error=0;
session_start();
if (!isset($_SESSION["username"]))
{
    header("Location: index.php", true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Add Class Error</title>
<?php
include "navbar.php";
?>
</head>
<body>
<section>
<?php
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", $pass, "harrings-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (($_POST["bname"]==null))
{
	echo "<p>Error to add a building it must have a name click <a href=\"switch.php\">here</a> to return to your records</p>";
}
$buildings=array();
if (!$stmt = $mysqli->query("SELECT name FROM Building")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))
{
	if (((!in_array($row['name'], $buildings)))&&($row['name']!=null))
	{
		array_push($buildings,$row['name']);
	}	
}
if (in_array($_POST['bname'], $buildings))
{
	echo "Could not add building as there is already another building with that name click <a href=\"switch.php\">here</a> to return to records";
}
else
{
	$newb=$_POST['bname'];
	if (!($stmt = $mysqli->prepare("INSERT INTO Building(name) VALUES (?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 $error=1;
	}
	if (!$stmt->bind_param("s", $newb)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	$stmt->close();

	if ($error==0)
	{
		header("Location: switch.php", true);
	}
	else
	{
		echo "Error click <a href=\"switch.php\">here</a> to return to your account";
	}
}
?>
</section>
</body>
</html>