<?php 
	session_start();
	
	if(!isset($_SESSION["login"])){
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/login.php';</script>";
	}
	/*else if($_SESSION["admin"] == false){
		echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/user.php';</script>";
	}*/
?>
<!DOCTYPE HTML>
<html>
	<head>
		<style>
			.error {color: #FF0000;}
		</style>
		<title>
			Update House Page
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
		$name = $price = $location = $information = $houseId = "";
		
		$time = date("Y/m/d"); //php取到的是 格林威治 時間(若要改成台北時間，則要另加東西)
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$name = test_input($_POST["name"]); 
				$price = test_input($_POST["price"]);
				$location = test_input($_POST["location"]);
				$houseId = test_input($_POST["houseId"]);
				if (empty($_POST["information"])) {
					$informationErr = "Information is required";
				}
				else{
					$information = $_POST["information"];
				}
		}
		
		function test_input($data) {
			$data = trim($data);
			$data = stripslashes($data);
			$data = htmlspecialchars($data);
			return $data;
		}
		?>
		
		<h2>Update House</h2>
		<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
			House Id: <input type="text" name="houseId" value="<?php echo $houseId;?>">
			<br><br>
			Name: <input type="text" name="name" value="<?php echo $name;?>">
			<br><br>
			Price: <input type="number" name="price" value="<?php echo $price;?>">
			<br><br>
			Location: <input type="text" name="location" value="<?php echo $location;?>">
			<br><br>
			Information(multiple-choice):
			<br>
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
			<br>
			<input type="submit" name="submit" value="Submit"> <!--save datas into the variables-->
		</form>
			<br>
			<input type="button" value="Cancel" onclick="location.href='house_management.php'">
		
		<?php
			$db = new PDO($dsn, $db_user, $db_password);
			$owner_id = $_SESSION["id"];
			$query = "SELECT * FROM House WHERE House.owner_id='$owner_id' and House.id='$houseId'";
			$result = $db->query($query);
			$num_rows = $result->rowCount();
			if($num_rows) { //////////	
				if($name != "" || $price != "" || $location != "" || $information != "")
				{
					if ($name != ""){
						$nameQ = "UPDATE House SET name='$name' WHERE House.id='$houseId' and House.owner_id='$owner_id'";
						$db->exec($nameQ);//throw into mysql
					}
					
					if ($price!= ""){
						$priceQ = "UPDATE House SET price='$price' WHERE House.id='$houseId' and House.owner_id='$owner_id'";
						$db->exec($priceQ);//throw into mysql
					}
					
					if ($location != ""){
						$locationQ = "UPDATE House SET location='$location' WHERE House.id='$houseId'";
						$db->exec($locationQ);//throw into mysql
					}
					$timeQ = "UPDATE House SET time='$time' WHERE House.id='$houseId'";
					$db->exec($timeQ);
					
					if($information != "")
					{
						$clear = "DELETE FROM Information WHERE house_id='$houseId'";
						$db->exec($clear);
					}
					foreach($information as $info){
						$query = "INSERT INTO Information (id, information, house_id) VALUES (NULL, '$info', '$houseId')";
						$db->exec($query);
					}
					$db = null;
					//echo $houseId;
					//if successful, connect to the house_management page (use JS)
					echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/house_management.php';</script>";
				}	
				else if(isset($_POST["submit"]))
				{
					echo "<p style='color:red'>You didn't update anything.</p>";
				}
			}
			else if(isset($_POST["submit"]) && $houseId != "")
			{
				echo "You don't own this house.";
			}
			else if($houseId == "")
			{
				echo "Please fill the House Id";
			}
		?>
	</body>
</html>