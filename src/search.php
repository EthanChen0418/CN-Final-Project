<!DOCTYPE html>
<head>
	<title>Search Results</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
	<?php
		session_start();
	?>
</head>

<body>
	<?php
		if(ISSET($_GET['email_or_account'])){
			$sql_servername = "localhost";
			$sql_username = "root";
			$sql_password = "root";
			$conn = new mysqli($sql_servername, $sql_username, $sql_password);
			if ($conn->connect_error)
			{
				die("SQL Connection failed: " . $conn->connect_error);
			}

			$selectDB = "USE userDB;";
			if($conn -> query($selectDB) == FALSE)
			{
				echo "Error: " . $sql . "<br>" . $conn->error;
			}

			$sql = "SELECT email, account FROM account_info WHERE email='" . $_GET['email_or_account'] . "' OR account='" . $_GET['email_or_account'] . "';";
			$result = $conn->query($sql);
			if($result->num_rows > 0)
	        {
	        	echo "<form action=\"chatroom.php\" method=\"get\"><br>";
	        	while($row = $result->fetch_assoc())
	        	{
	        		echo "<button class=\"login100-form-btn\" name=\"email\" value=\"" . $row['email'] . "\">" . $row['account'] . "(" . $row['email'] . ")<br></button>";
	        	}
	        	echo "</form>";
	        }
	        else
	        {
	        	echo "<h2>No results.<br></h2>";
	        }
    	}
    	else
    	{
    		echo "<h2>Error: no search request.<br></h2>";
    	}
	?>

	<div class="container-login100-form-btn">
		<button class="login100-form-btn" onclick="window.location.href='home.php'">
			Return
		</button>
	</div>
</body>