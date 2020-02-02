<html>
	<head>
		<link rel="stylesheet" type="text/css" href="style.css">
	</head>
	<body>

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
            <h1>Currently Alive Players:</h1>
            <?php
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
                $sql = "SELECT * FROM myusers WHERE ALIVE = 1 ORDER BY `myusers`.`KILLS` DESC";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Names:</th><th>Kills:</th></tr>";
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
                        echo "<tr><td>".$row["NAME"]."</td><td>".$row["KILLS"]."</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 Players";
                }
            ?>
        </div>
	</body>
</html>