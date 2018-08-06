<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$period = $_GET[period];
$period_length = strlen($period);
$end_date = '';
$start_date = '';

switch ($period) {
    case 1:
        $end_date = '2017-01-28';
        $start_date = '2017-01-01';
        break;
    case 2:
        $end_date = '2017-02-25';
        $start_date = '2017-01-29';
        break;
    case 3:
        $end_date = '2017-04-01';
        $start_date = '2017-02-26';
        break;
    case 4:
        $end_date = '2017-04-29';
        $start_date = '2017-04-02';
        break;
    case 5:
        $end_date = '2017-05-27';
        $start_date = '2017-04-30';
        break;
    case 6:
        $end_date = '2017-07-01';
        $start_date = '2017-05-28';
        break;
    case 7:
        $end_date = '2017-07-29';
        $start_date = '2017-07-02';
        break;
    case 8:
        $end_date = '2017-08-26';
        $start_date = '2017-07-30';
        break;
    case 9:
        $end_date = '2017-09-30';
        $start_date = '2017-08-27';
        break;
    case 10:
        $end_date = '2017-10-28';
        $start_date = '2017-10-01';
        break;
    case 11:
        $end_date = '2017-11-25';
        $start_date = '2017-10-29';
        break;
    case 12:
        $end_date = '2017-12-30';
        $start_date = '2017-11-26';
        break;
    default:
        $end_date = '2017-12-30';
        $start_date = '2017-11-26';
        break;
}

$historical_sales = array();

if(!empty($user_token)) {
    $conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
    $result = mysqli_query($conn, "SELECT `access_level`,`company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
    if(mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        if($row[access_level] > 0 || $row[company] == 1 || $row[company] == 99) {
            $historical_sales[active] = true;
            $xml = simplexml_load_file("../Data/Sales/SalesRevenue.xml");
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);

            $data = $array['Body']['MessageParts']['MAXSalesRevenuePerPeriod']['MAXSalesRevPerPeriod'];

            foreach ($data as $item) {
                if(($item['CompanyName'] === "MaxPax LLC") && ($item['EndDate'] === $end_date) && ($item['StartDate'] === $start_date) && (substr($item['Period'], -$period_length) === $period))
                {

                } elseif (($item['CompanyName'] === "US Packaging LLC") && ($item['EndDate'] === $end_date) && ($item['StartDate'] === $start_date) && (substr($item['Period'], -$period_length) === $period)) {

                }
            }


            /*$max_pax = $array["Body"]["MessageParts"]["MAXSalesRevDocument"]["MAXSalesRevenueTable"][0]; //MaxPax Results
            $us_pack = $array["Body"]["MessageParts"]["MAXSalesRevDocument"]["MAXSalesRevenueTable"][1]; //US Packaging Results

            $sales["month_name"] = $max_pax["CurrentMonthName"];
            $sales["month_range"] = $max_pax["CurrentMonthStr"];
            $sales["cur_year"] = $max_pax["CurrentYear"];
            $sales["prev_year"] = $max_pax["Previousyear"];

            $sales[0]["cur_mtd"] = number_format ( round($max_pax["MTDCurrentAmount"],2) , 2 , "." , "," );
            $sales[0]["prev_mtd"] = number_format ( round($max_pax["MTDPreviousAmount"],2) , 2 , "." , "," );
            $sales[0]["cur_ytd"] = number_format ( round($max_pax["YTDCurrentAmount"],2) , 2 , "." , "," );
            $sales[0]["prev_ytd"] = number_format ( round($max_pax["YTDPreviousAmount"],2) , 2 , "." , "," );

            $sales[1]["cur_mtd"] = number_format ( round($us_pack["MTDCurrentAmount"],2) , 2 , "." , "," );
            $sales[1]["prev_mtd"] = number_format ( round($us_pack["MTDPreviousAmount"],2) , 2 , "." , "," );
            $sales[1]["cur_ytd"] = number_format ( round($us_pack["YTDCurrentAmount"],2) , 2 , "." , "," );
            $sales[1]["prev_ytd"] = number_format ( round($us_pack["YTDPreviousAmount"],2) , 2 , "." , "," );

            $sales["cur_mtd_total"] = number_format ( round($max_pax["MTDCurrentAmount"] + $us_pack["MTDCurrentAmount"],2) , 2 , "." , "," );
            $sales["past_mtd_total"] = number_format ( round($max_pax["MTDPreviousAmount"] + $us_pack["MTDPreviousAmount"],2) , 2 , "." , "," );
            $sales["cur_ytd_total"] = number_format ( round($max_pax["YTDCurrentAmount"] + $us_pack["YTDCurrentAmount"],2) , 2 , "." , "," );
            $sales["past_ytd_total"] = number_format ( round($max_pax["YTDPreviousAmount"] + $us_pack["YTDPreviousAmount"],2) , 2 , "." , "," );
            */

        } else {
            $historical_sales[active] = false;
        }
    }
} else {
    $historical_sales[active] = false;
}

echo json_encode($historical_sales);