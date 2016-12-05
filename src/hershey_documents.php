<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET["user_token"];
$company = $_GET["company"];
$start_date = $_GET["start_date"];
$end_date = $_GET["end_date"];

$hershey[count] = 0;
$start_date_string = str_replace("-", "", $start_date);
$end_date_string = str_replace("-", "", $end_date);

$dir = '//sw-apps-01/Hersh/Processed';
//$list = scandir('//sw-apps-01/Hersh/Processed');

function cmp($a, $b)
{
    return strcmp($a['TransactionDate'], $b['TransactionDate']);
}

if (is_dir($dir)) {
    if ($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if ((substr($file, 0, 8) >= $start_date_string) && (substr($file, 0, 8) <= $end_date_string)) { //&& (substr($file, -3) != 'wip')) {
                //$hershey[$file] = $file;
                $xml = simplexml_load_file($dir . "/" . $file);
                $json = json_encode($xml);
                $array = json_decode($json, TRUE);
                //$hershey[$file] = $file;
                $data = $array['Body']['MessageParts']['MAX_ProdJournalProd']['ProdJournalProd'];
                //$hershey[$file] = $data;
                //if($data['MaterialNumber'] != null) {
                $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
                $lic_plate = $data['MAX_LicensePlateNumber'];
                $result = mysqli_query($conn, "SELECT `lic_plate` FROM `hershey` WHERE `lic_plate`='$lic_plate'") or die(mysqli_error($conn));
                if (mysqli_num_rows($result) > 0) {

                } else {
                    $result = mysqli_query($conn, "INSERT INTO `hershey` (lic_plate) VALUES ('$lic_plate')");
                    $hershey['data'][$file]['TransactionDate'] = $data['TransDate'];
                    $hershey['data'][$file]['LicPlate'] = $data['MAX_LicensePlateNumber'];
                    $hershey['data'][$file]['TimeStamp'] = $data['CreateTimeStamp'];
                    $hershey['data'][$file]['Item'] = $data['MaterialNumber'];
                    $hershey['data'][$file]['Batch'] = $data['BatchNumber'];
                    $hershey['data'][$file]['Production'] = $data['ProdId'];
                    $hershey['data'][$file]['Description'] = $data['Description'];
                    $hershey['data'][$file]['Qty'] = $data['QtyGood'];
                    $hershey['data'][$file]['UOMDenominator'] = $data['UOMDenominator'];
                    $hershey['data'][$file]['UOM'] = $data['UOM'];
                    $hershey['data'][$file]['file'] = $file;
                    $hershey[count]++;
                }
                //}
            }
        }

        //usort($hershey, "cmp");
    }
}


echo json_encode($hershey);