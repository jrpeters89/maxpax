<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$start_date = $_GET[start_date];
$end_date = $_GET[end_date];
$company_id = $_GET[company_id];

if (!empty($user_token)) {
    switch ($company_id) {
        case 1:
        case 2:
        case 3:
        case 4:
        case 5:
        case 6:
        case 7:
        case 8:
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
            $result = mysqli_query($conn, "SELECT `id` AS `company` FROM `companies` WHERE `id`='$company_id'") or die(mysqli_error($conn));
            break;
        default:
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
            $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/ReceivingTransactions/ReceivingTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['MAX_ReceiptTransAPP']['MAX_ReceiptTransTmp'];
        if ($row['company'] == 6) { //Energems Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['PurchaseOrder'] === "USP-V000330") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
                    $receipt['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $receipt['data'][$item['PackingSlipId']]['ReceiptDate'] = ($item['ReceiptDate'] != null ? $item['ReceiptDate'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");

                    $receipt['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                    );

                    $receipt['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);
                }
            }
        } elseif ($row['company'] == 3) { //Nourish Snacks
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['PurchaseOrder'] === "USP-V000240") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
                    $receipt['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $receipt['data'][$item['PackingSlipId']]['ReceiptDate'] = ($item['ReceiptDate'] != null ? $item['ReceiptDate'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");

                    $receipt['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),

                    );

                    $receipt['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);

                }
            }

        }
        $receipt['count'] = count($receipt['data']);


    }

}

echo(json_encode($receipt));

