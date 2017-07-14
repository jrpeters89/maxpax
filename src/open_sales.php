<?php
$xml = simplexml_load_file("../Data/SalesLines/OpenSalesLines.xml");
$json = json_encode($xml);
$array = json_decode($json,TRUE);

$type = $_GET['type'];

$data = $array['Body']['MessageParts']['MAX_SalesOpenLines']['CustSalesOpenLines'];

if($type == "items") {

	foreach($data as $sale) {
		if($sale['CustAccount'] == "USP-C000041") {

			$sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
			$remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
			$shipped = ($sales_qty - $remaining);

			if(empty($opensales['data'][$sale['ItemId']])) {
				$opensales['data'][$sale['ItemId']]['description'] = $sale['ItemName'];
			}

			$opensales['data'][$sale['ItemId']]['data'][] = array (
				'CustomerRef' => $sale['CustomerRef'],
				'SalesId' => $sale['SalesId'],
				'CustName' => $sale['CustName'],
				'SalesUnit' => $sale['SalesUnit'],
				'SalesQty' => number_format($sales_qty,0,".",","),
				 'Shipped' => number_format($shipped,0,".",","),
				'Remainder' => number_format($remaining,0,".",","),
				'DeliveryAddress' => (!empty($sale['DeliveryAddress']) ? $sale['DeliveryAddress'] : "")
			);
		} elseif ($sale['CustAccount'] == "MAX-C000070") {

            $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
            $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
            $shipped = ($sales_qty - $remaining);

            if(empty($opensales['data'][$sale['ItemId']])) {
                $opensales['data'][$sale['ItemId']]['description'] = $sale['ItemName'];
            }

            $opensales['data'][$sale['ItemId']]['data'][] = array (
                'CustomerRef' => $sale['CustomerRef'],
                'SalesId' => $sale['SalesId'],
                'CustName' => $sale['CustName'],
                'SalesUnit' => $sale['SalesUnit'],
                'SalesQty' => number_format($sales_qty,0,".",","),
                'Shipped' => number_format($shipped,0,".",","),
                'Remainder' => number_format($remaining,0,".",","),
                'DeliveryAddress' => (!empty($sale['DeliveryAddress']) ? $sale['DeliveryAddress'] : "")
            );
        }
	}

} else {

	foreach($data as $sale) {
		if($sale['CustAccount'] == "USP-C000041") {

			$sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
			$remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
			$shipped = ($sales_qty - $remaining);

			$opensales['data'][] = array (
				'CustomerRef' => $sale['CustomerRef'],
				'SalesId' => $sale['SalesId'],
				'ItemId' => $sale['ItemId'],
				'ItemName' => $sale['ItemName'],
				'SalesUnit' => $sale['SalesUnit'],
				'SalesQty' => number_format($sales_qty,0,".",","),
				'Shipped' => number_format($shipped,0,".",","),
				'Remainder' => number_format($remaining,0,".",",")
			);
		} elseif ($sale['CustAccount'] == "MAX-C000070") {

            $sales_qty = (!empty($sale['SalesQty']) ? $sale['SalesQty'] : 0);
            $remaining = (!empty($sale['RemainSalesPhysical']) ? $sale['RemainSalesPhysical'] : 0);
            $shipped = ($sales_qty - $remaining);

            if(empty($opensales['data'][$sale['ItemId']])) {
                $opensales['data'][$sale['ItemId']]['description'] = $sale['ItemName'];
            }

            $opensales['data'][$sale['ItemId']]['data'][] = array (
                'CustomerRef' => $sale['CustomerRef'],
                'SalesId' => $sale['SalesId'],
                'CustName' => $sale['CustName'],
                'SalesUnit' => $sale['SalesUnit'],
                'SalesQty' => number_format($sales_qty,0,".",","),
                'Shipped' => number_format($shipped,0,".",","),
                'Remainder' => number_format($remaining,0,".",","),
                'DeliveryAddress' => (!empty($sale['DeliveryAddress']) ? $sale['DeliveryAddress'] : "")
            );
        }
	}

}
$opensales['count'] = count($opensales['data']);

echo json_encode($opensales);
//echo '<pre>'.print_r($data,true);
