<?php
ob_start(); //from stack overflow
include 'pass.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
session_start();
if (!isset($_SESSION["username"]))
{
    header("Location: index.php", true);
}
$name=$_SESSION["username"];
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", $pass, "harrings-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!$stmt = $mysqli->query("SELECT teacher FROM USERDB WHERE username = '$name'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	
	{
	$teacher=$row['teacher'];
		
	}

if(!$_SESSION["teacher"])
		{
			header("Location: student.php", true);
		}
else
		{
			header("Location: teacher.php", true);
			
		}


?>