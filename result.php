<?php
// Start the session
session_start();
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
require 'vendor/autoload.php';
use Aws\S3\S3Client;
$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
echo $_POST['useremail'];
$uploaddir = '/tmp/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
echo '<pre>';
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
    echo "File is valid, and was successfully uploaded.\n";
} else {
    echo "Possible file upload attack!\n";
}
echo 'Here is some more debugging info:';
print_r($_FILES);
print "</pre>";
$bucket = uniqid("php-cjs",false);

$result = $s3->createBucket([
    'ACL' => 'public-read-write',
    'Bucket' => $bucket,
]);
print_r($result);

$result = $s3->putObject([
    'ACL' => 'public-read-write',
    'Bucket' => $bucket,
   'Key' => $uploadfile,
   'SourceFile' => $uploadfile,
]);  
$url = $result['ObjectURL'];
echo $url;
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-cjs-db',
    
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

//echo "begin database";^M
$link = mysqli_connect($endpoint,"root","letmein22","csironITMO444db") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$Uname = $_POST['username'];
$Email = $_POST['useremail'];
$Phone = $_POST['phone'];
$RawS3 = $url; //  $result['ObjectURL']; from above
$filename = basename($_FILES['userfile']['name']);
$finishedS3 = "none";
$status =0;
$issubscribed=0;
mysqli_query($link, "INSERT INTO comments (ID, Uname,Email,Phone,RawS3,filename,finishedS3,state,date) VALUES (NULL, '$Uname', '$Email', '$Phone', '$RawS3', '$finishedS3', '$filename', '$status', NULL)");
$results = $link->insert_id;
echo $link->error;
echo $results;

$query = "SELECT * FROM comments";
if($res =$link->query($query))
{
   printf("Select returned %d rows.\n", $res->num_rows);
}
echo "Result set order...\n";
while ($row = $res->fetch_assoc()) {
    echo $row['ID'] . " " . $row['email']. " " . $row['phone'];
}
$link->close();
//add code to detect if subscribed to SNS topic 
//if not subscribed then subscribe the user and UPDATE the column in the database with a new value 0 to 1 so that then each time you don't have to resubscribe them
// add code to generate SQS Message with a value of the ID returned from the most recent inserted piece of work
//  Add code to update database to UPDATE status column to 1 (in progress)
