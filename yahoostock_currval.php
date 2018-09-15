<?php
    
include_once('class.yahoostock.php');

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


$objYahooStock->addStock($currval);
   
 
/**
 * Printing out the data
*/ 

foreach($objYahooStock->getQuotes() as $code => $stock)
{ 
    $kodee = $stock[1];
    $namee = $stock[3];
    $pricee = $stock[4];
    $datee = $stock[5];
    $timee = $stock[7];
}
?>
