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
<?php
include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/header.php');

echo '<p>Policies</p>
	<a href="./acceptableuse">Acceptable Use Policy</a> | <a href="./termsofuse">Terms of Use</a> | <a href="./privacy">Privacy Policy</a>';

include($_SERVER['DOCUMENT_ROOT'].'/chronosynk/includes/footer.php');

?>
</body>

</html>
