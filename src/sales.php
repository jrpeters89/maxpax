<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

$sales = array();

if(!empty($user_token)) {
	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
	$result = mysqli_query($conn, "SELECT `access_level`,`company` FROM `users` WHERE `token`='$user_token'") or die(mysqli_error($conn));
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		if($row[access_level] > 0 || $row[company] == 1) {
			$sales[active] = true;
			$xml = simplexml_load_file("../Data/mtd_ytd_sales.xml");
			$json = json_encode($xml);
			$array = json_decode($json,TRUE);
			
			
			$max_pax = $array["Body"]["MessageParts"]["MAXSalesRevDocument"]["MAXSalesRevenueTable"][0]; //MaxPax Results
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
			
		} else {
			$sales[active] = false;
		}
	}
} else {
	$sales[active] = false;
}

echo json_encode($sales);