<?php
session_start();

$dbcon = new mysqli('localhost', 'S0267455', 'New2016', 'S0267455');

if(ISSET($_SESSION['user']))
{
	$result = $dbcon->query("SELECT u_username FROM Users");
	
	if ($result->num_rows > 1)
	{
		echo "<form action=\"send.php\" method=\"POST\">";
		while($row = $result->fetch_object())
		{
			if($row->u_username == $_SESSION['user'])
				continue;
			echo "<input type=\"checkbox\" name=\"people[]\" value=\"$row->u_username\"> $row->u_username";

		}
	
		echo "<br><input type=\"text\" name=\"subject\"> Subject<br><br>
				<textarea rows=\"6\" cols=\"50\" name=\"message\"></textarea><br>";

		
		echo "<input type=\"submit\" value=\"Send\">
				</form>";
	}
	else 
	{
		echo 'Sorry, you\'re the only user. ';
	}
}

echo "<br><a href=\"login.php\">Go back</a>";
?>
