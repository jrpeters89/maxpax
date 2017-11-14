<?php
$xml = simplexml_load_file("../Data/ProductionRAF/ProdRAF.xml");
$json = json_encode($xml);
$array = json_decode($json, TRUE);

$type = $_GET['type'];

$data = $array['Body']['MessageParts']['MAX_ProdRAFTmp']['MAX_ProdRAFTmp'];

if ($type == "raf") {
    //Portion Pac - Inventory Filter
    $i = 0;
    $items = array();
    foreach ($data as $inv) {
        //Item # = NOU
        $quantity = (!empty($inv['AvailPhysical']) ? $inv['AvailPhysical'] : 0);
        $case_num = (!empty($inv['Case']) ? $inv['Case'] : 0);
        $case = (!empty($inv['Case']) ? ($inv['AvailPhysical'] / $inv['Case']) : 0);
        $pallet_num = (!empty($inv['Pallet']) ? $inv['Pallet'] : 0);
        $pallet = (!empty($inv['Pallet']) ? ($inv['AvailPhysical'] / $inv['Pallet']) : 0);
        if (substr($inv['ItemId'], 0, 3) == "PAC" && $inv['Location'] == "RAF" && $inv['CompanyName'] == "MaxPax LLC") {
            $qty_total = $qty_total + $quantity;
            $case_total = $case_total + $case;
            $pallet_total = $pallet_total + $pallet;
            if (!isset($items[$inv['ItemId']])) {
                $inventory['data'][$i] = array(
                    'ItemId' => $inv['ItemId'],
                    'AvailPhysical' => number_format($quantity, 0, ".", ","),
                    'BatchNumber' => (!empty($inv['BatchNumber']) ? $inv['BatchNumber'] : ""),
                    'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
                    'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
                    'CaseNum' => $case_num,
                    'Case' => number_format($case, 2, ".", ","),
                    'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
                    'PalletNum' => $pallet_num,
                    'Pallet' => number_format($pallet, 2, ".", ","),
                    'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
                    'ProdDate' => (!empty($inv['prodDate']) ? $inv['prodDate'] : "")
                );
                $items[$inv['ItemId']] = $i;
                $i++;
            } else {
                $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical'] = number_format((str_replace(",", "", $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) + str_replace(",", "", $quantity)), 0, ".", ",");
                $inventory['data'][$items[$inv['ItemId']]]['Case'] = (!empty($inventory['data'][$items[$inv['ItemId']]]['CaseNum']) ? number_format((str_replace(",", "", $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) / $inventory['data'][$items[$inv['ItemId']]]['CaseNum']), 2, ".", ",") : 0);
                $inventory['data'][$items[$inv['ItemId']]]['Pallet'] = (!empty($inventory['data'][$items[$inv['ItemId']]]['PalletNum']) ? number_format((str_replace(",", "", $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) / $inventory['data'][$items[$inv['ItemId']]]['PalletNum']), 2, ".", ",") : 0);
            }
        }
    }
    $inventory['total'] = array(
        'quantity' => number_format($qty_total, 0, ".", ","),
        'case' => number_format($case_total, 2, ".", ","),
        'pallet' => number_format($pallet_total, 2, ".", ",")
    );

    $inventory['count'] = count($inventory['data']);

} else {
    $i = 0;
    $items = array();
    foreach ($data as $inv) {
        //Item # = NOU
        $quantity = (!empty($inv['AvailPhysical']) ? $inv['AvailPhysical'] : 0);
        $case_num = (!empty($inv['Case']) ? $inv['Case'] : 0);
        $case = (!empty($inv['Case']) ? ($inv['AvailPhysical'] / $inv['Case']) : 0);
        $pallet_num = (!empty($inv['Pallet']) ? $inv['Pallet'] : 0);
        $pallet = (!empty($inv['Pallet']) ? ($inv['AvailPhysical'] / $inv['Pallet']) : 0);
        if (substr($inv['ItemId'], 0, 3) == "PAC" && $inv['Location'] == "SHIP" && $inv['CompanyName'] == "US Packaging LLC") {
            $qty_total = $qty_total + $quantity;
            $case_total = $case_total + $case;
            $pallet_total = $pallet_total + $pallet;
            if (!isset($items[$inv['ItemId']])) {
                $inventory['data'][$i] = array(
                    'ItemId' => $inv['ItemId'],
                    'AvailPhysical' => number_format($quantity, 0, ".", ","),
                    'BatchNumber' => (!empty($inv['BatchNumber']) ? $inv['BatchNumber'] : ""),
                    'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
                    'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
                    'CaseNum' => $case_num,
                    'Case' => number_format($case, 2, ".", ","),
                    'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
                    'PalletNum' => $pallet_num,
                    'Pallet' => number_format($pallet, 2, ".", ","),
                    'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
                    'ProdDate' => (!empty($inv['prodDate']) ? $inv['prodDate'] : "")
                );
                $items[$inv['ItemId']] = $i;
                $i++;
            } else {
                $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical'] = number_format((str_replace(",", "", $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) + str_replace(",", "", $quantity)), 0, ".", ",");
                $inventory['data'][$items[$inv['ItemId']]]['Case'] = (!empty($inventory['data'][$items[$inv['ItemId']]]['CaseNum']) ? number_format((str_replace(",", "", $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) / $inventory['data'][$items[$inv['ItemId']]]['CaseNum']), 2, ".", ",") : 0);
                $inventory['data'][$items[$inv['ItemId']]]['Pallet'] = (!empty($inventory['data'][$items[$inv['ItemId']]]['PalletNum']) ? number_format((str_replace(",", "", $inventory['data'][$items[$inv['ItemId']]]['AvailPhysical']) / $inventory['data'][$items[$inv['ItemId']]]['PalletNum']), 2, ".", ",") : 0);
            }
        }
    }
    $inventory['total'] = array(
        'quantity' => number_format($qty_total, 0, ".", ","),
        'case' => number_format($case_total, 2, ".", ","),
        'pallet' => number_format($pallet_total, 2, ".", ",")
    );

    $inventory['count'] = count($inventory['data']);
}

echo json_encode($inventory);