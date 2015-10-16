<?php
$postdata = file_get_contents("php://input");
$request = json_decode($postdata);
$email = $request->email;

if($email == "test@maxpax.com") {
	echo true;
} else {
	echo false;
}