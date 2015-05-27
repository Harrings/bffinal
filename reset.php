<?php
ob_start(); //from stack overflow
include 'pass.php';
error_reporting(E_ALL);
ini_set('display_errors','On');
$error=0;
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", $pass, "harrings-db");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Reset Password</title>
  <link rel="stylesheet" href="main.css" type="text/css" />
  <div>
  <h1>Course Tracker</h1>
  </div>
</head>
<body>
<section>
<?php
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if (($_POST["username"]==null))
{
	echo "<p>Error to edit an account it must have a name click <a href=\"index.php\">here</a> to return to login screen</p>";
}
else if (($_POST["password"]==null))
{
	echo "<p>Error to edit an account you must have a new password click <a href=\"index.php\">here</a> to return to login screen</p>";
}
else if (($_POST["secretnumber"]==null))
{
	echo "<p>Error to edit an account you must enter a secret number click <a href=\"index.php\">here</a> to return to login screen</p>";
}
else
{
	$name=$_POST["username"];
	$category=$_POST["password"];
	$secret=$_POST["secretnumber"];
	if (!($stmt = $mysqli->prepare("SELECT secretnumber FROM USERDB WHERE username=? "))) {
		 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
		 $error=1;
	}
	if (!$stmt->bind_param("s", $name)) {
		echo "Binding parameters failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	if (!$stmt->execute()) {
		//echo "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
		$error=1;
	}
	$stmt->bind_result($test);

    $stmt->fetch();
	$stmt->close();
	if ($error==1||$test==null)
	{
		echo "<p>Username you entered was invalid click <a href=\"index.php\">here</a> to return to login page</p>";
	}
	else if ($test==$secret)
	{
		if (!($stmt = $mysqli->prepare("UPDATE USERDB SET password=? WHERE username=? and secretnumber=?"))) {
			 echo "Prepare failed: (" . $mysqli->errno . ") " . $mysqli->error;
			 $error=1;
		}
		if (!$stmt->bind_param("ssi", $category, $name, $secret)) {
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
			echo "<p>Password reset success click <a href=\"index.php\">here</a> to return to login page</p>";
		}
		else
		{
			echo "<p>Error in resetting passowrd click <a href=\"index.php\">here</a> to return to login page</p>";
		}
	}
	else
	{
		echo "<p>Secret number entered was wrong click <a href=\"index.php\">here</a> to return to login page</p>";
	}
	
}
?>
</section>
</body>
</html>