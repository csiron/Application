<html>
<body>
<link rel="stylesheet" type="text/css" href="cjsmp2.css">
<?php
// Start the session
session_start();
// In PHP versions earlier than 4.1.0, $HTTP_POST_FILES should be used instead
// of $_FILES.
require 'vendor/autoload.php';

$s3 = new Aws\S3\S3Client([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
echo $_POST['useremail'];
$email = $_POST['useremail'];
$sn = new Aws\Sns\SnsClient([
        'version' => 'latest',
        'region' => 'us-east-1'
]);
$resultsARN = $sn->createTopic([
        'Name' => 'cjsmp2',
]);
print("List All Platform Applications:\n");
$Model = $sn->listTopics();
foreach ($Model1['Topics'] as $App)
  {
    print($App['TopicArn'] . "\n");
  }
  print("\n");
  
  $mp2Arn = $Model['Topics'][0]['TopicArn'];
$resultsSetTopicAttr = $sn->setTopicAttributes([
    'AttributeName' => 'DisplayName', // REQUIRED
    'AttributeValue' => 'itmo444mp2',
    'TopicArn' => $mp2Arn, // REQUIRED
]);
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
$uploaddirT = '/tmp/';
$uploadfileT = $uploaddirT . basename($_FILES['userfile']['name']);
$file = $uploadfile;
$newfile = '/tmp/Thumb.png';
if(!copy($file, $newfile)){
        echo "falled to copy";
}
$image = new Imagick($newfile);
$image->thumbnailImage(50, 0);
$image->writeImage($newfile);

echo $uploadfileT;
echo $_FILES['userfile']['tmp_name'];
$cthumb = $s3->putObject([
  'ACL' => 'public-read-write',
  'Bucket' => $bucket,
    'Key' => $newfile,
    'SourceFile' => $newfile,
]);
$url = $result['ObjectURL'];
echo $Rawurl;
$finurl = $cthumb['ObjectURL'];
echo $finshedurl;
$rds = new Aws\Rds\RdsClient([
    'version' => 'latest',
    'region'  => 'us-east-1'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-cjs-db',
    
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];
print "============\n". $endpoint . "================\n";
//echo "begin database";^M
$link = mysqli_connect($endpoint,"root","letmein22","csironITMO444db") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

mysqli_query($link, "SELECT Count(*) FROM comments WHERE email = '$Email'");
$results = $link->insert_id;

$query = "SELECT Count(*) FROM comments WHERE email = '$Email'";
$res =$link->query($query);
$num_rows = mysqli_fetch_row($res);

if($num_rows[0] > 0){

  $Uname = $_POST['username'];
  $Email = $_POST['useremail'];
  $Phone = $_POST['phone'];
  $RawS3 = $Rawurl; //  $result['ObjectURL']; from above
  $filename = basename($_FILES['userfile']['name']);
  $finishedS3 = $Finshedurl;
  $status =0;
  $issubscribed=0;
  mysqli_query($link, "INSERT INTO comments (ID, Uname,Email,Phone,RawS3url,finishedS3url,jpgfile,state,date) VALUES (NULL, '$Uname', '$Email', '$Phone', '$Rawurl', '$finishedurl', '$filename', '$status', NULL)");
  $results = $link->insert_id;

  $subscriberArns = $sn->listSubscriptionsByTopic([
    'TopicArn' => $mp2Arn,
  ]);
  print $subscriberArns;
  $mp2Publish = $sn->publish([
    'Message' => 'An image has been posted to the gallery',
    'TopicArn' => $mp2Arn,
    ]);
}else{

  $resultSub = $sn->subscribe([
     'Endpoint' => $email,
     'Protocol' => 'email', // REQUIRED
     'TopicArn' => $mp2Arn, // REQUIRED
  ]);
  $uname = $_POST['username'];
  #$email = $_POST['useremail'];
  $phone = $_POST['phone'];
  $s3rawurl = $Rawurl; //  $result['ObjectURL']; from above
  $filename = basename($_FILES['userfile']['name']);
  $s3finishedurl = $finishedurl;
  $status =0;
  $issubscribed=0;
  mysqli_query($link, "INSERT INTO comments (ID, Uname,Email,Phone,RawS3,finishedS3,jpgfile,state,date) VALUES (NULL, '$Uname', '$Email', '$Phone', '$Rawurl', '$finishedurl', '$filename', '$status', NULL)");
  $results = $link->insert_id;
  $resultsubArns = $sn->listSubscriptionsByTopic([
  'TopicArn' => $AppArn,
  ]);

  $resulstPub = $sn->publish([
  'Message' => 'An image has been uploaded',
  'TopicArn' => $AppArn,
  ]);
}
  header('Location: gallery.php'); 

function thumb_create($file, $width , $height ) {
  try
  {

          $image = $file;
  
          $im = new Imagick();
  
          $im->pingImage($image);
  
          $im->readImage( $image );
  
          $im->thumbnailImage( $width, $height );
  
          $im->writeImage( $file );
  
          $im->destroy();
          return 'THUMB_'.$file;
          
  }
  catch(Exception $e)
  {
          print $e->getMessage();
          return $file;
  }
};
   
?>
</body>


</html>
