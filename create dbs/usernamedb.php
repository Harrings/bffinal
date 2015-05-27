<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", "", "harrings-db");
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}
if(!$mysqli->query("CREATE Table USERDB(
uid INT(11) NOT NULL AUTO_INCREMENT ,
username VARCHAR(45) ,
password VARCHAR(100) ,
units INT unsigned,
secretnumber INT unsigned,
teacher boolean NOT NULL default 0,
PRIMARY KEY (uid)
);
")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}
?>