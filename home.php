<?php
include('functions.php');
setlocale(LC_MONETARY, 'en_US');
 
?>
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
    
<h1>Current Portfolio</h1>
    
<body>
    
<div id="refresh_currval"> 
    <?php postLive();
    include('chartrate.php');?>
</div> 
    
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/
libs/jquery/1.3.0/jquery.min.js"></script>
<script type="text/javascript">
    
function loadlink(){
    $('#refresh_currval').load('record_count.php',function test() {
         $(this).unwrap();
    });
}
</script>   
    
    
<?php
if (isset($_GET["clearall"])){ 
    $pdo->exec("DELETE FROM stock_info");
    $pdo->exec("DELETE FROM live");
} 
?>

</body>
</html>