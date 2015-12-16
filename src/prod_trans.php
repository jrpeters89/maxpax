<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$start_date = $_GET[start_date];
$end_date = $_GET[end_date];

if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/ProductionTransactions/ProductionTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['MAX_ProdTransAPP']['MAX_ProdTransTmp'];
        if ($row['company'] == 6) { //Energems Filter
            foreach ($data as $item) {
                if ((substr($item['ItemNumber'], 0, 3) == "ENE") && ($item['CompanyName'] === "US Packaging LLC") && ($item['TransDate'] > $start_date) && ($item['TransDate'] < $end_date)) {
                    $prod_trans['data'][$item['Production']]['Production'] = ($item['Production'] != null ? $item['Production'] : "");
                    $prod_trans['data'][$item['Production']]['TransDate'] = ($item['TransDate'] != null ? $item['TransDate'] : "");
                    $prod_trans['data'][$item['Production']]['ItemNumber'] = ($item['ItemNumber'] != null ? $item['ItemNumber'] : "");
                    $prod_trans['data'][$item['Production']]['Description'] = ($item['Description'] != null ? $item['Description'] : "");

                    $prod_trans['data'][$item['Production']][] = array(
                        'Lot' => ($item['BatchNumber'] != null ? $item['BatchNumber'] : ""),
                        'ExpDate' => ($item['ExpDate'] != null ? $item['ExpDate'] : ""),
                        'GoodQuantity' =>($item['GoodQuantity'] != null ? $item['GoodQuantity'] : "")
                    );
                }
            }
        } else {
            //any additional filters in the future
        }

        $prod_trans['count'] = count($prod_trans['data']);
    }
}

echo(json_encode($prod_trans));