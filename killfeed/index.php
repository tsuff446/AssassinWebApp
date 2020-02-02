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
            <h1>Killfeed:</h1>
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
                $sql = "SELECT * FROM gamefeed";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo "<table><tr><th>Killer:</th><th>Victim:</th><th>Disputed?</th><th>Time Of:</th><th>Confirmed?</th></tr>";
                    // output data of each row
                    while($row = $result->fetch_assoc()) {
						echo "<tr><td>".$row["KILLER_NAME"]."</td><td>".$row["VICTIM_NAME"]."</td><td>".$row["DISPUTED"]."</td>
						<td>".$row["KILL_TIME"]."</td><td>".$row["CONFIRMED"]."</td></tr>";
                    }
                    echo "</table>";
                } else {
                    echo "0 Players";
                }
            ?>
        </div>
	</body>
</html>