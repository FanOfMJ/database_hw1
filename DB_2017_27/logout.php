<?php 
	session_start();
	session_destroy();
	echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/login.php';</script>";
?>