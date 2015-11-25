<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");
error_reporting(E_ALL & ~E_NOTICE);
ini_set('display_errors', 1);

$user_token = $_GET['user_token'];
$company = $_GET['company'];

$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
$result = mysqli_query($conn, "SELECT `access_level` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
if(mysqli_num_rows($result) > 0) {
  $xml = simplexml_load_file("../Data/CustAging/custAging.xml");
  $json = json_encode($xml);
  $array = json_decode($json,TRUE);

  $data = $array['Body']['MessageParts']['MAX_CustAgingAPP']['CustTransOpen'];
  $today = time();
  $days_180 = strtotime("-180 days", $today);
  $days_90 = strtotime("-90 days", $today);
  $days_60 = strtotime("-60 days", $today);
  $days_30 = strtotime("-30 days", $today);
  $cust_aging['data']['due']['180']['count'] = $cust_aging['data']['due']['90']['count'] = $cust_aging['data']['due']['60']['count'] = $cust_aging['data']['due']['30']['count'] = $cust_aging['data']['due']['now']['count'] = $cust_aging['data']['due']['not']['count'] = 0;

  foreach($data as $item) {
    $due_date = (!empty($item['DueDate']) ? $item['DueDate'] : "");


    if(!empty($due_date)) {
      $cur_date = strtotime($due_date);

      if($cur_date > $today) {  //If selected date is in the future
        $cust_aging['data']['due']['not']['count']++;
        $array_insert = "not";
      } else if ($today >= $cur_date && $cur_date > $days_30) { //If selected date is within the last 30 days
        $cust_aging['data']['due']['now']['count']++;
        $array_insert = "now";
      } else if ($days_30 >= $cur_date && $cur_date > $days_60) { //If selected date is within the last 60 days
        $cust_aging['data']['due']['30']['count']++;
        $array_insert = "30";
      } else if ($days_60 >= $cur_date && $cur_date > $days_90) { //If selected date is within the last 90 days
        $cust_aging['data']['due']['60']['count']++;
        $array_insert = "60";
      } else if ($days_90 >= $cur_date && $cur_date > $days_180) { //If selected date is within the last 180 days
        $cust_aging['data']['due']['90']['count']++;
        $array_insert = "90";
      } else {
        $cust_aging['data']['due']['180']['count']++;
        $array_insert = "180";
      }
    } else {
      $not_due++;
      $array_insert = "not";
    }

    $cust_aging['data']['due'][$array_insert]['items'][] = array (
      'AccountNum' => $item['AccountNum'],
      'AmountCur' => number_format($item['AmountCur'],2,".",","),
      'CompanyName' => $item['CompanyName'],
      'CustName' => $item['CustName'],
      'DueDate' => $due_date,
      'InvoiceId' => (!empty($item['InvoiceId']) ? $item['InvoiceId'] : "")
    );
  }
  $cust_aging['data']['chart'] = array(
    0 => array(0,$cust_aging['data']['due']['not']['count']),
    1 => array(1,$cust_aging['data']['due']['now']['count']),
    2 => array(2,$cust_aging['data']['due']['30']['count']),
    3 => array(3,$cust_aging['data']['due']['60']['count']),
    4 => array(4,$cust_aging['data']['due']['90']['count']),
    5 => array(5,$cust_aging['data']['due']['180']['count'])
  );

  $cust_aging['data']['total'] = $cust_aging['data']['due']['180']['count'] + $cust_aging['data']['due']['90']['count'] + $cust_aging['data']['due']['60']['count'] + $cust_aging['data']['due']['30']['count'] + $cust_aging['data']['due']['now']['count'] + $cust_aging['data']['due']['not']['count'];

  //echo '<pre>'.print_r($cust_aging,true);
} else {
  $cust_aging['data']['total'] = 0;
}

echo json_encode($cust_aging);
