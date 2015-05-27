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
if (($_POST["cname"]==null))
{
	echo "<p>Error to add a class it must have a name click <a href=\"switch.php\">here</a> to return to your records</p>";
}
else if (($_POST["cunits"]==null))
{
	echo "<p>Error to add a class it must have a number of units click <a href=\"switch.php\">here</a> to return to your records</p>";
}
else if (($_POST["cgrade"]==null))
{
	echo "<p>Error to add a class it must have a grade click <a href=\"switch.php\">here</a> to return to your records</p>";
}
else
{
	$username=$_SESSION["username"];
	$name=$_POST["cname"];
	$category=$_POST["cunits"];
	$length=$_POST["cgrade"];

	if (!($stmt = $mysqli->prepare("INSERT INTO CINFO(username, cname, cunits, cgrade) VALUES (?,?,?,?)"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 $error=1;
	}
	if (!$stmt->bind_param("ssii", $username, $name, $category, $length)) {
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
		echo "Error there is already a Class with the same name in the inventory click <a href=\"video.php\">here</a> to return to inventory managment";
	}
}
?>
</section>
</body>
</html>