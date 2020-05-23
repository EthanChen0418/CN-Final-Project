<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <script type="text/javascript">
        function closewindow(){
            close();
        }
    </script>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $target_dir = $_POST['dest_dir'];
        $target_file = $target_dir . "/" . basename($_FILES["fileToUpload"]["name"]);

        if (file_exists($target_file)){
            chmod($target_file, 0755);
            unlink($target_file);
        }
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        ?>    <script>closewindow();</script>
        <?php
        } else {
        echo "error occur";
        }
    }
    ?>
</body>
</html>