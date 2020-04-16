<?php 
	session_start();
	
	if(!isset($_SESSION["login"])){
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/login.php';</script>";
	}
	else if($_SESSION["admin"] == false){
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/user.php';</script>";
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<style>
			.error {color: #FF0000;}
		</style>
		<title>
			Add User/Admin Page
		</title>
	</head>
	<body>
		<?php
		$db_host = "dbhome.cs.nctu.edu.tw";
		$db_name = "yutian_cs_DB_HW2";
		$db_user = "yutian_cs";
		$db_password = "180701998$$$";
		$dsn = "mysql:host=$db_host;dbname=$db_name";
		
		// define variables and set to empty values.
		$adminErr = $nameErr = $emailErr = $accountErr = $passwordErr = "";
		$admin = $name = $email = $account = $password = $confirm = "";

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["name"])) {
				$nameErr = "Name is required";
			}
			else {
				$name = test_input($_POST["name"]);
			}
		
			if (empty($_POST["email"])) {
				$emailErr = "Email is required";
			}
			else {
				$email = test_input($_POST["email"]);
				// check if e-mail address is well-formed
				if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
					$emailErr = "Invalid email format";
				}
			}
			
			if(empty($_POST["account"])) {
				$accountErr = "Account is required";
			}
			else {
				$account = test_input($_POST["account"]);
				$db = new PDO($dsn, $db_user, $db_password);
				
				//check if the account is already existed
				$query = "SELECT * FROM yutian_cs_DB_HW1.users WHERE account = '$account'";
				$result = $db->query($query);
				$num_rows = $result->rowCount();
				$db = null; //close connection
				
				if(!preg_match("/[^ ]/",$_POST["account"])) {
					$accountErr = "White space is not allowed";
				}
				else if($num_rows) {
					$accountErr = "Account is already used";
				}
			}
			
			if(empty($_POST["password"])) {
				$passwordErr = "Password is required";
			}
			else {
				$password = test_input($_POST["password"]);
				$confirm = test_input($_POST["confirm"]);
				if($password != $confirm) {
					$passwordErr = "Confirm failed";
				}
			}
			
			if (empty($_POST["admin"])) {
				$adminErr = "Identity is required";
			}
			else {
				$admin = test_input($_POST["admin"]);
			}
		}
		
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		?>
		<h2>Add User/Admin</h2>
		<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
			Name: <input type="text" name="name" value="<?php echo $name;?>">
			<span class="error"> <?php echo $nameErr;?></span>
			<br><br>
			E-mail: <input type="text" name="email" value="<?php echo $email;?>">
			<span class="error"> <?php echo $emailErr;?></span>
			<br><br>
			Account: <input type="text" name="account" value="<?php echo $account;?>">
			<span class="error"> <?php echo $accountErr;?></span>
			<br><br>
			Password: <input type="password" name="password" value="<?php echo $password;?>">
			<span class="error"> <?php echo $passwordErr;?></span>
			<br><br>
			Confirm Password: <input type="password" name="confirm" value="<?php echo $confirm;?>">
			<br><br>
			Identity: <input type="radio" name="admin" <?php if (isset($admin) && $admin=="admin") echo "checked";?> value="admin">Admin
					<input type="radio" name="admin" <?php if (isset($admin) && $admin=="normal") echo "checked";?> value="normal">Normal
			<span class="error"> <?php echo $adminErr;?></span>
			<br><br>
			<input type="submit" name="submit" value="Submit"> <!--save datas into the variables-->
		</form>
		<br>
		<a href = "admin.php">Back to Admin Page</a>
		
		<?php
			if($nameErr == "" && $emailErr == "" && $accountErr == "" && $passwordErr == "" && $account != "") {
				$db = new PDO($dsn, $db_user, $db_password);
				
				//hash password with salt
				$hash_password = hash ('sha256',$password.$account);
				$sql = "INSERT INTO yutian_cs_DB_HW2.User (id, admin, account, password, name, mail)
				VALUES (NULL, '$admin', '$account', '$hash_password', '$name', '$email')";
				$db->exec($sql);//throw into mysql
				$db = null;
				
				//if successful, connect to the login page (use JS)
				echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/member_management.php';</script>";
			}
		?>
	</body>
</html>