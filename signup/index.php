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
		</div>
		<div class="centerBox">
			<h1> Sign up </h1>
			<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
				<p> Please make an account to continue </p>
				<label>Name</label> <br>
				<input type="text" name="name"><br>
				<label>Email</label> <br>
				<input type="text" name="email"><br>
				<br>
				<input type="submit">
				<input type="reset">
			</form>
		</div>
	<?php
	if($_SERVER["REQUEST_METHOD"] == "POST"){

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
		
		// Generating userID
		$username_add = "";
		while(strlen($username_add) < 10){
			if(random_int ( 0 , 1 ) == 1){
				$username_add = $username_add . chr(random_int(97,122));
			}else{
				$username_add = $username_add . chr(random_int(48,57));
			}
		}

		// Sanitizing inputs and checking for errors
		$unfilledFields = False;
		$name_add = htmlspecialchars($_POST["name"]);
		$email_add = htmlspecialchars($_POST["email"]);

		if(empty(trim($name_add)) || !(strpos($name_add, '\'') === False)){
			$unfilledFields = True;
		}else{
			$name_add = trim($name_add);
		}

		if(empty(trim($email_add)) || (strpos($email_add, '@') === False) || !(strpos($email_add, '\'') === False)){
			$unfilledFields = True;
		}else{
			$email_add = trim($email_add);
		}

		if(!$unfilledFields){
			$sql = "SELECT * FROM myusers WHERE EMAIL = '$email_add'";

			if ($conn->query($sql)->num_rows == 0) {
				if($conn->query("INSERT INTO myusers (USERNAME, NAME, EMAIL, ALIVE, KILLS) VALUES ('$username_add','$name_add', '$email_add', 1, 0)") == True){
					setCookie("userID", $username_add, time()+60*24*60*60, "/");
					echo "Form Submitted Successfully, your ID is ". $username_add . ". You will receive an email on start day with this code.";
				} else {
					echo "Error: " . $sql . "<br>" . $conn->error;
				}
			}else{
				echo "Error: Email is already registered with an account";
			}
			
		}else{
			echo "One or more fields not filled or are filled improperly";
		}
		$conn->close();
	}
	?> 
	</body>
</html>