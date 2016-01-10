<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
	
	if ($_SERVER['REQUEST_METHOD'] == 'GET')
	{
		if (isset($_GET['addFriend']))
		{
			$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
			$stmt = $dbConnection->prepare('insert into friend (fromid, toid) values (?, ?)');
			$stmt->bind_param('ii', $_SESSION['userID'], $_GET['user']);
			$stmt->execute();
			header('Location: ./?user='.$_GET['user']);
		}
		if (isset($_GET['cancelFriend']))
		{
			$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
			$stmt = $dbConnection->prepare('delete from friend where toid = ? and fromid = ?');
			$stmt->bind_param('ii', $_GET['user'], $_SESSION['userID']);
			$stmt->execute();
			header('Location: ./?user='.$_GET['user']);
		}
		if (isset($_GET['acceptFriend']))
		{
			$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
			$stmt = $dbConnection->prepare('update friend set status = ? where fromid = ? and toid = ?');
			$friend = 1;
			$stmt->bind_param('iii', $friend, $_GET['user'], $_SESSION['userID']);
			$stmt->execute();
			header('Location: ./?user='.$_GET['user']);
		}
		if (isset($_GET['removeFriend']))
		{
			$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
			$stmt = $dbConnection->prepare('delete from friend where toid = ? and fromid = ?');
			$stmt->bind_param('ii', $_SESSION['userID'], $_GET['user']);
			$stmt->execute();
			$stmt = $dbConnection->prepare('delete from friend where fromid = ? and toid = ?');
			$stmt->bind_param('ii', $_SESSION['userID'], $_GET['user']);
			$stmt->execute();
			header('Location: ./?user='.$_GET['user']);

		}
		
		if (isset($_GET['user']))
		{
			$dbConnection = mysqli_connect("localhost", "chronoRead", "password", "chronosynk");
			//start prepared statement
			$stmt = $dbConnection->prepare('
				select *
				from user 
				left join friend 
				on (userid = toid or userid = fromid)
					and ((fromid = ? and toid = ?)
				         or fromid = ? and toid = ?)
				where userid = ?');
			
			/* example (instead of question marks)
			select userid, username, fromid, toid, status from user 
			left join friend 
			on (userid = toid or userid = fromid)
				and ((fromid = 2 and toid = 6)
			         or fromid = 6 and toid = 2)
			where userid = 2*/
			
			//bind variables
			$stmt->bind_param('iiiii', $_SESSION['userID'], $_GET['user'], $_GET['user'], $_SESSION['userID'], $_GET['user']);
			$executed = $stmt->execute();
			//get the result
			$result = $stmt->get_result();
			//turn into array
			$_SESSION['user'] = $result->fetch_assoc();
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />

<?php
	if (isset($_SESSION['user']['username']))
	{
		echo '<title>ChronoSynk - '.$_SESSION['user']['username'].'</title>';
	}
	else
	{
		echo '<title>ChronoSynk - User</title>';
	}
?>
</head>

<body>
	<?php
		$path = $_SERVER['DOCUMENT_ROOT'].'chronosynk/';
		include($path.'includes/header.php');
		
		if (empty($_GET['user']))
		{
			//get user information
			$dbConnection = new mysqli("localhost", "chronoRead", "password", "chronosynk");
			$stmt = $dbConnection->prepare('select * from user order by timestamp desc');
			$stmt->execute();
			$result = $stmt->get_result();
			while ($user = $result->fetch_assoc())
			{
				echo '<div class="userSummary">
						<h3><a href="./?user='.$user['userID'].'">'.$user['username'].'</a></h3>
					</div>';
			}
		}
		else
		{
			//get friend information
			$dbConnection = new mysqli("localhost", "chronoRead", "password", "chronosynk");
			$stmt = $dbConnection->prepare('
				select fromID, u1.username as userfrom, toID, u2.username as userto
				from friend, user as u1, user as u2
				where (toid = ? or fromid = ?)
				and friend.status = 1
				and u1.userID = fromID
				and u2.userID = toID');
			$stmt->bind_param('ii', $_GET['user'], $_GET['user']);
			$stmt->execute();
			$result = $stmt->get_result();
			
			echo '<div id="friends">
					<h3><a href="./friends/?user='.$_GET['user'].'">Friends</a></h3>';
			while ($friend = $result->fetch_assoc())
			{
				echo '<p><a href="./?user=';
				if ($friend['fromID'] == $_GET['user'])
					echo $friend['toID'].'">'.$friend['userto'];
				else
					echo $friend['fromID'].'">'.$friend['userfrom'];
				echo '</a></p>';
			}
			echo '</div>';//end friends
			echo '<div id="profileLeft"><h3 style="display: inline-block;">'.$_SESSION['user']['username'].'</h3>';
			//if they are not viewing their own profile and logged in
			if (isset($_SESSION['userID']) && isset($_SESSION['user']['userID']) && $_SESSION['user']['userID'] != $_SESSION['userID'])
			{
				//friend button
				echo '<form action= "./" style="display: inline-block;">
						<input type="hidden" name="user" value="'.$_GET['user'].'"/>';
				if (is_null($_SESSION['user']['status']))
					echo '<button type="submit">Add Friend</button>
							<input type="hidden" name="addFriend"/>';
				else if ($_SESSION['user']['status'] == 0)
				{
					if ($_SESSION['user']['fromID'] == $_SESSION['userID'])
						echo '<button type="submit">Cancel Friend Request</button>
								<input type="hidden" name="cancelFriend"/>';
					else
						echo '<button type="submit">Accept Friend Request</button>
								<input type="hidden" name="acceptFriend"/>';
				}
				else if ($_SESSION['user']['status'] == 1)
					echo '<button type="submit">Remove Friend</button>
							<input type="hidden" name="removeFriend"/>';
				else
					echo 'Notify admin of invalid status';
				echo '</form>';
			}
			
			
			echo '<p>'.$_SESSION['user']['bio'].'</p></div>';
			unset($_SESSION['user']);
			$type = 'user';
			include $_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/commentSection.php';
		}
		

		include($path.'includes/footer.php');
	?>
</body>

</html>
