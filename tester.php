<?php

$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');


$userx = $dbcon->real_escape_string('ss');
$passx = $dbcon->real_escape_string('gggggggggggggggggggggggggggggggggggggggg');
$emailx = $dbcon->real_escape_string('the@hotmail.com');
$validate = $dbcon->real_escape_string('hhhhhhhhhhhhhhhhhhhh');
$salt = $dbcon->real_escape_string('uuuuuuuuuuuuuuuuuuuu');

//$result = $dbcon->query("SELECT * FROM Users WHERE u_username='jim'");
//$user_obj = $result->fetch_object();
//echo $user_obj->u_username;

$thenum = $dbcon->query("SELECT `AUTO_INCREMENT` FROM  INFORMATION_SCHEMA.TABLES
															WHERE  TABLE_SCHEMA = 'S0267455' AND TABLE_NAME = 'Messages'");

$obj = $thenum->fetch_object();
echo $obj->AUTO_INCREMENT;
/*$dbcon->query("INSERT INTO Users(u_username, u_passhash, u_email, u_validate, u_salt) 
				VALUES('$userx', '$passx', '$emailx', '$validate', '$salt')");*/

?>