<?php
//conection: 
echo "Hello world"; 
$link = mysqli_connect("csironITMO444db","root","letmein22","3306") or die("Error " . mysqli_error($link)); 

echo "Here is the result: " . $link;


$sql = "CREATE TABLE comments 
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
Uname VARCHAR(20),
Email VARCHAR(20),
Phone VARCHAR(20),
RawURL VARCHAR(256),
FinishedURL VARCHAR(256),
ImageFileName VARCHAR(256),
level TinyInt(0 1 2) pending finished or error,
time timestamp DEFAULT CURRENT_TIMESTAMP,
)";

$con->query($sql);

?>

