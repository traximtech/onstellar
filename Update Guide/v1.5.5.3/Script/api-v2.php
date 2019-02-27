<?php
// API v2 is not yet finished, you have to use the old API version.
header_remove('Server');
header("Content-type: application/json");
require('assets/init.php');

$response_data     = array();
$applications      = array('mobile', 'desktop');
$error_code    = 0;
$error_message = '';
$type              = (!empty($_GET['type'])) ? Wo_Secure($_GET['type']) : false;

if (empty($type)) {
	$response_data       = array(
        'api_status'     => '404',
        'errors'         => array(
            'error_id'   => '1',
            'error_text' => 'Error: 404 API Type not specified'
        )
    );
    echo json_encode($response_data, JSON_PRETTY_PRINT);
    exit();
}
$application       = (!empty($_GET['platform']) && in_array($_GET['platform'], $applications)) ? Wo_Secure($_GET['platform']) : 'mobile';
$api               = "api/v2/$application/$type.php"; 

$pages_without_access_token = array('settings');
$pages_without_loggedin = array('login', 'regsiter', 'settings');

if (!file_exists($api)) {
    $response_data       = array(
        'api_status'     => '404',
        'errors'         => array(
            'error_id'   => '1',
            'error_text' => 'Error: 404 API Type Not Found'
        )
    );
    echo json_encode($response_data, JSON_PRETTY_PRINT);
    exit();
}

if (!in_array($type, $pages_without_access_token)) {
	if (empty($_GET['access_token'])) {
	    $error_code    = 1;
	    $error_message = 'Error: access_token is missing';
	}
}

if (!empty($_GET['access_token'])) {
    $get_user_id_from_access_token = Wo_ValidateAccessToken($_GET['access_token']);
    if (is_numeric($get_user_id_from_access_token)) {
        $wo['user'] = Wo_UserData($get_user_id_from_access_token);
        $wo['loggedin'] = true;
        if ($wo['user']['user_id'] < 0 || empty($wo['user']['user_id']) || !is_numeric($wo['user']['user_id']) || Wo_UserActive($wo['user']['username']) === false) {
           $wo['loggedin'] = false;
        }
    }
}

if (!in_array($type, $pages_without_loggedin)) {
	if ($wo['loggedin'] == false && !empty($_GET['access_token'])) {
	    $error_code    = 2;
	    $error_message = 'Invalid or expired access_token';
	} else if ($wo['loggedin'] == false) {
		$error_code    = 2;
	    $error_message = 'Not authorized';
	}
}



if (!empty($error_code)) {
	$response_data       = array(
        'api_status'     => '404',
        'errors'         => array(
            'error_id'   => $error_code,
            'error_text' => $error_message
        )
    );
    echo json_encode($response_data, JSON_PRETTY_PRINT);
    exit();
}

require_once  "$api";

if (!empty($error_code)) {
    $response_data       = array(
        'api_status'     => '400',
        'errors'         => array(
            'error_id'   => $error_code,
            'error_text' => $error_message
        )
    );
}

echo json_encode($response_data, JSON_PRETTY_PRINT);
exit();

mysqli_close($sqlConnect);
unset($wo);
?>