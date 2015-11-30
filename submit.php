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

$Model1 = $sn->listTopics();
  
$cjsArn = $Model1['Topics'][0]['TopicArn'];

$snsARN = $sn->createTopic([
        'Name' => 'cjsmp2',
]);

$snsSetTopicAttr = $sn->setTopicAttributes([
    'AttributeName' => 'DisplayName', // REQUIRED
    'AttributeValue' => 'cjsmp2topic',
    'TopicArn' => $cjsArn, // REQUIRED
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
$bucket = uniqid("php-cjs-",false);
$result = $s3->createBucket([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
]);
print_r($result);
$client->waitUntilBucketExists(array('Bucket' => $bucket));
$result = $s3->putObject([
    'ACL' => 'public-read',
    'Bucket' => $bucket,
    'Key' => $uploadfile,
    'SourceFile' => $uploadfile,
]);  
echo 'finurl'.$finurl;
$tres = thumb_create( $_FILES['userfile']['name'],50,50));
print 'tress'.$tres;
$cjsthumb = $s3->putObject([
   'ACL' => 'public-read-write',
   'Bucket' => $bucket,
   'Key' => $tres,
   'SourceFile' => $tres,
]);
$url = $result['ObjectURL'];
echo $url;
$finurl = $cthumb['ObjectURL'];
echo $finurl;
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

mysqli_query($link, "SELECT Count(*) FROM comments WHERE email = '$email'");
$results = $link->insert_id;

$query = "SELECT Count(*) FROM comments WHERE email = '$email'";
$res =$link->query($query);
$num_rows = mysqli_fetch_row($res);

if($num_rows[0] > 0){
  $uname = $_POST['username'];
  $email = $_POST['useremail'];
  $phone = $_POST['phone'];
  $s3rawurl = $url; //  $result['ObjectURL']; from above
  $filename = basename($_FILES['userfile']['name']);
  $s3finishedurl = $finurl;
  $status =0;
  $issubscribed=0;
  mysqli_query($link, "INSERT INTO comments (ID, uname,email,phone,rs3URL,fs3URL,jpgfile,state,date) VALUES (NULL, '$uname', '$email', '$phone', '$s3rawurl', '$s3finishedurl', '$filename', '$status', NULL)");
  $results = $link->insert_id;

  $subArns = $sn->listSubscriptionsByTopic([
    'TopicArn' => $cjsArn,
  ]);

  $resultsPublish = $sn->publish([
    'Message' => 'A user has submitted an image',
    'TopicArn' => $cjsArn,
    ]);
}
else{

  $resultSubs = $sn->subscribe([
     'Endpoint' => $phone,
     'Protocol' => 'sms', // REQUIRED
     'TopicArn' => $cjsArn, // REQUIRED
  ]);
  $uname = $_POST['username'];
  $email = $_POST['useremail'];
  $phone = $_POST['phone'];
  $s3rawurl = $url; //  $result['ObjectURL']; from above
  $filename = basename($_FILES['userfile']['name']);
  $s3finishedurl = $finurl;
  $status =0;
  $issubscribed=0;
  mysqli_query($link, "INSERT INTO comments (ID, uname,email,phone,rs3URL,fs3URL,jpgfile,state,date) VALUES (NULL, '$uname', '$email', '$phone', '$s3rawurl', '$s3finishedurl', '$filename', '$status', NULL)");
  $results = $link->insert_id;
  $subArns = $sn->listSubscriptionsByTopic([
  'TopicArn' => $cjsArn,
  ]);

  $resultsPublish = $sn->publish([
  'Message' => 'An image has been posted to the gallery',
  'TopicArn' => $cjsArn,
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
