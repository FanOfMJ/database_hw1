<?php 
	session_start();
	
	/*if(!isset($_SESSION["login"])){
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/login.php';</script>";
	}
	else if($_SESSION["admin"] == true){
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/admin.php';</script>";
	}*/
	if(!isset($_SESSION["login"])){ //isset: 看此變量是否存在
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/login.php';</script>";
	}
?>
<!DOCTYPE HTML>
<html>
	<head>
		<title>
			House Management
		</title>
	</head>
	<body>
		<h2> ~~House Management~~ </h2>
		<input type="button" value="Add" onclick="location.href='add_house.php'">
		<input type="button" value="Update" onclick="location.href='update_house.php'">
		<br><br>
		<?php
			$db_host = "dbhome.cs.nctu.edu.tw";
			$db_name = "yutian_cs_DB_HW2";
			$db_user = "yutian_cs";
			$db_password = "180701998$$$";
			$dsn = "mysql:host=$db_host;dbname=$db_name";
			$delete = "";
			//$user = 1;
			$id = $_SESSION["id"];
			
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if($_REQUEST["submit"] == "X"){
					$delete = $_POST["delete"];
					$db = new PDO($dsn, $db_user, $db_password);
					$deleteQ = $db->prepare("DELETE FROM House WHERE id = '$delete'");
					$deleteQ->execute();
				}
			}
			$db = null;
		?>
		
		<?php
		$db_host = "dbhome.cs.nctu.edu.tw";
		$db_name = "yutian_cs_DB_HW2";
		$db_user = "yutian_cs";
		$db_password = "180701998$$$";
		$dsn = "mysql:host=$db_host;dbname=$db_name";
		$db = new PDO($dsn, $db_user, $db_password);
		
		$id = $_SESSION["id"];
		$houseQ = $db->prepare("SELECT House.id, House.name, House.price, House.location, House.time FROM yutian_cs_DB_HW2.House WHERE House.owner_id = '$id'");
		$houseQ->execute();
		$houseRows = $houseQ->fetchAll();
		
		$stmt = "SELECT User.name FROM House JOIN User ON User.id = '$id'";
		
		//echo $stmt;
		$userQ = $db->prepare($stmt);
		$userQ->execute();
		$user = $userQ->fetchAll();
		$j = 0;
		$Count = count($houseRows);
		if($Count < 1){
			echo "You haven't had any house yet"."\n";
		}
		else
		{
			echo "<table style = 'border: solid 1px black; width: 100%; text-align: center;'>";
			echo "<tr><th>id</th><th>name</th>";
			echo "<th>price</th>";
			echo "<th>location</th>";
			echo "<th>time</th>";
			echo "<th>owner</th>";
			echo "<th>information</th>";
			echo "<th>option</th></tr>";
		}
		foreach($houseRows as $row){
			echo "<tr>";
			$houseId = $row[0];
			
			for($i = 0;$i < 5;$i++){
				echo "<td style='width: 150px; border: 1px solid black;'>".$row[$i]."</td>";
			}
			echo "<td style='width: 150px; border: 1px solid black;'>".$user[$j][0]."</td>";
			$infoQ = $db->prepare("SELECT Information.information FROM yutian_cs_DB_HW2.Information WHERE Information.house_id = '$houseId'");

			$infoQ->execute();
			$info = $infoQ->fetchAll(PDO::FETCH_ASSOC);
			
			echo "<td style='width: 150px; border: 1px solid black;'>";
			foreach($info as $s)
			{
				foreach($s as $ss){
					echo $ss;
				}
				echo "</br>";
			}
			echo "</td>";
		
			//delete button
			echo '<td>';
			echo '<form method="post" action=';echo htmlspecialchars($_SERVER["REQUEST_URI"]);echo'>';			
						echo '<input type="hidden" name="delete" value='.$houseId.'>';
						echo '<input type="submit" name="submit" value="X" style="color:red">';
					echo '</td>';
			echo "</tr>" . "\n";
			$j++;
		}
		echo "</table>";
		
		$db = null; //cut the connection
		
		?>
		</br>
		<input type="button" value="Logout" onclick="location.href='logout.php'">
		<input type="button" value="Back To Homepage" onclick="location.href='homepage.php'">
	</body>
</html>