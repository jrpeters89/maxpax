<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$start_date = $_GET[start_date];
$end_date = $_GET[end_date];

if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/ShippingTransactions/ShippingTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['MAX_ShipTransAPP']['MAX_ShipTransTmp'];
        if ($row['company'] == 6) { //Energems Filter
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
                        'Delivered' => ($item['Quantity'] != null ? number_format($item['Quantity'], 2, ".", ",") : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );
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
                        'Delivered' => ($item['Quantity'] != null ? number_format($item['Quantity'], 2, ".", ",") : ""),
                        'UOM' => ($item['Unit'] != null ? $item['Unit'] : "")
                    );

                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] += intval($item['Quantity']);
                    $num_format = number_format($shipments['data'][$item['PackingSlipId']]['Subtotal'], 2, ".", ",");
                    $shipments['data'][$item['PackingSlipId']]['Subtotal'] = $num_format;
                }
            }
        }
        $shipments['count'] = count($shipments['data']);


    }

}

echo(json_encode($shipments));

