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
<h2>Add Building</h2>
<form action="addbuilding.php" method="post">
	<p>Building Name: <input type="text" name="bname" /></p>
	<input type="submit" value="Submit">
</form>
<h2>Students Ranked by GPA</h2>
<?php
	if (!$stmt = $mysqli->query("SELECT  U.username, G.GPA from USERDB U 
		INNER JOIN GPA G on U.uid=G.sid ORDER BY G.GPA DESC")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
?>
<table border="1">
<thead> 
<tr>
	<th>Username</th>
    <th>GPA</th>  
</tr> 
</thead>
<tbody>
<?php
while($row = mysqli_fetch_array($stmt))	
{
	echo "<tr>" ;
	echo "<td>" . $row['username'] . "</td>";
	echo "<td>" . $row['GPA'] . "</td>";	
	echo "</tr>";
}
?>
</tbody>
</table>
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
	if (!$stmt = $mysqli->query("SELECT B.name, U.username as teacher, C.username, C.uid, C.cname, C.cunits, C.cgrade FROM CINFO C
	INNER JOIN Class_Site CS on C.uid=CS.cid
	INNER JOIN Building B on CS.bid=B.bid
	INNER JOIN Teaches T on T.cid=C.uid
	INNER JOIN USERDB U on T.tid=U.uid")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}	
?>
<h3>Currently Viewing All Student CLasses</h3>
<table border="1">
<thead> 
<tr>
	<th>Username</th>
    <th>Course Name</th> 
    <th>Course Units</th> 
    <th>Course Grade</th> 
	<th>Teacher</th> 
	<th>Building</th> 
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
	echo "<td>" . $row['teacher'] . "</td>";	
	echo "<td>" . $row['name'] . "</td>";	
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
	if (!$stmt = $mysqli->query("SELECT B.name, U.username as teacher, C.uid, C.cname, C.cunits, C.cgrade FROM CINFO C
	INNER JOIN Class_Site CS on C.uid=CS.cid
	INNER JOIN Building B on CS.bid=B.bid
	INNER JOIN Teaches T on T.cid=C.uid
	INNER JOIN USERDB U on T.tid=U.uid
	where C.username='$sorter'")) {
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
	<th>Teacher</th> 
	<th>Building</th> 
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
	echo "<td>" . $row['teacher'] . "</td>";	
	echo "<td>" . $row['name'] . "</td>";	
	echo "</tr>";
	//$totalunits=$row['cunits']+$totalunits;
	//$totalgp=($row['cunits']*$row['cgrade'])+$totalgp;
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
	if ($stmt = $mysqli->prepare("SELECT SUM(cunits) FROM CINFO WHERE username=?")) {

    /* bind parameters for markers */
    $stmt->bind_param("s", $sorter);

    /* execute query */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($totalunits);

    /* fetch value */
    $stmt->fetch();

    /* close statement */
    $stmt->close();
	}
$unitsleft=$gradunits-$totalunits;
if ($totalunits==0)
{
	$gpa=0;
}
else
{
   if ($stmt = $mysqli->prepare("Select uid from USERDB WHERE username=?")) {

    /* bind parameters for markers */
    $stmt->bind_param("s", $sorter);

    /* execute query */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($sorterid);

    /* fetch value */
    $stmt->fetch();

    /* close statement */
    $stmt->close();
	}
	if ($stmt = $mysqli->prepare("SELECT GPA FROM GPA WHERE sid=?")) {

    /* bind parameters for markers */
    $stmt->bind_param("i", $sorterid);

    /* execute query */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($gpa);

    /* fetch value */
    $stmt->fetch();

    /* close statement */
    $stmt->close();
	}
	
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

	




