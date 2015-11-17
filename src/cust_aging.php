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
$not_due = $due_now = $due_30 = $due_60 = $due_90 = $due_180 = 0;

foreach($data as $item) {
  $due_date = (!empty($item['DueDate']) ? $item['DueDate'] : "");

  $cust_aging['data'][] = array (
    'AccountNum' => $item['AccountNum'],
    'AmountCur' => number_format($item['AmountCur'],2,".",","),
    'CompanyName' => $item['CompanyName'],
    'CustName' => $item['CustName'],
    'DueDate' => $due_date,
    'InvoiceId' => (!empty($item['InvoiceId']) ? $item['InvoiceId'] : "")
  );

  if(!empty($due_date)) {
    $cur_date = strtotime($due_date);

    if($cur_date > $today) {  //If selected date is in the future
      $not_due++;
    } else if ($today >= $cur_date && $cur_date > $days_30) { //If selected date is within the last 30 days
      $due_now++;
    } else if ($days_30 >= $cur_date && $cur_date > $days_60) { //If selected date is within the last 60 days
      $due_30++;
    } else if ($days_60 >= $cur_date && $cur_date > $days_90) { //If selected date is within the last 90 days
      $due_60++;
    } else if ($days_90 >= $cur_date && $cur_date > $days_180) { //If selected date is within the last 180 days
      $due_90++;
    } else {
      $due_180++;
    }
  } else {
    $not_due++;
  }
}

$cust_aging['due'] = array(
  '180' => $due_180,
  '90' => $due_90,
  '60' => $due_60,
  '30' => $due_30,
  'now' => $due_now,
  'not' => $not_due
);

echo '<pre>'.print_r($cust_aging,true);
