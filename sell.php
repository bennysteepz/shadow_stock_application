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
    
<h1>Sell</h1>    
    
<body>     
    
<?php  
    
echo "<br>";    
    
if (isset($_GET["clearall"])){ 
    $pdo->exec("DELETE FROM stock_info");
    $pdo->exec("DELETE FROM live");
} 
?>

<form method = "get" >
Stock you wish to sell: 
<input name = "stockname" type = "text">
<br>
Number of shares:
<input name = "sharenumber" type = "number"><br>
<input name = "submit" type = "submit">
</form>
  
<?php
$stockname = '';
$number = 0;
      
if (isset($_GET["submit"])) {
    // collect value of input field
    
    $stockname = strtoupper($_GET['stockname']);
    $number = $_GET['sharenumber'];
    
    $c_code = $pdo->prepare('SELECT count(code) FROM live
    WHERE code = ?');
    $c_code->execute([$stockname]);
    $find_code = $c_code->fetchColumn();
    
    $c_shn = $pdo->prepare('SELECT shares_now FROM live
    WHERE code = ?');
    $c_shn->execute([$stockname]);
    $find_shn = $c_shn->fetchColumn();
    
    if (empty($stockname)and empty($number)){
        echo "Please input stockname and number of shares above.<br>";
    }
    elseif (empty($stockname)) {
        echo "Please input stockname above.<br>";
    }
    elseif (empty($number)) {
        echo "Please input number of shares above.<br>";
    }
    elseif ($find_code == 0 or $number > $find_shn) {
        echo "You can't sell stock you don't own!";  
    }
    else {
        $p = getPrice($stockname);
        $n = getName($stockname);
        echo getConfirm($n,$p,$number,'Sell');
        echo "<br>";

?> 
    
    <form method = "post">
    <input name = "sellform" type="submit" value="sell!!"> 
    </form>
        
<?php 

    if (isset($_POST["sellform"])){
            
    $pricee = getPrice($stockname);
    $namee = getName($stockname);
    $number = intval($number);
            
        $sql = "INSERT INTO stock_info(full_name,stockname,sharenumber, 
        boughtat,bill,buy_or_sell) VALUES(?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$namee,$stockname,$number,$pricee,
        $pricee*$number,True]); 
        
        updateLive($stockname,$number,$pricee);
        
        $real = $pdo->query("SELECT balance FROM account");
        $sn_real = $real->fetch();
        $realamt = $sn_real['balance'];
        $newbal = $realamt + ($number * $pricee);

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