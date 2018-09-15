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
    
<h1>Buy</h1>    
    
<body>         
    
<?php

echo "<br>";
    
if (isset($_GET["clearall"])){ 
    $pdo->exec("DELETE FROM stock_info");
    $pdo->exec("DELETE FROM live"); 
}
?>
    
<form method = "get" >
Stock you wish to buy: 
<input name = "stockname" type = "text">
<br>
Number of shares:
<input name = "sharenumber" type = "number"><br>
<input name = "submit" type = "submit">
</form>
    
<?php
$real = $pdo->query("SELECT balance FROM account");
$sn_real = $real->fetch();     
    
$stockname = '';
$number = 0;
    
if (isset($_GET["submit"])) {
    // collect value of input field
    
    $stockname = strtoupper($_GET['stockname']);
    $number = $_GET['sharenumber'];
    $p = getPrice($stockname);
    $n = getName($stockname);
    $realamt = $sn_real['balance'];
    
    if (empty($stockname)and empty($number)){
        echo "Please input stockname and number of shares above.<br>";
    }
    elseif (empty($stockname)) {
        echo "Please input stockname above.<br>";
    }
    elseif (empty($number)) {
        echo "Please input number of shares above.<br>";
    }
    elseif ($p*$number > $realamt) {
        echo "Insufficient funds."."<br>";
        echo "Cost of ".$number." shares of ".$n." is ".
            money_format('%.2n',$p*$number).".<br>";
        echo "Account balance is only ".money_format('%.2n',$realamt).".";
    }
    else {
        echo getConfirm($n,$p,$number,'Buy');
        echo "<br>";
?> 
    <form method = "post">
    <input name = "buyform" type="submit" value="buy!!"> 
    </form>
        
<?php 

    if (isset($_POST["buyform"])){
    
    $pricee = getPrice($stockname);
    $namee = getName($stockname);
    $number = intval($number)*-1;
            
        $sql = "INSERT INTO stock_info(full_name,stockname,sharenumber, 
        boughtat,bill,buy_or_sell) VALUES(?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$namee,$stockname,$number,$pricee,
        $pricee*$number,False]); 
        
        updateLive($stockname,$number,$pricee);
        
        $real = $pdo->query("SELECT balance FROM account");
        $sn_real = $real->fetch();
        $realamt = $sn_real['balance'];
        $newbal = $realamt - ($number*-1 * $pricee);

        $sql = "UPDATE account SET balance = $newbal";
        $pdo->prepare($sql)->execute();
        
        echo "Success!";
        header ("Refresh: 1;home.php");
    }
}
}
?>

</body>
</html>