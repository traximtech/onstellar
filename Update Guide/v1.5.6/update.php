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
$query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '1.6' WHERE `name` = 'script_version'");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_host', 'localhost'), (NULL, 'ftp_port', '21');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_username', ''), (NULL, 'ftp_password', '');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_upload', '0');");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'ftp_endpoint', 'storage.wowonder.com');");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Posts` CHANGE `postText` `postText` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_BlogComments` CHANGE `text` `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Comments` CHANGE `text` `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Comment_Replies` CHANGE `text` `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Messages` CHANGE `text` `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_MovieComments` CHANGE `text` `text` TEXT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL;");
$query = mysqli_query($sqlConnect, "ALTER TABLE `Wo_Users` ADD `last_avatar_mod` INT NOT NULL DEFAULT '0' AFTER `sidebar_data`, ADD `last_cover_mod` INT NOT NULL DEFAULT '0' AFTER `last_avatar_mod`;");

$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_ads')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'manage_ads')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_new_ads')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_events')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'edit_event')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'event_going')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'event_intersted')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'event_invited')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'events_past')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'events_upcoming')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'crop_your_avatar')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'cookie_message')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'cookie_dismiss')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'cookie_link')");
$query = mysqli_query($sqlConnect, "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'terms_accept')");


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
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'تحرير الإعلانات');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'إدارة الإعلانات');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'إنشاء إعلان جديد');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'حدث جديد Craete');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'تحرير الحدث');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'أحداث الذهاب');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'الأحداث المهتمة');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'دعوة');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'الأحداث الماضية');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'الأحداث القادمة');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'اقتصاص الصورة الرمزية الخاصة بك');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'يستخدم موقع الويب هذا ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة على موقعنا.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'فهمتك!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'أعرف أكثر');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'يرجى الموافقة على شروط الاستخدام وسياسة الخصوصية');
    } else if ($value == 'dutch') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Bewerk advertenties');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Advertenties beheren');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Maak een nieuwe advertentie');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete nieuw evenement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Gebeurtenis bewerken');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Evenementen gaan');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Evenementen Geïnteresseerd');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Uitgenodigd');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Vorige evenementen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'aankomende evenementen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Snijd je avatar bij');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Deze website maakt gebruik van cookies om ervoor te zorgen dat u de beste ervaring op onze website krijgt.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Begrepen!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Kom meer te weten');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Ga akkoord met de gebruiksvoorwaarden en het privacybeleid');
    } else if ($value == 'french') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Modifier les annonces');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Gerer annonces');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Créer une nouvelle annonce');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete nouvel événement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Modifier l\'événement');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Evénements');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Événements intéressés');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Invité');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Événements passés');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'évènements à venir');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Recadrez votre avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Ce site utilise des cookies pour vous assurer la meilleure expérience sur notre site.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Je l\'ai!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Apprendre encore plus');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Veuillez accepter les conditions d\'utilisation et la politique de confidentialité');
    } else if ($value == 'german') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Anzeigen bearbeiten');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Anzeigen verwalten');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Erstellen Sie eine neue Anzeige');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete neues Ereignis');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Veranstaltung bearbeiten');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Veranstaltungen gehen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Veranstaltungen interessiert');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Eingeladen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Vergangene Ereignisse');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Kommende Veranstaltungen');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Beschneide deinen Avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Diese Website verwendet Cookies, um sicherzustellen, dass Sie die beste Erfahrung auf unserer Website erhalten.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Ich habs!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Erfahren Sie mehr');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Bitte stimme den Nutzungsbedingungen und Datenschutzrichtlinien zu');
    } else if ($value == 'italian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Modifica annunci');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Gestisci annunci');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Crea un nuovo annuncio');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete nuovo evento');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Modifica evento');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Eventi in corso');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Eventi interessati');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Invitato');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Eventi passati');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Prossimi eventi');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Ritaglia il tuo avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Questo sito Web utilizza i cookie per assicurarti di ottenere la migliore esperienza sul nostro sito web.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Fatto!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Per saperne di più');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Si prega di accettare i Termini d\'uso e l\'informativa sulla privacy');
    } else if ($value == 'portuguese') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Editar anúncios');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Gerenciar anúncios');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Crie um novo anúncio');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Novo evento Craete');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Editar evento');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Eventos indo');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Eventos Interessados');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Convidamos');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Eventos passados');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'próximos eventos');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Recorte seu avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Este site usa cookies para garantir que você obtenha a melhor experiência em nosso site.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Consegui!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Saber mais');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Por favor, aceite os Termos de Uso e Política de Privacidade');
    } else if ($value == 'russian') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Редактировать объявления');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Управление объявлениями');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Создать новое объявление');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Новое событие Craete');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Изменить событие');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'События');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'События');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'приглашенный');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Прошедшие события');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Предстоящие События');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Обрезать аватар');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'На этом веб-сайте используются файлы cookie, чтобы вы могли получить лучший опыт на нашем веб-сайте.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Понял!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Выучить больше');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Пожалуйста, соглашайтесь с Условиями использования и Политикой конфиденциальности');
    } else if ($value == 'spanish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Editar anuncios');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Administrar anuncios');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Crear nuevo anuncio');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Nuevo evento de Craete');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Editar evento');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Eventos en marcha');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Eventos Interesados');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Invitado');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Eventos pasados');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Próximos Eventos');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Recorta tu avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Este sitio web utiliza cookies para garantizar que obtenga la mejor experiencia en nuestro sitio web.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', '¡Lo tengo!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Aprende más');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Acepta los Términos de uso y la Política de privacidad');
    } else if ($value == 'turkish') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Reklamları düzenle');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Reklamları yönet');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Yeni reklam oluştur');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete yeni etkinlik');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Etkinliği düzenle');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Olaylar Gidiyor');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'İlgi Alanları');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Davetli');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Geçmiş Etkinlikler');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Yaklaşan Etkinlikler');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Avatarını kırp');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'Bu web sitesi, web sitemizde en iyi deneyimi yaşamanızı sağlamak için çerezleri kullanır.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Anladım!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Daha fazla bilgi edin');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Lütfen Kullanım Koşulları ve Gizlilik Politikasını kabul edin');
    } else if ($value == 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Edit ads');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Manage ads');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Create new ad');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete new event');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Edit event');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Events Going');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Events Interested');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Invited');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Past Events');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Upcoming Events');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Crop your avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'This website uses cookies to ensure you get the best experience on our website.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Got It!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Learn More');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Please agree to the Terms of use & Privacy Policy');
    } else if ($value != 'english') {
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_ads', 'Edit ads');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_ads', 'Manage ads');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_new_ads', 'Create new ad');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'create_events', 'Craete new event');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'edit_event', 'Edit event');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_going', 'Events Going');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_intersted', 'Events Interested');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'event_invited', 'Invited');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_past', 'Past Events');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'events_upcoming', 'Upcoming Events');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'crop_your_avatar', 'Crop your avatar');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_message', 'This website uses cookies to ensure you get the best experience on our website.');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_dismiss', 'Got It!');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'cookie_link', 'Learn More');
        $lang_update_queries[] = Wo_UpdateLangs($value, 'terms_accept', 'Please agree to the Terms of use & Privacy Policy');
    }
}

if (!empty($lang_update_queries)) {
    foreach ($lang_update_queries as $key => $query) {
        $sql = mysqli_query($sqlConnect, $query);
    }
}

use Aws\S3\S3Client;
if (!empty($wo['config']['amazone_s3_key']) && !empty($wo['config']['bucket_name'])) {
    try {
        $s3Client = S3Client::factory(array(
            'version' => 'latest',
            'region' => $wo['config']['region'],
            'credentials' => array(
                'key' => $wo['config']['amazone_s3_key'],
                'secret' => $wo['config']['amazone_s3_s_key']
            )
        ));
        $result = $s3Client->putBucketCors([
            'Bucket' => $wo['config']['bucket_name'], // REQUIRED
            'CORSConfiguration' => [ // REQUIRED
                'CORSRules' => [ // REQUIRED
                    [
                        'AllowedHeaders' => ['Authorization'],
                        'AllowedMethods' => ['POST', 'GET', 'PUT'], // REQUIRED
                        'AllowedOrigins' => ['*'], // REQUIRED
                        'ExposeHeaders' => [],
                        'MaxAgeSeconds' => 3000
                    ],
                ],
            ]
        ]);
    } catch (Exception $e) {

    } 
}

echo 'The script is successfully updated to v1.5.6!';
$name = md5(microtime()) . '_updated.php';
rename('update.php', $name);
exit();
?>