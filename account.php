<?php ob_start();
include('functions.php');
setlocale(LC_MONETARY, 'en_US'); ?>
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
    
<h1>Account</h1>
    
<body>  
<?php
if (isset($_GET["clearall"])){ 
    $pdo->exec("DELETE FROM stock_info");
    $pdo->exec("DELETE FROM live");
}       
$real = $pdo->query("SELECT balance FROM account");
$sn_real = $real->fetch(); 
echo "Balance: $".$sn_real['balance'];
?>
   
<form method = "get" >
Amount:
<input name = "amt" type = "number" step="0.01">
    <br>
<input name = "deposit" type = "submit" value = "Deposit">
<input name = "withdraw" type = "submit" value = "Withdraw">
    <br>
</form>
    
<?php 
    
if (isset($_GET["deposit"])) {
    $realamt = $sn_real['balance'];
    $amt = $_GET["amt"];
    
    if ($amt < 0 or $amt == NULL){
        echo "Please input positive number.";
    }
    else {
        $newbal = $realamt + $amt;
        $sql = "UPDATE account SET balance = $newbal";
        $pdo->prepare($sql)->execute();
        
        echo "Successfully deposited ".money_format('%.2n',$amt).
            " into account.";
        header ("Refresh: 2;account.php");
    }   
}
    
if (isset($_GET["withdraw"])) {
    $realamt = $sn_real['balance'];
    $amt = $_GET["amt"];
    
    if ($amt < 0 or $amt == NULL){
        echo "Please input positive number.";
    }
    elseif ($amt > $realamt) {
        echo "You can't withdraw more money than you have!";
    }
    else {
        $newbal = $realamt - $amt;
        $sql = "UPDATE account SET balance = $newbal";
        $pdo->prepare($sql)->execute();
        
        echo "Successfully withdrew ".money_format('%.2n',$amt).
        " from account.";
        header ("Refresh: 2;account.php");
    }
}
?>    
    
</body>  
</html>