<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET["user_token"];
$company = $_GET["company"];
$start_date = $_GET["start_date"];
$end_date = $_GET["end_date"];

$receiving[count] = 0;
$start_date_string = str_replace("-", "", $start_date);
$end_date_string = str_replace("-", "", $end_date);

$dir = '//sw-apps-01/Hersh/Processed';

function cmp($a, $b)
{
    return strcmp($a->TransDate, $b->TransDate);
}
if (!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');

    $result = mysqli_query($conn, "SELECT * FROM `receiving` WHERE `receipt_date` > '$start_date' and `receipt_date` < '$end_date' order by `receipt_date` desc") or die(mysqli_error($conn));
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $batch_number = $row["batch_number"];

            $receiving['data'][$batch_number]['batch_number'] = $row["batch_number"];
            $receiving['data'][$batch_number]['company_name'] = $row["company_name"];
            $receiving['data'][$batch_number]['item'] = $row["item"];
            $receiving['data'][$batch_number]['packing_slip_id'] = $row["packing_slip_id"];
            $receiving['data'][$batch_number]['description'] = $row["description"];
            $receiving['data'][$batch_number]['quantity'] = $row["quantity"];
            $receiving['data'][$batch_number]['purchase_order'] = $row["purchase_order"];
            $receiving['data'][$batch_number]['line_number'] = $row["line_number"];
            $receiving['data'][$batch_number]['receipt_date'] = $row["receipt_date"];
            $receiving['data'][$batch_number]['vendor_account'] = $row["vendor_account"];
            $receiving[count]++;
        }
    }
}

echo json_encode($receiving);