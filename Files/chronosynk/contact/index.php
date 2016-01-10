<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
	
	$contactError = '';
	if ($_POST)
	{
		$valid = true;
		if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
		{
			$contactError .= 'Invalid Email<br/>';
			$valid = false;
		}
		if (empty($_POST['message']))
		{
			$contactError .= 'Please type a message<br/>';
			$valid = false;
		}
		if ($valid)
		{
			$headers = 'MIME-Version: 1.0' . "\r\n";
			$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
			$headers .= 'From: ChronoSynk Help contact@chronosynk.com';
			$body = 'Thank you for contacting ChronoSynk. The following message was recieved:<br/><br/><p style="background-color: #D4D4D4; padding: 10px;">'.$_POST['message'].'</p><br/><br/>We will get back to you as soon as possible.';
			$mailed = mail($_POST['email'], "ChronoSynk Contact", $body, $headers);
			if ($mailed)
			{
				$contactError = 'Your message has been sent.<br/>';
				unset($_POST);
			}
			else
				$contactError = 'Something bad happened. Retry sending.<br/>';
		}
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
</head>

<body>
<?php include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');?>
	<p>Feel free to look at our <a href="/chronosynk/help/">help</a> page for a guide on how to use the website and frequently asked questions.<br/>
	Also feel free to contact us with any issues, concerns, or comments.</p>
	<form action="./" method="post">
		<label><?php echo $contactError ?></label>
		<input type="text" name="email" placeholder="E-mail" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>"/><br/>
		<input type="text" name="username" placeholder="Username (Optional)" value="<?php if(!empty($_POST['username'])) echo $_POST['username'] ?>"/><br/>
		<textarea name="message" maxlength="5000" placeholder="Message" ><?php if(!empty($_POST['message'])) echo $_POST['message'] ?></textarea><br/>
		<button type="submit">Send</button>
	</form>
<?php include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');?>
</body>

</html>
