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
            $test = $file . '*';
            //$filename = glob($file . '?*.pdf');
            $filename = glob($file . '*');
            echo('<script>alert("'.$file.'");</script>');
            echo('<script>alert("'.$test.'");</script>');
            echo('<script>alert("'.count($filename).'");</script>');
            echo('<script>alert("'.$filename[0].'");</script>');
            foreach($filename as $f) {
            if(file_exists($f) ){
                echo('<script>alert("'.$f.'");</script>');
                header('Content-Description: File Transfer');
                header('Content-Type: application/octet-stream');
                header('Content-Disposition: attachment; filename='.basename($f));
                header('Expires: 0');
                header('Cache-Control: must-revalidate');
                header('Pragma: public');
                header('Content-Length: ' . filesize($f));
                readfile($f);
                die($f);
                exit;
            }
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