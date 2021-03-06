<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$company_id = $_GET[company_id];
if(isset($_GET[part_number])) {
    $part_number = $_GET[part_number];
}

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
        case 10:
        case 11:
        case 12:
        case 13:
        case 14:
        case 15:
        case 16:
        case 17:
        case 18:
        case 19:
        case 20:
        case 21:
        case 22:
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
        
        if($row['company'] == 9) { //Novis Works
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "NOV" && $inv['CompanyName'] == "MaxPax LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
		} elseif($row['company'] == 10) { //Earthy
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "ETY" && $inv['CompanyName'] == "MaxPax LLC" && $inv['Location'] != "CONSUME") {
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
		} elseif($row['company'] == 13) { //Adeo
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "AHS" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 11) { //Treehouse
            foreach($data as $inv) {
                //Item # = NOU
                if((substr($inv['ItemId'], 0, 3) == "THF" || substr($inv['ItemId'], 0, 3) == "BVF") && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 5) { //Cargill
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "CAR" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 18) { //Positive Pretzel
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "PPI" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 22) { //Clown Global
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "CLN" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 21) { //Hydrite Chemical
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "HYD" && $inv['CompanyName'] == "MaxPax LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 20) { //Amsoil
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "AMS" && $inv['CompanyName'] == "MaxPax LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 19) { //V-Vax Products
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "VVP" && $inv['CompanyName'] == "MaxPax LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        }  elseif($row['company'] == 16) { //Bay Valley Foods
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "BVF" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 15) { //Strategia
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "STR" && $inv['CompanyName'] == "MaxPax LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 17) { //SW Fulfillment
            if ($part_number !== NULL) {
                foreach ($data as $inv) {
                    if ($inv['CompanyName'] === "Maxpax Fulfillment" && $inv['ItemId'] == $part_number) {
                        $inventory['data'][] = array(
                            'ItemId' => $inv['ItemId'],
                            'OnOrder' => number_format($inv['OnOrder'], 0, ".", ","),
                            'AvailPhysical' => number_format($inv['PhysicalInvent'] - $inv['OnOrder'], 0, ".", ","),
                            'OnHand' => number_format($inv['PhysicalInvent'], 0, ".", ","),
                            'BatchNumber' => (!empty($inv['BatchNumber']) ? $inv['BatchNumber'] : ""),
                            'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
                            'expDate' => (!empty($inv['expDate']) ? date("m/d/y", strtotime($inv['expDate'])) : "N/A"),
                            'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
                            'ItemGroupId' => (!empty($inv['ItemGroupId']) ? $inv['ItemGroupId'] : ""),
                            'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
                            'Case' => (!empty($inv['Case']) ? number_format(($inv['AvailPhysical'] / $inv['Case']), 0, ".", ",") : ""),
                            'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
                            'Pallet' => (!empty($inv['Pallet']) ? number_format(($inv['AvailPhysical'] / $inv['Pallet']), 0, ".", ",") : 0)
                        );
                    }
                }

            } else {
                foreach ($data as $inv) {
                    if ($inv['CompanyName'] === "Maxpax Fulfillment") {
                        $inventory['data'][] = array(
                            'ItemId' => $inv['ItemId'],
                            'OnOrder' => number_format($inv['OnOrder'], 0, ".", ","),
                            'AvailPhysical' => number_format($inv['PhysicalInvent'] - $inv['OnOrder'], 0, ".", ","),
                            'OnHand' => number_format($inv['PhysicalInvent'], 0, ".", ","),
                            'BatchNumber' => (!empty($inv['BatchNumber']) ? $inv['BatchNumber'] : ""),
                            'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
                            'expDate' => (!empty($inv['expDate']) ? date("m/d/y", strtotime($inv['expDate'])) : "N/A"),
                            'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
                            'ItemGroupId' => (!empty($inv['ItemGroupId']) ? $inv['ItemGroupId'] : ""),
                            'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
                            'Case' => (!empty($inv['Case']) ? number_format(($inv['AvailPhysical'] / $inv['Case']), 0, ".", ",") : ""),
                            'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
                            'Pallet' => (!empty($inv['Pallet']) ? number_format(($inv['AvailPhysical'] / $inv['Pallet']), 0, ".", ",") : 0)
                        );
                    }
                }
            }
        }   elseif($row['company'] == 14) { //Butterface Brands
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "BFB" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        }  elseif($row['company'] == 12) { //Ferrara
            foreach($data as $inv) {
                //Item # = NOU
                if(substr($inv['ItemId'], 0, 3) == "FER" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME" && $inv['AvailPhysical'] > 0) {
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
        } elseif($row['company'] == 7) {	//GoPicnic - Inventory Filter
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "GPB" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME") {
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
				if(substr($inv['ItemId'], 0, 3) == "ENE" && $inv['CompanyName'] == "US Packaging LLC" && $inv['Location'] != "CONSUME") {
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
					$case = (!empty($inv['Case']) ? ($inv['AvailPhysical'] / $inv['Case']) : 0);
					$pallet_num = (!empty($inv['Pallet']) ? $inv['Pallet'] : 0);
					$pallet = (!empty($inv['Pallet']) ? ($inv['AvailPhysical'] / $inv['Pallet']) : 0);
					if (substr($inv['ItemId'], 0, 3) == "PAC" && substr($inv['ItemId'], 0, 5) != "PAC-L" && substr($inv['ItemId'], 0, 5) != "PAC-K" && substr($inv['ItemId'], 0, 5) != "PAC-B" && $inv['Location'] != "RAF" && $inv['Location'] != "QUARANTINE" && $inv['CompanyName'] == "MaxPax LLC" && $inv['ItemGroupId'] == "FG" && $inv['Location'] != "CONSUME") {
						$qty_total = $qty_total + $quantity;
						$case_total = $case_total + $case;
						$pallet_total = $pallet_total + $pallet;
						if (!isset($items[$inv['ItemId']])) {
							$inventory['data'][$i] = array(
								'ItemId' => $inv['ItemId'],
								'AvailPhysical' => number_format($quantity, 0, ".", ","),
								'ItemName' => (!empty($inv['ItemName']) ? $inv['ItemName'] : ""),
								'BOMUnitId' => (!empty($inv['BOMUnitId']) ? $inv['BOMUnitId'] : ""),
								'CaseNum' => $case_num,
								'Case' => number_format($case, 2, ".", ","),
								'SellUOM' => (!empty($inv['SellUOM']) ? $inv['SellUOM'] : ""),
								'PalletNum' => $pallet_num,
								'Pallet' => number_format($pallet, 2, ".", ","),
								'Location' => (!empty($inv['Location']) ? $inv['Location'] : ""),
								'ProdDate' => (!empty($inv['prodDate']) ? $inv['prodDate'] : ""),
								'ItemGroupId' => (!empty($inv['ItemGroupId']) ? $inv['ItemGroupId'] : "")
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
				'quantity' => number_format($qty_total,0,".",","),
				'case' => number_format($case_total,2,".",","),
				'pallet' => number_format($pallet_total,2,".",",")
			);
		} else {	// Nourish Snacks - Inventory Filter
			foreach($data as $inv) {
				//Item # = NOU
				if(substr($inv['ItemId'], 0, 3) == "NOU" && $inv['Location'] != "CONSUME") {
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
