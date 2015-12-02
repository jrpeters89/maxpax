<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

if(!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);

        $xml = simplexml_load_file("../Data/InventoryTransactions/inventoryTransactions.xml");
        $json = json_encode($xml);
        $array = json_decode($json, TRUE);

        $data = $array['Body']['MessageParts']['InventTransAPP']['InventTrans'];

        foreach($data as $inv) {
            $inv_trans['data'][] = array(
                'CompanyId' => $data['CompanyId']
            );
        }
    }

}

echo(json_encode($inv_trans));