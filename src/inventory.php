<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if(!empty($user_token)) {
	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
	$result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		if($row['company'] == 4) {
			$item_search = $inv['ItemGroupId'];
			$item_type = "FG";
			$item_pre = 2;
		} else {
			$item_search = $inv['ItemId'];
			$item_type = "NOU";
			$item_pre = 3;
		}
		$xml = simplexml_load_file("../Data/Onhand/onhandOverview.xml");
		$json = json_encode($xml);
		$array = json_decode($json,TRUE);

		$data = $array['Body']['MessageParts']['MAX_InventOnhand']['MAX_InventOnhandTmp'];

		foreach($data as $inv) {
			//Item # = NOU
			if(substr($inv['ItemId'], 0, $item_pre) == $item_type) {
				$inventory['data'][] = array(
					'ItemGroupId' => $inv['ItemGroupId'],
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

		$inventory['count'] = count($inventory['data']);
	} else {
		$inventory['count'] = 0;
	}
} else {
	$inventory['count'] = 0;
}

echo json_encode($inventory);
