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
$rds = new Aws\Rds\RdsClient([
'version' => 'latest',
'region'  => 'us-east-1'
]);
$result = $rds->describeDBInstances([
    'DBInstanceIdentifier' => 'mp1-cjs-db',
    
]);
$endpoint = $result['DBInstances'][0]['Endpoint']['Address'];

//echo "begin database";
$link = mysqli_connect($endpoint,"root","letmein22","csironITMO444db") or die("Error " . mysqli_error($link));
/* check connection */
if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}
mysqli_query($link, "SELECT * FROM comments WHERE email = '$email'");
$results = $link->insert_id;

$query = "SELECT * FROM comments WHERE email = '$email'";
if($res =$link->query($query))
{
  # printf("Select returned %d rows.\n", $res->num_rows);
}
while ($row = $res->fetch_assoc()) {
	if($_SESSION['gallery']({
		echo "<img src =\" " . $row['finishedS3'] . "\" /> <br />";
	}
	else {
		echo $row['RawurlTable'];
		echo "<img src =\" " . $row['RawS3'] . "\" /> <br />";
	}
}
$link->close();
?>
</body>
</html>
