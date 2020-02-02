<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
        <?php  
            if(!isset($_COOKIE["userID"]))
                header("Location: ../");  
            $curr_user = $_COOKIE["userID"];
            
            $servername = 'localhost';
            $username = 'thomas';
            $password = 'notadmin';
            $dbname = 'userdata';
            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            if($conn->query("SELECT * FROM myusers WHERE ALIVE = '1' AND USERNAME = '$curr_user' ")->num_rows > 0)
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
		<div class="centerBox">
            <h1>You have been Killed...</h1>
            <?php
                $displayChange = "";
                if($conn->query("SELECT * FROM gamefeed WHERE VICTIM_ID = '$curr_user' AND CONFIRMED = '1'")->num_rows > 0){
                    $displayChange = "style='display: none'";
                }
            ?>
            <form method="post" <?php echo $displayChange; ?>>
            <input style="color: green" type="submit" name="ConfirmButton" value="Confirm"> 
            <input style="color: red" type="submit" name="DisputeButton" value="Dispute"> 
            </form> 
            <?php
                    if(array_key_exists('ConfirmButton', $_POST)) { 
                        $conn->query("DELETE FROM gameorder WHERE USERNAME = '$curr_user'");
                        $conn->query("UPDATE gamefeed SET CONFIRMED = '1' WHERE VICTIM_ID = '$curr_user'");
                        header("Refresh:0");
                    }
                    if(array_key_exists('DisputeButton', $_POST)) { 
                        $conn->query("UPDATE myusers SET ALIVE = '1' WHERE USERNAME = '$curr_user'");
                        header("Refresh:0");
                    }
            ?>
            </form>
		</div>
	</body>
</html>