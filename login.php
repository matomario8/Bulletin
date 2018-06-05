<?php
session_start();

$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');
if($dbcon->connect_error) die("sql connection error $dbcon->connect_error");

if(!ISSET($_SESSION['user']))
{
	$username = $_POST["username"];
	$password = $_POST["password"];
	$userx = $dbcon->real_escape_string($username);
	
	$result = $dbcon->query("SELECT * FROM Users WHERE u_username='$userx'");
	$user_obj = $result->fetch_object();
	
	if($user_obj)
	{
		$salt = $user_obj->u_salt;
		$passhash = SHA1($salt . $password);
		$sql_hash = $user_obj->u_passhash;

		$validate = $user_obj->u_validate;
		if($validate == '')
		{

			if($passhash == $sql_hash)
			{
				$_SESSION['user'] = $username;
				header("Location:login.php");
			}
			else
			{
				echo 'Incorrect password. ';
			}
		}
		else
		{
			echo 'Account not validated. Check your email. ';
		}
	}
	else
	{
		echo 'Account does not exist. ';
	}
	
	echo "<a href=\"login.html\">Go back</a>";
}
else
{
	$username = $_SESSION['user'];
	$userx = $dbcon->real_escape_string($username);
	
	echo "Welcome! You are logged in. ";
	$dbcon->query("UPDATE Users SET u_lastlogin=NOW() WHERE u_username='$userx'");
	
	$result = $dbcon->query("SELECT u_userid FROM Users WHERE u_username='$userx'");
	$user_obj = $result->fetch_object();
	
	$sendtos_result = $dbcon->query("SELECT s_msgid FROM SendTos WHERE s_toid='$user_obj->u_userid'");
	
	echo "<table border=1>
			<th>From</th>
			<th>Sent</th>
			<th>Subject</th>
			<th>Link</th>";
	
	while($row = $sendtos_result->fetch_object())
	{
		$msg_result = $dbcon->query("SELECT m_fromid, m_sent, m_subject FROM Messages WHERE m_msgid='$row->s_msgid'");
		$msg_obj = $msg_result->fetch_object();
		
		$result = $dbcon->query("SELECT u_username FROM Users WHERE u_userid='$msg_obj->m_fromid'");
		$user_obj = $result->fetch_object();
		
		echo "
			<tr>
				<td>" . htmlspecialchars($user_obj->u_username) . "</td>
				<td>" . htmlspecialchars($msg_obj->m_sent) . "</td>
				<td>" . htmlspecialchars($msg_obj->m_subject) . "</td>
				<td>  <a href=\"display.php?messageid=$row->s_msgid\">Read Message</a></td>
			</tr>";
	}
	echo "</table>";

	echo "<br><a href=\"newmessage.php\">New Message</a><br><br>";
	echo "<a href=\"logout.php\">Log out</a>";
}

?>
