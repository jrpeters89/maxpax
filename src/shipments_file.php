<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `access_level`,`documents` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $file = $_GET["loc"];
        if (!empty($file)) {
            $filename = glob($file . '*');
            //foreach ($filename as $f) {
                if (file_exists($filename[0])) {
                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    //header('Content-Disposition: attachment; filename=' . basename($filename[0]));
                    header("Content-Disposition: attachment; filename=".urlencode(basename($filename[0])));
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate');
                    header('Pragma: public');
                    header('Content-Length: ' . filesize($filename[0]));
                    readfile($filename[0]);

                    exit;
                }
            //}
        } else {
            echo "Invalid file path";
        }
    } else {
        echo "You do not have permission to view this file.";
    }
} else {
    echo "You do not have permission to view this file.";
}