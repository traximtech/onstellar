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
$blocked_users = Wo_GetBlockedMembers($wo['user']['user_id']);

$users = array();

foreach ($blocked_users as $key => $blocked_user) {
	foreach ($non_allowed as $key => $value) {
	   unset($blocked_user[$value]);
	}
	$users[] = $blocked_user;
}

$response_data = array(
    'api_status' => 200,
    'blocked_users' => $users
);