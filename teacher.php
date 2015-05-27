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
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", $pass, "harrings-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Teacher Courses</title>
 <?php
include "navbar.php";
?>
</head>
<body>
<section>
<?php
$sharedusers=array();
if (!$stmt = $mysqli->query("SELECT username FROM CINFO")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	
{
	if ((!(in_array($row['username'], $sharedusers)))&&($row['username']!=null))
	{
		array_push($sharedusers,$row['username']);
	}
}
$username=$_SESSION['username'];
$gradunits=$_SESSION['units'];

?>
<h2>Student reports: Sort by User</h2>
<form action="filter.php" method="POST">
<div align="center">
<select name="sort">
<option value="All">All Shared</option>
<option value="NONE">NONE</option>
<?php
$x=count($sharedusers);
for ($i=0;$i<$x; $i++)
{
	echo "<option value=$sharedusers[$i]>$sharedusers[$i]</option>";
}
?>
</select>
</div>
<input type="submit" value="Filter">
</form>
<?php
if(!isset($_SESSION['sort'])||$_SESSION['sort']=="NONE")
{
	echo "<h3>Currently Viewing No Shared Users</h3>";
}
else if ($_SESSION['sort']=="All")
{
	if (!$stmt = $mysqli->query("SELECT username, cname, cunits, cgrade FROM CINFO")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}	
?>
<h3>Currently Viewing All Shared User CLasses</h3>
<table border="1">
<thead> 
<tr>
	<th>Username</th>
    <th>Course Name</th> 
    <th>Course Units</th> 
    <th>Course Grade</th> 
</tr> 
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($stmt))	
{
	echo "<tr>" ;
	echo "<td>" . $row['username'] . "</td>";
	echo "<td>" . $row['cname'] . "</td>";
	echo "<td>" . $row['cunits'] . "</td>";
	echo "<td>" . $row['cgrade'] . "</td>";	
	echo "</tr>";
}
?>
</tbody>
</table>
<?php
}
else
{
$sorter=$_SESSION['sort'];
	if (!$stmt = $mysqli->query("SELECT units FROM USERDB WHERE username='$sorter'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
	while($row = mysqli_fetch_array($stmt))	
	{
		$gradunits=$row['units'];
	}
	if (!$stmt = $mysqli->query("SELECT cname, cunits, cgrade FROM CINFO WHERE username='$sorter'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
	echo "<h3>Currently Viewing All $sorter User CLasses</h3>";
?>
<table border="1">
<thead> 
<tr>
    <th>Course Name</th> 
    <th>Course Units</th> 
    <th>Course Grade</th> 
</tr> 
</thead>
<tbody>
<?php
	$totalunits=0;
	$totalgp=0;
while($row = mysqli_fetch_array($stmt))	
{

	echo "<tr>" ;
	echo "<td>" . $row['cname'] . "</td>";
	echo "<td>" . $row['cunits'] . "</td>";
	echo "<td>" . $row['cgrade'] . "</td>";	
	echo "</tr>";
	$totalunits=$row['cunits']+$totalunits;
	$totalgp=($row['cunits']*$row['cgrade'])+$totalgp;
}
?>
</tbody>
</table>


</tbody>
</table>
<h2>Student Record</h2>
<table border="1">
<thead> 
<tr>
    <th>Student Name</th> 
    <th>Units needed total</th> 
    <th>Units Taken</th> 
    <th>Units Left</th> 
    <th>GPA</th> 
</tr> 
</thead>
<tbody>
<tr>
<?php
$unitsleft=$gradunits-$totalunits;
if ($totalunits==0)
{
	$gpa=0;
}
else
{
$gpa=$totalgp/$totalunits;
}
	echo "<td>$sorter</td>";
	echo "<td>$gradunits</td>";
	echo "<td>$totalunits</td>";
	echo "<td>$unitsleft</td>";
	echo "<td>$gpa</td>";
?>
</tr>
</tbody>
</table>
<?php	
}
?>
</section>
</body>
</html>

	




