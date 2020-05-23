<?php
	session_start();
	if (!isset($_SESSION['id']))
		header("Location:index.php");
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

    $table = (string)(((int)$_POST['source_id'] < (int)$_POST['dest_id'])? $_POST['source_id']: $_POST['dest_id']) . "to" . (string) (((int)$_POST['source_id'] > (int)$_POST['dest_id'])? $_POST['source_id']: $_POST['dest_id']);
    $sql = "SELECT * FROM " . $table . " WHERE timestamp > " . $_POST['timestamp'] . ";";
    $result = $conn->query($sql);
    if ($result->num_rows > 0)
    {
        $latest_tstamp = 0;
        while ($row = $result->fetch_assoc())
        {
            if ($row["source"] == $_SESSION["id"])
            {
            	if($row['is_file'] == TRUE)
            	{
            		if($row['is_valid'] == TRUE)
            		{
            			echo $row['time_string'] . " Me: <a href=\"" . $table . "/" . $row['content'] . "\" download=\"\">" . $row['content'] . "</a><br>";
            		}
            		else
            		{
            			echo $row['time_string'] . " Me: " . $row['content'] . " (Invalid: expired or replaced)<br>";
            		}
            	}
            	else
            	{
            		echo $row['time_string'] . " Me: " . $row["content"] . "<br>";
            	}
            }
            else
            {
                if($row['is_file'] == TRUE)
            	{
            		if($row['is_valid'] == TRUE)
            		{
            			echo $row['time_string'] . " " . $_SESSION['dest_account'] . ": <a href=\"" . $table . "/" . $row['content'] . "\" download=\"\">" . $row['content'] . "</a><br>";
            		}
            		else
            		{
            			echo $row['time_string'] . " " . $_SESSION['dest_account'] . ": " . $row['content'] . " (Invalid: expired or replaced)<br>";
            		}
            	}
            	else
            	{
            		echo $row['time_string'] . " " . $_SESSION['dest_account'] . ": " . $row["content"] . "<br>";
            	}
            }
            $latest_tstamp = $row["timestamp"];
        }
        echo "@" . $latest_tstamp;
    }

?>