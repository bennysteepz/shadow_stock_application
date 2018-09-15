<html>
    <head>
        <title>Penguin</title>
    </head>
<nav>
    <a href="home.php">Home</a>
    <a href="buy.php">Buy</a>
    <a href="sell.php">Sell</a>
    <a href="history.php">History</a>
    <a href="account.php">Account</a>
    <form method = "get">
    <input name="clearall" type="submit" value="CLEAR">
    </form>
</nav>
    
<h1>History</h1>
    
<body>  
<?php
include('functions.php');
if (isset($_GET["clearall"])){ 
    $pdo->exec("DELETE FROM stock_info");
    $pdo->exec("DELETE FROM live");
} 
postHistory();
?>
</body>  
</html>