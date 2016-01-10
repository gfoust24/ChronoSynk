<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
	
	if (isset($_GET['reset']))
	{
		header('Location: ./?session='.$_GET['session']);
	}
	if (isset($_GET['del']) && strtolower($_GET['del']) === 'delete')
	{
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		$stmt = $dbConnection->prepare('delete from session where sessionID = ?');
		$stmt->bind_param('i', $_GET['session']);
		$stmt->execute();
		header('Location: /chronosynk/sessions/');
	}
	if (isset($_GET['editSession']))
	{
		include_once($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/methods.php');
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		//start prepared statement
		//$title, $description, $startDate, $startHour, $startMinute, $startPeriod, $endDate, $endHour, $endMinute, $endPeriod, $visibility, $cap)
		$validate = validateSession($_GET['title'], $_GET['description'], $_GET['startDate'], $_GET['startHour'], $_GET['startMinute'], $_GET['startPeriod'], $_GET['endDate'],
			 $_GET['endHour'], $_GET['endMinute'], $_GET['endPeriod'], $_GET['visibility'], $_GET['cap']);
		$formatInputToDateTime = 'm/d/Y h i A';
		if (!$validate['startDate'])
			$startDate = date_format(date_create_from_format($formatInputToDateTime, $_GET['startDate'].' '.$_GET['startHour'].' '.$_GET['startMinute'].' '.$_GET['startPeriod']), 'Y/m/d H:i:s');
		if (!$validate['endDate'])
			$endDate = date_format(date_create_from_format($formatInputToDateTime, $_GET['endDate'].' '.$_GET['endHour'].' '.$_GET['endMinute'].' '.$_GET['endPeriod']), 'Y/m/d H:i:s');
		
		$valid = true;
		$editSessionErrMsg = '';
		for ($i = 0; $i < sizeof($validate['fields']); $i++)
		{
			if (!empty($validate[$validate['fields'][$i]]))
			{
				$editSessionErrMsg .= $validate[$validate['fields'][$i]].'<br/>';
				$valid = false;
			}
		}
		
		$_SESSION['editSessionErrMsg'] = $editSessionErrMsg;
		if (!empty($_GET['password']))
		{
			$stmt = $dbConnection->prepare("update session set title = ?, description = ?, start = ?, end = ?, password = ?, cap = ?, visibility = ? where sessionID = ? and leader = ?");
			
			//bind variables
			//$_GET['description'] = htmlspecialchars(mysql_real_escape_string($_GET['description']));
			//startDate', 'startHour', 'startMinute', 'startPeriod', 
			$stmt->bind_param('sssssiiii', $_GET['title'], $_GET['description'], $startDate, $endDate, $_GET['password'], $_GET['cap'], $_GET['visibility'], $_GET['session'], $_SESSION['userID']);
		}
		else
		{
			$stmt = $dbConnection->prepare("update session set title = ?, description = ?, start = ?, end = ?, cap = ?, visibility = ? where sessionid = ? and leader = ?");
			//bind variables
			$stmt->bind_param('ssssiiii', $_GET['title'], $_GET['description'], $startDate, $endDate, $_GET['cap'], $_GET['visibility'], $_GET['session'], $_SESSION['userID']);
		}
		$executed = false;
		if ($valid) $executed = $stmt->execute();
		
		if ($executed)
		{
			$_SESSION['editSessionErrMsg'] = 'Saved successfully<br/>';
			//header('Location: ./?session='.$_GET['session']);
		}
		else
			$_SESSION['editSessionErrMsg'] = 'Something went wrong. Please Try again<br/>'.$_SESSION['editSessionErrMsg'];
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>ChronoSynk - Edit Session Settings</title>
<link rel="stylesheet" href="/chronosynk/media/jquery-ui.css"/>
<script type="text/javascript" src="/chronosynk/media/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/chronosynk/media/jquery-ui.js"></script>
<script type="text/javascript">
	$(function() {
	$( "#datepicker" ).datepicker();
	$( "#datepicker2" ).datepicker();
	});
</script>
</head>

<body>
	<?php
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');
		
		//get session leader
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		//start prepared statement
		$stmt = $dbConnection->prepare("select * from session where sessionID = ?");
		//bind variables
		$stmt->bind_param('s', $_GET['session']);
		$executed = $stmt->execute();
		//get the result
		$result = $stmt->get_result();
		//turn into array
		$session = $result->fetch_assoc();
		
		if (!isset($_SESSION['userID']))
			echo '<p>Log in to view this page</p>';
		else if ($session['leader'] != $_SESSION['userID'] && $_SESSION['permission'] == 0)
		{
			echo '<p>You do not have permission to edit this session.</p>';
		}
		else
		{
			$dbConnection = new mysqli('localhost', 'chronoRead', 'password', 'chronosynk');
			$stmt = $dbConnection->prepare('select * from session where sessionID = ?');
			$stmt->bind_param('i', $_GET['session']);
			$stmt->execute();
			$result = $stmt->get_result();
			$row = $result->fetch_assoc();
			if (empty($row['title']))
			{
				echo '<p>Session not found</p>';
			}
			else
			{
				if (empty($_SESSION['editSessionError']))
				{
					$_SESSION['editSessionError'] = '';
				}
				//initialize variables if not set so unset variable is not thrown below
				/*$strArr = array('title', 'description', 'startDate', 'startHour', 'startMinute', 'startPeriod', 'endDate', 'endHour', 'endMinute', 'endPeriod', 'cap', 'visibility', 'tags');
				
				for ($i = 0; $i < sizeof($strArr); $i++)
				{
					if (empty($_GET[$strArr[$i]]))
					{
						$_GET[$strArr[$i]] = '';
					}
				}*/
				//if the page is not loading from a form attempt, query for the session info and swap into get array to auto complete form
				if (empty($_GET['editSession']))
				{
					$_GET['title'] = $row['title'];
					$_GET['description'] = $row['description'];
					$_GET['cap'] = $row['cap'];
					$_GET['visibility'] = $row['visibility'];
					$_GET['tags'] = '';//TODO get tags
					//Y-m-d H:i:s
					$date = date_create_from_format('Y-m-d H:i:s', $row['start']);
					$_GET['startDate'] = date_format($date, 'm/d/Y');
					$_GET['startHour'] = date_format($date, 'h');
					$_GET['startMinute'] = date_format($date, 'i');
					$_GET['startPeriod'] = date_format($date, 'A');
					$date = date_create_from_format('Y-m-d H:i:s', $row['end']);
					$_GET['endDate'] = date_format($date, 'm/d/Y');
					$_GET['endHour'] = date_format($date, 'h');
					$_GET['endMinute'] = date_format($date, 'i');
					$_GET['endPeriod'] = date_format($date, 'A');
				}
				
				if (empty($_SESSION['editSessionErrMsg']))
				{
					$_SESSION['editSessionErrMsg'] = '';
				}
				echo '<form id="editSession" action="./">
						<label>'.$_SESSION['editSessionErrMsg'].'</label>
						<label>'.$_SESSION['editSessionError'].'</label>
						<input class="editWidth" name="title" placeholder="Title" type="text" size="25" value="'.$_GET['title'].'"/><br/>
						<textarea class="editWidth" name="description" placeholder="Description" cols="26">'.$_GET['description'].'</textarea><br/>
						<input name="startDate" id="datepicker" type="text" size="25" value="'.$_GET['startDate'].'"/>
						<select name="startHour">';
				unset ($_SESSION['editSessionErrMsg']);
						for ($i = 1; $i <= 12; $i++)
						{
							echo '<option value="'.$i.'"';
							if ($_GET['startHour'] == $i)
								echo ' selected="selected" ';
							echo '>'.$i.'</option>';
						}	
						echo '</select><select name="startMinute">';
							for ($i = 0; $i <= 59; $i++)
							{
								echo '<option value="'.substr('0'.$i, -2, 2).'"';
								if ($_GET['startMinute'] == $i)
									echo ' selected="selected" ';
								echo '>'.substr('0'.$i, -2, 2).'</option>';
							}
						echo '</select>	
						<select name="startPeriod">
							<option '; if (strcmp($_GET['startPeriod'], 'AM') == 0) echo ' selected="selected" '; echo 'value="AM">AM</option>
							<option '; if (strcmp($_GET['startPeriod'], 'PM') == 0) echo ' selected="selected" '; echo 'value="PM">PM</option>
						</select><br/>
						<input value="'.$_GET['endDate'].'" name="endDate" id="datepicker2" type="text" size="25"/>
						<select name="endHour">';
						for ($i = 1; $i <= 12; $i++)
						{
							echo '<option value="'.$i.'"';
							if ($_GET['endHour'] == $i)
								echo ' selected="selected" ';
							echo '>'.$i.'</option>';
						}
						echo '</select><select name="endMinute">';
						for ($i = 0; $i <= 59; $i++)
						{
							echo '<option value="'.substr('0'.$i, -2, 2).'"';
							if ($_GET['endMinute'] == $i)
								echo ' selected="selected" ';
							echo '>'.substr('0'.$i, -2, 2).'</option>';
						}
						echo '</select>	
						<select name="endPeriod">
							<option '; if (strcmp($_GET['endPeriod'], 'AM') == 0) echo ' selected="selected" '; echo 'value="AM">AM</option>
							<option '; if (strcmp($_GET['endPeriod'], 'PM') == 0) echo ' selected="selected" '; echo 'value="PM">PM</option>
						</select><br/>
						<input class="editWidth" name="password" placeholder="Password (Optional)" type="password" size="25"/><br/>
						<input class="editWidth" value="'.$_GET['tags'].'" name="tags" placeholder="tag 1,tag 2,tag 3,..." type="text"/><br/>
						<input value="'.$_GET['cap'].'" name="cap" placeholder="Player Limit (Blank for unlimited)" type="text"/>
						<select name="visibility">
							<option '; if ($_GET['visibility'] == 0) echo ' selected="selected" '; echo 'value="0">Public</option>
							<option '; if ($_GET['visibility'] == 1) echo ' selected="selected" '; echo 'value="1">Friends Only</option>
							<option '; if ($_GET['visibility'] == 2) echo ' selected="selected" '; echo 'value="2">Invite Only</option>
						</select>
						<button name="editSession" type="submit">Save</button>
						<button name="reset" value="reset" type="submit">Reset</button>
						<input type="hidden" name="editSession2" value="1"/>
						<input type="hidden" name="session" value="'.$_GET['session'].'"/>
					</form>
					<form action="./">
						<label>Type &quot;delete&quot; then submit to delete session</label><br/>
						<input type="hidden" name="session" value="'.$_GET['session'].'"/>
						<input type="text" name="del"/>
						<button type="submit">Delete</button>
					</form>
					<a href="../view/?session='.$_GET['session'].'">Back to session</a>';
				}
		}
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');
	?>
</body>
</html>
