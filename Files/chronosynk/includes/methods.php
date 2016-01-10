<?php
	function checkPassword($dbConnection, $username, $password)
	{
		//start prepared statement
		$stmt = $dbConnection->prepare("select * from user where username = ?");
		//bind variables
		$stmt->bind_param('s', $username);
		$executed = $stmt->execute();
		//get the result
		$result = $stmt->get_result();
		//turn into array
		$row = $result->fetch_assoc();
		//get the salted and hashed password
		$protectedPassword = $row['password'];
		//substring the salt
		$salt = substr($protectedPassword, 0, 16);
		//use the salt to rehash and salt the user given password and test for match against DB provided password
		if (strcmp($salt.hash_pbkdf2("sha256", $password, $salt, 1000), $protectedPassword) == 0)
			return array($row['userID'], $row['permission'], $row['username']);
		else
			return false;
	}
	
	
	function validateTitle($title)
	{
		//check length of session title
		$len = strlen($title);
		if ($len < 5 || $len > 50)
		{
			$_SESSION['addSessionError'] .= 'Title must be 5-50 characters<br/>';
			$valid = false;
		}
		else
			return false;
	}
	
	//username, email, bio
	function validateUser()
	{
	
	}
	
	//title, description, start, end, visibility, cap
	function validateSession($title, $description, $startDate, $startHour, $startMinute, $startPeriod, $endDate, $endHour, $endMinute, $endPeriod, $visibility, $cap)
	{
		//check length of session title
		$len = strlen($title);
		
		$addSessionError['fields'] = array('title', 'description', 'startDate', 'endDate', 'visibility', 'cap');
		for ($i = 0; $i < sizeof($addSessionError['fields']); $i++)
		{
			$addSessionError[$addSessionError['fields'][$i]] = '';
		}
		
		if ($len < 5 || $len > 50)
		{
			$addSessionError['title'] = 'Title must be 5-50 characters';
		}
		//check length of session description
		if (strlen($description) > 1000)
		{
			$addSessionError['description'] = 'Description must be 0-1000 characters';
		}
		//check visibility value = 0, 1, or 2
		if (!($visibility == 0 || $visibility == 1 || $visibility == 2))
		{
			$addSessionError['visibility'] = 'Invalid visibility value';
		}
		//check if input dates are valid
		$formatInputToDateTime = 'm/d/Y h i a';
		$startDate = date_create_from_format($formatInputToDateTime, $startDate.' '.$startHour.' '.$startMinute.' '.$startPeriod);
		if (!$startDate)
		{
			$addSessionError['startDate'] = 'Invalid start date';
		}
		$endDate = date_create_from_format($formatInputToDateTime, $endDate.' '.$endHour.' '.$endMinute.' '.$endPeriod);
		if (!$endDate)
		{
			$addSessionError['endDate'] = 'Invalid end date';
		}
		if (empty($cap))
		{
			$cap = 0;
		}
		else if (!intval($cap))
		{
			$addSessionError['cap'] = 'Player limit must be a number';
		}
		else if ($cap < 0)
		{
			$addSessionError['cap'] = 'Player limit cannot be negative';
		}
		return $addSessionError;
	}
		/*$temp = validateTitle($_GET['title']);
		if ()
		//check length of session description
		if (strlen($_GET['description']) > 1000)
		{
			$_SESSION['addSessionError'] .= 'Description must be 0-1000 characters<br/>';
			$valid = false;
		}
		//check visibility value = 0, 1, or 2
		if (!($_GET['visibility'] == 0 || $_GET['visibility'] == 1 || $_GET['visibility'] == 2))
		{
			$_SESSION['addSessionError'] .= 'Invalid visibility value';
			$valid = false;
		}
		//check if input dates are valid
		$formatInputToDateTime = 'm/d/Y h i a';
		$startDate = date_create_from_format($formatInputToDateTime, $_GET['startDate'].' '.$_GET['startHour'].' '.$_GET['startMinute'].' '.$_GET['startPeriod']);
		if (!$startDate)
		{
			$_SESSION['addSessionError'] .= 'Invalid start date<br/>';
			$valid = false;
		}
		$endDate = date_create_from_format($formatInputToDateTime, $_GET['endDate'].' '.$_GET['endHour'].' '.$_GET['endMinute'].' '.$_GET['endPeriod']);
		if (!$endDate)
		{
			$_SESSION['addSessionError'] .= 'Invalid end date<br/>';
			$valid = false;
		}
		if (empty($_GET['cap']))
		{
			$_GET['cap'] = 0;
		}
		else if (!intval($_GET['cap']))
		{
			$_SESSION['addSessionError'] .= 'Player limit must be a number<br/>';
			$valid = false;
		}
		else if ($_GET['cap'] < 0)
		{
			$_SESSION['addSessionError'] .= 'Player limit cannot be negative<br/>';
			$valid = false;
		}*/
	
?>