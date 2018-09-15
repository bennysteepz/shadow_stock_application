<?php

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
 $objYahooStock->addStock("msft");

 /**
  * Printing out the data
  */
 foreach( $objYahooStock->getQuotes() as $code => $stock)
 {
?>
Code: <?php echo $stock[0]; ?> <br />
Name: <?php echo $stock[1]; ?> <br />
Last Trade Price: <?php echo $stock[2]; ?> <br />
Last Trade Date: <?php echo $stock[3]; ?> <br />
Last Trade Time: <?php echo $stock[4]; ?> <br />
Change and Percent Change: <?php echo $stock[5]; ?> <br />
Volume: <?php echo $stock[6]; ?> <br /><br />
<?php
 }
 ?>

<?php
class YahooStock {
 private $stocks = array();
 private $format;
 public function addStock($stock)
{
    $this->stocks[] = $stock;
}
 public function addFormat($format)
{
    $this->format = $format;
}
 public function getQuotes()
{       
    $result = array();     
    $format = $this->format;

    foreach ($this->stocks as $stock)
    {           
       $s = file_get_contents("http://finance.yahoo.com/d/quotes.csv?s=$stock&f=$format&e=.csv");
             $data = explode( ',', $s);
             $result[$stock] = $data;
    }
    return $result;
}
} 
?>