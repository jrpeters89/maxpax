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
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
            $result = mysqli_query($conn, "SELECT `id` AS `company` FROM `companies` WHERE `id`='$company_id'") or die(mysqli_error($conn));
            break;
        default:
            $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
            $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    }
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/Onhand/onhandOverview.xml");
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);
        $data = $array['Body']['MessageParts']['MAX_InventOnhand']['MAX_InventOnhandTmp'];

        if($row['company'] == 4) {	//PortionPac - Inventory Filter
            foreach($data as $inv) {
                if(substr($inv['ItemId'], 0, 3) == "PAC" && $inv['CompanyName'] == "MaxPax LLC" && ($inv['ItemGroupId'] == "LB" || $inv['ItemGroupId'] == "PM")) {

                    $inventory['data'][$inv['ItemGroupId']][$inv['ItemId']] = array(
                        'ItemId' => $inv['ItemId'],
                        'AvailPhysical' => number_format($inv['AvailPhysical'],0,".",","),
                        'BatchNumber' => (!empty($inv['BatchNumber']) ? $inv['BatchNumber'] : ""),
                        'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
                        'expDate' => (!empty($inv['expDate']) ? date("m/d/y", strtotime($inv['expDate'])) : "N/A"),
                        'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
                        'ItemGroupId' => (!empty($inv['ItemGroupId']) ? $inv['ItemGroupId'] : ""),
                        'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
                        'Case' => (!empty($inv['Case']) ? number_format(($inv['AvailPhysical']/$inv['Case']),0,".",",") : ""),
                        'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
                        'Pallet' => (!empty($inv['Pallet']) ? number_format(($inv['AvailPhysical']/$inv['Pallet']),0,".",",") : 0)
                    );
                }
            }
        }

        $inventory['count'] = count($inventory['data']);
    } else {
        $inventory['count'] = 0;
    }

} else {
    $inventory['count'] = 0;
}

echo json_encode($inventory);