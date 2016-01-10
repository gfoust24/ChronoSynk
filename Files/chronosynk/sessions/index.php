<?php
	session_start();
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/top.php');
	include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');
	//validate add session input
	include_once($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/methods.php');
	if (isset($_GET['addSession']))
	{
		$validate = validateSession($_GET['title'], $_GET['description'], $_GET['startDate'], $_GET['startHour'], $_GET['startMinute'], $_GET['startPeriod'], $_GET['endDate'],
			 $_GET['endHour'], $_GET['endMinute'], $_GET['endPeriod'], $_GET['visibility'], $_GET['cap']);
		$formatInputToDateTime = 'm/d/Y h i a';
		if (!$validate['startDate']) $startDate = date_create_from_format($formatInputToDateTime, $_GET['startDate'].' '.$_GET['startHour'].' '.$_GET['startMinute'].' '.$_GET['startPeriod']);
		if (!$validate['endDate']) $endDate = date_create_from_format($formatInputToDateTime, $_GET['endDate'].' '.$_GET['endHour'].' '.$_GET['endMinute'].' '.$_GET['endPeriod']);
		
		$valid = true;
		$addSessionErrMsg = '';
		for ($i = 0; $i < sizeof($validate['fields']); $i++)
		{
			if (!empty($validate[$validate['fields'][$i]]))
			{
				$addSessionErrMsg .= $validate[$validate['fields'][$i]].'<br/>';
				$valid = false;
			}
		}
		$_SESSION['addSessionErrMsg'] = $addSessionErrMsg;
		if ($valid)
		{
			$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
			mysqli_set_charset ($dbConnection, "utf8");
			//start prepared statement
			$stmt = $dbConnection->prepare('INSERT INTO session (title, description, start, end, password, visibility, cap, leader) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
			//bind variables
			$startDate = date_format($startDate,"Y-m-d H:i:s");
			$endDate = date_format($endDate,"Y-m-d H:i:s");
			$stmt->bind_param('sssssiii', $_GET['title'], $_GET['description'], $startDate, $endDate, $_GET['password'], $_GET['visibility'], $_GET['cap'], $_SESSION['userID']);
			$executed = $stmt->execute();
			
			/*
			if (!empty($_GET['tags'])
			{
				$tags = explode(',', $_GET['tags']);
				$query = 'insert all ';
				for ($i = 0; $i < sizeof($tags); $i++)
				{
					$query .= 'into tag ('
				}// INTO suppliers (supplier_id, supplier_name) VALUES (1000, 'IBM')
				
				$stmt = $dbConnection->prepare('');
			}*/
			//header('Location: ./');
		}
	}
	//code to search through sessions
	if (isset($_GET['search']))
	{
		//'title', 'description', 'sSDate', 'sSHr', 'sSMin', 'sSPer', 'sEDate', 'sEHr', 'sEMin', 'sEPer', 'cap', 'visibility', 'tags');
		//parameters that don't need validated are given bogus information such as aaaaa since searching for a title does not need to be 5-50 characters
		$validate = validateSession('aaaaa', '', $_GET['sSDate'], $_GET['sSHr'], $_GET['sSMin'], $_GET['sSPer'], $_GET['sEDate'],
			 $_GET['sEHr'], $_GET['sEMin'], $_GET['sEPer'], 0, $_GET['cap']);
		$formatInputToDateTime = 'm/d/Y h i a';
		if (!$validate['startDate'])
			$startDate = date_format(date_create_from_format($formatInputToDateTime, $_GET['sSDate'].' '.$_GET['sSHr'].' '.$_GET['sSMin'].' '.$_GET['sSPer']), 'Y-m-d H:i:s');
		if (!$validate['endDate'])
			$endDate = date_format(date_create_from_format($formatInputToDateTime, $_GET['sEDate'].' '.$_GET['sEHr'].' '.$_GET['sEMin'].' '.$_GET['sEPer']), 'Y-m-d H:i:s');
		//if start/end dates are blank, dont send error message
		if (empty($_GET['startDate']))
			$validate['startDate'] = '';
		if (empty($_GET['endDate']))
			$validate['endDate'] = '';
			
		$valid = true;
		$searchSessionErrMsg = '';
		for ($i = 0; $i < sizeof($validate['fields']); $i++)
		{
			if (!empty($validate[$validate['fields'][$i]]))
			{
				$searchSessionErrMsg .= $validate[$validate['fields'][$i]].'<br/>';
				$valid = false;
			}
		}
		$_SESSION['searchSessionErrMsg'] = $searchSessionErrMsg;
		if ($valid)
		{
			//search
			/********************************************************
			*********************************************************
			Search code based of Christos Pontikis's guide: http://www.pontikis.net/blog/dynamically-bind_param-array-mysqli
			*********************************************************
			********************************************************/
			$titleTerms = explode(' ', $_GET['sTitle']);
			$param_type = '';
			$query = 'select * from session join user on leader = userID where 1=1';
			$a_params[0] = '';
			if (!empty($_GET['sTitle']))
			{
				$query .= ' and (';
				for ($i = 0; $i < sizeof($titleTerms); $i++)
				{
					$query .= 'title like ?';
					$param_type .= 's';
					if ($i + 1 < sizeof($titleTerms))
						$query .= ' or  ';
				}
				$a_params[0] = & $param_type;
				for ($i = 0; $i < sizeof($titleTerms); $i++)
				{
					$titleTerms[$i] = '%'.$titleTerms[$i].'%';
					$a_params[] = & $titleTerms[$i];
				}
				$query .= ')';
			}
			if (!empty($startDate))
			{
				$query .= ' and start > ? ';
				$a_params[] = & $startDate;
				$a_params[0] .= 's';
			}
			if (!empty($endDate))
			{
				$query .= ' and end < ? ';
				$a_params[] = & $endDate;
				$a_params[0] .= 's';
			}
			
			$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
			mysqli_set_charset($dbConnection, "utf8");
			//start prepared statement
			$stmt = $dbConnection->prepare($query);
			//bind variables
			//$startDate = date_format($startDate,"Y-m-d H:i:s");
			//$endDate = date_format($endDate,"Y-m-d H:i:s");
			//$stmt->bind_param('sssssiii', $_GET['title'], $_GET['description'], $startDate, $endDate, $_GET['password'], $_GET['visibility'], $_GET['cap'], $_SESSION['userID']);
			if (empty($a_params[0])) unset($a_params);
			if (isset($a_params)) call_user_func_array(array($stmt, 'bind_param'), $a_params);
			/********************************************************
			*********************************************************
			End of code based of Christos Pontikis
			*********************************************************
			********************************************************/
			$executed = $stmt->execute();
			$result = $stmt->get_result();
			$_SESSION['searchResults'] = $result;
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>ChronoSynk - Sessions</title>
<!-- Online sources of below js and css files released under MIT license
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
-->
<link rel="stylesheet" href="/chronosynk/media/jquery-ui.css"/>
<script type="text/javascript" src="/chronosynk/media/jquery-1.10.2.js"></script>
<script type="text/javascript" src="/chronosynk/media/jquery-ui.js"></script>
<script type="text/javascript">
	$(function() {
	$( "#datepicker" ).datepicker();
	$( "#datepicker2" ).datepicker();
	$( "#sdatepicker" ).datepicker();
	$( "#sdatepicker2" ).datepicker();
	});
</script>
</head>

<body>
	<div id="sessionSearch">
		<h2>Search</h2>
		<?php 
		//initialize variables if not set so unset variable is not thrown below
		$strArr = array('sTitle', 'description', 'sSDate', 'sSHr', 'sSMin', 'sSPer', 'sEDate', 'sEHr', 'sEMin', 'sEPer', 'cap', 'visibility', 'tags');
		for ($i = 0; $i < sizeof($strArr); $i++)
		{
			if (empty($_GET[$strArr[$i]]))
			{
				$_GET[$strArr[$i]] = '';
			}
		}
		if (empty($_SESSION['searchSessionErrMsg'])) $_SESSION['searchSessionErrMsg'] = '';
		
		echo '<form id="searchSession" action="./">
				<label>'.$_SESSION['searchSessionErrMsg'].'</label>
				<input class="searchSessionWidth" value="'.$_GET['sTitle'].'" name="sTitle" placeholder="Title" type="text" size="25"/><br/>
				<input value="'.$_GET['sSDate'].'" name="sSDate" id="sdatepicker" type="text" size="25" value="'.$_GET['sSDate'].'"/>
				<select name="sSHr">
					<option value="--">--</option>';
				for ($i = 1; $i <= 12; $i++)
				{
					echo '<option value="'.$i.'"';
					if ($_GET['sSHr'] == $i)
						echo ' selected="selected" ';
					echo '>'.$i.'</option>';
				}	
				echo '</select>
					<select name="sSMin">
						<option selected="selected" value="--">--</option>';
					for ($i = 0; $i <= 59; $i++)
					{
						echo '<option value="'.substr('0'.$i, -2, 2).'"';
						if ($_GET['sSMin'] === $i)
							echo ' selected="selected" ';
						echo '>'.substr('0'.$i, -2, 2).'</option>';
					}
				echo '</select>	
				<select name="sSPer">
					<option '; if (strcmp($_GET['sSPer'], 'AM') == 0) echo ' selected="selected" '; echo 'value="AM">AM</option>
					<option '; if (strcmp($_GET['sSPer'], 'PM') == 0) echo ' selected="selected" '; echo 'value="PM">PM</option>
				</select><br/>
				<input value="'.$_GET['sEDate'].'" name="sEDate" id="sdatepicker2" type="text" size="25"/>
				<select name="sEHr">
					<option value="--">--</option>';
					for ($i = 1; $i <= 12; $i++)
					{
						echo '<option value="'.$i.'"';
						if ($_GET['sEHr'] == $i)
							echo ' selected="selected" ';
						echo '>'.$i.'</option>';
					}
				echo '</select>
				<select name="sEMin">
					<option value="--">--</option>';
					for ($i = 0; $i <= 59; $i++)
					{
						echo '<option value="'.substr('0'.$i, -2, 2).'"';
						if ($_GET['sEMin'] === $i)
							echo ' selected="selected" ';
						echo '>'.substr('0'.$i, -2, 2).'</option>';
					}
				echo '</select>	
				<select name="sEPer">
					<option '; if (strcmp($_GET['sEPer'], 'AM') == 0) echo ' selected="selected" '; echo 'value="AM">AM</option>
					<option '; if (strcmp($_GET['sEPer'], 'PM') == 0) echo ' selected="selected" '; echo 'value="PM">PM</option>
				</select><br/>
				<input class="searchSessionWidth" name="tags" placeholder="tag 1,tag 2,tag 3,..." type="text"/><br/>
				<select name="inequality">
					<option value="0">&#60;</option>
					<option value="1">&#61;</option>
					<option value="2">&#62;</option>
				</select>
				
				<input name="cap" placeholder="Player Limit"/>
				<button type="submit">Search</button>
				<input type="hidden" name="search" value="1"/>
			</form>';
		unset($_SESSION['searchSessionErrMsg'])?>
	</div>
	<div class="clear"></div>
	<?php
		//if user is logged in, show create session form
		if (isset($_SESSION['userID']))
		{
			if (empty($_SESSION['addSessionErrMsg']))
			{
				$_SESSION['addSessionErrMsg'] = '';
			}
			//initialize variables if not set so unset variable is not thrown below
			$strArr = array('title', 'description', 'startDate', 'startHour', 'startMinute', 'startPeriod', 'endDate', 'endHour', 'endMinute', 'endPeriod', 'cap', 'visibility', 'tags');
			for ($i = 0; $i < sizeof($strArr); $i++)
			{
				if (empty($_GET[$strArr[$i]]))
				{
					$_GET[$strArr[$i]] = '';
				}
			}
			echo '
				<div id="addSessionDiv">
					<h2>Create</h2>
					<form id="addSession" action="./">
						<label class="addSessionfullWidth" style="color:red">'.$_SESSION['addSessionErrMsg'].'</label>
						<input class="addSessionfullWidth" name="title" placeholder="Title" type="text" size="25" value="'.$_GET['title'].'"/><br/>
						<textarea class="addSessionfullWidth" name="description" placeholder="Description" cols="26">'.$_GET['description'].'</textarea><br/>
						<input placeholder="Start Date" name="startDate" id="datepicker" type="text" size="25" value="'.$_GET['startDate'].'"/>
						<select name="startHour">';
						unset($_SESSION['addSessionErrMsg']);
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
						<input placeholder="End Date" value="'.$_GET['endDate'].'" name="endDate" id="datepicker2" type="text" size="25"/>
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
						<input class="addSessionfullWidth" name="password" placeholder="Password (Optional)" type="password" size="25"/><br/>
						<input class="addSessionfullWidth" value="'.$_GET['tags'].'" name="tags" placeholder="tag 1,tag 2,tag 3,..." type="text"/><br/>
						<input value="'.$_GET['cap'].'" name="cap" placeholder="Player Limit (Blank for unlimited)" type="text"/>
						<select name="visibility">
							<option '; if ($_GET['visibility'] == 0) echo ' selected="selected" '; echo 'value="0">Public</option>
							<option '; if ($_GET['visibility'] == 1) echo ' selected="selected" '; echo 'value="1">Friends Only</option>
							<option '; if ($_GET['visibility'] == 2) echo ' selected="selected" '; echo 'value="2">Invite Only</option>
						</select>
						<button type="submit">Create</button>
						<input type="hidden" name="addSession" value="1"/>
					</form>
				</div>';
			unset($_SESSION['addSessionErrMsg']);
		}
		
		if (empty($_SESSION['searchResults']))
		{
			//retreive sessions
			$dbConnection = mysqli_connect("localhost", "chronoRead", "password", "chronosynk");
			mysqli_set_charset ($dbConnection, "utf8");
			//start prepared statement
			/*$stmt = $dbConnection->prepare(
				'SELECT *
				FROM session
				join user
				on leader = userID
				where start > ?');*/
			$stmt = $dbConnection->prepare('
				select *
				from session
				join user
				on leader = userID
				where
				(
					(
						session.leader in
					   	(
						   	select user.userid
							from user
							where user.userid in
							(
								select toid
						   		from friend
						   		where (fromid = ?) and status = 1
						   	)
							or user.userid in
							(
								select fromid
						    	from friend
						    	where toid = ? and status = 1
						    )
						)
						and visibility = 1
				    )
					or visibility = 0
					or leader = ?
				)
				and start > ?');
			//get current date TODO: current time zone?
			$datetime = date('Y-m-d H:i:s');
			//bind variables
			$stmt->bind_param('iiis', $_SESSION['userID'], $_SESSION['userID'], $_SESSION['userID'], $datetime);
			$executed = $stmt->execute();
			//get the result
			$result = $stmt->get_result();
		}
		else
		{
			$result = $_SESSION['searchResults'];
		}
		//loop for each record found
		//TODO: limit number of records displayed
		echo '<div id="sessions">';
		
		while($row = $result->fetch_assoc())
		{
			//print session
			if (strlen($row['description']) > 100)
				$row['description'] = htmlspecialchars(substr($row['description'], 0, 100)).'<a href="./view/?session='.$row['sessionID'].'">[more]</a>';
			else
				$row['description'] = htmlspecialchars($row['description']);
			echo '
				<div class="session">
					<h3><a href="./view/?session='.$row['sessionID'].'">'.htmlspecialchars($row['title']).'</a></h3>
					<p>by <a href="/chronosynk/user/?user='.$row['userID'].'">'.$row['username'].'</a></p>
					<p>'.$row['start'].' - '.$row['end'].'</p>
					<p>'.$row['description'].'</p>
				</div>';
			//echo var_dump($row);
		}
		unset($_SESSION['searchResults']);
		echo '</div>';
		//datetime format Y-m-S H:i:s
		
		include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');
	?>
</body>

</html>
