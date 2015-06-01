<?php
error_reporting(E_ALL);
ini_set('display_errors','On');
$mysqli = new mysqli("oniddb.cws.oregonstate.edu", "harrings-db", "minstFy7WEjCWSCr", "harrings-db");
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


if(!$mysqli->query("CREATE Table CINFO(
uid INT(11) NOT NULL AUTO_INCREMENT ,
username VARCHAR(45) ,
cname VARCHAR(100) ,
cunits INT unsigned,
cgrade INT unsigned,
shared boolean NOT NULL default 0,
FOREIGN KEY (username)
      REFERENCES USERDB(username),
PRIMARY KEY (uid)
);
")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if(!$mysqli->query("CREATE Table Building(
bid INT NOT NULL AUTO_INCREMENT ,
name VARCHAR(100) ,
PRIMARY KEY (bid)

);
")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if(!$mysqli->query("CREATE Table Class_Site(
bid INT ,
cid INT ,
FOREIGN KEY (bid)
      REFERENCES Building (bid),
FOREIGN KEY (cid)
      REFERENCES CINFO (uid)	 
);
")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}

if(!$mysqli->query("CREATE Table GPA(
sid INT ,
GPA FLOAT ,
utaken INT ,
FOREIGN KEY (sid)
      REFERENCES USERDB(uid)

);
")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}






if(!$mysqli->query("CREATE Table Teaches(
tid INT ,
cid INT ,
FOREIGN KEY (tid)
      REFERENCES USERDB (uid),
FOREIGN KEY (cid)
      REFERENCES CINFO (uid)	 
);
")) {
	echo "Table creation failed: (" . $mysqli->errno . ") " . $mysqli->error;
}




?>