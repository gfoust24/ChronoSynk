<?php
	include_once($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/methods.php');
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
	
	$_SESSION['error'] = '';
	if (isset($_POST['passchange']) && isset($_POST['passchange2']))
	{
		if (strcmp($_POST['passchange'], $_POST['passchange2']) == 0)
		{
			//Change password
			$dbConnection = new mysqli("localhost", "chronoWrite", "password", "chronosynk");
			
			if (checkPassword($dbConnection, $_SESSION['username'], $_POST['currpass']))
			{
				$stmt = $dbConnection->prepare("update user set password = ? where userid = ?");
				$salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
				$pass = $salt.hash_pbkdf2("sha256", $_POST['passchange'], $salt, 1000);
				$stmt->bind_param('ss', $pass, $_SESSION['userID']);
				echo $_POST['passchange'];
				$executed = $stmt->execute();
				if ($executed)
					$_SESSION['passChange'] = 1;
			}
			else
			{
				$_SESSION['error'] = 'Incorrect password';
			}
		}
		else
		{
			$_SESSION['error'] = 'Passwords do not match';
		}
	}
	if (isset($_POST['settings']))
	{
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		//start prepared statement
		$stmt = $dbConnection->prepare("update user set bio = ? where userid = ?");
		//bind variables
		$stmt->bind_param('si', $_POST['bio'], $_SESSION['userID']);
		$executed = $stmt->execute();
		if ($executed)
			$_SESSION['bioChange'] = 1;
		//get the result
		//$result = $stmt->get_result();
		//turn into array
		//$row = $result->fetch_assoc();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>ChronoSynk - Settings</title>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');
	
		if (isset($_SESSION['userID']))
		{
			echo '
				<form action="./" method="post">
					<input type="password" name="currpass" placeholder="Current Password" /><br/>
					<input type="password" name="passchange" placeholder="Password"/><br/>
					<input type="password" name="passchange2" placeholder="Confirm Password"/><br/>
				';
				echo $_SESSION['error'];
				if (isset($_SESSION['passChange']))
					echo 'Password changed successfully';
				unset($_SESSION['passChange']);
				unset($_SESSION['error']);
				echo '<button type="submit">Change Password</button></form><br/>
					<form action="./" method="post">
						<textarea name="bio">';
				
				$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
				//start prepared statement
				$stmt = $dbConnection->prepare("select bio from user where userID = ?");
				//bind variables
				$stmt->bind_param('s', $_SESSION['userID']);
				$executed = $stmt->execute();
				//get the result
				$result = $stmt->get_result();
				//turn into array
				$row = $result->fetch_assoc();
				
				echo $row['bio'].'</textarea>
					<input type="hidden" name="settings" value="1" /><br/>
					<button type="submit">Submit</button>
				</form>';
				if (isset($_SESSION['bioChange']))
					echo 'Bio saved successfully';
				unset($_SESSION['bioChange']);
		}
		else
		{
			echo '<p>Log in to view this page</p>';
		}
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');
	?>
</body>

</html>
