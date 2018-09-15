<?php
  
include('../functions.php');
$colors = ['#007FFF', '#A9A9A9', '#90ed7d', '#f7a35c', '#8085e9', 
   '#f15c80', '#e4d354', '#2b908f', '#ff0000', '#00ced1'];

$code = $pdo->prepare("SELECT code FROM live");
$code->execute();

$code = $pdo->prepare("SELECT code FROM live");
$code->execute();

$codes = array();
while($scode = $code->fetchAll()) {
    foreach($scode as $key => $value) {
        $c = $scode[$key]['code'];
        array_push($codes, $c);
    }
}

$fin = array(); 
foreach($codes as $cod) {
    $code_pos = array_search($cod,$codes);
    if ($code_pos > 9){
        $code_pos = $code_pos % 10;
    }
    $col = $colors[$code_pos];

    $y1y2 = $pdo->query("SELECT y1,y2,pl,pl_rate
    FROM live WHERE code = '$cod' AND shares_now != 0");
    $sn_y1y2 = $y1y2->fetch();
    
    $thisarray = array($sn_y1y2['y1'],$sn_y1y2['y2']);
    
    $done = array('name'=>"$cod",'color'=>$col,'data'=>$thisarray);
    
    array_push($fin,$done);
}

$final = json_encode($fin, JSON_NUMERIC_CHECK);

print $final

?>