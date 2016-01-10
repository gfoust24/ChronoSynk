<?php
	//if user is admin log them out if innactive for 10 minutes
	if (isset($_SESSION['lastSeen']) && isset($_SESSION['permission']) && $_SESSION['permission'] >= 20 && (time() - $_SESSION['lastSeen'] > 60))
	{
		session_unset();
		session_destroy();
		header('Location: /chronosynk/');
	}
	$_SESSION['lastSeen'] = time();
	if (!isset($_SESSION['CREATED'])) {
	    $_SESSION['CREATED'] = time();
	}
	else if (time() - $_SESSION['CREATED'] > 1800) {
	    // session started more than 30 minutes ago
	    session_regenerate_id(true);    // change session ID for the current session and invalidate old session ID
	    $_SESSION['CREATED'] = time();  // update creation time
	}
?>