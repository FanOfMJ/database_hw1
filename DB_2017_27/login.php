<?php 
	session_start();
	
	if(isset($_SESSION["login"]) && $_SESSION["login"] == true){
		/*if($_SESSION["admin"] == false){
			echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/user.php';</script>";
		}
		else{
			echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/admin.php';</script>";
		}*/
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/homepage.php';</script>";
	}

?>
<!DOCTYPE HTML>
<html>
	<head>
		<style>
			.error {color: #FF0000;}
			body{
				background-image: url("https://media.giphy.com/media/pd2y49jnB2wVi/giphy.gif");
				background-repeat: no-repeat;
				background-position: Top center;
				-moz-background-size:cover;
				-webkit-background-size:cover;
				background-size:cover;
			}
		</style>
		<title>
			Login Page
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="style.css">
	</head>
	<body>
		<?php
		$db_host = "dbhome.cs.nctu.edu.tw";
		$db_name = "yutian_cs_DB_HW2";
		$db_user = "yutian_cs";
		$db_password = "180701998$$$";
		$dsn = "mysql:host=$db_host;dbname=$db_name";
		
		// define variables and set to empty values.
		$accountErr = $passwordErr = "";
		$account = $password = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if(empty($_POST["account"])) {
				$accountErr = "Account is required";
			}
			else {
				$account = test_input($_POST["account"]);
				$db = new PDO($dsn, $db_user, $db_password);
				if(!preg_match("/[^ ]/",$_POST["account"])) {
					$accountErr = "White space is not allowed";
				}
			}
			
			if(empty($_POST["password"])) {
				$passwordErr = "Password is required";
			}
			else {
				$password = test_input($_POST["password"]);
				$hash_password = hash ('sha256',$password.$account);
				
				$query_1 = "SELECT * FROM yutian_cs_DB_HW2.User WHERE account = '$account' AND password = '$hash_password' AND admin = 'normal'";
				$query_2 = "SELECT * FROM yutian_cs_DB_HW2.User WHERE account = '$account' AND password = '$hash_password' AND admin = 'admin'";
				$result_1 = $db->query($query_1);
				$result_2 = $db->query($query_2);
				$num_rows_1 = $result_1->rowCount();
				$num_rows_2 = $result_2->rowCount();
				$db = null; //close connection
				
				if ($num_rows_1)
				{
					$_SESSION["login"] = true;
					$_SESSION["admin"] = false;
					$_SESSION["id"] = $result_1->fetchColumn();
					echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/homepage.php';</script>";
				}
				else if ($num_rows_2)
				{
					$_SESSION["login"] = true;
					$_SESSION["admin"] = true;
					$_SESSION["id"] = $result_2->fetchColumn();
					echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/homepage.php';</script>";
				}
				else{
					echo "Your account or password is wrong!!!";
				}
			}
			
		}
		
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		?>
		<h2>Login</h2>
		<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
			Account: <input type="text" name="account" value="<?php echo $account;?>">
			<span class="error"> <?php echo $accountErr;?></span>
			<br><br>
			Password: <input type="password" name="password" value="<?php echo $password;?>">
			<span class="error"> <?php echo $passwordErr;?></span>
			<br><br>
			<div class="btn"><input type="submit" name="submit" value="Login"></div>
			<div class="word">
				Not yet a member?
			<a href = "register.php">Register</a>
			</div>
		</form>
		<br>
	</body>
</html>