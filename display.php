<?php
session_start();

if(ISSET($_SESSION['user']))
{
	$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');
	
	$userx = $dbcon->real_escape_string($_SESSION['user']);
	$result = $dbcon->query("SELECT u_userid FROM Users WHERE u_username='$userx'");
	$usrid_obj = $result->fetch_object();
	
	$msgid = $_GET['messageid'];
	$result = $dbcon->query("SELECT s_readit FROM SendTos 
							WHERE s_msgid='$msgid' AND s_toid='$usrid_obj->u_userid'");
	$sendtos_obj = $result->fetch_object();
	
	if($sendtos_obj)
	{
		$result = $dbcon->query("SELECT m_fromid, m_sent, m_subject, m_text FROM Messages WHERE
						m_msgid='$msgid'");
		$msg_obj = $result->fetch_object();
		
		$result = $dbcon->query("SELECT u_username FROM Users WHERE u_userid='$msg_obj->m_fromid'");
		$from_obj = $result->fetch_object();
		
		
		echo "From: $from_obj->u_username<br>
				Sent: $msg_obj->m_sent<br>
				Subject: $msg_obj->m_subject<br><br>
				$msg_obj->m_text";


		if($sendtos_obj->s_readit == null)
		{
			$dbcon->query("UPDATE SendTos SET s_readit=NOW() 
							WHERE s_msgid='$msgid' AND s_toid='$usrid_obj->u_userid'");
		}
		
		echo "<br><a href=\"login.php\">Go back</a>";
	}
	else
	{
		echo 'This isn\'t your message to read';
	}
} 
else
{
	echo "You are not <a href=\"login.html\">logged in</a>";
}
?>