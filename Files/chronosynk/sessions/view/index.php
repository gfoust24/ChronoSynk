<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>ChronoSynk - View Session</title>
</head>

<body>
	<?php
		include $_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php';
		
		//database connection
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		
		if (isset($_GET['joinSession']))
		{
			//add user to session
			//TODO make sure user isnt already in session or that session is full etc
			$stmt = $dbConnection->prepare('insert into participant (sessionID, userID) values (?, ?)');
			$stmt->bind_param('ii', $_GET['session'], $_SESSION['userID']);
			$stmt->execute();
			$result = $stmt->get_result();
			//$session = $result->fetch_assoc();
			header('Location: ./?session='.$_GET['session']);
		}
		else if (isset($_GET['leaveSession']))
		{
			//remove user from session
			//TODO make sure user is in session
			$stmt = $dbConnection->prepare('delete from participant where sessionID = ? and userID = ?');
			$stmt->bind_param('ii', $_GET['session'], $_SESSION['userID']);
			$stmt->execute();
			$result = $stmt->get_result();
			//$session = $result->fetch_assoc();
			header('Location: ./?session='.$_GET['session']);
		}
		
		if (!empty($_GET['session']))
		{
			
			//get session information
			$stmt = $dbConnection->prepare('
				select *
				from session
				join user
				on leader = userID
				where ((session.leader in
				   	(select user.userid
					from user
					where user.userid in
						(select toid
				   		from friend
				   		where (fromid = ?) and status = 1)
					or user.userid in
						(select fromid
				    	from friend
				    	where toid = ? and status = 1)) and visibility = 1)
				or visibility = 0
				or leader = ?)
				and sessionid = ?');
			$stmt->bind_param('iiii', $_SESSION['userID'], $_SESSION['userID'], $_SESSION['userID'], $_GET['session']);
			$stmt->execute();
			$result = $stmt->get_result();
			$session = $result->fetch_assoc();
			
			if (!$session)
			{
				echo '<p>You do not have permission to view this session<p>';
			}
			else
			{
				//get participants of session
				//$stmt = $dbConnection->prepare('select * from participant where sessionID = ? order by username');
				$stmt = $dbConnection->prepare('
					select participant.userID, participant.timestamp, username
					from participant
					inner join user
					on participant.userID = user.userID
					where sessionID = ?
					order by username');
	
				$stmt->bind_param('i', $_GET['session']);
				$stmt->execute();
				$result = $stmt->get_result();
				
				echo '
					<div id="participants">
						<h3>Participants</h3>';
				
				$participating = false;
				$participants = '';
				while ($participant = $result->fetch_assoc())
				{
					$participants .= '<p><a href="/chronosynk/user/?user='.$participant['userID'].'" class="participant">'.$participant['username'].'</a></p>';
					if (isset($_SESSION['userID']) && $participant['userID'] == $_SESSION['userID'])
					{
						$participating = true;
					}
				}
				
				//if user is logged in, show leave/join button
				if (isset($_SESSION['userID']))
				{
					echo '<form action="./">
							<input name="session" type="hidden" value="'.$_GET['session'].'"/>';
					//if they are already in the session, show the leave button
					if ($participating)
					{
						echo '<input name="leaveSession" type="hidden" value="1"/>
							<button type="submit">Leave</button>';
					}
					//if they are not in the event, show the join button
					else
					{
						echo '<input name="joinSession" type="hidden" value="1"/>
							<button type="submit">Join</button>';
					}
					echo '</form>';
				}
				
				echo $participants;
				
				echo '</div>';
							
				echo '<div id="profileLeft">
						<h2>'.$session['title'].'</h2>
						<p>Leader: <a href="/chronosynk/user/?user='.$session['userID'].'">'.$session['username'].'</a></p>
						<p>Description:</p>
						<p>'.htmlspecialchars($session['description']).'</p>
					</div>';
				
				//if user is leader, print link to edit session
				if (isset($_SESSION['userID']) && $session['leader'] == $_SESSION['userID'])
				{
					echo '<a href="../edit/?session='.$session['sessionID'].'">Edit Settings</a>';
				}
				
				$type = 'session';
				include $_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/commentSection.php';
			}
		}
		else
		{
			echo '<p>Session not found</p>';
		}

		include $_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php';
	?>
</body>

</html>
