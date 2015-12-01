<?php

session_start();

require '/var/www/html/vendor/autoload.php';

$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1',
]);

$result = $rds->createDBInstance([
    'AllocatedStorage' =>10,
    'DBInstanceClass' => 'db.t1.micro', // REQUIRED
    'DBInstanceIdentifier' => 'mp1-cjs-db', // REQUIRED
    'DBName' => 'csironITMO444db',
    'Engine' => 'MySQL', // REQUIRD
    'MasterUserPassword' => 'letmein22',
    'MasterUsername' => 'root',
    'PubliclyAccessible' => true,
]);
print "Create RDS DB results: \n";

$result = $rds->waitUntil('DBInstanceAvailable',['DBInstanceIdentifier' => 'mp1-cjs-db',
]);

// Create a table 
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-cjs-db',
]);

$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";
$link = mysqli_connect($endpoint,"controller","letmein22","csironITMO444db") or die("Error " . mysqli_error($link));
#echo "Here is the result: " . $link;
$sql = "DROP TABLE IF EXISTS comments";
if(!mysqli_query($link, $sql)) {
   echo "Error : " . mysqli_error($link);
} 
$sql = "CREATE TABLE comments  
(
ID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
uname VARCHAR(20),
email VARCHAR(20),
phone VARCHAR(20),
rawS3url VARCHAR(256),
finishedS3url VARCHAR(256),
filename VARCHAR(256),
state TINYINT(3),
datetime timestamp 
)";
?>
