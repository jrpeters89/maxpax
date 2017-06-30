<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);

$user_token = $_GET[user_token];
$act = $_GET['act'];
$register = array();

if(!empty($user_token)) {
	if($act == "create") {
		$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
		$result = mysqli_query($conn, "SELECT `access_level`,`company` FROM `users` WHERE `token`='$user_token' LIMIT 1") or die(mysqli_error($conn));
		if(mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			if($row[access_level] > 0) {
				$first = $request[0]->value;
				$last = $request[1]->value;
				$email = $request[2]->value;
				$pass = $request[3]->value;
				$company_id = ($request[4]->value ? $request[4]->value : $row[company]);
				$access_level = $request[5]->value;
				
				if(!empty($email) && !empty($first) && !empty($last) && !empty($pass)) {
					
					$result = mysqli_query($conn, "SELECT `id` FROM `users` WHERE `email`='$email' LIMIT 1") or die(mysqli_error($conn));
					if(mysqli_num_rows($result) == 0) {
						
						if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
							
							$first_sql = mysqli_escape_string($first);
							$last_sql = mysqli_escape_string($last);
							$pass_sql = md5($pass);
							
							$result = mysqli_query($conn, "INSERT INTO `users` (`id`,`first`,`last`,`email`,`pass`,`company`,`access_level`,`token`) VALUES (NULL,'$first_sql','$last_sql','$email','$pass_sql','$company_id','$access_level','')") or die(mysqli_error($conn));
							
							$user_id = mysqli_insert_id($conn);
							if($user_id > 0) {
								
								$to = $email;
					
								$subject = 'MaxPax Registration';
								
								$headers = "From: admin@maxpaxllc.com \r\n";
								$headers .= "MIME-Version: 1.0\r\n";
								$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
								
								$message = '<html><body>';
								$message .= '<h4>'.strip_tags($first).', a MaxPax user account has been created for you. Please use the information below to sign in.</h4>';
								$message .= '<table rules="all" style="border-color: #666;" cellpadding="10">';
								$message .= "<tr><td><strong>Website:</strong> </td><td><a href='http://reports.maxpax.com'>http://reports.maxpax.com</a></td></tr>";
								$message .= "<tr><td><strong>Email:</strong> </td><td>" . strip_tags($email) . "</td></tr>";
								$message .= "<tr><td><strong>Password:</strong> </td><td>" . strip_tags($pass) . "</td></tr>";
								$message .= "</table>";
								$message .= "</body></html>";
								
								mail($to, $subject, $message, $headers);
								
								$register[success] = true;
								$register[user_id] = $user_id;
								$register[new_name] = strip_tags($first." ".$last);
								$register[email] = $email;
								
							} else {
								$register[success] = false;
								$register[error_msg] = "Error creating new user. Please try again.";
							}
						} else {
							$register[success] = false;
							$register[error_msg] = "Invalid email address. Please try again.";
						}
					} else {
						$register[success] = false;
						$register[error_msg] = "Email address already in use.";
					}
				} else {
					$register[success] = false;
					$register[error_msg] = "Please make sure all information is filled out.";
				}
			}
		}
	} else if ($act == "update") {
		
		$user_id = $request[0]->value;
		$first = $request[1]->value;
		$last = $request[2]->value;
		$email = $request[3]->value;
		$pass = $request[4]->value;
		$company_id = ($request[5]->value ? $request[5]->value : $row[company]);
		$access_level = $request[6]->value;
		
		if(!empty($email) && !empty($first) && !empty($last) && $user_id > 0) {
			if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
				$first_sql = mysqli_escape_string($first);
				$last_sql = mysqli_escape_string($last);
				if(!empty($pass)) {
					$pass_sql = md5($pass);
					$pass_change = ",`pass`='$pass_sql'";
				} else {
					$pass_change = "";
				}
				
				$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
				$result = mysqli_query($conn, "UPDATE `users` SET `first`='$first_sql',`last`='$last_sql',`email`='$email',`company`='$company_id',`access_level`='$access_level' $pass_change WHERE `id`='$user_id'") or die(mysqli_error($conn));
				
				if(mysqli_affected_rows($conn)) {
					$register[success] = true;
					$register[user_id] = $user_id;
					$register[new_name] = strip_tags($first." ".$last);
					$register[email] = $email;
				} else {
					$register[success] = false;
					$register[error_msg] = "Error creating new user. Please try again.";
				}
			} else {
				$register[success] = false;
				$register[error_msg] = "Invalid email address. Please try again.";
			}
		} else {
			$register[success] = false;
			$register[error_msg] = "Please make sure all information is filled out.";
		}
		
	} else {
		$register[success] = false;
		$register[error_msg] = "Invalid command.";
	}
} else {
	$register[success] = false;
	$register[error_msg] = "Invalid token.";
}
echo json_encode($register);