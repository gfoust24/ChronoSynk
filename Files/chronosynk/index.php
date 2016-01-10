<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>ChronoSynk</title>
<script type="text/javascript">
	function validateUsername(input)
	{
		var usernameRegex = /^[a-zA-Z0-9_-]{3,20}$/;
		var valid = input.value.match(usernameRegex);
		if (valid)
			document.getElementById('usernameError').innerHTML = '';
		else
			document.getElementById('usernameError').innerHTML = '<img alt="" src="/chronosynk/media/images/x.png">letters, numbers, -, and _ 3 - 20 characters';
	};
	
</script>
</head>

<body>
	<?php
		if ($_POST)
		{
			//register code (processing)
			if (isset($_POST['register']))
			{
				$valid = true;
				$len = strlen($_POST['rusername']);
				$_SESSION['registerError'] = '';
				if ($len > 20 || $len < 3)
				{
					$_SESSION['registerError'] .= 'Username must be 3-20 characters<br/>';
					$valid = false;
				}
				if (!preg_match("/^[a-zA-Z0-9_-]{3,20}$/", $_POST['rusername']))
				{
					$_SESSION['registerError'] .= 'Username can only contain a-z, 0-9, -, and _<br/>';
					$valid = false;
				}
				if (!filter_var($_POST['remail'], FILTER_VALIDATE_EMAIL))
				{
					$_SESSION['registerError'] .= 'Email is not valid<br/>';
					$valid = false;
				}
				if (strcmp($_POST['rpassword1'], $_POST['rpassword2']) != 0)
				{
					$_SESSION['registerError'] .= 'Passwords do not match<br/>';
					$valid = false;
				}
				else if (strlen($_POST['rpassword1']) < 6)
				{
					$_SESSION['registerError'] .= 'Password must be 6+ characters<br/>';
					$valid = false;
				}
				if (empty($_POST['policies']))
				{
					$_SESSION['registerError'] .= 'You must agree to ChronoSynk\'s Policies<br/>';
					$valid = false;
				}
				//connect to DB
				$dbConnection = new mysqli("localhost", "chronoRead", "password", "chronosynk");
				//start prepared statement
				$stmt = $dbConnection->prepare("select * from user where username = ? or email = ?");
				//bind variables
				$stmt->bind_param('ss', $_POST['rusername'], $_POST['remail']);
				//execute prepared statement
				$executed = $stmt->execute();
				$result = $stmt->get_result();
				$usertaken = false;
				$emailtaken = false;
				while ($row = $result->fetch_assoc())
				{
					if (strcmp($row['username'], $_POST['rusername']) == 0)
					{
						$usertaken = true;
					}
					if (strcmp($row['email'], $_POST['remail']) == 0)
					{
						$emailtaken = true;
					}
				}
				if ($emailtaken)
				{
					$_SESSION['registerError'] .= 'Email is already registered<br/>';
					$valid = false;
				}
				if ($usertaken)
				{
					$_SESSION['registerError'] .= 'Username taken<br/>';
					$valid = false;
				}
				if ($valid)
				{
					//connect to DB
					$dbConnection = new mysqli("localhost", "chronoWrite", "password", "chronosynk");
					//start prepared statement
					$stmt = $dbConnection->prepare("INSERT INTO user (username, email, password, emailVerify) VALUES (?, ?, ?, ?)");
					//initialize variables
					$rusername = $_POST['rusername'];
					$remail = $_POST['remail'];
					//generate salt
					$salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
					//salt password and hash 1000 times then salt again
					$hashedpassword = $salt.hash_pbkdf2("sha256", $_POST['rpassword1'], $salt, 1000);
					//bind variables
					$emailVerify = md5(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
					$stmt->bind_param('ssss', $rusername, $remail, $hashedpassword, $emailVerify);
					//execute prepared statement
					$executed = $stmt->execute();
					
					//if statement was successful. log new user in by setting session variables (used by header)
					if ($executed)
					{
						echo 'executed';
						$_POST['username'] = $_POST['rusername'];
						$_POST['password'] = $_POST['rpassword1'];
						$_POST['login'] = 1;
						$headers = 'Content-type: text/html; charset=utf-8'."\r\n";
						$headers .= "From: ChronoSynk do-not-reply@chronosynk.com";
						
						$mailed = mail('gaf3@pct.edu',//$_POST['remail'],
							'Welcome To ChronoSynk",
							"Click <a href="127.0.0.1/chronosynk/email/?x='.$emailVerify.'">here to verify your email or type this URL into your browser: 127.0.0.1/chronosynk/email/?x='.$emailVerify.'',
							$headers);
					}
				}
			}
		}

		include('/includes/header.php');
		//if user is logged in
		if(isset($_SESSION['username']))
		{
			$results = false;
			//connect to DB
			$dbConnection = new mysqli("localhost", "chronoRead", "password", "chronosynk");
			//prepare statement
			//find new friends, new sessions, new comments, updated sessions?, new participants, 
			
			//get comments
			$stmt = $dbConnection->prepare('
				select commentID, text, u1.username as poster, u2.username as target, u1.userid as posterID, u2.userid as targetID, comment.timestamp, "comment"
				from comment
				join user as u1
				on comment.userid = u1.userid
				join user as u2
				on comment.parentid = u2.userid
				where comment.userid in
					(select user.userid
					from user
					where user.userid in
						(select toid
				    	from friend
				    	where (fromid = ?) and status = 1)
					or user.userid in
						(select fromid
				    	from friend
				    	where toid = ? and status = 1))
				order by comment.timestamp desc');
			$stmt->bind_param('ii', $_SESSION['userID'], $_SESSION['userID']);
			$executed = $stmt->execute();
			$result = $stmt->get_result();
			$i = 0;
			//store comments
			while ($row = $result->fetch_assoc())
			{
				$results[$i] = $row;
				$i++;
			}
			
			//get sessions
			$stmt = $dbConnection->prepare('
				select session.timestamp, title, username, "session", userid, sessionid
				from session
				join user
				on user.userid = session.leader
			    where user.userid in
			    	(select user.userid
					from user
					where user.userid in
						(select toid
			    		from friend
			    		where (fromid = ?) and status = 1)
					or user.userid in
						(select fromid
			    		from friend
			    		where toid = ? and status = 1))
				order by session.timestamp;');
			$stmt->bind_param('ii', $_SESSION['userID'], $_SESSION['userID']);
			$stmt->execute();
			$result = $stmt->get_result();
			//store sessions
			while ($row = $result->fetch_assoc())
			{
				$results[$i] = $row;
				$i++;
			}
			
			//sort
			$sorted = false;
			//while its not sorted
			while (!$sorted)
			{
				$sorted = true;
				for ($i = 0; $i < count($results) - 1; $i++)
				{
					//if the current time is older than the next time, swap them
					if ($results[$i]['timestamp'] < $results[$i + 1]['timestamp'])
					{
						$temp = $results[$i];
						$results[$i] = $results[$i + 1];
						$results[$i + 1] = $temp;
						
						//items were swapped so array is not sorted yet
						$sorted = false;
					}
				}
			}
			
			//print activity
			for ($i = 0; $i < count($results) && $results; $i++)
			{
				echo '<div class="activity">';
				$row = $results[$i];
				if (!empty($row['comment']))
				{
					echo '<p><a href="/chronosynk/user/?user='.$row['posterID'].'">'.$row['poster'].'</a> commented on <a href="/chronosynk/user/?user='.$row['targetID'].'">'.$row['target'].'\'s</a> profile</p>
						<p>'.htmlspecialchars($row['text']).'</p>';
				}
				else if (!empty($row['session']))
				{
					echo '<a href="/chronosynk/user/?user='.$row['userid'].'">'.$row['username'].
						'</a> created the session <a href="/chronosynk/sessions/view/?session='.$row['sessionid'].'">"'.$row['title'].'"</a>';
				}
				echo '</div>';
			}
			if (!$results)
			{
				echo '<p>No recent activity</p>';
			}
			
			//print news feed items
			/*while ($row = $result->fetch_assoc())
			{
				echo '<div style="border: thin black solid;">
						<p>'.$row['poster'].' commented on '.$row['target'].'\'s profile</p>
						<p>'.htmlspecialchars($row['text']).'</p>
					</div>';
			}*/
		}
		//if user isnt logged in
		else
		{
			//register form
			if (empty($_POST['rusername'])) $_POST['rusername'] = '';
			if (empty($_POST['remail'])) $_POST['remail'] = '';
			if (empty($_SESSION['registerError'])) $_SESSION['registerError'] = '';
			
			echo '
				<div id="register" style="text-align: right">
					<h2>Register</h2>
					<form method="POST" style="text-align:right">
						<label class="registerInput" maxlength="20" id="usernameError">'.$_SESSION['registerError'].'</label><br/>
						<input class="registerInput" name="rusername" oninput="validateUsername(this);" id="rusername" placeholder="Username" type="text" value="'.$_POST['rusername'].'"><br/>
						<input class="registerInput" maxlength="50" name="remail" id="remail" placeholder="Email" type="text" value="'.$_POST['remail'].'"><br/>
						<input class="registerInput" name="rpassword1" id="rpassword1" placeholder="Password" type="password"><br/>
						<input class="registerInput" name="rpassword2" id="rpassword2" placeholder="Re-Enter Password" type="password"><br/>
						<input name="policies" type="checkbox">I Agree to Chronosynk\'s <a href="/chronosynk/policies/">policies</a><br/>
						<input name="register" type="hidden" value="1"/>
						<button type="submit">Register</button>
					</form>
				</div>
				<div id="welcomeImageDiv"><img id="welcomeImage" alt="Welcome Image" src="media/images/welcome_image.jpg"/></div>';
			unset($_SESSION['registerError']);
		}
		include('/includes/footer.php');
	?>
</body>
</html>
