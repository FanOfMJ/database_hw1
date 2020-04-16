<?php 
	session_start();
	
	if(!isset($_SESSION["login"])){
		echo "<script type = 'text/javascript'>document.location.href = 'login.php';</script>";
	}
	else if($_SESSION["admin"] == false){
		echo "<script type = 'text/javascript'>document.location.href = 'homepage.php';</script>";
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			Member Management Page
		</title>
	</head>
	<body>
		<h2>Welcome to the Member Management page!</h2>
		<h3>
			<a href="homepage.php">Home Page</a>&emsp;
			<a href="logout.php">Logout</a>
		</h3>
		<?php
			$db_host = "dbhome.cs.nctu.edu.tw";
			$db_name = "yutian_cs_DB_HW2";
			$db_user = "yutian_cs";
			$db_password = "180701998$$$";
			$dsn = "mysql:host=$db_host;dbname=$db_name";
			
			$delete = "";
			$deleteErr = "";
			
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				if($_REQUEST["submit"] == "Promote"){
					$promote = $_POST["promote"];
					$db = new PDO($dsn, $db_user, $db_password);	
					$query = "UPDATE User SET admin = 'admin' WHERE id = '$promote'";
					$db->query($query);
					$db = null;
				}
				else if($_REQUEST["submit"] == "X"){
					$delete = $_POST["delete"];
					$db = new PDO($dsn, $db_user, $db_password);
					$id = 1;//$_SESSION['id'];
					$query = "SELECT * FROM User WHERE id = '$id'";
					$result = $db->query($query);
					if($delete == $result->fetchColumn(2)){
						$deleteErr = "Deletion failed";
					}
					else{
						$deleteQ = $db->prepare("DELETE FROM User WHERE id = '$delete'");
						$deleteQ->execute();
					}
					$db = null;
				}
			}
		?>
		<?php
			echo "<table style = 'border: solid 1px black;'>";
			echo "<tr><th>Account</th>";
			echo "<th>Name</th>";
			echo "<th>Email</th></tr>";
			$db = new PDO($dsn, $db_user, $db_password);
			
			$id = 1;//$_SESSION["id"];
			$query = $db->prepare("SELECT User.account, User.name, User.mail FROM User WHERE id = '$id'");
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row){
				foreach($row as $v){
					echo "<td style='width: 150px; border: 1px solid black;'>".$v."</td>";
				}
				echo "</tr>" . "\n";
			}
			echo "</table>";
		?>
		<br>Users List：
		<?php
			echo "<table style = 'border: solid 1px black;'>";
			echo "<tr><th>Identity</th>";
			echo "<th>Account</th>";
			echo "<th>Name</th>";
			echo "<th>Email</th>";
			echo "<th>option</th></tr>";		
			$query = $db->prepare("SELECT User.admin, User.account, User.name, User.mail, User.id FROM User");
			$query->execute();
			$result = $query->fetchAll(PDO::FETCH_ASSOC);
			foreach($result as $row){
				$userId = $row['id'];
				foreach($row as $k=>$v){
					if($k != 'id')
						echo "<td style='width: 150px; border: 1px solid black;'>".$v."</td>";
				}
				echo '<form method="post" action=';echo htmlspecialchars($_SERVER["REQUEST_URI"]);echo'>';
					echo '<td style="width: 150px; border: 1px solid black;">';
						echo '<input type="hidden" name="promote" value='.$userId.'>';
						echo '<input type="submit" name="submit" value="Promote">';
						echo '<input type="hidden" name="delete" value='.$userId.'>';
						echo '<input type="submit" name="submit" value="X" style="color:red">';
					echo '</td>';
				if($delete == 1){/*$_SESSION["id"]*/
					echo "</br><font color='red'>".$deleteErr."</font>";
				}
				echo "</form>";
				echo "</tr>" . "\n";
			}
			echo "</table>";
			$db = null;
		?>
		<br><h3><a href="add_user.php">Add User/Admin</a></h3>
	</body>
</html>