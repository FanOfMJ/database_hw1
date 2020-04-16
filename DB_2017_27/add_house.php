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
			Add House Page
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
		$nameErr = $priceErr = $locationErr = $ownerErr = $informationErr = "";
		$name = $price = $location = $owner = $information = "";
		$time = date("Y/m/d"); //php取到的是 格林威治 時間(若要改成台北時間，則要另加東西)
		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			if (empty($_POST["name"])) {
				$nameErr = "Name is required";
			}
			else {
				$name = test_input($_POST["name"]); 
			}
		
			if (empty($_POST["price"])) {
				$priceErr = "Price is required";
			}
			else {
				$price = test_input($_POST["price"]);
			}
			
			if(empty($_POST["location"])) {
				$locationErr = "Location is required";
			}
			else{
				$location = test_input($_POST["location"]);
			}
			
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
		<h2>Add House</h2>
		<form method="post" action=<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>>
			Name: <input type="text" name="name" value="<?php echo $name;?>">
			<span class="error"> <?php echo $nameErr;?></span>
			<br><br>
			Price: <input type="number" name="price" value="<?php echo $price;?>">
			<span class="error"> <?php echo $priceErr;?></span>
			<br><br>
			Location: <input type="text" name="location" value="<?php echo $location;?>">
			<span class="error"> <?php echo $locationErr;?></span>
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
			if($nameErr == "" && $priceErr == "" && $locationErr == "" && $ownerErr == "" && $name != "") {
				$db = new PDO($dsn, $db_user, $db_password);
				$owner_id = $_SESSION["id"];
				$sql = "INSERT INTO yutian_cs_DB_HW2.House (id, name, price, location, time, owner_id)
				VALUES (NULL, '$name', '$price', '$location', '$time', '$owner_id')";
				$db->exec($sql);//throw into mysql
				
				if($information != "")
				{
					$test = $db->lastInsertId();
					foreach($information as $info){
						$query = "INSERT INTO yutian_cs_DB_HW2.Information (id, information, house_id) VALUES (NULL, '$info', '$test')";
						$db->exec($query);
					}
				}
				$db = null;
				
				//if successful, connect to the house_management page (use JS)
				echo "<script type = 'text/javascript'>document.location.href = 'http://people.cs.nctu.edu.tw/~yutian/house_management.php';</script>";
			}
		?>
	</body>
</html>