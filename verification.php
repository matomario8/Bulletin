<?php

if(!empty($_GET['username']) && !empty($_GET['validate']))
{
	$username = $_GET['username'];
	$validate = $_GET['validate'];
	
	$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');
	if($dbcon->connect_error) die("sql connection error $dbcon->connect_error");
	
	
	$userx = $dbcon->real_escape_string($username);
	
	$result = $dbcon->query("SELECT * FROM Users WHERE u_username='$userx'");
	$user_object = $result->fetch_object();
	
	if($user_object->u_validate == $validate)
	{
		$dbcon->query("UPDATE Users SET u_validate='' WHERE u_username='$userx'");
		echo 'Validation was successful. ';
	}
	elseif($user_object->u_validate == '')
	{
		echo "User $username has already been validated. ";
	}
	else
	{
		echo 'User doesn\'t exist. ';
	}
}
echo "Log in <a href=\"login.html\">HERE</a>";
				  
?>