<?php

include_once('class.yahoostock.php');
setlocale(LC_MONETARY, 'en_US');

$objYahooStock = new YahooStock;
/**
    Add format/parameters to be fetched
    
    s = Symbol
    n = Name
    l1 = Last Trade (Price Only)
    d1 = Last Trade Date
    t1 = Last Trade Time
    c = Change and Percent Change
    v = Volume
 */
$objYahooStock->addFormat("snl1d1t1cv"); 
/**
    Add company stock code to be fetched
    
    msft = Microsoft
    amzn = Amazon
    yhoo = Yahoo
    goog = Google
    aapl = Apple
 */
$objYahooStock->addStock($stockname);
/**
 * Printing out the data
*/ 
foreach($objYahooStock->getQuotes() as $code => $stock)
{ 
    $kode = $stock[1];
    $name = $stock[3];
    $price = $stock[4];
    //$date = $stock[5];
    //$time = $stock[7];
    //$change = $stock[9]; 
    //$volume = $stock[10];
}

if (isset($name)) {
    //echo "Stock Code: " . $kode //code 
    echo $number." share(s) of ".$name." at ".money_format('%.2n', $price).
        "/share for ".money_format('%.2n', $price*$number)."?";
    //echo "<br>";
    //echo "Last Trade Date: " . $date; //last trade date
    //echo "<br>";
    //echo "Last Trade Time: " . $time; //last trade time
    //echo "<br>";
    //echo "Change and Percent Change: " . $change; //change and percent change
    //echo "<br>";
    //echo "Volume: " . $volume; //volume
    //echo "<br>";
}
else {
    exit ("Hmmm... we can't find this stock. Sorry.");
}

?>
