<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2018 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
$response_data = array(
    'api_status' => 400
);
if (empty($_POST['post_id'])) {
    $error_code    = 4;
    $error_message = 'post_id (POST) is missing';
} else if (empty($_POST['action'])) {
    $error_code    = 5;
    $error_message = 'action (POST) is missing';
} else if (!in_array($_POST['action'], array('edit', 'delete'))) {
	$error_code    = 6;
    $error_message = 'Undefined action value';
}

if (empty($error_code)) {
	$action = '';
    if ($_POST['action'] == 'delete') {
		if (Wo_DeletePost($_POST['post_id']) === true) {
			$action = 'deleted';
		}
	} else if ($_POST['action'] == 'edit') {
		if (empty($_POST['text'])) {
			$error_code    = 7;
            $error_message = 'text (POST) is empty';
		} else {
			$updatePost = Wo_UpdatePost(array(
                'post_id' => $_POST['post_id'],
                'text' => $_POST['text']
            ));
            if (!empty($updatePost)) {
            	$action = 'edited';
            }
		}
	}
	if (!empty($action)) {
		$response_data = array(
			'api_status' => 200,
			'action' => $action
		);
	}
}