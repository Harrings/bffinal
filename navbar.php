<link rel="stylesheet" href="main.css" type="text/css" />
  <div>
  <h1>Course Tracker</h1>
	<nav>
<?php

	echo "<a href=\"edit.php\">$_SESSION[username]</a>";
?>
	<p> </p>
	<a href="switch.php">View Records</a>
	<p> </p>
	<a href="logout.php"> Log Out </a>
  </div>