<!DOCTYPE html>
<html>
<head>
	<title>server</title>
</head>
<body>
	<?php
		if(isset($_POST))
		{
			echo "post works";
			echo $_POST['email'];
			echo $_POST['pass'];
		}
		else
		{
			echo "post failed";
		}
	?>
</body>
</html>