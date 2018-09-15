<html>
<head><title>Penguin</title></head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // collect value of input field
    $name = $_REQUEST['stockname'];
    if (empty($name)) {
        echo "Stockname is empty";
    } else {
        echo $name;
    }
}
    
$stockname = $_POST["stockname"];
$sharenumber = $_POST["sharenumber"];
    
echo "Purchase " . $sharenumber . " shares of stock " . $stockname . "?";
?>
<br>   

    
<input type="button" value="Confirm" onclick="location='buy.php'" />
    
</body>
</html>