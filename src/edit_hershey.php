<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$transaction_date = $request[0]->value;
$lic_plate = $request[1]->value;
$timestamp = $request[2]->value;
$item = $request[3]->value;
$batch = $request[4]->value;
$production = $request[5]->value;
$qty = $request[6]->value;
$uom_denominator = $request[7]->value;
$uom = stripslashes($request[8]->value);
$file = stripslashes($request[9]->value);

$dir = '//sw-apps-01/Hersh';

if(is_dir($dir)) {
    if($dh = opendir($dir)) {
        $xml = simplexml_load_file($dir . "/Processed/" . $file);
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->BatchNumber = $batch;
        //$xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->CreateTimeStamp = $timestamp;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->MaterialNumber = $item;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->MAX_LicensePlateNumber = $lic_plate;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->ProdId = $production;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->QtyGood = $qty;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->TransDate = $transaction_date;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->UOMDenominator = $uom_denominator;
        $xml->Body->MessageParts->MAX_ProdJournalProd->ProdJournalProd->UOM = $uom;
        $xml->asXML($dir . "/" . $file);
    }
}

header('Location: http://reports.maxpaxllc.com');

echo json_encode($result);