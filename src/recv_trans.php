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
        case 10:
        case 11:
        case 12:
        case 13:
        case 14:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
        case 21:
        case 22:
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
        } elseif ($row['company'] == 10) { //Earthy Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['VendorAccount'] === "MAX-V000530") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 15) { //Strategia Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['VendorAccount'] === "MAX-V000531") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 13) { //Adeo
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000510") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 18) { //Positive Pretzel
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000577") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 22) { //Clown Global
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && (($item['VendorAccount'] === "USP-V000462") || ($item['VendorAccount'] === "USP-V000460") || ($item['VendorAccount'] === "USP-V000482") || ($item['VendorAccount'] === "USP-V000461") || ($item['VendorAccount'] === "USP-V000451")) && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 21) { //Hydrite Chemical
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['VendorAccount'] === "MAX-V000055") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date) && (substr($item['Item'], 0, 3) == "HYD")) {
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
        } elseif ($row['company'] == 20) { //Amsoil
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['VendorAccount'] === "MAX-V000629") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 19) { //V-Vax Products
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['VendorAccount'] === "MAX-V000216") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        }  elseif ($row['company'] == 16) { //Bay Valley Foods Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000551") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 17) { //SW Fulfillment Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "Maxpax Fulfillment") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 5) { //Cargill Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000093") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 14) { //Butterface Brands Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000575") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        } elseif ($row['company'] == 12) { //Ferrara Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['VendorAccount'] === "USP-V000481") && ($item['ReceiptDate'] >= $start_date) && ($item['ReceiptDate'] <= $end_date)) {
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
        }  elseif ($row['company'] == 7) { //GoPicnic Filter
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

