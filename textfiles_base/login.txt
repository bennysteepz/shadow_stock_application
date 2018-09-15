<html>
<head><title>Penguin</title></head>
<body>
 
Password
<form method = "post" action = "<?php echo $_SERVER['PHP_SELF'];?>">
<input name = "password" type = "text"><br>
<input type = "submit">
</form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $password = $_REQUEST['password'];
    if ($password == 'guru') {
        ?><script type="text/javascript">
        window.location = "http://localhost:8888/home.php";
        </script><?php
    }
    else {
        echo "Wrong password, try again.";
    }
}
?>

</body>
</html>  