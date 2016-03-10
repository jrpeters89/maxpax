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

                    $inventory['data'][$inv['ItemId']]['ItemId'] = ($inv['ItemId'] != null ? $inv['ItemId'] : "");

                    $inventory['data'][$inv['ItemId']]['AvailPhysical'] = ($inv['AvailPhysical'] != null) ? number_format($inv['AvailPhysical'],0,".",",") : "";
                    $inventory['data'][$inv['ItemId']]['BatchNumber'] = ($inv['BatchNumber'] != null) ? $inv['BatchNumber'] : "";
                    $inventory['data'][$inv['ItemId']]['Location'] = ($inv['Location'] != null) ? $inv['AvailPhysical'] : "";
                    $inventory['data'][$inv['ItemId']]['expDate'] = ($inv['expDate'] != null) ? $inv['expDate'] : "";
                    $inventory['data'][$inv['ItemId']]['ItemName'] = ($inv['ItemName'] != null) ? $inv['ItemName'] : "";
                    $inventory['data'][$inv['ItemId']]['ItemGroupId'] = ($inv['ItemGroupId'] != null) ? $inv['ItemGroupId'] : "";
                    $inventory['data'][$inv['ItemId']]['BOMUnitId'] = ($inv['BOMUnitId'] != null) ? $inv['BOMUnitId'] : "";
                    $inventory['data'][$inv['ItemId']]['Case'] = (!empty($inv['Case']) ? number_format(($inv['AvailPhysical']/$inv['Case']),0,".",",") : "");
                    $inventory['data'][$inv['ItemId']]['SellUOM'] = (!empty($inv['SellUOM']) ? $inv['SellUOM'] : "");
                    $inventory['data'][$inv['ItemId']]['Pallet'] = (!empty($inv['Pallet']) ? number_format(($inv['AvailPhysical']/$inv['Pallet']),0,".",",") : 0);

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