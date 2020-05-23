<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>
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
</head>

<body>
	<?php
		if(isset($_POST))
		{
			$sql_servername = "localhost";
			$sql_username = "root";
			$sql_password = "root";
			$conn = new mysqli($sql_servername, $sql_username, $sql_password);

			if ($conn->connect_error)
			{
 			   die("SQL Connection failed: " . $conn->connect_error);
			}

			$selectDB = "USE userDB;";
			if($conn -> query($selectDB) == FALSE){
				echo "Error: " . $sql . "<br>" . $conn->error;
			}
			$encrypted_pw = password_hash($_POST['pass'], PASSWORD_BCRYPT);
			$sql = "INSERT INTO account_info (email, password, account) VALUES('" . $_POST['email'] . "', '" . $encrypted_pw . "', '" . $_POST['account'] . "');";

			if($conn -> query($sql) == TRUE){
				echo "Successfully signed up.";
			}
			else{
				if($conn->error == "Duplicate entry '" . $_POST['email'] . "' for key 'email'")
				{
					echo "Error: email already used.";
				}
				else
				{
					echo $conn->error;
				}
			}
		}
		else
		{
			die("POST failed.");
		}
	?>

	<div class="container-login100-form-btn">
		<button class="login100-form-btn" onclick="window.location.href='index.php'">
			Return
		</button>
	</div>
	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script>
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>
</body>
</html>