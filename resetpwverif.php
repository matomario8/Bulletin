<?php
$username = $_GET['username'];
$passhash = $_GET['pp'];
$new_pass = $_GET['np'];

$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');
$userx = $dbcon->real_escape_string($username);

$result = $dbcon->query("SELECT u_passhash, u_newpass, u_newsalt FROM Users WHERE u_username='$userx'");
$user_obj = $result->fetch_object();

if(($passhash == $user_obj->u_passhash) && ($new_pass == $user_obj->u_newpass))
{
	$dbcon->query("UPDATE Users SET u_passhash='$user_obj->u_newpass', u_salt='$user_obj->u_newsalt' WHERE u_username='$userx'");
	
	echo 'Password successfully updated.';
}
echo "<br><a href=\"index.html\">Home</a>";
?>