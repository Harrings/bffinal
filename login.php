<?php
ob_start(); //from stack overflow
include 'pass.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
session_start();
$username=$_POST["username"];
$password=$_POST["password"];
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", $pass, "harrings-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!($stmt = $mysqli->prepare("SELECT uid, teacher, units from USERDB WHERE username=? and password=?"))) {
     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("ss", $username, $password)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->bind_result($count, $teacher, $units);

    
    $stmt->fetch();
$stmt->close();
if ($count>0)
{
	echo "Login Successful";
	$_SESSION["units"]=$units;
	$_SESSION["username"]=$username;
	$_SESSION["teacher"]=$teacher;
	
}



?>