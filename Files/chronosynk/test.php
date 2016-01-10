<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
<meta content="text/html; charset=utf-8" http-equiv="Content-Type" />
<title>Untitled 1</title>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css"/>
<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
<script type="text/javascript" src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
	$(function() {
	$( "#datepicker" ).datepicker();
	$( "#datepicker2" ).datepicker();
	});
</script>
</head>

<body>
<p>Date: <input type="text" id="datepicker"/></p>
<p>Date: <input type="text" id="datepicker2"/></p>
	<?php
		/*$dbConnection = new mysqli('localhost', 'chronoRead', 'password', 'chronosynk');
		mysqli_set_charset ($dbConnection, "utf8");
		$stmt = $dbConnection->prepare('select * from session where sessionid = 5');
		$stmt->execute();
		$result = $stmt->get_result();
		$row = $result->fetch_assoc();
		echo $row['start'].'<br/>';
		//$test = new DateTime('02/31/2011');
  		//echo date_format($test, 'Y-m-d H:i:s'); // 2011-07-01 00:00:00
		$s = date_create_from_format('Y-m-d H:i:s', $row['start']);
		echo 's '.date_format($s,"m/d/Y").'<br/>';
		
		$startDate = date_create_from_format('m/d/Y h i a', '10/20/2015 2 23 AM');
		echo '|'.date_format($startDate,"Y-m-d H:i:s");
		$dbConnection = new mysqli('localhost', 'chronoWrite', 'password', 'chronosynk');
		mysqli_set_charset ($dbConnection, "utf8");
		$stmt = $dbConnection->prepare('update session set start = ? where sessionID = 1');
		$out = date_format($startDate,"Y-m-d H:i:s");
		$stmt->bind_param('s', $out);
		$stmt->execute();
		echo var_dump($stmt);
		
		$strArr = array('startDate','ed','bla');
		echo var_dump($strArr);*/
		//echo intval('p');
		
		/*$dbConnection = mysqli_connect("localhost", "chronoWrite", "password", "chronosynk");
		mysqli_set_charset ($dbConnection, "utf8");
		//start prepared statement
		$stmt = $dbConnection->prepare('INSERT INTO session (title, description, start, end, password, visibility, cap, leader) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
		//bind variables
		$title = '2015-10-13 13:11:12';
		$desc = 'testing what happens if empty string is given for cap';
		$start = '2015-10-13 13:11:12';
		$end = '2015-10-13 23:11:12';
		$pass = '';
		$vis = 0;
		$cap = '';
		$id = 1;
		$stmt->bind_param('sssssiii', $title, $desc, $start, $end, $pass, $vis, $cap, $id);
		$executed = $stmt->execute();*/
		
		//$arr = array('text', 'text2');
		/*$arr['asdf'] = 'asdf';
		$arr['234'] = '';
		$arr['fdsa'] = 'fdsa';
		
		echo implode('<br/>', $arr);*/
		
		//echo '|'.('2015-10-20 02:23:00' > '2015-10-20 12:23:00').'|';
		
		//$s = date_create_from_format('Y-m-d H:i:s', '2015-10-20 13:12:01');
		//echo 's '.date_format($s,"A").'<br/>';
		/*$a_params[] = '1';
		$a_params[] = '2';
		$a_params[] = '3';
		$a_params[] = '4';
		$a_params[] = '5';
		echo var_dump($a_params);*/
	?>
	<?php
		/*$test1 = "asdf";
		//$headers = 'MIME-Version: 1.0' . "\r\n";
		//$headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
		//$headers .= 'From: gaf3@pct.edu'."\r\n";
		$headers = "From: Greggory Foust gaf3@pct.edu";
		$mailed = mail("gaf3@pct.edu","test subject","test body",$headers);
		if ($mailed)
			echo 'mailed';*/
	?>
	
	<?php
		$str = 'asdfasdf';
		echo substr($str, 0, strlen($str) - 1);
	?>
</body>

</html>
