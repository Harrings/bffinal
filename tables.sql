CREATE Table USERDB(
uid INT(11) NOT NULL AUTO_INCREMENT ,
username VARCHAR(45) ,
password VARCHAR(100) ,
units INT unsigned,
secretnumber INT unsigned,
teacher boolean NOT NULL default 0,
PRIMARY KEY (uid)
);




CREATE Table CINFO(
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




CREATE Table Building(
bid INT NOT NULL AUTO_INCREMENT ,
name VARCHAR(100) ,
PRIMARY KEY (bid)

);




CREATE Table Class_Site(
bid INT ,
cid INT ,
FOREIGN KEY (bid)
      REFERENCES Building (bid),
FOREIGN KEY (cid)
      REFERENCES CINFO (uid)	 
);




CREATE Table GPA(
sid INT ,
GPA FLOAT ,
utaken INT ,
FOREIGN KEY (sid)
      REFERENCES USERDB(uid)

);









CREATE Table Teaches(
tid INT ,
cid INT ,
FOREIGN KEY (tid)
      REFERENCES USERDB (uid),
FOREIGN KEY (cid)
      REFERENCES CINFO (uid)	 
);


