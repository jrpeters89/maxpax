<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if(!empty($user_token)) {
	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
	$result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);

		$xml = simplexml_load_file("../Data/Onhand/onhandOverview.xml");
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);
		$data = $array['Body']['MessageParts']['MAX_InventOnhand']['MAX_InventOnhandTmp'];

		if($row['company'] == 4) {
			foreach($data as $inv) {
				//Item # = NOU
				$quantity = (!empty($inv['AvailPhysical']) ? number_format($inv['AvailPhysical'],0,".",",") : 0);
				$qty_total += $quantity;
				$case = (!empty($inv['Case']) ? number_format(($inv['AvailPhysical']/$inv['Case']),2,".",",") : 0);
				$case_total += $case;
				$pallet = (!empty($inv['Pallet']) ? number_format(($inv['AvailPhysical']/$inv['Pallet']),2,".",",") : 0);
				$pallet_total += $pallet;
				if(substr($inv['ItemGroupId'], 0, 2) == "FG" && substr($inv['ItemId'], 0, 3) == "PAC" && $inv['Location'] != "Quarantine") {
					$inventory['data'][] = array(
						'ItemId' => $inv['ItemId'],
						'AvailPhysical' => $quantity,
						'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
						'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
						'Case' => $case,
						'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
						'Pallet' => $pallet,
						'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
					);
				}
			}
			$inventory['total'] = array(
				'quantity' => $qty_total,
				'case' => $case_total,
				'pallet' => $pallet_total
			);
		} else {
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "NOU") {
					$inventory['data'][] = array(
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
