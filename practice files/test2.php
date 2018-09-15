<html>
<head>
</head>

<body>
<?php

function distance($Rate,$Time){
$Distance = $Rate * $Time;
return $Distance;
}    
if (distance(44,4) > 200) {
echo "<br>This distance is too far.<br>";
}
elseif(distance(40,3) == 120) {
echo "<br>This distance is the best.<br>";
}
else {
echo "<br>This is an ok distance.<br>";
}
    
    
    
    
    
$Color = "Yellow";
switch ($Color) {
case "Red":
echo "Red is a primary color to the rainbow<br>";
break; 
case "Yellow":
echo "Yellow is a primary color to the rainbow<br>";
break; 
default:
echo "Sorry that was not a primary color<br>";
break;
}
    
    
    
    
    
$Counter = 1;
while ($Counter < 10){
echo $Counter;
echo "<br>";
$Counter++;
}
echo "<br>You have finished the loop.<br>";
    
    
    
    
$Counter = 15;
do{
echo $Counter;
echo "<br/>";
$Counter--;
}while ($Counter > 1);
echo "<br>You have finished the loop!<br>";
    
    
    
    
for ($Counter = 1;$Counter < 20;$Counter++){
echo $Counter;
echo "<br>";
}
echo "<br>You have finished the loop.<br>";
    
    
    
    
    
$Days = array("Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday");
foreach($Days as $Day){
echo $Day;
echo "<br>";
echo "Today is ";
} 
echo "<br>";
    
    
$Time = 24;
if (5 < 6){
echo $Time;
echo "<br>";
$varscope = 20;
}
function Total() {
global $Time;
echo $Time;
echo "<br>";
}
Total();
echo $varscope;
echo "<br><br><br><br>";

    
    
$string = "this is a happy animal catfish";
$test = preg_replace('/\s+/', '', $string);
echo $test;
echo "<br>";
    
    
    
$y = "55455";
$x = str_replace('5', '', $y);
echo $x;
    
?>
</body>
    
</html>