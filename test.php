<?php
$username=$_SESSION['username'];
$gradunits=$_SESSION['units'];
if (!$stmt = $mysqli->query("SELECT B.name, T.username, C.uid, C.cname, C.cunits, C.cgrade, shared FROM CINFO C
	INNER JOIN Class_Site CS on C.uid=CS.bid
	INNER JOIN Building B on CS.bid=B.bid
	INNER JOIN Teaches T on T.cid=C.uid
	INNER JOIN USERDB U on T.tid=U.uid
	WHERE C.username='$username'")) {
		echo "Query Failed!: (" . $mysqli->errno . ") ". $mysqli->error;
	}
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
while($row = mysqli_fetch_array($stmt))	
{
	echo "<tr>" ;
	echo "<td>" . $row['C.cname'] . "</td>";
	echo "<td>" . $row['C.cunits'] . "</td>";
	echo "<td>" . $row['C.cgrade'] . "</td>";
	echo "<td>" . $row['T.username'] . "</td>";
	echo "<td>" . $row['B.name'] . "</td>";
	echo "<td>";
	if (!$row['shared'])
	{
		echo "Not Shared </td>";
		echo "<td><form method=\"POST\" action=\"share.php\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$row['C.uid']."\">";
		echo "<input type=\"submit\" value=\"share\">";
		echo "</form> </td>";
	}
	else
	{
		echo "Shared </td>";
		echo "<td><form method=\"POST\" action=\"unshare.php\">";
		echo "<input type=\"hidden\" name=\"uid\" value=\"".$row['C.uid']."\">";
		echo "<input type=\"submit\" value=\"unshare\">";
		echo "</form> </td>";
	}
	echo "<td><form method=\"POST\" action=\"delete.php\">";
	echo "<input type=\"hidden\" name=\"uid\" value=\"".$row['C.uid']."\">";
	echo "<input type=\"submit\" value=\"delete\">";
	echo "</form> </td>";
	echo "</tr>";
	//$totalunits=$row['cunits']+$totalunits;
	$totalgp=($row['cunits']*$row['cgrade'])+$totalgp;
}
?>
</tbody>
</table>