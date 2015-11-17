<?php
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

  $cust_aging['data']['due'][$array_insert]['items'] = array (
    'AccountNum' => $item['AccountNum'],
    'AmountCur' => number_format($item['AmountCur'],2,".",","),
    'CompanyName' => $item['CompanyName'],
    'CustName' => $item['CustName'],
    'DueDate' => $due_date,
    'InvoiceId' => (!empty($item['InvoiceId']) ? $item['InvoiceId'] : "")
  );
}

echo '<pre>'.print_r($cust_aging,true);
