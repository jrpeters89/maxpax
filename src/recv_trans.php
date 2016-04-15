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
        case 9:
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
        if ($row['company'] == 9) { //Novis Works
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['VendorAccount'] === "MAX-V000339") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
                    $receipt['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $receipt['data'][$item['PackingSlipId']]['ReceiptDate'] = ($item['ReceiptDate'] != null ? $item['ReceiptDate'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");

                    $receipt['data'][$item['PackingSlipId']][] = array(
                        'PurchaseOrder' => ($item['PurchaseOrder'] != null ? $item['PurchaseOrder'] : ""),
                        'LineNumber' => ($item['LineNumber'] != null ? $item['LineNumber'] : ""),
                        'ItemNumber' => ($item['Item'] != null ? $item['Item'] : ""),
                        'Description' => ($item['Description'] != null ? $item['Description'] : ""),
                        'Received' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'Date' => ($item['DatePhysical'] != null ? $item['DatePhysical'] : ""),
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'Quantity' => ($item['Quantity'] != null ? $item['Quantity'] : "")
                    );

                }
            }
        } elseif ($row['company'] == 7) { //GoPicnic Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000153") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
                    $receipt['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $receipt['data'][$item['PackingSlipId']]['ReceiptDate'] = ($item['ReceiptDate'] != null ? $item['ReceiptDate'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");

                    $receipt['data'][$item['PackingSlipId']][] = array(
                        'PurchaseOrder' => ($item['PurchaseOrder'] != null ? $item['PurchaseOrder'] : ""),
                        'LineNumber' => ($item['LineNumber'] != null ? $item['LineNumber'] : ""),
                        'ItemNumber' => ($item['Item'] != null ? $item['Item'] : ""),
                        'Description' => ($item['Description'] != null ? $item['Description'] : ""),
                        'Received' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'Date' => ($item['DatePhysical'] != null ? $item['DatePhysical'] : ""),
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'Quantity' => ($item['Quantity'] != null ? $item['Quantity'] : "")
                    );

                }
            }
        } elseif ($row['company'] == 6) { //Energems Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000309") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
                    $receipt['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $receipt['data'][$item['PackingSlipId']]['ReceiptDate'] = ($item['ReceiptDate'] != null ? $item['ReceiptDate'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");

                    $receipt['data'][$item['PackingSlipId']][] = array(
                        'PurchaseOrder' => ($item['PurchaseOrder'] != null ? $item['PurchaseOrder'] : ""),
                        'LineNumber' => ($item['LineNumber'] != null ? $item['LineNumber'] : ""),
                        'ItemNumber' => ($item['Item'] != null ? $item['Item'] : ""),
                        'Description' => ($item['Description'] != null ? $item['Description'] : ""),
                        'Received' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'Date' => ($item['DatePhysical'] != null ? $item['DatePhysical'] : ""),
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'Quantity' => ($item['Quantity'] != null ? $item['Quantity'] : "")
                    );

                }
            }
        } elseif ($row['company'] == 3) { //Nourish Snacks
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000240") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
                    $receipt['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $receipt['data'][$item['PackingSlipId']]['ReceiptDate'] = ($item['ReceiptDate'] != null ? $item['ReceiptDate'] : "");
                    $receipt['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");

                    $receipt['data'][$item['PackingSlipId']][] = array(
                        'PurchaseOrder' => ($item['PurchaseOrder'] != null ? $item['PurchaseOrder'] : ""),
                        'LineNumber' => ($item['LineNumber'] != null ? $item['LineNumber'] : ""),
                        'ItemNumber' => ($item['Item'] != null ? $item['Item'] : ""),
                        'Description' => ($item['Description'] != null ? $item['Description'] : ""),
                        'Received' => ($item['Quantity'] != null ? $item['Quantity'] : ""),
                        'Date' => ($item['DatePhysical'] != null ? $item['DatePhysical'] : ""),
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'Quantity' => ($item['Quantity'] != null ? $item['Quantity'] : "")
                    );

                    $receipt['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);

                }
            }

        }
        $receipt['count'] = count($receipt['data']);


    }

}

echo(json_encode($receipt));

