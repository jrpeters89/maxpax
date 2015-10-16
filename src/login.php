<?php
header('Access-Control-Allow-Origin: *'); 
header('Access-Control-Allow-Headers: Content-Type, x-xsrf-token');
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
if(is_array($request)) {
	$email = $request[0]->value;
	$pass = $request[1]->value;
} else {
	$email = $request->email;
	$pass = $request->password;
}

$login = array();

if(!empty($email) && !empty($pass)) {
	$pass = md5($pass);

	$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
			 
	$result = mysqli_query($conn, "SELECT `id`,`first`,`last`,`access_level` FROM `users` WHERE `email`='$email' AND `pass`='$pass'") or die(mysqli_error($conn));
	if(mysqli_num_rows($result) > 0) {
		$row = mysqli_fetch_array($result);
		$token = md5($row[id].time());
		mysqli_query($conn, "UPDATE `users` SET `token`='$token' WHERE `id`='$row[id]'") or die(mysqli_error($conn));
		if(mysqli_affected_rows($conn) > 0) {
			$login[success] = true;
			$login[user_name] = $row[first]." ".$row[last];
			$login[admin] = ($row[access_level] > 0 ? true : false);
			$login[token] = $token;
		} else {
			$login[success] = false;
		}
	} else {
		$login[success] = false;
		$login[error_msg] = "Login Invalid. Try again.";
	}
} else {
	$login[success] = false;
	$login[error_msg] = "Make sure to fill out email & password!";
}

echo json_encode($login);