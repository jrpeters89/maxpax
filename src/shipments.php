<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if(!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/ShippingTransactions/ShippingTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['MAX_ShipTransAPP']['MAX_ShipTransTmp'];

				foreach($data as $ship) {
					$shipments['data'][] = array (
									'CustomerRef' => $ship['CustomerRef'],
									'BatchNumber' => $ship['BatchNumber'],
									'ExpirationDate' => $ship['ExpirationDate'],
									'Item' => $ship['Item'],
									'Description' => $ship['Description'],
									'Quantity' => $ship['Quantity'],
									'SalesOrder' => $ship['SalesOrder']
            );
        }
        $shipments['count'] = count($shipments['data']);
    }

}

echo(json_encode($shipments));
