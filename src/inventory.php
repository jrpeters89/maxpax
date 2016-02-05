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

		if($row['company'] == 7) {	//GoPicnic - Inventory Filter
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "GPB" && $inv['CompanyName'] == "US Packaging LLC") {
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
		} else if($row['company'] == 6) {	//Energems - Inventory Filter
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "ENE" && $inv['CompanyName'] == "US Packaging LLC") {
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
		} else if($row['company'] == 4) {	//Portion Pac - Inventory Filter
			$i = 0;
			$items = array();
			foreach($data as $inv) {
				//Item # = NOU
				$quantity = (!empty($inv['AvailPhysical']) ? $inv['AvailPhysical'] : 0);
				$case_num = (!empty($inv['Case']) ? $inv['Case'] : 0);
				$case = (!empty($inv['Case']) ? ($inv['AvailPhysical']/$inv['Case']) : 0);
				$pallet_num = (!empty($inv['Pallet']) ? $inv['Pallet'] : 0);
				$pallet = (!empty($inv['Pallet']) ? ($inv['AvailPhysical']/$inv['Pallet']) : 0);
				if(substr($inv['ItemGroupId'], 0, 2) == "FG" && substr($inv['ItemId'], 0, 3) == "PAC" && $inv['Location'] != "QUARANTINE" && $inv['CompanyName'] == "MaxPax LLC") {
					$qty_total = $qty_total + $quantity;
					$case_total = $case_total + $case;
					$pallet_total = $pallet_total + $pallet;
					if(!isset($items[$inv['ItemId']])) {
						$inventory['data'][$i] = array(
							'ItemId' => $inv['ItemId'],
							'AvailPhysical' => number_format($quantity,0,".",","),
							'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
							'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
							'CaseNum' => $case_num,
							'Case' => number_format($case,2,".",","),
							'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
							'PalletNum' => $pallet_num,
							'Pallet' => number_format($pallet,2,".",","),
							'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
							'ProdDate' => (!empty($inv['ProdDate']) ? $inv['ProdDate'] : "")
						);
						$items[$inv['ItemId']] = $i;
						$i++;
					} else {
						$inventory['data'][$items[$inv['ItemId']]]['AvailPhysical'] = number_format((str_replace(",", "",$inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) + str_replace(",","",$quantity)),0,".",",");
						$inventory['data'][$items[$inv['ItemId']]]['Case'] = (!empty($inventory['data'][$items[$inv['ItemId']]]['CaseNum']) ? number_format((str_replace(",","",$inventory['data'][$items[$inv['ItemId']]]['AvailPhysical'])/$inventory['data'][$items[$inv['ItemId']]]['CaseNum']),2,".",",") : 0);
						$inventory['data'][$items[$inv['ItemId']]]['Pallet'] = (!empty($inventory['data'][$items[$inv['ItemId']]]['PalletNum']) ? number_format((str_replace(",","",$inventory['data'][$items[$inv['ItemId']]]['AvailPhysical'])/$inventory['data'][$items[$inv['ItemId']]]['PalletNum']),2,".",",") : 0);
					}
				}
			}
			$inventory['total'] = array(
				'quantity' => number_format($qty_total,0,".",","),
				'case' => number_format($case_total,2,".",","),
				'pallet' => number_format($pallet_total,2,".",",")
			);
		} else {	// Nourish Snacks - Inventory Filter
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
