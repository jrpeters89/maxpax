<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];

$files = array();

if(!empty($user_token)) {
	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
	$result = mysqli_query($conn, "SELECT `access_level`,`documents`,`doc_path` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		if($row['documents'] > 0) {
			$files['active'] = true;
			
			if(!empty($row['doc_path'])) {
				$dir = $row['doc_path'];
				
				$list = scandir($dir);
				
				foreach($list as $key => $file) {
					$path = pathinfo($file);
					$files['list'][$key]['name'] = $path["filename"];
					if($path["extension"] == "xls" || $path["extension"] == "xlsx" || $path["extension"] == "xlsm") {
						$ext = "excel";
					} else if ($path["extension"] == "pdf") {
						$ext = "pdf";
					} else {
						$ext = "text";
					}
					$files['list'][$key]['ext'] = $ext;
					$files['list'][$key]['url'] = "/src/file.php?loc=".$dir."/".$file."&user_token=".$user_token;
				}
			} else {
				$files['active'] = false;
			}
		} else {
			$files['active'] = false;
		}
	} else {
		$files['active'] = false;
	}
} else {
	$files['active'] = false;
}

echo json_encode($files);