<?php

if(!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["email"]))
{
	$username = $_POST["username"];
	$email = $_POST["email"];
	$password = $_POST["password"];
	
	if(isValid($username))
	{
		$dbcon = new mysqli('localhost', 'root', 'Bulletin', '');
		if($dbcon->connect_error) die("sql connection error $dbcon->connect_error");
		
		$userx = $dbcon->real_escape_string($username);

		$result = $dbcon->query("SELECT * FROM Users WHERE u_username='$userx'");
		if($result->num_rows == 0) //if user doesn't exist
		{
			
			$salt = randomString();
			$passx = $dbcon->real_escape_string(SHA1($salt . $password));
			$saltx = $dbcon->real_escape_string($salt);
			$emailx = $dbcon->real_escape_string($email);
			$validate = $dbcon->real_escape_string(randomString());
			
			
			$dbcon->query("INSERT INTO Users(u_username, u_passhash, u_email, u_validate, u_salt) 
									VALUES('$userx', '$passx', '$emailx', '$validate', '$saltx')");
			
			mail($email, 'Validate your account', 
			"http://weblab.salemstate.edu/~S0267455/Bulletin/verification.php?username=$username" . "&validate=$validate");
			
			echo "Validation email has been sent to $email. ";
			
		}
		else
		{
			echo "Username $username taken. <a href=\"register.html\">Go Back</a>";
		}
		

	}
	else
	{
		echo "Invalid characters in username. <a href=\"register.html\">Go Back</a>";
	}
}
else
{
	echo"<a href=\"register.html\">Go Back</a> and fill in all the fields.";
}




function isValid($str) {
    return !preg_match('/[^A-Za-z0-9.#\\-$]/', $str);
}

function randomString()
{
	$char_sequence = 'abcdefghijklmnopqrstuvwxyz1234567890';
	$randomseq = '';
	for($cnt=0; $cnt<20; $cnt++)
	{
		$randomseq = $randomseq . substr($char_sequence, rand(0,35), 1);
	}
	return $randomseq;
}
?>