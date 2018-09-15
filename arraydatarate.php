<?php
  
include('functions.php');
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

$net = 0; 
$rates = array();
$categories = array();
$colar = array();
foreach($codes as $cod) {
    $code_pos = array_search($cod,$codes);
    if ($code_pos > 9){
        $code_pos = $code_pos % 10;
    }
    $col = $colors[$code_pos];

    $rate = $pdo->query("SELECT pl_rate
    FROM live WHERE code = '$cod' AND shares_now != 0");
    $sn_rate = $rate->fetch();
    
    $net += $sn_rate['pl_rate'];
    
    array_push($categories,"$cod");
    array_push($rates,$sn_rate['pl_rate']);
    array_push($colar,$col);
}

array_push($categories,"Net");
array_push($rates,$net);
array_push($colar,"#2b908f");

$stocklist = array('name'=>"Portfolio",'data'=>$categories);
$allrates = array('name'=>"Percent Change",'color'=>$colors[7],'data'=>$rates);
array_push($fin,$stocklist);
array_push($fin,$allrates);

$final = json_encode($fin, JSON_NUMERIC_CHECK);

print $final

?>