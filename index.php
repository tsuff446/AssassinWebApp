<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>
        <?php
			if(isset($_COOKIE["userID"]))
                header("Location: /portal");
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
            <h1>Welcome to Slivka Assassins!</h1>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
                <p>Login with your assassin's ID here</p>
                <input type="text" name="ID">
                <input type="submit">
            </form>
            <span style="display: flex; justify-content: center; align-items: center;">
                <p>Don't have an ID?</p><a href="signup">Sign up</a><p>here!</p>
            </span>
		</div>
        <?php
        if($_SERVER["REQUEST_METHOD"] == "POST"){
    
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

            $unfilledFields = False;
            $user_input = htmlspecialchars($_POST["ID"]);

            if(empty(trim($user_input)) || !(strpos($user_input, '\'') === False)){
                $unfilledFields = True;
            }else{
                $user_input = trim($user_input);
            }

            $sql = "SELECT * FROM myusers WHERE USERNAME = '$user_input'";
            if(!$unfilledFields){
                if($conn->query($sql)->num_rows > 0){
                    setCookie("userID", $user_input, time()+60*24*60*60, "/");
                    header("Location: ../portal/index.php");
                }else{
                    echo "User not Found";
                }
            }else{
                echo "Input to field is improper";
            }
        }
        ?>
	</body>
</html>