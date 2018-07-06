<?php
header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
include("C:/inetpub/protected/database_connect.php");

$user_token = $_GET[user_token];
$company_id = $_GET[company_id];
$tab = $_GET[tab];

$files = array();

if(!empty($user_token)) {
	switch($company_id) {
		case 1:
		case 2:
		case 3:
		case 4:
		case 5:
		case 6:
		case 7:
		case 8:
		case 9:
        case 10:
        case 11:
        case 12:
        case 13:
		$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
		$result = mysqli_query($conn, "SELECT `id`,`documents`,`doc_path` FROM `companies` WHERE `id`='$company_id'") or die(mysqli_error($conn));
		if (mysqli_num_rows($result) > 0) {
			$row = mysqli_fetch_array($result);
			if ($row['documents'] > 0 || ($row['documents'] == 0 && $row['id'] == 9)) {
			    if ($row['id'] == 12) {
			        $files['active'] = true;
			        $dir = '//sw-fs-02/Shared/Docs-USP/CUSTOMERS/Ferrara/usage';

			        $list = scandir($dir);

                    foreach ($list as $key => $file) {
                        $path = pathinfo($file);
                        $files['list'][$key]['name'] = $path["filename"];
                        if ($path["extension"] == "xls" || $path["extension"] == "xlsx" || $path["extension"] == "xlsm") {
                            $ext = "excel";
                        } else if ($path["extension"] == "pdf") {
                            $ext = "pdf";
                        } else {
                            $ext = "text";
                        }
                        $files['list'][$key]['ext'] = $ext;
                        $files['list'][$key]['url'] = "/src/file.php?loc=" . $dir . "/" . $file . "&user_token=" . $user_token;
                    }
                } else if($row['id'] == 9) {
					$files['active'] = true;
					if (!empty($row['doc_path'])) {
						if ($tab == 'coas') {
							$dir = $row['doc_path'] . '/Completed COAs 2018';
						} else if ($tab == 'batch') {
							$dir = $row['doc_path'] . '/FINISHED BATCH TICKETS';
						}
						$list = scandir($dir);

						foreach ($list as $key => $file) {
							$path = pathinfo($file);
							$files['list'][$key]['name'] = $path["filename"];
							if ($path["extension"] == "xls" || $path["extension"] == "xlsx" || $path["extension"] == "xlsm") {
								$ext = "excel";
							} else if ($path["extension"] == "pdf") {
								$ext = "pdf";
							} else {
								$ext = "text";
							}
							$files['list'][$key]['ext'] = $ext;
							$files['list'][$key]['url'] = "/src/file.php?loc=" . $dir . "/" . $file . "&user_token=" . $user_token;
						}
					} else {
						$files['active'] = false;
					}

				} else {
					$files['active'] = true;

					if (!empty($row['doc_path'])) {
						$dir = $row['doc_path'];

						$list = scandir($dir);

						foreach ($list as $key => $file) {
							$path = pathinfo($file);
							$files['list'][$key]['name'] = $path["filename"];
							if ($path["extension"] == "xls" || $path["extension"] == "xlsx" || $path["extension"] == "xlsm") {
								$ext = "excel";
							} else if ($path["extension"] == "pdf") {
								$ext = "pdf";
							} else {
								$ext = "text";
							}
							$files['list'][$key]['ext'] = $ext;
							$files['list'][$key]['url'] = "/src/file.php?loc=" . $dir . "/" . $file . "&user_token=" . $user_token;
						}
					} else {
						$files['active'] = false;
					}
				}
			} else {
				$files['active'] = false;
			}
		} else {
			$files['active'] = false;
		}
			break;

		default:
			$conn = mysqli_connect(DBHOST, DBUSER, DBPASS, DBAPP) or die('Could not select database.');
			$result = mysqli_query($conn, "SELECT `access_level`,`documents`,`doc_path` FROM `users` LEFT JOIN `companies` ON `users`.`company` = `companies`.`id` WHERE `token`='$user_token'") or die(mysqli_error($conn));
			if (mysqli_num_rows($result) > 0) {
				$row = mysqli_fetch_array($result);
				if ($row['documents'] > 0) {
					$files['active'] = true;

					if (!empty($row['doc_path'])) {
						$dir = $row['doc_path'];

						$list = scandir($dir);

						foreach ($list as $key => $file) {
							$path = pathinfo($file);
							$files['list'][$key]['name'] = $path["filename"];
							if ($path["extension"] == "xls" || $path["extension"] == "xlsx" || $path["extension"] == "xlsm") {
								$ext = "excel";
							} else if ($path["extension"] == "pdf") {
								$ext = "pdf";
							} else {
								$ext = "text";
							}
							$files['list'][$key]['ext'] = $ext;
							$files['list'][$key]['url'] = "/src/file.php?loc=" . $dir . "/" . $file . "&user_token=" . $user_token;
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
	}
} else {
		$files['active'] = false;

}

echo json_encode($files);