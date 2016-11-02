<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$company_id = $_GET[company_id];

if(!empty($user_token)) {
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
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/PurchLines/OpenPurchLines.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);
        $data = $array['Body']['MessageParts']['MAX_PurchOpenLines']['VendPurchOpenLines'];

        if ($row['company'] == 3) { //Nourish Snacks
            foreach ($data as $purch) {
                //Item # = NOU
                if ($purch['VendAccount'] == "USP-V000240" && $purch['CompanyName'] == "US Packaging LLC") {
                    $purchase['data'][] = array(
                        'PurchaseOrder' => $purch['PurchId'],
                        'LineNumber' => $purch['LineNumber'],
                        'ItemNumber' => $purch['ItemId'],
                        'Description' => $purch['Name'],
                        'DeliveryDate' => $purch['DeliveryDate'],
                        'OrderQuantity' => $purch['PurchQty'],
                        'DeliverRemainder' => $purch['RemainPurchPhysical'],
                        'Unit' => $purch['PurchUnit']
                    );
                }
            }
        }

        else if ($row['company'] == 4) { //Portion Pac
            foreach ($data as $purch) {
                //Item # = NOU
                if (((substr($purch['ItemNumber'], 0, 5) == "PAC-L" || substr($purch['ItemNumber'], 0, 5) == "PAC-K")) ){//&& $purch['CompanyName'] == "MaxPax LLC") {
                    $purchase['data'][] = array(
                        'PurchaseOrder' => $purch['PurchId'],
                        'LineNumber' => $purch['LineNumber'],
                        'ItemNumber' => $purch['ItemId'],
                        'Description' => $purch['Name'],
                        'DeliveryDate' => $purch['DeliveryDate'],
                        'OrderQuantity' => $purch['PurchQty'],
                        'DeliverRemainder' => $purch['RemainPurchPhysical'],
                        'Unit' => $purch['PurchUnit']
                    );
                }
            }
        }
        $purchase['count'] = count($purchase['data']);
    } else {
            $purchase['count'] = 0;
    }

} else {
    $purchase['count'] = 0;
}

    echo json_encode($purchase);