<?php
if ($_SERVER["REQUEST_METHOD"] == "POST"){
	$con = mysqli_connect("localhost","root","root","userdb");
	if ($con->connect_error)
		die("Connection failed: ".$con->connect_error);

	$sql = "SELECT id, password,account from account_info where email='$_POST[email]' ";
	$result = $con->query($sql);
	if ($result->num_rows > 0)
	{
		while ($row = $result->fetch_assoc())
		{
			if (password_verify($_POST['pass'], $row['password']))
			{
				header("Location:home.php");
				session_start();
				$_SESSION['user_id'] = $row['account'];
				$_SESSION['email'] = $_POST['email'];
				$_SESSION['id'] = $row['id'];
			}
			else
				echo 'Wrong password !';
		}
	}
	else
		echo "Account not found !";
}
?>