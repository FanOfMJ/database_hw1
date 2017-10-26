<?php
    $account=$_POST["account"];
    $password=$_POST["password"]; //TODO get data by $_POST
	$confirmP=$_POST["confirmP"];
	$name=$_POST["name"];
	$email=$_POST["email"];
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link type="text/css" rel="stylesheet" href="hw1-1.css">
</head>
<body>
	<div class="board">
		<br>Account <?php echo $account; ?> <!-- TODO echo your id and email -->
		<br>Password<?php echo $password; ?>
		<br>Confirm Password<?php echo $confirmP; ?>
		<br>Name<?php echo $name; ?>
		<br>Email<?php echo $email; ?>
	</div>
</body>
</html>