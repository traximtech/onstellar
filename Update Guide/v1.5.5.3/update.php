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
$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '1.5.5.3' WHERE `name` = 'script_version'");

$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Posts` ADD INDEX(`multi_image`);");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Posts` ADD INDEX(`album_name`);");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'push_id_2', ''), (NULL, 'push_key_2', '');");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_VideoCalles` ADD `room_name` VARCHAR(50) NOT NULL DEFAULT '' AFTER `to_id`;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_AudioCalls` ADD `room_name` VARCHAR(50) NOT NULL DEFAULT '' AFTER `to_id`;");

$query = mysqli_query($sqlConnect, "CREATE TABLE `Wo_AgoraVideoCall` (
  `id` int(11) NOT NULL,
  `from_id` int(11) NOT NULL DEFAULT '0',
  `to_id` int(11) NOT NULL DEFAULT '0',
  `type` varchar(50) NOT NULL DEFAULT 'video',
  `room_name` varchar(50) NOT NULL DEFAULT '0',
  `time` int(11) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=latin1;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_AgoraVideoCall`
  ADD PRIMARY KEY (`id`),
  ADD KEY `from_id` (`from_id`),
  ADD KEY `to_id` (`to_id`),
  ADD KEY `type` (`type`),
  ADD KEY `room_name` (`room_name`),
  ADD KEY `time` (`time`),
  ADD KEY `status` (`status`);");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_AgoraVideoCall`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'whatsapp');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'post_login_requriement_dislike');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'post_login_requriement_none');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'e_disliked_my_posts');");

if (file_exists('.htaccess') && file_exists('htaccess.txt')) {
    $put = @file_put_contents('.htaccess', file_get_contents('htaccess.txt'));
}

$data  = array();
$query = mysqli_query($sqlConnect, "SHOW COLUMNS FROM `Wo_Langs`");
while ($fetched_data = mysqli_fetch_assoc($query)) {
    $data[] = $fetched_data['Field'];
}
unset($data[0]);
unset($data[1]);
function Wo_UpdateLangs($lang, $key, $value) {
    $update_query         = "UPDATE Wo_Langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
    $update_replace_array = array(
        "{lang}",
        "{lang_text}",
        "{lang_key}"
    );
    return str_replace($update_replace_array, array(
        $lang,
        $value,
        $key
    ), $update_query);
}
$lang_update_queries = array();
foreach ($data as $key => $value) {
    $value = ($value);
    if ($value == 'arabic') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'ال WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'الرجاء تسجيل الدخول لإبداء الإعجاب ، وعدم الإعجاب ، والمشاركة والتعليق!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'يرجى تسجيل الدخول لإبداء الإعجاب والمشاركة والتعليق!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'شخص لم يعجبني مشاركاتي');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Meld u aan om leuk te vinden, niet leuk te vinden, te delen en te reageren!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Log in om leuk te vinden, delen en reageren!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Iemand vond mijn berichten niet leuk');
    } else if ($value == 'french') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Veuillez vous connecter pour aimer, ne pas aimer, partager et commenter!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Veuillez vous connecter pour aimer, partager et commenter!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Quelqu\'un n\'aimait pas mes messages');
    } else if ($value == 'german') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Bitte einloggen um zu mögen, nicht mögen, teilen und kommentieren!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Bitte einloggen um zu liken, teilen und kommentieren!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Jemand hat meine Beiträge nicht gemocht');
    } else if ($value == 'italian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Effettua il login per piacere, non mi piace, condividi e commenta!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Effettua il login per piacere, condividere e commentare!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'A qualcuno non sono piaciuti i miei post');
    } else if ($value == 'portuguese') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Por favor, faça o login para curtir, não gostar, compartilhar e comentar!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Por favor, faça o login para curtir, compartilhar e comentar!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Alguém não gostou de minhas postagens');
    } else if ($value == 'russian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Пожалуйста, войдите в систему, чтобы не любить, делиться и комментировать!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Войдите, чтобы добавить, поделиться и прокомментировать!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Кто-то не любил мои сообщения');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Por favor inicie sesión para gustar, no me gusta, compartir y comentar!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Por favor inicie sesión para gustar, compartir y comentar!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'A alguien no le gustó mis publicaciones');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Naber');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Lütfen beğenmek, beğenmemek, paylaşmak ve yorum yapmak için giriş yapın!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Lütfen beğenmek, paylaşmak ve yorum yapmak için giriş yapın!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Birisi yayınlarımı beğenmedi');
    } else if ($value == 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Please log in to like, dislike, share and comment!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Please log in to like, share and comment!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Someone disliked my posts');
    } else if ($value != 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'WhatsApp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_dislike', 'Please log in to like, dislike, share and comment!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'post_login_requriement_none', 'Please log in to like, share and comment!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'e_disliked_my_posts', 'Someone disliked my posts');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($sqlConnect, $query);
    }
}


echo 'The script is successfully updated to v1.5.5.3!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();