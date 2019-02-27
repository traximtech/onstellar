<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate Social Networking Platform
// | Copyright (c) 2016 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

if (file_exists('assets/init.php')) {
    require 'assets/init.php';
}

else {
    die('Please put this file in the home directory !');
}


$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '" . time() . "' WHERE `name` = 'last_update'");
$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '1.5.5.2' WHERE `name` = 'script_version'");

$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Users` ADD `last_data_update` INT NOT NULL DEFAULT '0' AFTER `share_my_location`, ADD INDEX (`last_data_update`);");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Users` ADD `details` VARCHAR(300) NOT NULL DEFAULT 'a:6:{s:10:\"post_count\";i:0;s:11:\"album_count\";i:0;s:15:\"following_count\";i:0;s:15:\"followers_count\";i:0;s:12:\"groups_count\";i:0;s:11:\"likes_count\";i:0;}' AFTER `last_data_update`;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Users` ADD `sidebar_data` TEXT NULL DEFAULT NULL AFTER `details`;");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'update_user_profile', '3600');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'cache_sidebar', '1');");




echo 'The script is successfully updated to v1.5.5.2!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();