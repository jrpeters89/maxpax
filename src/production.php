<?php
$xml = simplexml_load_file("../Data/ProductionOverview/productionOverview.xml");
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$data = $array['Body']['MessageParts']['ProdOverview_MAX']['ProdTable'];

foreach($data as $prod) {
	if(substr($prod['ItemId'], 0, 3) == "NOU") {
		$scheduled = (!empty($prod['QtySched']) ? $prod['QtySched'] : 0);
		$started = (!empty($prod['ReportedFinishedGood']) ? $prod['ReportedFinishedGood'] : 0);
		$remainder = ($scheduled - $started);
	
		$production['data'][] = array(
			'ItemId' => $prod['ItemId'],
			'Name' => $prod['Name'],
			'ProdId' => $prod['ProdId'],
			'ProdStatus' => $prod['ProdStatus'],
			'Scheduled' => number_format($scheduled,2,".",","),
			'Started' => number_format($started,2,".",","),
			'Remainder' => number_format($remainder,2,".",",")	
		);
	}
}

$production['count'] = count($production['data']);

echo json_encode($production);