<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>ChronoSynk - Friends</title>
</head>

<body>
	<?php
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');
		
		if ($_GET)
		{
			//get accepted friend information
			$dbConnection = new mysqli("localhost", "chronoRead", "password", "chronosynk");
			$stmt = $dbConnection->prepare('
				select fromID, u1.username as userfrom, toID, u2.username as userto, status
				from friend, user as u1, user as u2
				where (toid = ? or fromid = ?)
				and u1.userID = fromID
				and u2.userID = toID');
			$stmt->bind_param('ii', $_GET['user'], $_GET['user']);
			$stmt->execute();
			$result = $stmt->get_result();
			
			
			$acceptedFriends = '';
			$requestedFriends = '';
			$unacceptedFriends = '';
			while ($friend = $result->fetch_assoc())
			{
				//if friendship is mutual, print the opposite user of the current profile
				if ($friend['status'] == 1)
					if ($friend['fromID'] == $_GET['user'])
						$acceptedFriends .= '<p><a href="../?user='.$friend['toID'].'">'.$friend['userto'].'</a></p>';
					else
						$acceptedFriends .= '<p><a href="../?user='.$friend['fromID'].'">'.$friend['userfrom'].'</a></p>';
				//if friendship is one way
				else if ($friend['status'] == 0)
					//if request is from the profile's user, add to friendship requests that the user has sent
					if ($friend['fromID'] == $_GET['user'])
						$requestedFriends .= '<p><a href="../?user='.$friend['toID'].'">'.$friend['userto'].'</a></p>';
					//if the request is from another user, add the friendship to requests that the user has received
					else
						$unacceptedFriends .= '<p><a href="../?user='.$friend['fromID'].'">'.$friend['userfrom'].'</a></p>';

			}
			echo '<div id="acceptedFriends">
					<h3>Current Friends</h3>'.
					$acceptedFriends.
				'</div>';
			
			//only print requests and invitations if they belong to logged in user
			if ($_GET['user'] == $_SESSION['userID'])
			{
				echo '<div id="acceptedFriends">
					<h3>Friend Requests Sent</h3>'.
					$requestedFriends.
				'</div>';
				
				echo '<div id="acceptedFriends">
					<h3>Friend Requests Received</h3>'.
					$unacceptedFriends.
				'</div>';
			}
			
		}
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');
	?>
</body>

</html>
