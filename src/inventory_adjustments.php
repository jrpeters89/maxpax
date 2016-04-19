<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET['user_token'];
$company = $_GET['company'];
$start_date = $_GET[start_date];
$end_date = $_GET[end_date];

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
$result = mysqli_query($conn, "SELECT `access_level` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
$internal_user = mysqli_query($conn, "SELECT `access_level` FROM `users` WHERE `token` = '$user_token'") or die(mysqli_error($conn));

if (mysqli_num_rows($result) > 0 || mysqli_num_rows($internal_user) > 0) {
    $row = mysqli_fetch_array($result);

    $xml = simplexml_load_file("../Data/GLData/GLData.xml");
    $json = json_encode($xml);
    $array = json_decode($json, TRUE);
    $data = $array['Body']['MessageParts']['MAX_GLTransAPP']['MAX_GLTransData_1'];

    if ($company == 2) {
        $company_name = "usp";
    } else {
        $company_name = "max";
    }

    $total = 0;

    foreach ($data as $item) {
        if (($item['SubledgerVoucherDataAreaId'] === $company_name) && ($item['AccountingDate'] >= $start_date) && ($item['AccountingDate'] <= $end_date)) {
            $inv_adj['data'][$item['Item']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
            $inv_adj['data'][$item['Item']]['Name'] = ($item['Name'] != null ? $item['Name'] : "");
            $inv_adj['data'][$item['Item']]['ItemGroup'] = ($item['ItemGroup'] != null ? $item['ItemGroup'] : "");

            $inv_adj['data'][$item['Item']][] = array(
                'Date' => ($item['AccountingDate'] != null ? $item['AccountingDate'] : ""),
                'User' => ($item['CreatedUser'] != null ? $item['CreatedUser'] : ""),
                'Voucher' => ($item['Voucher'] != null ? $item['Voucher'] : ""),
                'Amount' => ($item['AccountingCurrencyAmount'] != null ? number_format($item['AccountingCurrencyAmount'],2,".",",") : ""),
            );

            $inv_adj['data'][$item['Item']]['Subtotal'] += round(floatval($item['AccountingCurrencyAmount']),2);
            $inv_adj['data'][$item['Item']]['SubtotalString'] = number_format($inv_adj['data'][$item['Item']]['Subtotal'],2,".",",");

            $inv_adj['Total'] += round(floatval($item['AccountingCurrencyAmount']),2);
        }
        
    }

    $inv_adj['count'] = count($inv_adj['data']);
}



echo json_encode($inv_adj);