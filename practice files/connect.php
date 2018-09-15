<?php

$db_handle = mysqli_connect("localhost", "base1", "Snowcycle7" );

$database = "base1";

$db_found = mysqli_select_db($db_handle, $database);

if ($db_found) {

$add = "INSERT INTO stock_info (stockname, sharenumber, boughtat)
VALUES ('X', 6, 4.32)"; 
$delete = "DELETE FROM stock_info";
    
if(mysqli_query($db_handle, $delete)){
    echo "Records inserted successfully.";
} else{
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
  
$SQL = "SELECT * FROM stock_info";
$result = mysqli_query($db_handle, $SQL);

while ( $db_field = mysqli_fetch_assoc($result) ) {

print $db_field['stockname'] . "<BR>";
print $db_field['sharenumber'] . "<BR>";
print $db_field['boughtat'] . "<BR>";
}
}

else {

print "Database NOT Found ";

}

mysqli_close($db_handle);

?>