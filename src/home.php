<!DOCTYPE html>
<html>
<head>
<style>
body {
  font-family: Arial;
  color: white;
}

.split {
  height: 100%;
  width: 30%;
  position: fixed;
  z-index: 1;
  top: 0;
  overflow-x: hidden;
  padding-top: 20px;
}

.left {
  left: 0%;;
  background-color: gray;
}

.right {
  left: 30%;
  width: 70%;
  background-color: white;
}

.centered {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
}

.centered_up {
  position: absolute;
  top: 0%;
  left: 50%;
  transform: translate(-50%, 5%);
  text-align: center;
}

.down {
  position: absolute;
  top: 85%;
  height: 0%;
  background-color: white;
}

<?php
  session_start();
  if (!isset($_SESSION['id']))
        header("Location:index.php");
?>
</style>
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

<div class="split left">
  <h1><strong>Contacts</strong><br></h1>
  <?php
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

    $user_sql = "SELECT id, email, account FROM account_info;";
    $user_result = $conn->query($user_sql);
    if($user_result->num_rows > 0)
    {
      $any = false;
      while($row = $user_result->fetch_assoc())
      {
        $table1 = (string) $row['id'] . "to" . (string) $_SESSION['id'];
        $table2 = $_SESSION['id'] . "to" . $row['id'];
        $table_sql = "SHOW TABLES WHERE Tables_in_userdb='" . $table1 . "' OR Tables_in_userdb='" . $table2 . "';";
        $table_result = $conn->query($table_sql);
        if($table_result->num_rows > 0)
        {
          if($any == false)
          {
            echo "<form action=\"chatroom.php\" method=\"get\"><br>";
          }
          echo "<button class=\"login100-form-btn\" name=\"email\" value=\"" . $row['email'] . "\">" . $row['account'] . "(" . $row['email']. ")<br></button>";
          $any = true;
        }
      }
      if($any == true)
      {
        echo "</form>";
      }
      else
      {
        echo "<h3>No contacts.</h3>";
      }

    }
    else
    {
      echo "<h3>No contacts.</h3>";
    }
  ?>
  <div class="down">
    <form action="search.php" method="get"><br>
      <input type="text" name="email_or_account" placeholder="Type to search user" style="width:401px; height:100%;" size="100">
      <button type="submit" style="background-color: green;">Search<br></button>
    </form>
    <form action="logout.php">
      <button class="login100-form-btn" type="submit" value="">Logout</button>
    </form>
  </div>
</div>

<div class="split right">
  <div class="centered">
    <img src="images/hatsune_miku.jpg" alt="IMG" style="height:100%;">
  </div>
</div>
     
</body>
</html> 
