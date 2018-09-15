<?php
  
include('../functions.php');
$colors = ['#7cb5ec', '#FFB6C1', '#90ed7d', '#f7a35c', '#8085e9', 
   '#f15c80', '#e4d354', '#2b908f', '#f45b5b', '#91e8e1'];

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
    
    $cyc = $pdo->prepare("SELECT cycle
    FROM $cod ORDER BY datetime");
    $cyc->execute();
    
    $code_pos = array_search($cod,$codes);
    if ($code_pos > 9){
        $code_pos = $code_pos % 10;
    }
    $col = $colors[$code_pos];

    $cycnums = array();
    while($c = $cyc->fetchAll()) {
        foreach($c as $k => $val) {
            $cycnum = $c[$k]['cycle'];
            if (!in_array($cycnum, $cycnums)) {

                $id = $pdo->prepare("SELECT ID FROM $cod 
                WHERE cycle = $cycnum");
                $id->execute();

                $sth = $pdo->prepare("SELECT pl FROM $cod
                WHERE cycle = $cycnum");
                $sth->execute();

                $tim = $pdo->prepare("SELECT datetime FROM $cod
                WHERE cycle = $cycnum");
                $tim->execute();
                
                    $numbers = array();
                    while($row = $sth->fetchAll()) {
                        foreach($row as $key => $value) {
                            $n = $row[$key]['pl'];
                            array_push($numbers, $n);
                        }
                    }
                
                    $times = array();
                    while($row = $tim->fetchAll()) {
                        foreach($row as $key => $value) {
                            $a = $row[$key]['datetime'];
                            array_push($times, $a);
                        }
                    }

                    $combined = array();
                    foreach($id as $key => $value) {
                        $n = $numbers[$key];
                        $t = $times[$key];
                        $format = "[".strtotime($t).",".$n."]";
                        array_push($combined, $format);
                    }
                $dataset = array('name'=>"$cod",'color'=>"$col",'data'=>$combined);
                array_push($fin,$dataset);
            }
            array_push($cycnums,$cycnum);
        }
    }
}

$id = $pdo->prepare("SELECT ID FROM net");
$id->execute();

$sth = $pdo->prepare("SELECT sumpl FROM net");
$sth->execute();

$tim = $pdo->prepare("SELECT timestamp FROM net");
$tim->execute();

    $numbers = array();
    while($row = $sth->fetchAll()) {
        foreach($row as $key => $value) {
            $n = $row[$key]['sumpl'];
            array_push($numbers, $n);
        }
    }

    $times = array();
    while($row = $tim->fetchAll()) {
        foreach($row as $key => $value) {
            $a = $row[$key]['timestamp'];
            array_push($times, $a);
        }
    }

    $combined = array();
    foreach($id as $key => $value) {
        $n = $numbers[$key];
        $t = $times[$key];
        $format = "[".strtotime($t).",".$n."]";
        array_push($combined, $format);
    }
$dataset = array('name'=>"Total",'color'=>"#000000",'data'=>$combined);
array_push($fin,$dataset);

$final = json_encode($fin, JSON_NUMERIC_CHECK);
$final1 = str_replace('["','[',$final);
$final2 = str_replace('"[','[',$final1);
$final3 = str_replace(']"',']',$final2);
$final4 = str_replace('"]',']',$final3);

print $final4;

?>