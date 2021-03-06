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

        $xml = simplexml_load_file("../Data/InventoryTransactions/inventoryTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['InventTransAPP']['InventTrans'];

        if ($row['company'] == 6) { //Energems Filter
            foreach ($data as $item) {
                if ((substr($item['ItemId'], 0, 3) == "ENE") && ($item['CompanyId'] === "usp") && ($item['Qty'] != null) && ($item['InventTransRefId'] != null) && ($item['InventBatchId'] != null) && ($item['DatePhysical'] != null) && ($item['DatePhysical'] > $start_date) && ($item['DatePhysical'] < $end_date)) {
                    $inv_trans['data'][$item['ItemId']][$item['InventTransType']][] = array(
                        'ReferenceId' => $item['InventTransRefId'],
                        'Qty' => number_format($item['Qty'], 2, ".", ","),
                        'UOM' => $item['InventUnitId'],
                        'Lot' => $item['InventBatchId'],
                        'Date' => $item['DatePhysical'],
                        'ItemId' => $item['ItemId'],
                        'TransType' => $item['InventTransType']
                    );
                }
            }

        } else { //ADM
            foreach ($data as $item) {
                if ((substr($item['ItemId'], 0, 3) == "ADM") && ($item['CompanyId'] === "usp") && ($item['Qty'] != null) && ($item['InventTransRefId'] != null) && ($item['InventBatchId'] != null) && ($item['DatePhysical'] != null) && ($item['DatePhysical'] > $start_date) && ($item['DatePhysical'] < $end_date)) {
                    $inv_trans['data'][$item['ItemId']][$item['InventTransType']][] = array(
                        'ReferenceId' => $item['InventTransRefId'],
                        'Qty' => number_format($item['Qty'], 2, ".", ","),
                        'UOM' => $item['InventUnitId'],
                        'Lot' => $item['InventBatchId'],
                        'Date' => $item['DatePhysical'],
                        'ItemId' => $item['ItemId'],
                        'TransType' => $item['InventTransType']
                    );

                }
            }
        }
        $inv_trans['count'] = count($inv_trans['data']);
    }

}

echo(json_encode($inv_trans));