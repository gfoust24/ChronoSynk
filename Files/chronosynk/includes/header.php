<link rel="stylesheet" type="text/css" href="/chronosynk/styles.css"/>
<link rel="shortcut icon" href="http://127.0.0.1/chronosynk/favicon2.ico" type="image/x-icon" />
<div id="headerWrapper">
<div id="header">
	<a style=" display: inline" href="/chronosynk/"><img id="logo" src="/chronosynk/favicon2.ico" alt="ChronoSynk Logo"></a>
	
	<?php
		echo getHostByName(getHostName());
		include_once($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/methods.php');
		//login code
		if (isset($_POST['login']))
		{
			//connect to DB
			$dbConnection = new mysqli("localhost", "chronoRead", "password", "chronosynk");
						
			$login = checkPassword($dbConnection, $_POST['username'], $_POST['password']);
			if ($login[0])
			{
				//if passwords match, log user in
				$_SESSION['username'] = $login[2];
				$_SESSION['userID'] = $login[0];
				$_SESSION['permission'] = $login[1];
				//if ($row['']) //TODO if admin set $_session['admin']
			}
			else
			{
				//if passwords do not match, make sure username and id is unset (logged out)
				session_unset();
				session_destroy();
				$_SESSION['loginFailed'] = 1;
			}
		}
					
		//logout
		if (isset($_POST['logout']))
		{
			//unset session variables
			session_unset();
			//destroy the session 
			session_destroy();
			unset($_POST);
			header('Location: /chronosynk/');
		}
		
		if (empty($_SESSION['userID']))
		{
			$loginFailed = "";
			if (isset($_SESSION['loginFailed']))
			{
				$loginFailed = "Invalid username or password";
				unset($_SESSION['loginFailed']);
			}
			//login form
			$get = '';
			if ($_GET)
			{
				$get = '?';
				foreach ($_GET as $key => $value)
				{
					$get .= $key.'='.$value.'&';
				}
				$get = substr($get, 0, strlen($get) - 1);
			}
			echo '
				<div id="login">
					<form method="POST" action="./'.$get.'">
						<input placeholder="Username" name="username" type="text">
						<input placeholder="Password" name="password" type="password">
						<input name="login" type="hidden" value=1>
						<button type="submit">Login</button>
						<br/>
						'.$loginFailed.'
					</form>
					<br><a href="/chronosynk/recovery/">Account Recovery</a>
				</div>';
			
		}
		else
		{
			//logout form
			echo '
				<div id="logout"><form action="./" method="POST">
					Welcome, <a href="/chronosynk/user/?user='.$_SESSION['userID'].'">'.$_SESSION['username'].'</a>
					<input style="display: none;" name="logout" value=1>
					<button type="submit">Logout</button>
				</form>
				<br><a href="/chronosynk/settings/">Settings</a>
				</div>';
		}
		
	?>
	<div id="navigation">
		<ul>
			<li><a href="/chronosynk/">Home</a></li>
			<li><a href="/chronosynk/sessions/">Sessions</a></li>
			<li><a href="/chronosynk/user/">Users</a></li>
		</ul>
	</div>
</div>
</div>
<div class="clear"></div>
<div id="wrapper">