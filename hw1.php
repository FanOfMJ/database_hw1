<?php
    $account=$_POST["account"];
    $password=$_POST["password"]; //TODO get data by $_POST 
?>

<!DOCTYPE html>
<html>
<head>
	<title>hw1</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<link type="text/css" rel="stylesheet" href="hw1.css">
</head>
<body>
	<div class="board">
		<h2><?php echo $YourName; ?></h2>
		<br>Account <?php echo $account; ?> <!-- TODO echo your id and email -->
		<br>Password<?php echo $password; ?>
	</div>
</body>
</html>

