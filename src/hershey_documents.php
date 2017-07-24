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
    return strcmp($a->TransDate, $b->TransDate);
}
if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');

    $result = mysqli_query($conn, "SELECT * FROM `hersheylabel` WHERE `TransDate` > '$start_date' and `TransDate` < '$end_date'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $lic_plate = $row["MAX_LicensePlateNumber"];

            $hershey['data'][$lic_plate]['TransDate'] = $row["TransDate"];
            $hershey['data'][$lic_plate]['LicPlate'] = $row["MAX_LicensePlateNumber"];
            $hershey['data'][$lic_plate]['BatchNumber'] = $row["BatchNumber"];
            $hershey['data'][$lic_plate]['ProdId'] = $row["ProdId"];
            $hershey['data'][$lic_plate]['Description'] = $row["Description"];
            $hershey['data'][$lic_plate]['QtyGood'] = $row["QtyGood"];
            $hershey['data'][$lic_plate]['UOMDenominator'] = $row["UOMDenominator"];
            $hershey['data'][$lic_plate]['UOM'] = $row["UOM"];
            $hershey['data'][$lic_plate]['InventTransId'] = $row['InventTransId'];
            $hershey['data'][$lic_plate]['JournalId'] = $row['JournalId'];
            $hershey['data'][$lic_plate]['MaterialNumber'] = $row['MaterialNumber'];
            $hershey['data'][$lic_plate]['Id'] = $row['Id'];
            //$hershey['data'][$lic_plate]['file'] = $file;
            $hershey[count]++;
        }

    }

//    usort($hershey, "cmp");


}

echo json_encode($hershey);