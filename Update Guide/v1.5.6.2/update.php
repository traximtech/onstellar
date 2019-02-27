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
$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '1.5.6.2' WHERE `name` = 'script_version'");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_ads')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'find_friends_nearby')");

$data  = array();
$query = mysqli_query($sqlConnect, "SHOW COLUMNS FROM `Wo_Langs`");
while ($fetched_data = mysqli_fetch_assoc($query)) {
    $data[] = $fetched_data['Field'];
}
unset($data[0]);
unset($data[1]);
function Wo_UpdateLangs($lang, $key, $value) {
    global $sqlConnect;
    $update_query         = "UPDATE Wo_Langs SET `{lang}` = '{lang_text}' WHERE `lang_key` = '{lang_key}'";
    $update_replace_array = array(
        "{lang}",
        "{lang_text}",
        "{lang_key}"
    );
    return str_replace($update_replace_array, array(
        $lang,
        Wo_Secure($value),
        $key
    ), $update_query);
}
$lang_update_queries = array();
foreach ($data as $key => $value) {
    $value = ($value);
    if ($value == 'arabic') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'إنشاء الإعلان');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'البحث عن أصدقاء');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Maak advertentie');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Zoek vrienden');
    } else if ($value == 'french') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Créer une publicité');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Retrouver des amis');
    } else if ($value == 'german') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Erstellen Sie Werbung');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Freunde finden');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'dislike', 'nicht gefallen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
    } else if ($value == 'italian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Crea pubblicità');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Trova amici');
    } else if ($value == 'portuguese') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Criar anúncio');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Encontrar amigos');
    } else if ($value == 'russian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Создать рекламу');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Найти друзей');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Crear publicidad');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Encontrar amigos');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Reklam oluştur');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Arkadaşları bul');
    } else if ($value == 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Create advertisement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Find friends');
    } else if ($value != 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ads', 'Create advertisement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'find_friends_nearby', 'Find friends');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($sqlConnect, $query);
    }
}

echo 'The script is successfully updated to v1.5.6.2!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();
?>
