<?php
session_start();

if(ISSET($_SESSION['user']))
{
	$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');
	
	$sender = $dbcon->real_escape_string($_SESSION['user']);
	$subject = $dbcon->real_escape_string($_POST['subject']);
	$text = $dbcon->real_escape_string($_POST['message']);
	
	$sendtos = array();
	foreach($_POST['people'] as $value)
		$sendtos[] = $value;
	
	$result = $dbcon->query("SELECT u_userid FROM Users WHERE u_username='$sender'");
	$user_obj = $result->fetch_object();
	
	$dbcon->query("INSERT INTO Messages (m_fromid, m_sent, m_subject, m_text)
					VALUES ('$user_obj->u_userid', NOW(), '$subject', '$text')");
	
	$last_id = $dbcon->insert_id;
	
	foreach($sendtos as $target)
	{
		
		$targetx = $dbcon->real_escape_string($target);
		
		$temp = $dbcon->query("SELECT u_userid FROM Users WHERE u_username='$targetx'");
		$temp_obj = $temp->fetch_object();
		$targetid = $temp_obj->u_userid;
		
		$dbcon->query("INSERT INTO SendTos (s_msgid, s_toid) 
						VALUES ('$last_id', '$targetid')"); 
	}
	
	echo "Message sent!<br> 
			Go <a href=\"login.php\">Home</a><br>
			<a href=\"newmessage.php\">New Message</a>";
	
}

?>