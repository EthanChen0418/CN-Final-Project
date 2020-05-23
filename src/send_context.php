<?php
	session_start();
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

    if($_POST['is_file'] == "true"){
    	$invalid_sql = "UPDATE " . $table . " SET is_valid = false WHERE is_file IS true AND content = '" . $_POST['context'] ."';";
    	$conn->query($invalid_sql);
    }

    $sql = "INSERT INTO " . $table . " (timestamp, time_string, content, source, is_file, is_valid) VALUES (" . $_POST['timestamp'] . ", '" . $_POST['time_string'] . "', '" . $_POST['context'] . "', " . $_POST['source_id'] . ", " . $_POST['is_file'] . ", " . $_POST['is_valid'] . ");";
    
    if($conn->query($sql) == FALSE)
    {
    	echo $conn->error;
    }
?>