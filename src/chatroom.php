<!DOCTYPE html>
<html>
<head>
<style>
	body {
	  font-family: Arial;
	  background-image: url('images/hatsune_miku_2.jpg');
	  background-repeat: no-repeat;
	  background-size: cover;
	}

	.split_up_down {
	  height: 88%;
	  width: 100%;
	  position: fixed;
	  z-index: 1;
	  left: 0;
	  overflow-x: hidden;
	  padding-top: 20px;
	}

	.split_left_right {
	  height: 100%;
	  width: 80%;
	  position: fixed;
	  z-index: 1;
	  overflow-x: hidden;
	  padding-top: 20px;
	}

	.up {
	  top: 0%;
	  height: 85%;
	}

	.down {
	  top: 85%;
	  height: 15%;
	}

	.centered {
	  position: absolute;
	  top: 50%;
	  left: 50%;
	  transform: translate(-50%, -50%);
	  text-align: center;
	}

	.left {
	  left: 0%;
	}

	.right {
	  left: 80%;
	  width: 20%;
	}
	.centered_up {
	  position: absolute;
	  top: 0%;
	  left: 50%;
	  transform: translate(-50%, 5%);
	  text-align: center;
	}
	</style>

	<?php
		session_start();		
		if (!isset($_SESSION['id']))
    		header("Location:index.php");

    	if ($_SERVER["REQUEST_METHOD"] == "GET"){
			$con = mysqli_connect("localhost","root","root","userdb");
			if ($con->connect_error)
				die("Connection failed: " . $con->connect_error);
			$sql = "SELECT id,account from account_info where email='".$_GET['email']."'";
			$result = $con->query($sql);
			
			if ($result->num_rows > 0)
			{
				if (!isset($_SESSION['latest_timestamp']))
					$_SESSION['latest_timestamp'] = 0;
				while ($row = $result->fetch_assoc())
				{
					$_SESSION['dest_id'] = $row['id'];
					$_SESSION['dest_account'] = $row['account'];
				}
			}

			$table = (string)(((int)$_SESSION['id'] < (int)$_SESSION['dest_id'])? $_SESSION['id']: $_SESSION['dest_id']) . "to" . (string) (((int)$_SESSION['id'] > (int)$_SESSION['dest_id'])? $_SESSION['id']: $_SESSION['dest_id']);
        	$table_sql = "SHOW TABLES WHERE Tables_in_userdb='" . $table . "';";
        	$table_result = $con->query($table_sql);
        	if($table_result->num_rows == 0)
        	{
        		$add_sql = "CREATE TABLE " . $table . " (timestamp bigint, time_string varchar(127), content varchar(255), source int, is_file bool, is_valid bool);";
        		$add_result = $con->query($add_sql);
        		mkdir($table);
        	}
        }
	?>

	<script type="text/javascript">
		var tstamp = "";
		function get_content(ifonload){
			let xmlhttp = new XMLHttpRequest();
			let formData = new FormData();
			if (ifonload)
				tstamp = "0";
			formData.append('timestamp', tstamp);
			formData.append('dest_id', <?php echo $_SESSION['dest_id']?>);
			formData.append('source_id', <?php echo $_SESSION['id']?>);
			xmlhttp.onreadystatechange = function(){
				if(this.readyState == 4 && this.status == 200 && this.responseText){
					var tstamp_pos = this.responseText.lastIndexOf("@") + 1;
					tstamp = this.responseText.slice(tstamp_pos, this.responseText.length);
					let start = 0;
					let end = -1;
					let findex = 0;
					let pathname = "";
					let filename = "";
					let regex_me = new RegExp();
					let regex_other = new RegExp();
					let reg_special = /\(|\)|\[|\||\/|\$|\^|\*|\\|\?/g;
					let regex_repeat = new RegExp(this.responseText.slice(0, tstamp_pos-1).replace(reg_special, function(x){return "\\" + x;}), "g");
					document.getElementById("chat_content").innerHTML = document.getElementById("chat_content").innerHTML.replace(regex_repeat, "");
					while((start = this.responseText.indexOf("<a href=\"", start)) != -1){
						start = start + 9;
						end = this.responseText.indexOf("\" download=\"\">", start);
						pathname = this.responseText.slice(start, end);
						findex = this.responseText.indexOf("/", start) + 1;
						filename = this.responseText.slice(findex, end);
						regex_me = new RegExp(("<a href=\"" + pathname + "\" download=\"\">" + filename + "</a>").replace(reg_special, function(x){return "\\" + x;}), "g");
						regex_other = new RegExp(("<a href=\"" + pathname + "\" download=\"\">" + filename + "</a>").replace(reg_special, function(x){return "\\" + x;}), "g");
						document.getElementById("chat_content").innerHTML = document.getElementById("chat_content").innerHTML.replace(regex_me, filename + " (Invalid: expired or replaced)");
						document.getElementById("chat_content").innerHTML = document.getElementById("chat_content").innerHTML.replace(regex_other, filename + " (Invalid: expired or replaced)");
					}
					document.getElementById("chat_content").innerHTML += this.responseText.slice(0, tstamp_pos-1);
					document.getElementById("chatbox").scrollTop = document.getElementById("chatbox").scrollHeight - document.getElementById("chatbox").clientHeight;
					//document.getElementById("send").scrollIntoView();
				}
			};
			xmlhttp.open("POST", "get_content.php", true);
			xmlhttp.send(formData);
		}
		
		function send_and_get_content(){
			let xmlhttp = new XMLHttpRequest();
			let formData = new FormData();
			var d = new Date();
			var time_string = d.toLocaleString();
			var t = d.getTime();
			let context = document.getElementById("context").value;
			context = context.replace(/&/g, "&amp;");
			context = context.replace(/</g, "&lt;");
			context = context.replace(/>/g, "&gt;");
			context = context.replace(/\\/g, "\\\\");
			context = context.replace(/\'/g, "\\\'");
			formData.append('timestamp', t);
			formData.append('time_string', time_string);
			formData.append('dest_id', <?php echo $_SESSION['dest_id']?>);
			formData.append('source_id', <?php echo $_SESSION['id']?>);
			formData.append('context', context);
			formData.append('is_file', "false");
			formData.append('is_valid', "false");
			document.getElementById("context").value = '';
			xmlhttp.open("POST", "send_context.php", true);
			xmlhttp.send(formData);
			get_content(false);
		}
		setInterval("get_content(false)", 1000);

		function fileupload(){
			var filepath = document.getElementById("fileToUpload").value;
			var fname_pos = filepath.lastIndexOf("\\") + 1;
			var fname = filepath.slice(fname_pos, filepath.length);
			let xmlhttp = new XMLHttpRequest();
			let formData = new FormData();
			var d = new Date();
			var time_string = d.toLocaleString();
			var t = d.getTime();
			fname = fname.replace(/&/g, "&amp;");
			fname = fname.replace(/</g, "&lt;");
			fname = fname.replace(/>/g, "&gt;");
			formData.append('timestamp', t);
			formData.append('time_string', time_string);
			formData.append('dest_id', <?php echo $_SESSION['dest_id']?>);
			formData.append('source_id', <?php echo $_SESSION['id']?>);
			formData.append('context', fname);
			formData.append('is_file', "true");
			formData.append('is_valid', "true");
			xmlhttp.open("POST", "send_context.php", true);
			xmlhttp.send(formData);
			get_content(false);
		}

	</script>
</head>

<body onload="get_content(true)">
	<div id="chatbox" class="split_up_down up">
		<legend><?php echo $_SESSION['dest_account']?></legend>
		<fieldset><div id="chat_content" style="overflow:auto"></div></fieldset>
	</div>
	<div class="split_up_down down">
		<div class="split_left_right left">
			<fieldset>
			<form>
				<input type="text" id="context" placeholder="Type to chat">
				<button type="button" id="send" onclick="send_and_get_content()"><img src="/images/mail.png" height="10" width="10"></button>
			</form>
			<?php require 'upload.php'; ?>
			<form action="upload.php" method="post" enctype="multipart/form-data" target="_blank">
			    <input type="file" name="fileToUpload" id="fileToUpload">
			    <button type="submit" id="upload" name="dest_dir" value="<?php echo $table?>" onclick="fileupload()"><img src="/images/paperclip.png" height="10" width="10"></button>
			</form>
			</fieldset>
			<p><span id="query"></span></p>
		</div>
		<div class="split_left_right right">
			<form action="home.php">
				<button type="submit" value="" style="background-color:green; width: 100%">Return Home</button>
			</form>
			<form action="logout.php">
				<button type="submit" value="" style="background-color:green; width: 100%">Logout</button>
			</form>
		</div>
	</div>
	<script type="text/javascript">
		var input = document.getElementById("context");
		input.addEventListener("keydown", function(event){
			if(event.keyCode === 13){
				event.preventDefault();
				document.getElementById("send").click();
			}
		});
	</script>
</body>
</html>