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
$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '1.5.5' WHERE `name` = 'script_version'");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'people')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'nature')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'share_to')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'timeline')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pinterest')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'whatsapp')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'group')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pro_members')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'copyrights')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'popular_posts')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'duration')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pro_feature_control_profile')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'why_choose_pro')");

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
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'اشخاص');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'طبيعة');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'مشاركة ل');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'الجدول الزمني');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'موقع Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'مجموعة');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'الأعضاء المحترفون');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'منشورات شائعة');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'المدة الزمنية');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'تمنحك الميزات الاحترافية تحكمًا كاملاً في ملفك الشخصي.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'لماذا اخترت للمحترفين؟');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'واتس اب');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'Mensen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Natuur');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Delen naar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Tijdlijn');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Groep');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Pro-leden');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'populaire posts');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Looptijd');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Pro-functies geven u volledige controle over uw profiel.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Waarom kiezen voor PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'french') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'Gens');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'La nature');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Partager à');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Chronologie');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Groupe');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Membres Pro');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'Messages populaires');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Durée');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Les fonctionnalités Pro vous donnent un contrôle total sur votre profil.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Pourquoi choisir PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'german') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'Menschen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Natur');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Teilen mit');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Zeitleiste');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Gruppe');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Pro Mitglieder');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {Datum} {Site_Name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'Beliebte Beiträge');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Dauer');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Pro-Funktionen geben Ihnen die vollständige Kontrolle über Ihr Profil.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Warum wählen Sie PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'italian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'Persone');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Natura');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Condividere a');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Sequenza temporale');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Gruppo');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Membri Pro');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'Post popolari');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Durata');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Le funzionalità Pro ti danno il controllo completo sul tuo profilo.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Perché scegliere PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'portuguese') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'Ludzie');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Natura');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Dzielić się z');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Oś czasu');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Grupa');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Pro Członkowie');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'popularne posty');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Trwanie');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Funkcje Pro zapewniają pełną kontrolę nad Twoim profilem.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Dlaczego warto wybrać PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'russian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'люди');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Природа');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Поделиться с');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'График');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'группа');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Пользователи');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'популярные посты');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'продолжительность');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Функции Pro дают вам полный контроль над вашим профилем.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Почему выбирают PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'Gente');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Naturaleza');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Compartir a');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Cronología');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Grupo');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Miembros Pro');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'entradas populares');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Duración');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Las funciones Pro le brindan un control total sobre su perfil.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', '¿Por qué elegir PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'İnsanlar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Doğa');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Ile paylaş');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Zaman çizelgesi');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'grup');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Profesyonel Üyeler');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'popüler gönderiler');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'süre');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Pro özellikleri profilinizde tam kontrol sağlar.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'PRO Neden Tercih Edilir?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    } 
    else if ($value == 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'People');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Nature');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Share to');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Timeline');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Group');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Pro Members');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'Popular Posts');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Duration');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Pro features give you complete control over your profile.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Why Choose PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    }
    else if ($value != 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'people', 'People');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'nature', 'Nature');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'share_to', 'Share to');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'timeline', 'Timeline');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pinterest', 'Pinterest');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'group', 'Group');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_members', 'Pro Members');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'copyrights', '© {date} {site_name}');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts', 'Popular Posts');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'duration', 'Duration');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'pro_feature_control_profile', 'Pro features give you complete control over your profile.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'why_choose_pro', 'Why Choose PRO?');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'whatsapp', 'Whatsapp');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($sqlConnect, $query);
    }
}

echo 'The script is successfully updated to v1.5.5!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();