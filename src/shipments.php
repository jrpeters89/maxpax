<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {

        $row = mysqli_fetch_array($result);

        $xml   = simplexml_load_file("../Data/ShippingTransactions/ShippingTransactions.xml");
        $json  = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['MAX_ShipTransAPP']['MAX_ShipTransTmp'];
        //if($data['CompanyName'] == "USP-C000030") {
        foreach ($data as $item) {
            $shipments['data'][$item['PackingSlipId']][] = array(
                'CustomerRef' => $item['CustomerRef'],
                'ShipDate' => $item['ShipDate'],
                'Item' => $item['Item'],
                'Description' => $item['Description'],
                'SalesOrder' => $item['SalesOrder']


            );


            // $Shipments['data'][$item['PackingSlipId']][] = array (
            //         'PackingSlipId' => $item['PackingSlipId'],
            // 				'BatchNumber' => $item['BatchNumber'],
            // 				'ExpirationDate' => $item['ExpirationDate'],
            // 				'Quantity' => $item['Quantity'],
            // 				'Unit' => $item['Unit']
            //   );
        }
        //  }
        $shipments['count'] = count($shipments['data']);
        var_dump($shipments['count']);
    }

}

echo (json_encode($shipments));

// //get all packing lists
//         $packingListsXml = $array->xpath('Body/MessageParts/MAX_ShipTransAPP/MAX_ShipTransTmp/PackingSlipId')
//
//         if (!empty($packingListsXml)){
//           $allPackingLists = array();
// //add them to an array
//           foreach ($packingListsXml as $list) {
//             $allPackingLists[] = $list;
//           }
// //remove duplicates
//           $allPackingLists = array_unique($allPackingLists);
//
// //put them back together?
//           $shipments = array();
//           foreach ($allPackingLists as $slipId) {
//             $data = $array->xpath('MAX_ShipTransTmp[PackingSlipId = "' . $slipId .'"]');
//             if (!empty($data)) {
//               foreach ($data as $info) {
//                 $shipments[$slipId][] = $info->Item . ' - ' / $info->BatchNumber;
//
//                 $shipments['count'] = count($shipments['data']);
//               }
//             }
//           }
//
//           echo(json_encode($shipments));
//         }
