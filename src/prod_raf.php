<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET['user_token'];
$company = $_GET['company'];


$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
$result = mysqli_query($conn, "SELECT `access_level` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
$internal_user = mysqli_query($conn, "SELECT `access_level` FROM `users` WHERE `token` = '$user_token'") or die(mysqli_error($conn));

if (mysqli_num_rows($result) > 0 || mysqli_num_rows($internal_user) > 0) {
    $row = mysqli_fetch_array($result);

    $xml = simplexml_load_file("../Data/ProductionRAF/ProdRAF.xml");
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $data = $array['Body']['MessageParts']['MAX_ProdRAFTmp']['MAX_ProdRAFTmp'];
    if ($company == 2) {
        $company_name = "US Packaging LLC";
    } else {
        $company_name = "MaxPax LLC";
    }

    $total = 0;

    foreach ($data as $item) {
        if (($item['CompanyName'] === $company_name) ) {
            $inv_adj['data'][$item['ItemId']]['ItemId'] = ($item['ItemId'] != null ? $item['ItemId'] : "");
            $inv_adj['data'][$item['ItemId']]['Name'] = ($item['Name'] != null ? $item['Name'] : "");

            $inv_adj['data'][$item['ItemId']]['ProdId'] = ($item['ProdId'] != null ? $item['ProdId'] : "");
            $inv_adj['data'][$item['ItemId']]['ProdStatus'] = ($item['ProdStatus'] != null ? $item['ProdStatus'] : "");
            $inv_adj['data'][$item['ItemId']]['QtySched'] = ($item['QtySched'] != null ? $item['QtySched'] : "");
            $inv_adj['data'][$item['ItemId']]['RemainInventPhysical'] = ($item['RemainInventPhysical'] != null ? $item['RemainInventPhysical'] : 0);
            $inv_adj['data'][$item['ItemId']]['Notes'] = ($item['Notes'] != null ? $item['Notes'] : "");
            $inv_adj['data'][$item['ItemId']]['FirstRAFDate'] = ($item['FirstRAFDate'] != null ? $item['FirstRAFDate'] : "");


            /*$inv_adj['data'][$item['Item']]['Subtotal'] += round(floatval($item['AccountingCurrencyAmount']),2);
            $inv_adj['data'][$item['Item']]['SubtotalString'] = number_format($inv_adj['data'][$item['Item']]['Subtotal'],2,".",",");

            $inv_adj['Total'] += round(floatval($item['AccountingCurrencyAmount']),2);*/
        }

    }

    $inv_adj['count'] = count($inv_adj['data']);
}



echo json_encode($inv_adj);