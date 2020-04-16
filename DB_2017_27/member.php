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
		<style type = "text/css">
			.error {color: #FF0000;}
			body{
				background-image: url("https://cdn.dribbble.com/users/1896/screenshots/2660027/polar_bear.gif");
				background-repeat: no-repeat;
				background-position: center;
				-moz-background-size:cover;
				-webkit-background-size:cover;
				background-size:cover;
			}
			
		</style>
		<title>
			Admin Page
		</title>
	</head>
	<body>
		<h2>Welcome to the admin page!</h2>
		<?php
			$db_host = "dbhome.cs.nctu.edu.tw";
			$db_name = "yutian_cs_DB_HW1";
			$db_user = "yutian_cs";
			$db_password = "180701998$$$";
			$dsn = "mysql:host=$db_host;dbname=$db_name";
			
			$promote = $delete = "";
			$promoteErr = $deleteErr = "";
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if($_REQUEST["submit"] == "Promote"){
					if(empty($_POST["promote"])) {
						$promoteErr = "Account is required";
					}
					else {
						$promote = test_input($_POST["promote"]);
						$db = new PDO($dsn, $db_user, $db_password);
						
						$query = "SELECT * FROM yutian_cs_DB_HW1.users WHERE account = '$promote'";
						$result = $db->query($query);
						$num_rows = $result->rowCount();
						
						if($num_rows) {
							$query = "UPDATE yutian_cs_DB_HW1.users SET admin = 'admin' WHERE account = '$promote'";
							$db->query($query);
							$promoteErr = "Promotion success";
						}
						else{
							$promoteErr = "Account does not exist";
						}
						$db = null;
					}
				}
				else{
					if(empty($_POST["delete"])) {
						$deleteErr = "Account is required";
					}
					else {
						$delete = test_input($_POST["delete"]);
						$db = new PDO($dsn, $db_user, $db_password);
						
						$query1 = "SELECT * FROM yutian_cs_DB_HW1.users WHERE account = '$delete'";
						$id = $_SESSION['id'];
						$query2 = "SELECT * FROM yutian_cs_DB_HW1.users WHERE id = '$id'";
						$result1 = $db->query($query1);
						$result2 = $db->query($query2);
						$num_rows = $result1->rowCount();
						
						if($delete == $result2->fetchColumn(2)){
							$deleteErr = "Deletion failed";
						}
						else if($num_rows) {
							$query = "DELETE FROM yutian_cs_DB_HW1.users WHERE account = '$delete'";
							$db->query($query);
							$deleteErr = "Deletion success";
						}
						else{
							$deleteErr = "Account does not exist";
						}
						$db = null;
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
		<?php
			echo "<table style = 'border: solid 1px black; width: 100%; text-align: center;'>";
			echo "<tr><th>Identity</th><th>Account</th><th>Name</th><th>Email</th></tr>";
			class TableRows extends RecursiveIteratorIterator {
				function __construct($it) {
					parent::__construct($it, self::LEAVES_ONLY);
				}
				function current() {
					return "<td style='width: 150px; border: 1px solid black;'>" . parent::current(). "</td>";
				}
				function beginChildren() {
					echo "<tr>";
				} 
				function endChildren() {
					echo "</tr>" . "\n";
				} 
			} 		
		?>
		<?php
		$db = new PDO($dsn, $db_user, $db_password);
		
		$id = $_SESSION["id"];
		$query = $db->prepare("SELECT * FROM yutian_cs_DB_HW1.users WHERE id = '$id'");
		$query->execute();
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		foreach(new TableRows(new RecursiveArrayIterator($query->fetchAll())) as $k=>$v){
			if($k == "name" || $k == "account" || $k == "mail" || $k == "admin"){
				echo $v;
			}
		}
		echo "</table>";
		?>
		<br>Users List：
		<?php
		echo "<table style = 'border: solid 1px black; width: 100%; text-align: center;'>";
		echo "<tr><th>Identity</th><th>Account</th><th>Name</th><th>Email</th></tr>";		
		$query = $db->prepare("SELECT * FROM yutian_cs_DB_HW1.users");
		$query->execute();
		$result = $query->setFetchMode(PDO::FETCH_ASSOC);
		foreach(new TableRows(new RecursiveArrayIterator($query->fetchAll())) as $k=>$v){
			if($k == "name" || $k == "account" || $k == "mail" || $k == "admin"){
				echo $v;
			}
		}
		$db = null;
		echo "</table>";
		?>
		<a href="logout.php">Logout</a>
		<br><br>Users Management： 
		<br><a href="AddUser.php">Add User/Admin</a>
		<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
			<br>Promote User：<input type="text" name="promote" value="<?php echo $promote;?>">
			<input type="submit" name="submit" value="Promote">
			<span class="error"> <?php echo $promoteErr;?></span>
		</form>
		<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
			<br>Delete User/Admin：<input type="text" name="delete" value="<?php echo $delete;?>">
			<input type="submit" name="submit" value="Delete">
			<span class="error"> <?php echo $deleteErr;?></span>
		</form>
	</body>
</html>