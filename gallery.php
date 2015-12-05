<html>
<head><title>Gallery</title>
<link rel="stylesheet" type="text/css" href="cjsmp2.css">
</head>
<body>

<?php
session_start();
$email = $_POST["email"];
echo $email;
require 'vendor/autoload.php';

use Aws\Rds\RdsClient;

$rds = new Aws\Rds\RdsClient([
'version' => 'latest',
'region'  => 'us-east-1'
]);

$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-cjs-db',
]);

$endpoint = $result['dbInstances'][0]['Endpoint']['Address'];

//echo "begin database";
$link = mysqli_connect($endpoint,"root","letmein22","csironITMO444db") or die("Error " . mysqli_error($link));

/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}


//below line is unsafe - $email is not checked for SQL injection -- don't do this in real life or use an ORM instead
$link->real_query("SELECT * FROM comments WHERE email = '$email'");

$results = $link->insert_id;

//$link->real_query("SELECT * FROM items");

$res = $link->use_result();
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
if ($_SESSION['gallerySession']){
    echo "<img src =\"" .$row['finishedS3'] . "\"/>";
}
else {
    echo "<img src =\"" .$row['RawS3'] . "\"/>
$link->close();
?>
</body>
</html>

