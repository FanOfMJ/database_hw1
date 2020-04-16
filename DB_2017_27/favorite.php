<?php 
	session_start();
	
	if(!isset($_SESSION["login"])){
		echo "<script type = 'text/javascript'>document.location.href = 'login.php';</script>";
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			Favorite List Page
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="style.css">
	</head>
	<body>
		<h2>Welcome to the Favorite List page!</h2>
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
			function test_input($data) {
				$data = trim($data);
				$data = stripslashes($data);
				$data = htmlspecialchars($data);
			return $data;
		}
		?>
		<?php
			$delete = "";
			$user = $_SESSION["id"];
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if($_REQUEST["submit"] == "X"){
					$delete = $_POST["delete"];
					$db = new PDO($dsn, $db_user, $db_password);
					$deleteQ = $db->prepare("DELETE FROM Favorite WHERE user_id = '$user' AND favorite_id = '$delete'");
					$deleteQ->execute();
				}
			}
			$db = null;
		?>
		<?php
		$db = new PDO($dsn, $db_user, $db_password);
		$order = "";
		if(!empty($_GET['c']) && !empty($_GET['o'])){
			$c = test_input($_GET['c']);
			$o = test_input($_GET['o']);
			$order = "ORDER BY House.$c $o";
		}
		
		$stmt = "SELECT House.id, House.name, House.price, House.location, House.time, User.name FROM House 
			JOIN User ON User.id = House.owner_id 
			JOIN Favorite ON Favorite.favorite_id = House.id 
			WHERE Favorite.user_id = '$user'".$order;
		//echo $stmt;
		$houseQ = $db->prepare($stmt);
		$houseQ->execute();
		$rowNum = $houseQ->rowCount();
		$houseRows = $houseQ->fetchAll();
		
		if(!$rowNum){
			echo "<h4>You don't have any favorite house</h4>";
		}
		else{
			echo "<table style = 'border: solid 1px black; width: 100%; text-align: center;'>";
			echo "<tr><th>id</th>";
			echo "<th>name</th>";
			echo "<th><a href='favorite.php?c=price&amp;o=ASC'><span class='tri_up'></span></a>&emsp;price <a href='favorite.php?c=price&amp;o=DESC'><span class='tri_down'></span></a></th>";
			echo "<th>location</th>";
			echo "<th><a href='favorite.php?c=time&amp;o=ASC'><span class='tri_up'></span></a>&emsp;time <a href='favorite.php?c=time&amp;o=DESC'><span class='tri_down'></span></a></th>";
			echo "<th>owner</th>";
			echo "<th>information</th>";
			echo "<th>option</th></tr>";
			foreach($houseRows as $row){
				echo "<tr>";
				$houseId = $row[0];
				for($i = 0;$i < 6;$i++){
					echo "<td style='width: 150px; border: 1px solid black;'>".$row[$i]."</td>";
				}
				$infoQ = $db->prepare("SELECT Information.information FROM yutian_cs_DB_HW2.Information WHERE Information.house_id = '$houseId'");
				$infoQ->execute();
				$info = $infoQ->fetchAll(PDO::FETCH_ASSOC);
				
				echo "<td style='width: 150px; border: 1px solid black;'>";
				foreach($info as $s){
					foreach($s as $ss){
						echo $ss;
					}
					echo "</br>";
				}
				echo "</td>";
				
				echo '<form method="post" action=';echo htmlspecialchars($_SERVER["REQUEST_URI"]);echo'>';
					echo '<td style="width: 150px; border: 1px solid black;">';
						echo '<input type="hidden" name="delete" value='.$houseId.'>';
						echo '<input type="submit" name="submit" value="X" style="color:red">';
					echo '</td>';
				echo "</form>";
				echo "</tr>" . "\n";
			}
			echo "</table>";
		}
		$db = null;
		?>
	</body>
</html>