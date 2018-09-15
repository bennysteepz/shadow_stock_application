<?php
include('functions.php');
$colors = ['#7cb5ec', '#FFB6C1', '#90ed7d', '#f7a35c', '#8085e9', 
   '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];

$now = new DateTime(null, new DateTimeZone('America/New_York'));
$thisdate = $now->format('Y-m-d'); // MySQL datetime format

$sql = "SELECT code FROM live WHERE shares_now != 0";
$scode = $pdo->query($sql)->fetchAll(PDO::FETCH_COLUMN);

$a = window()[0];
$b = window()[1];

$final = array();
$net = array();
foreach ($scode as $s) {
    $code_pos = array_search($s,$scode);
    if ($code_pos > 9){
        $code_pos = $code_pos % 10;
    }
    $col = $colors[$code_pos];
    
    $getSTARTdate = $pdo->query("SELECT datetime FROM $s ORDER BY datetime ASC LIMIT 1");
    $sn_getSTARTdate = $getSTARTdate->fetch(); 
    $STARTdatetime = $sn_getSTARTdate['datetime'];
    $ex = explode(" ",$STARTdatetime);
    
    $gety1 = $pdo->query("SELECT y1,shares_now FROM live WHERE code = '$s'");
    $sn_gety1 = $gety1->fetch(); 
    
    $y1 = $sn_gety1['y1'];
    $shares_now = $sn_gety1['shares_now'];
    $buydate = $ex[0];
    $fourteenago = date('Y-m-d', strtotime($thisdate. " - $b days"));

    if(strtotime($buydate) > strtotime($fourteenago)) {
        $startdate = $buydate;
    }
    else {
        $startdate = $fourteenago;

    }
    
    $enddate = date('Y-m-d', strtotime($thisdate. " - $a days"));
    
    print $startdate;
    print "<br>";
    print $enddate;
    print "<br>";
    
    $string = "https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20yahoo.finance.historicaldata%20where%20symbol%20%3D%20%22".urlencode($s)."%22%20and%20startDate%20%3D%20%22".urlencode($startdate)."%22%20and%20endDate%20%3D%20%22".urlencode($enddate)."%22&format=json&diagnostics=true&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys&callback=";
    $string = file_get_contents($string);
    $json_result = json_decode($string, true);
    
    $jsonlength = count($json_result['query']['results']['quote']);
    
    $x = 0;
    if(null !== $json_result['query']['results']['quote'][$x]['Close']) {
    $points = array();    
    while($x < $jsonlength) {
        $closeprice = $json_result['query']['results']['quote'][$x]['Close'];
        $closeprice = round($closeprice,2);
        $thedate = $json_result['query']['results']['quote'][$x]['Date'];
        
        $newy2 = $shares_now*$closeprice;
        $newpl = $newy2-$y1;
        
        $set = array();
        array_push($set,strtotime($thedate));
        array_push($set,$newpl);
        array_push($points,$set);
        $net[strtotime($thedate)] = 0; 
        
        $x += 1;
    }
        
    $dataset = array('name'=>"$s",'color'=>"$col",'data'=>$points);
    array_push($final,$dataset);
    }
}

foreach($final as $f) {
    foreach($f['data'] as $set) {
        $net["$set[0]"] += $set[1];
        
    }
}

$netdata = array();
foreach($net as $key => $value) {
    $set = array();
    array_push($set,$key);
    array_push($set,$value);
    array_push($netdata,$set);
}

$datazet = array('name'=>"Total",'color'=>"#000000",'data'=>$netdata);
array_push($final,$datazet);

$fin = json_encode($final, JSON_NUMERIC_CHECK);
print $fin;
?>