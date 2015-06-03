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
  <title>Student Courses</title>
<?php
include "navbar.php";
$buildings=array();
if (!$stmt = $mysqli->query("SELECT name FROM Building")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	
{
	if ((!(in_array($row['name'], $buildings)))&&($row['name']!=null))
	{
		array_push($buildings,$row['name']);
	}
}
$teachers=array();
if (!$stmt = $mysqli->query("SELECT username FROM USERDB WHERE teacher=1")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	
{
	if ((!(in_array($row['username'], $teachers)))&&($row['username']!=null))
	{
		array_push($teachers,$row['username']);
	}
}
?>
</head>
<body>
<section>
<h2>Add Class</h2>
<form action="addclass.php" method="post">
		<p>Course Name: <input type="text" name="cname" /></p>
		<p>Course Units: <input type="number" name="cunits" min="1" max="10" /></p>	
		<p>Grade as number: <input type="number" step="any" name="cgrade" min="1" max="4" /></p>
		<p>Building: <select name="building">
<?php
$x=count($buildings);
for ($i=0;$i<$x; $i++)
{
	echo "<option value='$buildings[$i]'>$buildings[$i]</option>";
}
?>
</select></p>	
<p>Building 2 Optional: <select name="building2">
<option value="NONE">NONE</option>
<?php
for ($i=0;$i<$x; $i++)
{
	echo "<option value='$buildings[$i]'>$buildings[$i]</option>";
}
?>
</select></p>	
		<p>Teacher: <select name="teacherpick">
<?php
$x=count($teachers);
for ($i=0;$i<$x; $i++)
{
	echo "<option value='$teachers[$i]'>$teachers[$i]</option>";
}
?>
</select></p>	
		<br><br>
		<input type="submit" value="Submit">
		<br><br>
</form>
<?php
$username=$_SESSION['username'];
$gradunits=$_SESSION['units'];

if (!$stmt = $mysqli->query("SELECT B.name, U.username, C.uid, C.cname, C.cunits, C.cgrade, C.shared FROM CINFO C
	INNER JOIN Class_Site CS on C.uid=CS.cid
	INNER JOIN Building B on CS.bid=B.bid
	INNER JOIN Teaches T on T.cid=C.uid
	INNER JOIN USERDB U on T.tid=U.uid
	WHERE C.username='$username'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
/*
if (!$stmt = $mysqli->query("SELECT U.username, C.uid, C.cname, C.cunits, C.cgrade, C.shared FROM CINFO C
	INNER JOIN Teaches T on T.cid=C.uid
	INNER JOIN USERDB U on T.tid=U.uid
	WHERE C.username='$username'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
*/
?>
<h2>Classes Taken</h2>
<table border="1">
<thead> 
<tr>
    <th>Course Name</th> 
    <th>Course Units</th> 
    <th>Course Grade</th> 
	<th>Teacher</th> 
	<th>Building</th> 
    <th>Shared</th> 
    <th>Change Status</th> 
    <th>Delete</th>
</tr> 
</thead>
<tbody>
<?php
$totalunits=0;
$totalgp=0;
$usedid=0;
while($row = mysqli_fetch_array($stmt))	
{
	echo "<tr>" ;
	echo "<td>" . $row['cname'] . "</td>";
	echo "<td>" . $row['cunits'] . "</td>";
	echo "<td>" . $row['cgrade'] . "</td>";
	echo "<td>" . $row['username'] . "</td>";
	echo "<td>" . $row['name'] . "</td>";
	echo "<td>";
	if (!$row['shared'])
	{
		echo "Not Shared </td>";
		echo "<td><form method=\"POST\" action=\"share.php\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$row['uid']."\">";
		echo "<input type=\"submit\" value=\"share\">";
		echo "</form> </td>";
	}
	else
	{
		echo "Shared </td>";
		echo "<td><form method=\"POST\" action=\"unshare.php\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$row['uid']."\">";
		echo "<input type=\"submit\" value=\"unshare\">";
		echo "</form> </td>";
	}
	echo "<td><form method=\"POST\" action=\"delete.php\">";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$row['uid']."\">";
	echo "<input type=\"submit\" value=\"delete\">";
	echo "</form> </td>";
	echo "</tr>";
	//$totalunits=$row['cunits']+$totalunits;
	if ($usedid!=$row['uid']) //used to keep track if class has been calculated yet
	{
		$totalgp=($row['cunits']*$row['cgrade'])+$totalgp;
		$usedid=$row['uid'];
	}
}
?>
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
    $stmt->bind_param("s", $username);

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

$gpa=$totalgp/$totalunits;

}

if ($stmt = $mysqli->prepare("Select uid from USERDB WHERE username=?")) {

    /* bind parameters for markers */
    $stmt->bind_param("s", $username);

    /* execute query */
    $stmt->execute();

    /* bind result variables */
    $stmt->bind_result($userid);

    /* fetch value */
    $stmt->fetch();

    /* close statement */
    $stmt->close();
	}
if (!($stmt = $mysqli->prepare("UPDATE GPA SET GPA=?, utaken=? WHERE sid=?"))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 $error=1;
	}
	if (!$stmt->bind_param("dii", $gpa, $totalunits, $userid)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	$stmt->close();


	echo "<td>$username</td>";
	echo "<td>$gradunits</td>";
	echo "<td>$totalunits</td>";
	echo "<td>$unitsleft</td>";
	echo "<td>$gpa</td>";
?>
</tr>
</tbody>
</table>
<?php
$sharedusers=array();
if (!$stmt = $mysqli->query("SELECT username FROM CINFO WHERE shared=1")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
while($row = mysqli_fetch_array($stmt))	
{
	if ((!(in_array($row['username'], $sharedusers)))&&($row['username']!=null))
	{
		array_push($sharedusers,$row['username']);
	}
}
?>
<h2>Shared Info Sort by User</h2>
<form action="filter.php" method="POST">
<div align="center">
<select name="sort">
<option value="All">All Shared</option>
<option value="NONE">NONE</option>
<?php
$x=count($sharedusers);
for ($i=0;$i<$x; $i++)
{
	echo "<option value='$sharedusers[$i]'>$sharedusers[$i]</option>";
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
	INNER JOIN USERDB U on T.tid=U.uid
	WHERE C.shared=1")) {
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
	if (!$stmt = $mysqli->query("SELECT B.name, U.username as teacher, C.uid, C.cname, C.cunits, C.cgrade FROM CINFO C
	INNER JOIN Class_Site CS on C.uid=CS.cid
	INNER JOIN Building B on CS.bid=B.bid
	INNER JOIN Teaches T on T.cid=C.uid
	INNER JOIN USERDB U on T.tid=U.uid
	WHERE C.shared=1 and C.username='$sorter'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
	echo "<h3>Currently Viewing $sorter Shared User CLasses</h3>";
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
while($row = mysqli_fetch_array($stmt))	
{
	echo "<tr>" ;
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
?>
</section>
</body>
</html>

	




