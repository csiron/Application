<?php

require 'vendor/autoload.php';
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);

$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-cjs-db',
]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";
$link = mysqli_connect($endpoint,"root","letmein22","csironITMO444db") or die("Error " . mysqli_error($link)); 
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
//conection: 
//echo "Hello world"; 
//echo "Here is the result: " . $link;
$sql = "CREATE TABLE comments
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
PosterName VARCHAR(32),
Title VARCHAR(32),
Content VARCHAR(500),
uname VARCHAR(20),
email VARCHAR(20),
phone VARCHAR(20),
raws3URL VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
date TIMESTAMP)";

if (mysqli_query($link, $sql)){
    echo "Table persons created successfully";
} else {
    echo "ERROR: Could not able to execute $sql. " . mysqli_error($link);
}
mysqli_close($link);
?>
