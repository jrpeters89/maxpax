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
  $cust_aging['data']['total'] = 0;
  for($a=0; $a <= 5; $a++) {
    $cust_aging['data']['due'][$a]['count'] = 0;
    $cust_aging['data']['due'][$a]['amount'] = 0;
    $cust_aging['data']['due'][$a]['items'] = array();
    $totals[$a] = 0;
  }

  if($company == 2) {
    $company_name = "US Packaging LLC";
  } else {
    $company_name = "MaxPax LLC";
  }

  foreach($data as $item) {
    if($item['CompanyName'] == $company_name) {
      $due_date = (!empty($item['DueDate']) ? $item['DueDate'] : "");


      if(!empty($due_date)) {
        $cur_date = strtotime($due_date);

        if($cur_date > $today) {  //If selected date is in the future
          $cust_aging['data']['due'][0]['count']++;
          $array_insert = 0;
        } else if ($today >= $cur_date && $cur_date > $days_30) { //If selected date is within the last 30 days
          $cust_aging['data']['due'][1]['count']++;
          $array_insert = 1;
        } else if ($days_30 >= $cur_date && $cur_date > $days_60) { //If selected date is within the last 60 days
          $cust_aging['data']['due'][2]['count']++;
          $array_insert = 2;
        } else if ($days_60 >= $cur_date && $cur_date > $days_90) { //If selected date is within the last 90 days
          $cust_aging['data']['due'][3]['count']++;
          $array_insert = 3;
        } else if ($days_90 >= $cur_date && $cur_date > $days_180) { //If selected date is within the last 180 days
          $cust_aging['data']['due'][4]['count']++;
          $array_insert = 4;
        } else {
          $cust_aging['data']['due'][5]['count']++;
          $array_insert = 5;
        }
      } else {
        $cust_aging['data']['due'][0]['count']++;
        $array_insert = 0;
      }

      $raw_num = number_format($item['AmountCur'],2,".","");

      $cust_aging['data']['due'][$array_insert]['items'][$item['CustName']][] = array (
        'AccountNum' => $item['AccountNum'],
        'AmountCur' => number_format($item['AmountCur'],2,".",","),
        'RawNum' => $raw_num,
        'CompanyName' => $item['CompanyName'],
        'CustName' => $item['CustName'],
        'DueDate' => (!empty($due_date) ? date("m/d/Y",$cur_date) : ""),
        'InvoiceId' => (!empty($item['InvoiceId']) ? $item['InvoiceId'] : "")
      );

      $totals[$array_insert] += number_format($raw_num,0);
      $cust_aging['data']['due'][$array_insert]['amount'] += $item['AmountCur'];
      $cust_aging['data']['due'][$array_insert]['detail'] .= number_format($raw_num,0).' + ';
    }

    $cust_aging['data']['chart'] = array(
      0 => array(0,$cust_aging['data']['due'][0]['amount']),
      1 => array(1,$cust_aging['data']['due'][1]['amount']),
      2 => array(2,$cust_aging['data']['due'][2]['amount']),
      3 => array(3,$cust_aging['data']['due'][3]['amount']),
      4 => array(4,$cust_aging['data']['due'][4]['amount']),
      5 => array(5,$cust_aging['data']['due'][5]['amount'])
    );

    for($b=0; $b <= 5; $b++) {
      ksort($cust_aging['data']['due'][$b]['items']);
      $cust_aging['data']['total'] += $cust_aging['data']['due'][$b]['count'];
      if(!empty($cust_aging['data']['due'][$b]['amount'])) {
        $cust_aging['data']['due'][$b]['amount'] = number_format($cust_aging['data']['due'][$b]['amount'],2,".",",");
      }
    }
  }

  //echo '<pre>'.print_r($cust_aging,true);
} else {
  $cust_aging['data']['total'] = 0;
}

echo json_encode($cust_aging);
