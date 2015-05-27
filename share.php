<?php
ob_start(); //from stack overflow
include 'pass.php';
session_start();
if (!isset($_SESSION["username"]))
{
    header("Location: index.php", true);
}
error_reporting(E_ALL);
ini_set('display_errors','On');
$nameset=$_POST["uid"];
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", $pass, "harrings-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (!($stmt = $mysqli->prepare("UPDATE CINFO SET shared=1 WHERE uid=?"))) {
     echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
if (!$stmt->bind_param("s", $nameset)) {
    echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
}
if (!$stmt->execute()) {
    echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
}
$stmt->close();
header("Location: student.php", true);
?>