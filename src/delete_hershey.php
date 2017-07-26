<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

//get license plate number

$lic_plate = $request[1]->value;

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');

$result = mysqli_query($conn, "DELETE FROM `hersheylabel` where MAX_LicensePlateNumber = `$lic_plate`") or die(mysqli_error($conn));

/**$file = stripslashes($request[10]->value);

$dir = '//sw-apps-01/Hersh/Processed/';

if(is_dir($dir)) {
    if ($dh = opendir($dir)) {
        unlink($dir . $file);
    }
}

header('Location: http://reports.maxpaxllc.com');**/

echo json_encode($result);