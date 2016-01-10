<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
</head>

<body>
	<?php include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php'); ?>
	
	<div id="helpDiv">
	
	
	<p>Having trouble using the site? Look no further! If you have other questions please read our <a href="/chronosynk/faq/">FAQ</a> or please feel free to <a href="/chronosynk/contact/">contact</a> us</p>
	
	<img style="float: right;" alt="home" src="/chronosynk/media/images/help-home.png" />
	<p>The red outlined section to the right is the navigation bar. You can easily go to main parts of the site by clicking these links. Home will take you to your activity feed
		if logged in. Session will display available meetups (referred to as sessions) along with a search menu and, if logged in, a form to create new sessions. 
		Users will provide you a list of users so you can find others.<br/>
		The orange section is where you can log in or recover your password if necessary.<br/>
		The yellow section is where you can register a new account if you do not yet have one.<br/>
		The blue section is the footer navigation that contains information the site such as who we are, how to use the site, how to contact us, and our policies.</p>
	<div class="clearDiv"></div>
	<br/><br/>
	<img style="float: left;" alt="home" src="/chronosynk/media/images/help-feed.png" />
	<p>After logging in you will be presented with this screen, your activity feed. You can log out in the red section as well as go to your profile or settings page.<br/>
		The orange section will display activity of your friends and things you might be interested in.</p>
	
	<div class="clearDiv"></div>
	
	<img style="float: right;" alt="home" src="/chronosynk/media/images/help-sessions.png" />
	<p>The sessions page looks like this when logged in. The red section will allow you to narrow down the sessions that are displayed.<br/>
		If you are logged in, you can create a new session in the orange area.<br/>
		The yellow area contains active sessions and some information about them. Clicking on the title of a session will bring you to another page with more information.</p>
	<div class="clearDiv"></div>
	<br/><br/>
	<img style="float: left;" alt="home" src="/chronosynk/media/images/help-session-view.png" />
	<p>This is the page you will see when viewing a session. You will see more information such as who is participating, comments, and a longer description.
		If you are the session creator, you will have the option to edit the session settings.</p>
	<div class="clearDiv"></div>
	
	<img style="float: right;" alt="home" src="/chronosynk/media/images/help-session-edit.png" />
	<p>Here you can make any changes to your session. If you no longer need your session, type delete into the field next to the delete button and click delete. 
		You can return back to the session view with the link at the bottom of the form.</p>
	<div class="clearDiv"></div>
	<br/><br/>
	<img style="float: left;" alt="home" src="/chronosynk/media/images/help-profile.png" />
	<p>You can get to someone's profile by clicking their name almost anywhere you see it. Here you can request them as a friend, see their profile biography, leave a comment, and view their friends.</p>
	<div class="clearDiv"></div>

	<img style="float: right;" alt="home" src="/chronosynk/media/images/help-settings.png" />
	<p>On the settings page, you can change your password and update your profile biography.</p>
	<div class="clearDiv"></div>
	<br/><br/>
	
	<p>These are the main features of ChronoSynk but there is more and we plan on adding to the site<br/>We hope you enjoy your stay at ChronoSynk!</p>

	
	</div>
	<?php include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php'); ?>
</body>

</html>
