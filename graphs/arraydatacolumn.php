<?php
  
include('../functions.php');
$colors = ['#007FFF', '#FFB6C1', '#90ed7d', '#f7a35c', '#8085e9', 
   '#f15c80', '#e4d354', '#2b908f', '#ff0000', '#91e8e1'];

$code = $pdo->prepare("SELECT code FROM live WHERE shares_now != 0");
$code->execute();

$codes = array();
while($scode = $code->fetchAll()) {
    foreach($scode as $key => $value) {
        $c = $scode[$key]['code'];
        array_push($codes, $c);
    }
}

$fin = array();
$y1net = 0;
$y2net = 0; 
$categories = array();
$y1 = array();
$y2 = array();
$colar = array();
foreach($codes as $cod) {
    $code_pos = array_search($cod,$codes);
    if ($code_pos > 9){
        $code_pos = $code_pos % 10;
    }
    $col = $colors[$code_pos];

    $y1y2 = $pdo->query("SELECT y1,y2,pl,pl_rate
    FROM live WHERE code = '$cod' AND shares_now != 0");
    $sn_y1y2 = $y1y2->fetch();
    
    $y1net += $sn_y1y2['y1'];
    $y2net += $sn_y1y2['y2'];
    
    array_push($categories,"$cod");
    array_push($y1,$sn_y1y2['y1']);
    array_push($y2,$sn_y1y2['y2']);
    array_push($colar,$col);
}

array_push($categories,"Net");
array_push($y1,$y1net);
array_push($y2,$y2net);

$cat_array = array('name'=>"Portfolio",'data'=>$categories);
$y1_array = array('name'=>"Total Invested",'color'=>$colors[0],'data'=>$y1);
$y2_array = array('name'=>"Current Value",'color'=>$colors[3],'data'=>$y2);
array_push($fin,$cat_array);
array_push($fin,$y1_array);
array_push($fin,$y2_array);

$final = json_encode($fin, JSON_NUMERIC_CHECK);

print $final

?>