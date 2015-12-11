<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if(!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `access_level`,`documents` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if(mysqli_num_rows($result) > 0) {
        $file = $_GET["loc"];
        if(!empty($file)) {
            //if (file_exists($file)) {
            //if(glob($file.'-*.pdf')){
            $test = $file . "?*.pdf";
            //$filename = glob($file . '?*.pdf');
            $filename = glob($test);
            echo('<script>alert("'.$file.'");</script>');
            echo('<script>alert("'.$test.'");</script>');
            echo('<script>alert("'.$filename[0].'");</script>');
            //foreach(glob($file."*.pdf") as $filename) {
            if(file_exists($filename[0]) && is_file($filename[0])){
                echo('<script>alert("'.$filename[0].'");</script>');
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($filename[0]));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($filename[0]));
                readfile($filename[0]);
                die($filename[0]);
                exit;
            } else {
                die($filename[0]);
            }
        } else {
            echo "Invalid file path";
        }
    } else {
        echo "You do not have permission to view this file.";
    }
} else {
    echo "You do not have permission to view this file.";
}