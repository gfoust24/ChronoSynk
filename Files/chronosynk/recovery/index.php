<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Account Recovery</title>
</head>

<body>
	<?php
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');
		
		if ($_POST)
		{
			$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
			$stmt = $dbConnection->prepare('select email, userID from user where email = ?');
			$stmt->bind_param('s', $_POST['email']);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			/*// the message
			$msg = "First line of text\nSecond line of text";
			
			// use wordwrap() if lines are longer than 70 characters
			$msg = wordwrap($msg,70);
			
			// send email
			mail("someone@example.com","My subject",$msg);*/
			//check if email is registered in db. row will be empty if email is not found
			if(!empty($row))
			{
				
				$random = md5(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
				$stmt = $dbConnection->prepare('update user set recovery = ? where userID = ?');
				$stmt->bind_param('ss', $random, $row['userID']);
				$stmt->execute();
				$result = $stmt->get_result();
				//$row = $result->fetch_assoc();
				
				
				$headers = 'MIME-Version: 1.0' . "\r\n";
				$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers .= 'From: ChronoSynk contact@chronosynk.com';
				$url = 'http://127.0.0.1/chronosynk/recovery/?val='.$random.'&u='.$row['userID'].md5($row['userID'].md5($row['userID']));
				$body = 'Click here to start your account recovery: <a href="'.$url.'">here</a> or copy and paste this link into your browser: '.$url;
				$mailed = mail($_POST['email'], "ChronoSynk Account Recovery", $body, $headers);
				if ($mailed)
				{
					echo 'An email has been sent with recovery instructions.<br/>';
					unset($_POST);
				}
			}
			else
			{
				echo 'unregistered email';
			}
			
			if (isset($_POST['passchange']))
			{
				//Change password
				$dbConnection = new mysqli("localhost", "chronoWrite", "password", "chronosynk");
				//UPDATE table_name SET column1=value1,column2=value2 WHERE some_column=some_value;
				$stmt = $dbConnection->prepare("update user set password = ? where recovery = ?");
				$salt = mcrypt_create_iv(16, MCRYPT_DEV_URANDOM);
				$pass = $salt.hash_pbkdf2("sha256", $_POST['password'], $salt, 1000);
				$stmt->bind_param('ss', $pass, $_POST['passchange']);
				echo $_POST['passchange'];
				$executed = $stmt->execute();
				header('Location: /chronosynk/');
			}
		}
		else if ($_GET)
		{
			if (isset($_GET['u']))
			{
				$id = substr($_GET['u'], 0, 1);
				if (strcmp($_GET['u'], $id.md5($id.md5($id))) == 0)
				{
					$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
					$stmt = $dbConnection->prepare('select * from user where userID = ?');
					$stmt->bind_param('s', $id);
					$stmt->execute();
					$result = $stmt->get_result();
					$row = $result->fetch_assoc();
					
					if (isset($_GET['val']) && strcmp($row['recovery'], $_GET['val']) == 0)
					{
						echo '<form action="./" method="POST">
								<input type="password" placeholder="New Password" name="password"/>
								<input name="passchange" value='.$row['recovery'].' type="hidden"/>
								<button type="submit">Submit</button>
							</form>';
					}
				}
			}
		}
		else
		{
			echo '<form action="./" method="post">
				<input name="email" type="text" placeholder="Email"/>
				<button type="submit">Submit</button>
			</form>';

		}
	
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');
	?>
</body>

</html>
