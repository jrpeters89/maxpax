<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$user_token = $_GET[user_token];
$act = $_GET['act'];
$users = array();

if(!empty($user_token)) {
	if($act == "list") {
		$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
		$result = mysqli_query($conn, "SELECT `id`,`access_level`,`company` FROM `users` WHERE `token`='$user_token' LIMIT 1") or die(mysqli_error($conn));
		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			$users['edit'] = ($row[access_level] > 0 ? true : false);
			$users['company'] = $row[company];
			$cur_user_id = $row[id];
			if($row[company] == 1) {
				$result = mysqli_query($conn, "SELECT `users`.`id` AS `user_id`,`first`,`last`,`email`,`company`,`access_level`,`company_name` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `users`.`id` != '$cur_user_id' ORDER BY `last`,`first`") or die(mysqli_error($conn));
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$users['list'][] = $row;
					}
					$users['active'] = true;
				} else {
					//No Users
					$users['active'] = false;
				}
			}  else {
				$result = mysqli_query($conn, "SELECT `id`,`first`,`last`,`email` FROM `users` WHERE `id`!='$cur_user_id' AND `company`='$row[company]' ORDER BY `last`,`first`") or die(mysqli_error($conn));
				if(mysqli_num_rows($result) > 0) {
					while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
						$users['list'][] = $row;
					}
					$users['active'] = true;
				} else {
					//No Users
					$users['active'] = false;
				}
			}
		} else {
			$users['active'] = false;
		}
		echo json_encode($users);
	} else if ($act == "info") {
		
		$user_id = $_REQUEST[user_id];
		if(!empty($user_id)) {
			$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
			$result = mysqli_query($conn, "SELECT `access_level` FROM `users` WHERE `token`='$user_token' LIMIT 1") or die(mysqli_error($conn));
			if(mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				if($row[access_level] > 0) {
					$result = mysqli_query($conn, "SELECT `id`,`first`,`last`,`email`,`company`,`access_level` FROM `users` WHERE `id`='$user_id' LIMIT 1") or die(mysqli_error($conn));
					if(mysqli_num_rows($result) > 0) {
						$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
						$users['active'] = true;
						$users['info'] = $row;
					} else {
						$users['active'] = false;
					}
				} else {
					$users['active'] = false;
				}
			} else {
				$users['active'] = false;
			}
		} else {
			$users['active'] = false;
		}
		
		echo json_encode($users);
		
	} else if ($act == "delete") {
		
		$user_id = $_REQUEST[user_id];
		if($user_id > 0) {
			$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
			$result = mysqli_query($conn, "SELECT `access_level` FROM `users` WHERE `token`='$user_token' LIMIT 1") or die(mysqli_error($conn));
			if(mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				if($row[access_level] > 0) {
					$result = mysqli_query($conn, "DELETE FROM `users` WHERE `id`='$user_id' LIMIT 1") or die(mysqli_error($conn));
					if(mysqli_affected_rows($conn) > 0) {
						echo true;
					} else {
						echo "Unable to delete user. Please refresh and try again";
					}
				} else {
					echo "You do not have access to delete users.";
				}
			} else {
				echo "Unable to process request. Please try again.";
			}	
		} else {
			echo "No user selected. Please try again.";
		}
	} else {
		//No action selected
	}
}