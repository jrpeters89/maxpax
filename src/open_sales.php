<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
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
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
            $result = mysqli_query($conn, "SELECT `id` AS `company` FROM `companies` WHERE `id`='$company_id'") or die(mysqli_error($conn));
            break;
        default:
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
            $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    }
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_array($result);
        $xml = simplexml_load_file("../Data/SalesLines/OpenSalesLines.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $type = $_GET['type'];

        $data = $array['Body']['MessageParts']['MAX_SalesOpenLines']['CustSalesOpenLines'];

        if ($type == "items") {
            if ($row['company'] == 3) { //Nourish Snacks
                foreach ($data as $sale) {
                    if ($sale['CustAccount'] == "USP-C000041") {

                        $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
                        $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
                        $shipped = ($sales_qty - $remaining);

                        if (empty($opensales['data'][$sale['ItemId']])) {
                            $opensales['data'][$sale['ItemId']]['description'] = $sale['ItemName'];
                        }

                        $opensales['data'][$sale['ItemId']]['data'][] = array(
                            'CustomerRef' => $sale['CustomerRef'],
                            'SalesId' => $sale['SalesId'],
                            'CustName' => $sale['CustName'],
                            'SalesUnit' => $sale['SalesUnit'],
                            'SalesQty' => number_format($sales_qty, 0, ".", ","),
                            'Shipped' => number_format($shipped, 0, ".", ","),
                            'Remainder' => number_format($remaining, 0, ".", ","),
                            'DeliveryAddress' => (!empty($sale['DeliveryAddress']) ? $sale['DeliveryAddress'] : "")
                        );
                    }
                }
            } elseif ($row['company'] == 10) { //Earthy Filter
                foreach ($data as $sale) {
                    if ($sale['CustAccount'] == "MAX-C000070") {

                        $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
                        $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
                        $shipped = ($sales_qty - $remaining);

                        if (empty($opensales['data'][$sale['ItemId']])) {
                            $opensales['data'][$sale['ItemId']]['description'] = $sale['ItemName'];
                        }

                        $opensales['data'][$sale['ItemId']]['data'][] = array(
                            'CustomerRef' => $sale['CustomerRef'],
                            'SalesId' => $sale['SalesId'],
                            'CustName' => $sale['CustName'],
                            'SalesUnit' => $sale['SalesUnit'],
                            'SalesQty' => number_format($sales_qty, 0, ".", ","),
                            'Shipped' => number_format($shipped, 0, ".", ","),
                            'Remainder' => number_format($remaining, 0, ".", ","),
                            'DeliveryAddress' => (!empty($sale['DeliveryAddress']) ? $sale['DeliveryAddress'] : "")
                        );
                    }
                }
            } elseif ($row['company'] == 11) { //Treehouse Filter
                foreach ($data as $sale) {
                    if ($sale['CustAccount'] == "USP-C000065") {

                        $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
                        $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
                        $shipped = ($sales_qty - $remaining);

                        if (empty($opensales['data'][$sale['ItemId']])) {
                            $opensales['data'][$sale['ItemId']]['description'] = $sale['ItemName'];
                        }

                        $opensales['data'][$sale['ItemId']]['data'][] = array(
                            'CustomerRef' => $sale['CustomerRef'],
                            'SalesId' => $sale['SalesId'],
                            'CustName' => $sale['CustName'],
                            'SalesUnit' => $sale['SalesUnit'],
                            'SalesQty' => number_format($sales_qty, 0, ".", ","),
                            'Shipped' => number_format($shipped, 0, ".", ","),
                            'Remainder' => number_format($remaining, 0, ".", ","),
                            'DeliveryAddress' => (!empty($sale['DeliveryAddress']) ? $sale['DeliveryAddress'] : "")
                        );
                    }
                }
            }

        } else {
            if ($row['company'] == 3) { //Nourish Snacks
                foreach ($data as $sale) {
                    if ($sale['CustAccount'] == "USP-C000041") {

                        $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
                        $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
                        $shipped = ($sales_qty - $remaining);

                        $opensales['data'][] = array(
                            'CustomerRef' => $sale['CustomerRef'],
                            'SalesId' => $sale['SalesId'],
                            'ItemId' => $sale['ItemId'],
                            'ItemName' => $sale['ItemName'],
                            'SalesUnit' => $sale['SalesUnit'],
                            'SalesQty' => number_format($sales_qty, 0, ".", ","),
                            'Shipped' => number_format($shipped, 0, ".", ","),
                            'Remainder' => number_format($remaining, 0, ".", ",")
                        );
                    }
                }
            } else if ($row['company'] == 3) { //Earthy
                foreach ($data as $sale) {
                    if ($sale['CustAccount'] == "MAX-C000070") {

                        $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
                        $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
                        $shipped = ($sales_qty - $remaining);

                        $opensales['data'][] = array(
                            'CustomerRef' => $sale['CustomerRef'],
                            'SalesId' => $sale['SalesId'],
                            'ItemId' => $sale['ItemId'],
                            'ItemName' => $sale['ItemName'],
                            'SalesUnit' => $sale['SalesUnit'],
                            'SalesQty' => number_format($sales_qty, 0, ".", ","),
                            'Shipped' => number_format($shipped, 0, ".", ","),
                            'Remainder' => number_format($remaining, 0, ".", ",")
                        );
                    }
                }
            } else if ($row['company'] == 11) { //Treehouse
                foreach ($data as $sale) {
                    if ($sale['CustAccount'] == "USP-C000065") {

                        $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
                        $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
                        $shipped = ($sales_qty - $remaining);

                        $opensales['data'][] = array(
                            'CustomerRef' => $sale['CustomerRef'],
                            'SalesId' => $sale['SalesId'],
                            'ItemId' => $sale['ItemId'],
                            'ItemName' => $sale['ItemName'],
                            'SalesUnit' => $sale['SalesUnit'],
                            'SalesQty' => number_format($sales_qty, 0, ".", ","),
                            'Shipped' => number_format($shipped, 0, ".", ","),
                            'Remainder' => number_format($remaining, 0, ".", ",")
                        );
                    }
                }
            }

        }
        $opensales['count'] = count($opensales['data']);
    }
}

echo json_encode($opensales);
//echo '<pre>'.print_r($data,true);
