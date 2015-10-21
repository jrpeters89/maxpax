<?php
$xml = simplexml_load_file("../Data/Onhand/onhandOverview.xml");
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$data = $array['Body']['MessageParts']['MAX_InventOnhand']['MAX_InventOnhandTmp'];

foreach($data as $inv) {
	//Item # = NOU
	if(substr($inv['ItemId'], 0, 3) == "NOU") {
		$inventory['data'][] = array(
			'ItemGroupId' => $inv['ItemGroupId'],
			'ItemId' => $inv['ItemId'],
			'AvailPhysical' => number_format($inv['AvailPhysical'],0,".",","),
			'BatchNumber' => (!empty($inv['BatchNumber']) ? $inv['BatchNumber'] : ""),
			'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
			'expDate' => (!empty($inv['expDate']) ? date("m/d/y", strtotime($inv['expDate'])) : "N/A"),
			'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
			'ItemGroupId' => (!empty($inv['ItemGroupId']) ? $inv['ItemGroupId'] : ""),
			'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : "")
		);
	}
}

$inventory['count'] = count($inventory['data']);

echo json_encode($inventory);
