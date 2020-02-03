<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
        <?php
			if(!isset($_COOKIE["userID"]))
				header("Location: ../");

			date_default_timezone_set("America/Chicago");
			$myfile = fopen("../sql_creds.txt", "r") or die("Unable to open file!");
            $servername = trim(fgets($myfile));
            $username = trim(fgets($myfile));
            $password = trim(fgets($myfile));
            $dbname = trim(fgets($myfile));
            fclose($myfile);
			// Create connection
			$conn = new mysqli($servername, $username, $password, $dbname);
			// Check connection
			if ($conn->connect_error) {
				die("Connection failed: " . $conn->connect_error);
			}
			$curr_user = $_COOKIE["userID"];
			if($conn->query("SELECT * FROM myusers WHERE USERNAME = '$curr_user' AND ALIVE = '0'")->num_rows > 0)
				header("Location: ../dead");
		?>
		<div class="navBar">
			<a href="../">
			<div class="navElement">
				Home
			</div>
			</a>
			<a href="../portal">
			<div class="navElement">
				Assassin's Portal
			</div>
			</a>
			<a href="../standings">
			<div class="navElement">
				Standings
			</div>
			</a>
			<a href="../killfeed">
			<div class="navElement">
				Killfeed
			</div>
			</a>
			<a href="../signout">
			<div class="navElement">
				Sign Out
			</div>
			</a>
		</div>
		<div class="centerBox">
            <h1>Your Target:</h1>
			<?php
				$sql = "SELECT * FROM gameorder";
				$result = $conn->query($sql);
				$j = 0;
				$next = False;
				$target = "";
				$target_id = "";
				$curr_user_name = "";
				while($row = $result->fetch_assoc()){
					if($j == 0){
						$target = $row["NAME"];
					}
					if($next){
						$target = $row["NAME"];
						$target_id = $row["USERNAME"];
						break;
					}
					if($row["USERNAME"] == $curr_user){
						$next = True;
						$curr_user_name = $row["NAME"];
					}
					$j = $j + 1;
				}
				echo "<p>";
				echo "$target";
				echo "</p>";				
			?>
			<hr>
			<form method="post"> 
				<p> Type your name, <?php echo $curr_user_name;?>, into the box and hit submit if you've killed your target </p>
				<input type="text" name="nameBox">
                <input type="submit" name="killButton" value="Submit"> 
            </form> 
		</div>
		<?php
		

		if($_SERVER["REQUEST_METHOD"] == "POST"){
			if($conn->query("SELECT * FROM gamefeed WHERE KILLER_ID = '$curr_user' AND VICTIM_ID = '$target_id'")->num_rows > 0){
				echo "This request is already in the system";
			}else{
			$curr_timestamp = date("Y-m-d H:i:s", time()); 
			$success = True;
			if($_POST["nameBox"] == $curr_user_name){
				// Adds a kill to the assassin
				$conn->query("UPDATE myusers SET KILLS = KILLS + 1 WHERE USERNAME = '$curr_user'");
				// Adds a line to the killfeed
				$conn->query("INSERT INTO gamefeed (KILLER_ID, KILLER_NAME, VICTIM_ID, VICTIM_NAME, KILL_TIME, DISPUTED, CONFIRMED)
							VALUES ('$curr_user','$curr_user_name', '$target_id', '$target','$curr_timestamp', '0', '0')");
				// Sets the victim to not alive
				$conn->query("UPDATE myusers SET ALIVE = 0 WHERE USERNAME = '$target_id'");
				echo "Your request has been submitted.";
			}else{
				echo "Wrong name";
			}
			}
		}
		?>
	</body>
</html>