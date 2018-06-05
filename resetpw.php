<?php
$username  = $_POST['username'];
$currentpw = $_POST['currentpw'];
$newpw = $_POST['newpw'];

$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');
$userx = $dbcon->real_escape_string($username);

$result = $dbcon->query("SELECT u_username, u_passhash, u_email, u_salt FROM Users WHERE u_username='$userx'");
$user_obj = $result->fetch_object();

if($user_obj)
{
	$passhash = SHA1($user_obj->u_salt . $currentpw);
	
	if($passhash == $user_obj->u_passhash)
	{
		$new_salt = randomString();
		$new_pass = SHA1($new_salt . $newpw);
		
		$new_saltx = $dbcon->real_escape_string($new_salt);
		$new_passx = $dbcon->real_escape_string($new_pass);
		
		$dbcon->query("UPDATE Users SET u_newpass='$new_passx', u_newsalt='$new_saltx' WHERE u_username='$userx'");
		
		mail($user_obj->u_email, 'Reset password', 
			"http://weblab.salemstate.edu/~S0267455/Bulletin/resetpwverif.php?username=$username" . "&pp=$passhash" . "&np=$new_pass");
		
		echo 'To finish resetting your password, follow the link sent to this account\'s email address. ';
		
	}
	else
	{
		echo 'Incorrect password';
	}
}
else
{
	echo "User $username does not exist";
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