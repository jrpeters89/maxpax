<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$company = array();
$act = $_GET[act];
$company_id = $_GET[company_id];

if(!empty($user_token)) {
	if($company_id != '') {
		$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');

		$result = mysqli_query($conn, "SELECT `id`,`company_name`,`logo_path`,`default_page`,`documents` FROM `companies` WHERE `id`='$company_id'") or die(mysqli_error($conn));
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$company['active'] = true;
			$company['id'] = $row['id'];
			$company['name'] = $row['company_name'];
			$company['access'] = 1;
			$company['logo'] = (!empty($row['logo_path']) ? $row['logo_path'] : "maxpax");
			$company['default_page'] = $row['default_page'];
			$company['documents'] = $row['documents'];
		} else {
			//$company['active'] = false;
		}
	} else {
		if ($act == "list") {
			$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
			$result = mysqli_query($conn, "SELECT `company`,`company_name` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				$company['number'] = $row[company];
				if ($row[company] == 1) {
					$company['active'] = true;
					$test = 1;
					$result = mysqli_query($conn, "SELECT `id`,`company_name` FROM `companies` WHERE 1 ORDER BY `company_name`") or die(mysqli_error($conn));
					while ($row = mysqli_fetch_array($result)) {
						$test = $test + 1;
						$company['options'][] = array(
							'id' => $row[id],
							'name' => $row[company_name]
						);
					}
				} else {
					$company['active'] = true;
					$company['options'][] = array(
						'id' => $row[id],
						'name' => $row[company_name]
					);
				}
			} else {
				$company['active'] = false;
			}
		} else {
			$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');

			$result = mysqli_query($conn, "SELECT `access_level`,`company`,`company_name`,`logo_path`,`default_page`,`documents` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				$company['active'] = true;
				$company['id'] = $row['company'];
				$company['name'] = $row['company_name'];
				$company['access'] = $row['access_level'];
				$company['logo'] = (!empty($row['logo_path']) ? $row['logo_path'] : "maxpax");
				$company['default_page'] = $row['default_page'];
				$company['documents'] = $row['documents'];
			} else {
				$company['active'] = false;
			}
		}
	}
} else {
	$company['active'] = false;
}

echo json_encode($company);