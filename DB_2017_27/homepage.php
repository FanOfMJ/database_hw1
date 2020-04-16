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
			Home Page
		</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<link type="text/css" rel="stylesheet" href="style.css">
	</head>
	<body>
		<h2>Welcome to the Home page!</h2>
		<h3>
		<?php
			if($_SESSION["admin"] == true){
				echo '<a href="member_management.php">Member Management</a>&emsp;';
			}
		?>
			<a href="house_management.php">House Management</a>&emsp;
			<a href="favorite.php">Favorite</a>&emsp;
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
			$id = $name = $price = $location = $time = $owner = $information = "";
			$favorite = $favoriteErr = "";
			$search = $condition = "";
			$user = $_SESSION["id"];
			if($_SERVER["REQUEST_METHOD"] == "POST"){
				if($_REQUEST["submit"] == "favorite"){
					$favorite = $_POST["favorite"];
					$db = new PDO($dsn, $db_user, $db_password);
					$favoriteQ = $db->prepare("SELECT * FROM Favorite 
						WHERE user_id = '$user' AND favorite_id = '$favorite'");
					$favoriteQ->execute();
					$result = $favoriteQ;
					$rowNum = $result->rowCount();
					if($rowNum){
						$favoriteErr = "Already in the list";
					}
					else{
						$favoriteQ = $db->prepare("INSERT INTO Favorite (id, user_id, favorite_id)
					VALUES (NULL, '$user', '$favorite')");
						$favoriteQ->execute();
					}
				}
				else if($_REQUEST["submit"] == "X"){
					$delete = $_POST["delete"];
					$db = new PDO($dsn, $db_user, $db_password);
					$deleteQ = $db->prepare("DELETE FROM House WHERE id = '$delete'");
					$deleteQ->execute();
				}
				else if($_REQUEST["submit"] == "search"){
					if(!empty($_POST['id']) || !empty($_POST['name']) || !empty($_POST['price']) 
					|| !empty($_POST['location']) || !empty($_POST['time']) || !empty($_POST['owner'])){
						$search = "";
						if(!empty($_POST['id'])){
							$id = test_input($_POST['id']);
							if(!empty($search)){
								$search = $search."AND ";
							}
							$search = $search."House.id like '%$id%' ";
						}
						if(!empty($_POST['name'])){
							$name = test_input($_POST['name']);
							if(!empty($search)){
								$search = $search."AND ";
							}
							$search = $search."House.name like '%$name%' ";
						}
						if(!empty($_POST['price'])){
							$price = $_POST['price'];
							if(!empty($search)){
								$search = $search."AND ";
							}
							$search = $search.$price." ";
						}
						if(!empty($_POST['location'])){
							$location = test_input($_POST['location']);
							if(!empty($search)){
								$search = $search."AND ";
							}							
							$search = $search."House.location like '%$location%' ";
						}
						if(!empty($_POST['owner'])){
							$owner = test_input($_POST['owner']);
							if(!empty($search)){
								$search = $search."AND ";
							}							
							$search = $search."User.name like '%$owner%' ";
						}
						if(!empty($_POST['time'])){
							$time = test_input($_POST['time']);
							if(!empty($search)){
								$search = $search."AND ";
							}
							$search = $search."House.time like '%$time%' ";
						}
					}
					if(!empty($_POST['information'])){
						foreach($_POST['information'] as $v){
							if(!empty($condition)){
								$condition = $condition."AND ";
							}
							$condition = $condition."House.id IN ( select Information.house_id from Information where Information.information ='$v') ";
						}
					}
					if(!empty($_POST['id']) || !empty($_POST['name']) || !empty($_POST['price']) 
					|| !empty($_POST['location']) || !empty($_POST['time']) || !empty($_POST['owner']) || !empty($_POST['information'])){
						$search = "WHERE ".$search." ".$condition;
					}
				}
				else if($_REQUEST['submit'] == "sort"){
					$search = $_POST['search'];
				}
			}
			$db = null;
		?>
		<table style = 'width: 100%; text-align: center;'>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["REQUEST_URI"]);?>">
			<tr>
				<td valign="bottom"><input type="text" placeholder = "keyword" name="id" style = "width: 100%;" value="<?php echo $id;?>"></td>
				<td valign="bottom"><input type="text" placeholder = "keyword" name="name" style = "width: 100%;" value="<?php echo $name;?>"></td>
				<td valign="bottom">
					<select name="price" style = "width: 100%;">
						<option value="" disabled selected hidden>interval</span></option>
						<option value=""></option>
						<option value="House.price < 3000">0 ~ 2999</option>
						<option value="House.price >= 3000 AND House.price < 6000">3000 ~ 5999</option>
						<option value="House.price >= 6000 AND House.price < 12000">6000 ~ 11999</option>
						<option value="House.price >= 12000">12000 ~ </option>
					</select>
				</td>
				<td valign="bottom"><input type="text" placeholder = "keyword" name="location" style = "width: 100%;" value="<?php echo $location;?>"></td>
				<td valign="bottom"><input type="text" placeholder = "keyword" name="time" style = "width: 100%;" value="<?php echo $time;?>"></td>
				<td valign="bottom"><input type="text" placeholder = "keyword" name="owner" style = "width: 100%;" value="<?php echo $owner;?>"></td>
				<td>
				<input type="checkbox" value="laundry facilities" name="information[]"> laundry facilities <br>
				<input type="checkbox" value="wifi" name="information[]"> wifi <br>
				<input type="checkbox" value="lockers" name="information[]"> lockers <br>
				<input type="checkbox" value="kitchen" name="information[]"> kitchen <br>
				<input type="checkbox" value="elevator" name="information[]"> elevator <br>
				<input type="checkbox" value="no smoking" name="information[]"> no smoking <br>
				<input type="checkbox" value="television" name="information[]"> television <br>
				<input type="checkbox" value="breakfast" name="information[]"> breakfast <br>
				<input type="checkbox" value="toiletries provided" name="information[]"> toiletries provided <br>
				<input type="checkbox" value="shuttle service" name="information[]"> shuttle service <br>
				</td>
				<td valign="bottom"><input type="submit" name="submit" value="search" style = "width: 50%;"></td>
			</tr>
		</form>
		<tr>
			<th style='width: 150px; border: 1px solid black;'>id</th>
			<th style='width: 150px; border: 1px solid black;'>name</th>
			<th style='width: 150px; border: 1px solid black;'>
				<table style = 'width: 100%; text-align: center; '>
					<tr><td>
						<form method="post" action="homepage.php?c=price&amp;o=ASC" class="inline">
							<input type="hidden" name="search" value="<?php echo $search;?>">
							<button type="submit" name="submit" value="sort" class="link-button">
								<span class='tri_up'></span>
							</button>
						</form>
					</td>
					<td>price</td>
					<td>
						<form method="post" action="homepage.php?c=price&amp;o=DESC" class="inline">
							<input type="hidden" name="search" value="<?php echo $search;?>">
							<button type="submit" name="submit" value="sort" class="link-button">
								<span class='tri_down'></span>
							</button>
						</form>
					</td></tr>
				</table>
			</th>
			<th style='width: 150px; border: 1px solid black;'>location</th>
			<th style='width: 150px; border: 1px solid black;'>
				<table style = 'width: 100%; text-align: center;'>
					<tr><td>
						<form method="post" action="homepage.php?c=time&amp;o=ASC" class="inline">
							<input type="hidden" name="search" value="<?php echo $search;?>">
							<button type="submit" name="submit" value="sort" class="link-button">
								<span class='tri_up'></span>
							</button>
						</form>
					</td>
					<td>time</td>
					<td>
						<form method="post" action="homepage.php?c=time&amp;o=DESC" class="inline">
							<input type="hidden" name="search" value="<?php echo $search;?>">
							<button type="submit" name="submit" value="sort" class="link-button">
								<span class='tri_down'></span>
							</button>
						</form>
					</td></tr>
				</table>
			</th>
			<th style='width: 150px; border: 1px solid black;'>owner</th>
			<th style='width: 150px; border: 1px solid black;'>information</th>
			<th style='width: 150px; border: 1px solid black;'>option</th>
		</tr>
		<?php
		$db = new PDO($dsn, $db_user, $db_password);
		$order = "";
		if(!empty($_GET['c']) && !empty($_GET['o'])){
			$c = test_input($_GET['c']);
			$o = test_input($_GET['o']);
			$order = "ORDER BY House.$c $o";
		}
		
		$stmt = "SELECT House.id, House.name, House.price, House.location, House.time, User.name FROM House 
			JOIN User ON User.id = House.owner_id ".$search.$order;
		//echo $stmt;
		$houseQ = $db->prepare($stmt);
		$houseQ->execute();
		$houseRows = $houseQ->fetchAll();
		
		foreach($houseRows as $row){
			echo "<tr>";
			$houseId = $row[0];
			for($i = 0;$i < 6;$i++){
				echo "<td style='width: 150px; border: 1px solid black;'>".$row[$i]."</td>";
			}
			$infoQ = $db->prepare("SELECT Information.information FROM Information WHERE Information.house_id = '$houseId'");
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
					echo '<input type="hidden" name="favorite" value='.$houseId.'>';
					echo '<input type="submit" name="submit" value="favorite">';
				if($_SESSION["admin"] == true){
					echo '<input type="hidden" name="delete" value='.$houseId.'>';
					echo '<input type="submit" name="submit" value="X" style="color:red">';
				}
				if($favorite == $houseId){
					echo "</br><font color='red'>".$favoriteErr."</font>";
				}
				echo '</td>';
			echo "</form>";
			echo "</tr>" . "\n";
		}
		echo "</table>";
		$db = null;
		?>
	</body>
</html>