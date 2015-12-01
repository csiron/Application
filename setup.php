<?php
session_start();
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
echo "Here is the result: " . $link;

$sql = "CREATE TABLE comments 
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
Uname VARCHAR(32),
Email VARCHAR(32),
Phone VARCHAR(32),
RawS3 VARCHAR(256),
finishedS3 VARCHAR(256),
filename VARCHAR(256),
jpgfile VARCHAR(256),
state TINYINT(3),
date TIMESTAMP):;
)";

mysqli_close($link);
?>
