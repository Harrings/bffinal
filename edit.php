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
$units=$_SESSION['units'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Edit</title>
 <?php
 include "navbar.php";
 ?>
</head>
<body>
<section>
<?php
if(!$_SESSION["teacher"])
{
?>
<h2>Edit number of units needed</h2>
<form action="editunits.php" method="post">
<?php
		echo "<p>Total units needeed to graduate: $units </p>"; 
?>
		<p> Edit :<input type="number" name="units" min="1" max="120" /></p>
	<input type="submit" value="Submit">
</form>	
<br>
<br>
<?php
}
?>
<h2>Delete Account</h2>
<form action="deleteaccount.php" method="post">
	<input type="submit" value="Delete">	
</section>
</body>
</html>