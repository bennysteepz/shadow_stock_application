<?php
    include('functions.php');

    date_default_timezone_set("America/New_York");
    $current_time = date("h:i:a");
    $open = "9:45 am";
    $close = "4:15 pm";
    $time1 = DateTime::createFromFormat('H:i a', $current_time);
    $time2 = DateTime::createFromFormat('H:i a', $open);
    $time3 = DateTime::createFromFormat('H:i a', $close);

    $dt=date("Y-m-d");
        $dt1 = strtotime($dt);  
        $dt2 = date("l", $dt1);  
        $dt3 = strtolower($dt2);  
    if(($dt3 !== "saturday" )|| ($dt3 !== "sunday")){  
        if ($time1 > $time2 && $time1 < $time3){
            updateCurrval();
            postLive();
            include('chartrate.php');
        } 
        else {
            postLive();
            include('chartrate.php');
        }
    }  
?>