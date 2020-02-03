<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
        <?php
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

            $myfile = fopen("../admin_password.txt", "r") or die("Unable to open file!");
            $admin_pass = trim(fgets($myfile));
            fclose($myfile);
            if(!isset($_COOKIE["userID"]) && strlen($admin_pass) > 0)
                header("Location: ../"); 
            if(isset($_COOKIE["userID"]) && strlen($admin_pass) > 0 && $_COOKIE["userID"] != $admin_pass)
                header("Location: ../"); 
             
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
		<div class="centerBox" style="width: 75%; display:flex; justify-content: space-around; align-items: center;">
            <div style="width:50%">
            <form method="post"> 
                <label> Shuffle Assassin Targets </label>
                <input type="submit" name="shuffleButton" value="Shuffle"> 
            </form> 
            <?php
                    if(array_key_exists('shuffleButton', $_POST)) { 
                        if($conn->query("SELECT * FROM gamefeed WHERE CONFIRMED = '0'")->num_rows == 0){
                            $conn->query("TRUNCATE TABLE gameorder;");

                            $sql = "INSERT INTO gameorder (username, name) 
                            SELECT username, name FROM myusers WHERE ALIVE = '1' ORDER BY RAND()";
                            if(!$conn->query($sql)){
                                echo "Error description: " . $conn -> error;
                            }else{
                                echo "Succcess";
                            } 
                        }else{
                            echo "Solve all pending kills first";
                        }                     
                    } 
            ?>
            </div>
            <div style="width:50%;">
                <?php
                $sql = "SELECT * FROM gamefeed WHERE CONFIRMED = 0";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Killer:</th><th>Victim:</th><th>Disputed?</th><th>Time:</th></tr>";
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        if($row["CONFIRMED"] != '1'){
						    echo "<tr><td>".$row["KILLER_NAME"]."</td><td>".$row["VICTIM_NAME"]."</td><td>".$row["DISPUTED"]."</td>
                                <td>".$row["KILL_TIME"]."</td><td> <form method='post'> ";
                                echo "<button type='submit' name='approve' value=".$row["KILLER_ID"].$row["VICTIM_ID"].">APPROVE</button>";
                                echo "<button type='submit' name='deny' value=".$row["KILLER_ID"].$row["VICTIM_ID"].">DENY</button>";
                            echo "</form></td></tr>";
                        }
                    }
                    echo "</table>";
                } else {
                    echo "0 Players";
                }
                ?>
                <?php
                    if($_SERVER["REQUEST_METHOD"] == "POST"){

                        if(isset($_POST['approve'])) {
                            
                            $killer_id = substr($_POST['approve'],0, strlen($_POST['approve'])/2);
                            $target_id = substr($_POST['approve'], strlen($_POST['approve'])/2, strlen($_POST['approve']));
                            $conn->query("UPDATE myusers SET ALIVE = 0 WHERE USERNAME = '$target_id'");
                            $conn->query("DELETE FROM gameorder WHERE USERNAME = '$target_id'");
                            $conn->query("UPDATE gamefeed SET CONFIRMED = '1' WHERE VICTIM_ID = '$target_id'");
                            $conn->query("UPDATE gamefeed SET DISPUTED = '0' WHERE VICTIM_ID = '$target_id'");
                            header("Refresh:0");
                        }
                        if(isset($_POST['deny'])) {
                            $killer_id = substr($_POST['deny'],0, strlen($_POST['deny'])/2);
                            $target_id = substr($_POST['deny'], strlen($_POST['deny'])/2, strlen($_POST['deny']));

                            $conn->query("UPDATE myusers SET ALIVE = 1 WHERE USERNAME = '$target_id'");
                            $conn->query("DELETE FROM gamefeed WHERE VICTIM_ID = '$target_id'");
                            $conn->query("UPDATE myusers SET KILLS = KILLS - 1 WHERE USERNAME = '$killer_id'");
                            header("Refresh:0");
                        }
                    
                    }
                ?>
            </div>
		</div>
	</body>
</html>