<?php 
ignore_user_abort(true);
//--$acct_balance = 10000;
$host = 'localhost';
$db   = 'base1';
$user = 'base1';
$pass = 'Snowcycle7';
$charset = 'utf8';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$opt = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
$pdo = new PDO($dsn, $user, $pass, $opt);


function getBuy($bs) {
    if ($bs == 0) {
        return 'Bought';
    }
    elseif ($bs == 1){
        return 'Sold';
    }
}
    
function makPl($ng) {
    if ($ng < 0) {
        return "-" . $ng*-1;
    }
    else {
        return "+" .$ng;
    }
}

function makRate($mp) {
    if ($mp >= 0) {
        return "+" . $mp . "%"; 
    }
    else {
        return $mp . "%"; 
    }   
}

function makPos($mp) {
    if ($mp < 0) {
        return $mp * -1;
    }
    else {
        return $mp;
    }
}

function postLive() {
    global $pdo;
    global $acct_balance;
    setlocale(LC_MONETARY, 'en_US');
    
    $getLive = $pdo->query("SELECT * FROM live WHERE shares_now != 0");
    echo "<table>";
    echo "<tr><th>Code</th><th>Shares</th><th>Price</th><th>Bought@</th>
    <th>Assets</th><th>Cash</th><th>P/L</th><th>Rate</th></tr>";
    foreach ($getLive as $line) {
        echo "<tr>";
        echo "<td>".$line['code']."</td>";
        echo "<td>".$line['shares_now']."</td>";
        echo "<td>".money_format('%.2n', $line['price_now'])."</td>";
        echo "<td>".money_format('%.2n', $line['av_spent'])."</td>";
        echo "<td>".money_format('%.2n', $line['ass_now'])."</td>";
        echo "<td>".money_format('%.2n', $line['cash_now'])."</td>";
        echo "<td>".makPl(money_format('%.2n', $line['pl']))."</td>";
        echo "<td>".makRate($line['pl_rate'])."</td>";
    }
        echo "</tr>";
    echo "</table>";
}

function updateLive($s,$n,$p) {
    global $pdo;
    $stockname = $s;
    $number = $n;
    $pricee = $p;
    $number = floatval($number);
    
//if table does not exist, create it.. otherwise move along
$sql = "SHOW TABLES LIKE '".$stockname."'";
$result = $pdo->prepare($sql);
$result->execute();
$count = $result->rowCount();
    
    if ($count == 0) {
        //stock never been bought before
        //table does not exist
        $pdo->exec("CREATE TABLE $stockname (
            ID INT NOT NULL AUTO_INCREMENT,
            shares INT(11), 
            deltashares INT(11), 
            price DECIMAL(10,2), 
            avspent DECIMAL(10,2),
            assets DECIMAL(10,2), 
            cash DECIMAL(10,2), 
            pl DECIMAL(10,2), 
            plrate DECIMAL(10,2),
            cycle INT(11),
            spent DECIMAL(10,2),
            datetime TIMESTAMP,
            PRIMARY KEY (ID));");

        $sql = "INSERT INTO $stockname (shares,deltashares,price,avspent, 
        assets,cash,pl,plrate,cycle,spent) VALUES(?,?,?,?,?,?,?,?,?,?)";
        $pdo->prepare($sql)->execute([$number*-1,$number,$pricee,
        ($number*$pricee*-1)/$number,$number*$pricee*-1,$number*$pricee,0,0,1,abs($number*$pricee)]); 

        $sql = 'INSERT INTO live (code,shares_now,price_now,av_spent,ass_now,cash_now,
        pl,pl_rate,spent) VALUES (?,?,?,?,?,?,?,?,?)';
        $pdo->prepare($sql)->
            execute([$stockname,$number*-1,$pricee,($number*$pricee)/$number*-1,
                     $number*$pricee*-1,$number*$pricee,0,0,abs($number*$pricee)]);
        }
    
    elseif ($count == 1) { //table exists (cycles exist: bought stock before)
        $sql = "SELECT count(shares) FROM $stockname WHERE shares = 0";
        $zero_shares = $pdo->prepare($sql);
        $zero_shares->execute();
        $shares_number_of_zeros = $zero_shares->fetchColumn();
        
        $newrow = $pdo->query("SELECT shares,deltashares,cash,cycle,spent
        FROM $stockname ORDER BY datetime DESC LIMIT 1");
        $sn_newrow = $newrow->fetch(); 
        
        $current_shares = $sn_newrow['shares']-$number;
        $delta = $number;
        $cash = ($delta*$pricee)+$sn_newrow['cash'];
            if ($current_shares == 0) {
                $avspent = 0;
            }
            else {
                $avspent = $cash / $current_shares;
            }
        $assets = $current_shares * $pricee;
        $pl = $assets + $cash;
        $cycle = $shares_number_of_zeros + 1;
            if ($delta < 0) {
                $spent = (abs($delta*$pricee)) + $sn_newrow['spent'];
            }
            elseif ($delta > 0) {
                $spent = $sn_newrow['spent'];
            }
        $rate = $pl / $spent * 100;
        
        if ($sn_newrow['shares'] == 0) { //start new cycle
            
            $sql = "INSERT INTO $stockname (shares,deltashares,price,avspent,
            assets,cash,pl,plrate,cycle,spent) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $pdo->prepare($sql)->execute([
                $number*-1, //how many shares you now own
                $number, //deltashares
                $pricee, //price
                $pricee*-1, //avspent
                $number*$pricee*-1, //assets: current shares * current price
                $number*$pricee, //cash: assets * -1
                0, //pl
                0, //plrate
                $shares_number_of_zeros + 1,
                abs($number*$pricee)]); //cycle #
        }
        
        else { //continue with ongoing cycle
            
            $sql = "INSERT INTO $stockname (shares,deltashares,price,avspent,
            assets,cash,pl,cycle,plrate,spent) VALUES(?,?,?,?,?,?,?,?,?,?)";
            $pdo->prepare($sql)->execute([
                $current_shares,
                $delta,
                $pricee,
                $avspent,
                $assets,
                $cash,
                $pl,
                $cycle,
                $rate,
                $spent]);  
        }
        
        $tolive = $pdo->query("SELECT plrate,shares,cash,avspent,spent
        FROM $stockname ORDER BY datetime DESC LIMIT 1");
        $sn_tolive = $tolive->fetch(); 
        
        $pricee = getPrice($stockname);
        
        $sql = "UPDATE live SET shares_now=?, price_now=?, av_spent=?, ass_now=?, 
        cash_now=?, pl=?, pl_rate=?, spent=? WHERE code = ?";
        $pdo->prepare($sql)->
            execute([
                $sn_tolive['shares'],
                $pricee,
                $sn_tolive['avspent'],
                $sn_tolive['shares']*$pricee,
                $sn_tolive['cash'],
                ($sn_tolive['shares']*$pricee)+($sn_tolive['cash']),
                $sn_tolive['plrate'],
                $sn_tolive['spent'],
                $stockname
        ]);
    }
}

function getPrice($sn) {
    global $pdo;
    $currval = $sn;
    include('yahoostock_currval.php');
    return $pricee;
}

function getName($sn) {
    global $pdo;
    $currval = $sn;
    include('yahoostock_currval.php');
    return $namee;
}

function getConfirm($fullname,$price,$number,$buysell) {   
    $total = money_format('%.2n', $number*$price);
    $price = money_format('%.2n', $price);
    return $buysell." "."$number"." share(s) of ".$fullname." at ".$price.
        " a share for ".$total."?";
}

function updateCurrval() {
    global $pdo;
    $sql = "SELECT code FROM live WHERE shares_now != 0";
    $scode = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);
    
    $netrate = 0;
    $netpl = 0;
    $netspent = 0;
    foreach ($scode as $s) {
        $info = $pdo->query("SELECT shares,cash,spent
        FROM $s ORDER BY datetime DESC LIMIT 1");
        $sn_info = $info->fetch(); 
        
        $currprice = getPrice($s);
        $assets = $currprice * $sn_info['shares'];
        $pl = $assets + $sn_info['cash'];
        $rate = $pl / $sn_info['spent'] * 100;
        $avspent = $sn_info['cash']*-1 / $sn_info['shares'];
        
        $sql = 'UPDATE live SET price_now=?, av_spent=?, ass_now=?, pl=?,pl_rate=? WHERE code=?';
        $pdo->prepare($sql)->execute([$currprice,$avspent,$assets,$pl,$rate,$s]);
        
        $netrate += $rate;
        $netpl += $pl;
        $netspent += $sn_info['spent'];
    }
    $sql = "UPDATE nets SET pl=$netpl, rate=$netrate, spent=$netspent";
    $doit = $pdo->prepare($sql);
    $doit->execute();
}
 
function postHistory(){
    global $pdo;
    setlocale(LC_MONETARY, 'en_US');
    $gethist = $pdo->query("SELECT buy_or_sell,full_name,sharenumber,
    boughtat,bill,date_time FROM stock_info ORDER BY date_time DESC");
    echo "<table>";
    echo "<tr><th>Bought/Sold</th><th>Company</th><th>Shares</th>
    <th>Price</th><th>Bill</th><th>Time</th></tr>";
    foreach ($gethist as $line) {
        echo "<tr>";
        echo "<td>".getbuy($line["buy_or_sell"])."</td>";
        echo "<td>".$line["full_name"]."</td>";
        echo "<td>".makPos($line["sharenumber"])."</td>";
        echo "<td>".money_format('%.2n', makPos($line['boughtat']))."</td>";
        echo "<td>".money_format('%.2n', makPos($line['bill']))."</td>";
        echo "<td>".$line["date_time"]."</td>";
    }
        echo "</tr>";
    echo "</table>";
}

function window() {
    date_default_timezone_set("America/New_York");
    $current_time = date("h:i:a");
    $open = "9:30 am";
    $close = "4:00 pm";
    $time1 = DateTime::createFromFormat('H:i a', $current_time);
    $time2 = DateTime::createFromFormat('H:i a', $open);
    $time3 = DateTime::createFromFormat('H:i a', $close);

    $dt=date("Y-m-d");
            $dt1 = strtotime($dt);  
            $dt2 = date("l", $dt1);  
            $dt3 = strtolower($dt2);  
    if($dt3 == "saturday" ) {  
        $a = 2;
        $b = 12;
    }  
    elseif($dt3 == "sunday" ) {  
        $a = 3;
        $b = 13;
    }  
    elseif($dt3 == "monday" ) {  
        $a = 4;
        $b = 18;
    }  
    else {
        $a = 1;
        $b = 11;
    }
    return array($a,$b);
}

?>