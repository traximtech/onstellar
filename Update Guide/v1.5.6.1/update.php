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
$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '1.5.6.1' WHERE `name` = 'script_version'");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_path', './');");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'good_morning')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'good_afternoon')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'good_evening')");


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
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'صباح الخير');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'طاب مسائك');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'مساء الخير');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Goedemorgen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Goedenmiddag');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Goedenavond');
    } else if ($value == 'french') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Bonjour');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Modifier l\'événement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Je l\'ai!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Veuillez accepter les conditions d\'utilisation et la politique de confidentialité');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Modifier l\'événement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'bonne après-midi');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Bonsoir');
    } else if ($value == 'german') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Guten Morgen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'guten Tag');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Guten Abend');
    } else if ($value == 'italian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Buongiorno');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Buon pomeriggio');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Buonasera');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Si prega di accettare i Termini d\'uso e l\'informativa sulla privacy');
    } else if ($value == 'portuguese') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Bom Dia');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Boa tarde');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Boa noite');
    } else if ($value == 'russian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Доброе утро');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Добрый день');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Добрый вечер');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Buenos días');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Buenas tardes');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Buenas tardes');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Günaydın');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Tünaydın');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'İyi akşamlar');
    } else if ($value == 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Good morning');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Good afternoon');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Good evening');
    } else if ($value != 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning', 'Good morning');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon', 'Good afternoon');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening', 'Good evening');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($sqlConnect, $query);
    }
}

echo 'The script is successfully updated to v1.5.6.1!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();
?>
