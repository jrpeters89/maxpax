<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$start_date = $_GET[startDate];
if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/InventoryTransactions/inventoryTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['InventTransAPP']['InventTrans'];

        foreach ($data as $item) {
            if ((substr($item['ItemId'], 0, 3) == "ADM") && ($item['InventTransRefId'] != null) && ($item['InventBatchId'] != null) && ($item['DatePhysical'] != null)){// &&  ($item['DatePhysical'] == $start_date)) {

                $inv_trans['data'][$item['ItemId']][$item['InventTransType']][] = array(
                    'ReferenceId' => $item['InventTransRefId'],
                    'Qty' => number_format($item['Qty'],2,".",","),
                    'UOM' => $item['InventUnitId'],
                    'Lot' => $item['InventBatchId'],
                    'Date' => $item['DatePhysical']
                );


                /*$inv_trans['data'][] = array(
                    'CompanyId' => $inv['CompanyId'],
                    'DatePhysical' => $inv['DatePhysical'],
                    'InventTransRefId' => $inv['InventTransRefId'],
                    'InventTransType' => $inv['InventTransType'],
                    'InventUnitId' => $inv['InventUnitId'],
                    'ItemId' => $inv['ItemId'],
                    'ItemName' => $inv['ItemName'],
                    'Qty' => $inv['Qty']
                );*/
            }
        }

        $inv_trans['count'] = count($inv_trans['data']);
    }

}

echo(json_encode($inv_trans));