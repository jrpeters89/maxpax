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

        $xml = simplexml_load_file("../Data/ShippingTransactions/ShippingTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['MAX_ShipTransAPP']['MAX_ShipTransTmp'];
        if($row['company'] == 9) { //Novis Works
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['CustomerNumber'] === "MAX-C000060") && ($item['ShipDate'] >= $start_date) && ($item['ShipDate'] <= $end_date)) {
                    $shipments['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $shipments['data'][$item['PackingSlipId']]['ShipDate'] = ($item['ShipDate'] != null ? $item['ShipDate'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");
                    $shipments['data'][$item['PackingSlipId']]['SalesOrder'] = ($item['SalesOrder'] != null ? $item['SalesOrder'] : "");
                    $shipments['data'][$item['PackingSlipId']]['CustomerRef'] = ($item['CustomerRef'] != null ? $item['CustomerRef'] : "");

                    $shipments['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'ExpirationDate' => ($item['ExpirationDate'] != null ? $item['ExpirationDate'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );

                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);
                }
            }
        } elseif ($row['company'] == 10) { //Earthy Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "MaxPax LLC") && ($item['CustomerNumber'] === "MAX-C000070") && ($item['ShipDate'] >= $start_date) && ($item['ShipDate'] <= $end_date)) {
                    $shipments['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $shipments['data'][$item['PackingSlipId']]['ShipDate'] = ($item['ShipDate'] != null ? $item['ShipDate'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");
                    $shipments['data'][$item['PackingSlipId']]['SalesOrder'] = ($item['SalesOrder'] != null ? $item['SalesOrder'] : "");
                    $shipments['data'][$item['PackingSlipId']]['CustomerRef'] = ($item['CustomerRef'] != null ? $item['CustomerRef'] : "");

                    $shipments['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'ExpirationDate' => ($item['ExpirationDate'] != null ? $item['ExpirationDate'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );

                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);
                }
            }
        } elseif ($row['company'] == 6) { //Energems Filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['CustomerNumber'] === "USP-C000042") && ($item['ShipDate'] >= $start_date) && ($item['ShipDate'] <= $end_date)) {
                    $shipments['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $shipments['data'][$item['PackingSlipId']]['ShipDate'] = ($item['ShipDate'] != null ? $item['ShipDate'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");
                    $shipments['data'][$item['PackingSlipId']]['SalesOrder'] = ($item['SalesOrder'] != null ? $item['SalesOrder'] : "");
                    $shipments['data'][$item['PackingSlipId']]['CustomerRef'] = ($item['CustomerRef'] != null ? $item['CustomerRef'] : "");

                    $shipments['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'ExpirationDate' => ($item['ExpirationDate'] != null ? $item['ExpirationDate'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );

                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);
                }
            }
        } elseif ($row['company'] == 3) { //Nourish Snacks
            foreach ($data as $item) {
                            if (($item['CompanyName'] === "US Packaging LLC") && ($item['CustomerNumber'] === "USP-C000041") && ($item['ShipDate'] >= $start_date) && ($item['ShipDate'] <= $end_date)) {
                                $shipments['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                                $shipments['data'][$item['PackingSlipId']]['ShipDate'] = ($item['ShipDate'] != null ? $item['ShipDate'] : "");
                                $shipments['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                                $shipments['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");
                                $shipments['data'][$item['PackingSlipId']]['SalesOrder'] = ($item['SalesOrder'] != null ? $item['SalesOrder'] : "");
                                $shipments['data'][$item['PackingSlipId']]['CustomerRef'] = ($item['CustomerRef'] != null ? $item['CustomerRef'] : "");

                                $shipments['data'][$item['PackingSlipId']][] = array(
                                    'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                                    'ExpirationDate' => ($item['ExpirationDate'] != null ? $item['ExpirationDate'] : ""),
                                    'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                                    'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                                );

                                $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);

                            }
                        }

        } elseif ($row['company'] == 8) { //WF Young
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['CustomerNumber'] === "USP-C000012") && ($item['ShipDate'] >= $start_date) && ($item['ShipDate'] <= $end_date)) {
                    $shipments['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $shipments['data'][$item['PackingSlipId']]['ShipDate'] = ($item['ShipDate'] != null ? $item['ShipDate'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");
                    $shipments['data'][$item['PackingSlipId']]['SalesOrder'] = ($item['SalesOrder'] != null ? $item['SalesOrder'] : "");
                    $shipments['data'][$item['PackingSlipId']]['CustomerRef'] = ($item['CustomerRef'] != null ? $item['CustomerRef'] : "");

                    $shipments['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'ExpirationDate' => ($item['ExpirationDate'] != null ? $item['ExpirationDate'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );

                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);

                }
            }

        } else { //AMD filter
            foreach ($data as $item) {
                if (($item['CompanyName'] === "US Packaging LLC") && ($item['CustomerNumber'] === "USP-C000030") && ($item['ShipDate'] >= $start_date) && ($item['ShipDate'] <= $end_date)) {
                    $shipments['data'][$item['PackingSlipId']]['PackingSlipId'] = ($item['PackingSlipId'] != null ? $item['PackingSlipId'] : "");
                    $shipments['data'][$item['PackingSlipId']]['ShipDate'] = ($item['ShipDate'] != null ? $item['ShipDate'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Item'] = ($item['Item'] != null ? $item['Item'] : "");
                    $shipments['data'][$item['PackingSlipId']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");
                    $shipments['data'][$item['PackingSlipId']]['SalesOrder'] = ($item['SalesOrder'] != null ? $item['SalesOrder'] : "");
                    $shipments['data'][$item['PackingSlipId']]['CustomerRef'] = ($item['CustomerRef'] != null ? $item['CustomerRef'] : "");

                    $shipments['data'][$item['PackingSlipId']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'ExpirationDate' => ($item['ExpirationDate'] != null ? $item['ExpirationDate'] : ""),
                        'Delivered' => ($item['Quantity'] != null ? intval($item['Quantity']) : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );

                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);

                }
            }
        }
        $shipments['count'] = count($shipments['data']);


    }

}

echo(json_encode($shipments));

