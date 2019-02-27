<?php
if (file_exists('assets/init.php')) {
    require 'assets/init.php';
} else {
    die('Please put this file in the home directory !');
}


function check_($check) {
    $siteurl           = urlencode(getBaseUrl());
    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false
        )
    );
    $file              = file_get_contents('http://www.wowonder.com/purchase.php?code=' . $check . '&url=' . $siteurl, false, stream_context_create($arrContextOptions));
    if ($file) {
        $check             = json_decode($file, true);
    } else {
        $check             = array('status' => 'SUCCESS', 'url' => $siteurl, 'code' => $check);
    }
    return $check;
}

$updated = false;
if (!empty($_GET['updated'])) {
   $updated = true;
}
if (!empty($_POST['code'])) {
    $code = check_($_POST['code']);
    if ($code['status'] == 'SUCCESS') {
       $data['status'] = 200;
       $get_config = file_get_contents('config.php');
       $file_content = 
'<?php
// +------------------------------------------------------------------------+
// | @author Deen Doughouz (DoughouzForest)
// | @author_url 1: http://www.wowonder.com
// | @author_url 2: http://codecanyon.net/user/doughouzforest
// | @author_email: wowondersocial@gmail.com   
// +------------------------------------------------------------------------+
// | WoWonder - The Ultimate PHP Social Networking Platform
// | Copyright (c) 2016 WoWonder. All rights reserved.
// +------------------------------------------------------------------------+
// MySQL Hostname
$sql_db_host = "'  . $sql_db_host . '";
// MySQL Database User
$sql_db_user = "'  . $sql_db_user . '";
// MySQL Database Password
$sql_db_pass = "'  . $sql_db_pass . '";
// MySQL Database Name
$sql_db_name = "'  . $sql_db_name . '";

// Site URL
$site_url = "' . $site_url . '"; // e.g (http://example.com)

// Purchase code
$purchase_code = "' . trim($_POST['code']) . '"; // Your purchase code, don\'t give it to anyone. 
?>';
      $config_file = file_put_contents('config.php', $file_content);
    } else {
       $data['status'] = 400;
       $data['error'] = $code['ERROR_NAME'];//'Invalid or expired purchase code, or this purchase code is not allowed to be installed on this domain, if you think you get this message by mistake, please contact us.';
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}

if (!empty($_POST['query'])) {
    $query = mysqli_query($sqlConnect, base64_decode($_POST['query']));
    if ($query) {
       $data['status'] = 200;
    } else {
       $data['status'] = 400;
       $data['error'] = mysqli_error($sqlConnect);
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['update_langs'])) {
    $query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '" . time() . "' WHERE `name` = 'last_update'");
    $query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '2.0' WHERE `name` = 'script_version'");
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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'رقم الهاتف موجود بالفعل.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'الاسترداد عن طريق البريد الإلكتروني');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'استرداد عن طريق الرسائل القصيرة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'رقم الهاتف غير موجود');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'رقم الهاتف غير صالح أو يحتوي على أحرف');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'تم إرسال رسالة SMS الاسترداد بنجاح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'المعاملات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'أحدث الأنشطة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Wallet Topup');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'الإعلانات التي تنفق يوميا');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'معاملات نظام برو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'لم يتم العثور على أي معاملات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'الوصول المقدر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'رمز التأكيد الخاص بك هو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'يستخدم موقع الويب هذا ملفات تعريف الارتباط لضمان حصولك على أفضل تجربة على موقعنا.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'فهمتك!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'أعرف أكثر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'نكز');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'لقد نقزت');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'لم يتم العثور على نداءات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'الناس الذين طعنك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'أعد النظر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'مطعون!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'الوخزات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'المشاركات الشعبية والتعليقات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'تحميل المزيد من أشرطة الفيديو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'تحميل المزيد من الصور');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'لا مزيد من مقاطع الفيديو للعرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'لا مزيد من الصور للعرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'وكزتك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'إضافة هدية بنجاح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'خطأ أثناء إضافة هدية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'ارسل هدية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'الهدية ارسلت بنجاح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'أرسل لك هدية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'بلدي Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'برقية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'لا مزيد من المشاركات للعرض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'تعليمات اختصار لوحة المفاتيح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'انتقل إلى المشاركة التالية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'انتقل إلى الوظيفة السابقة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'تمت إضافة الملصق بنجاح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'حدث خطأ أثناء إضافة الملصق');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'رد على مشاركتك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'نقاط');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'نقاطي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'أجوري');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'اربح ٪d نقطة عن طريق الإعجاب بأي مشاركة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'اربح ٪d من النقاط عن طريق عدم الإعجاب بأي مشاركة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'اربح ٪d من خلال تعليق أي مشاركة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'أربح ٪d من النقاط بالتعجب عن أي مشاركة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'اربح ٪d نقطة عن طريق إنشاء منشور جديد');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'اربح ٪d نقطة عن طريق التفاعل على أي مشاركة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'لا ردود أفعال حتى الآن');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'حب');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'هههه');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'رائع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'حزين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'غاضب');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'رد على تعليقك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'رد على ردك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'افتعل على  {user} <a class=\"main-color\" href=\"{post}\">منشور</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'تم الإبلاغ عن التعليق بنجاح.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'الإبلاغ عن تعليق');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'تقرير تعليق حذف بنجاح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'الصفحات المقترحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'المجموعات المقترحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'غير مثبت عليه');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'مثل');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'حب');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'هههه');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'رائع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'حزين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'الاصدقاء المشتركه');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'لم يتم العثور على أصدقاء مشتركين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'ينظر قصتك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'علق على مقالك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'هل أنت متأكد أنك تريد غير صديق؟');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'إدارة الجلسات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'برنامج');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'المتصفح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'الماضي نشط');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'إعدادات الإشعار');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'اعلمني عندما');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'اسم المستخدم مدرج في القائمة السوداء وغير مسموح به ، يرجى اختيار اسم مستخدم آخر.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'عنوان البريد الإلكتروني مدرج في القائمة السوداء وغير مسموح به ، يرجى اختيار بريد إلكتروني آخر.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'أنشطة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'يتابع {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'أصبح أصدقاء مع {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'السماح لمحركات البحث بفهرسة ملفي الشخصي والمشاركات؟');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'اجعل كل المحادثات مقروءة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'لم تنته مشاركتك بعد. هل تريد المغادرة دون الانتهاء؟');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'سوف تذهب تلقائيا النقاط الخاصة بك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'الوضع الليلي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'وضع الإضاءة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'اختصارات لوحة المفاتيح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'تعليق');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'اكتب شيئًا هنا ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'عرض الصفحة الشخصية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'منع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'انشاء صفحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'إنشاء مجموعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'انشاء حدث');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'أعلن');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'انشاء مدونة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'كل يوم جديد هو فرصة لتغيير حياتك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'نرجو أن يكون هذا المساء خفيفًا ومباركًا ومستنيرًا ومنتجًا وسعيدًا.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'الأمسيات هي طريقة الحياة للقول بأنك أقرب إلى أحلامك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'وضع اليوم');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'telefoonnummer bestaat al');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Herstel per e-mail');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Herstel via sms');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'Het telefoonnummer kan niet worden gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'Het telefoonnummer is ongeldig of heeft tekens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recover SMS is succesvol verzonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'transacties');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Laatste activiteiten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Portemonnee-opwaardering');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Advertenties die dagelijks worden besteed');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Pro systeemtransacties');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'Geen transacties gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Geschat bereik');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Uw bevestigingscode is');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Deze website maakt gebruik van cookies om ervoor te zorgen dat u de beste ervaring op onze website krijgt.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Begrepen!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Kom meer te weten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Por');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Je hebt geplooid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Geen pokes gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Mensen die je hebben gepakt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Terug prikken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Prikte!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Populaire berichten en reacties');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Laad meer video\'s');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Laad meer foto\'s');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'geen video\'s meer om te laten zien');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'geen foto\'s meer om te laten zien');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Prikte jou');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Gift toevoegen succesvol');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Fout bij het toevoegen van een cadeau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Verstuur een cadeau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Gift met succes verzonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'Heeft je een geschenk gestuurd');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'Mijn porren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Geen posts meer om te laten zien');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Help voor sneltoetsen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Ga naar het volgende bericht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Ga naar het vorige bericht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Sticker met succes toegevoegd');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Fout tijdens het toevoegen van de sticker');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'reageerde op je bericht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'punten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Mijn punten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'mijn Inkomens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Verdien %d punten door een post te waarderen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Verdien %d punten door een post te negeren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Verdien %d punten door een bericht te becommentariëren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Verdien %d punten door een bericht te vragen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Verdien %d punten door een nieuw bericht te maken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Verdien %d punten door op een bericht te reageren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Nog geen reacties');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Liefde');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'Wauw');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'verdrietig');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Boos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'reageerde op je reactie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'reageerde op je antwoord');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'reageerde op {user} <a class=\"main-color\" href=\"{post}\">post</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Reactie succesvol gerapporteerd.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Rapporteer commentaar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Commentaarrapport succesvol verwijderd');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Voorgestelde pagina\'s');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Voorgestelde groepen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'geverifieerde');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Graag willen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Liefde');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Wauw');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'verdrietig');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Gemeenschappelijke vrienden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Geen wederzijdse vrienden gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'heb je verhaal bekeken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'hebben gereageerd op je artikel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Weet je zeker dat je wilt ontvrienden?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Sessies beheren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Platform');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'browser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Laatst actief');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Notificatie instellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Laat me weten wanneer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'De gebruikersnaam staat op de zwarte lijst en is niet toegestaan, kies een andere gebruikersnaam.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'Het e-mailadres staat op de zwarte lijst en is niet toegestaan, kies een andere e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'Activiteiten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'volgt {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'word vrienden met {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Sta zoekmachines toe mijn profiel en berichten te indexeren?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Markeer alle gesprekken als gelezen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Je hebt je bericht nog niet voltooid. Wil je vertrekken zonder te eindigen?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Je verdiende punten gaan automatisch naar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Nachtstand');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Lichtmodus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Toetsenbord sneltoetsen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Commentaar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Schrijf hier iets ...');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Bekijk profiel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Blok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Creëer pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Maak een groep');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Creëer evenement');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Maak advertentie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Maak een blog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Elke nieuwe dag is een kans om je leven te veranderen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Moge vanmiddag licht, gezegend, verlicht, productief en gelukkig zijn.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Avonden zijn de manier om te zeggen dat je dichter bij je dromen bent.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Dagmodus');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'le numéro de téléphone existe déjà.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Récupérer par email');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Récupérer par sms');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'Le numéro de téléphone est introuvable');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'Le numéro de téléphone est invalide ou contient des caractères');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Récupérer des SMS a été envoyé avec succès');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Transactions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Dernières activités');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Portefeuille Topup');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Annonces dépensées quotidiennement');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Transactions système pro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'Aucune transaction trouvée');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Portée estimée');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Votre code de confirmation est');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Ce site utilise des cookies pour vous garantir la meilleure expérience sur notre site.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Je l\'ai!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Apprendre encore plus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Poussée');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Vous avez fourré');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Aucun coup trouvé');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Les gens qui vous ont piqué');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Envoyer un poke en retour');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Fourré!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Messages et commentaires populaires');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Charger plus de vidéos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Charger plus de photos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'plus de vidéos à montrer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'plus de photos à montrer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Vous a Poké');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Cadeau ajouter avec succès');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Erreur lors de l\'ajout d\'un cadeau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Envoyer un cadeau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Cadeau envoyé avec succès');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'Vous a envoyé un cadeau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'Mes coups de coude');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Plus de messages à afficher');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Aide sur le raccourci clavier');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Faites défiler jusqu\'au prochain message');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Faites défiler jusqu\'au message précédent');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Autocollant ajouté avec succès');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Erreur lors de l\'ajout de l\'autocollant');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'réagi à votre message');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Points');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Mes points');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'Mes gains');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Gagnez %d points en aimant n\'importe quel poste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Gagnez %d points en ne détestant aucun message');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Gagnez %d points en commentant n\'importe quel article');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Gagnez %d points en vous demandant n\'importe quel message');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Gagnez %d points en créant un nouveau message');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Gagnez %d points en réagissant à n\'importe quel poste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Aucune réaction encore');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Amour');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'Sensationnel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'En colère');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'réagi à ton commentaire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'réagi à votre réponse');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'réagi à {user} <a class="main-color" href="{post}"> poster </a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Commentaire signalé avec succès.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Rapport de commentaire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Supprimer le rapport de commentaire avec succès');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Pages suggérées');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Groupes suggérés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Non vérifié');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Comme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Amour');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Sensationnel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Amis communs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Aucun ami commun trouvé');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'vu votre histoire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'a commenté votre article');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Êtes-vous sûr de vouloir vous libérer?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Gérer les sessions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Plate-forme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Navigateur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Dernière activité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Paramètres de notification');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Me prévenir quand');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'Le nom d\'utilisateur est sur liste noire et n\'est pas autorisé, veuillez choisir un autre nom d\'utilisateur.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'L\'adresse e-mail est sur liste noire et n\'est pas autorisée, veuillez choisir un autre e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'Activités');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'suit {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'devenir amis avec {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Autoriser les moteurs de recherche à indexer mon profil et mes publications?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Marquer toutes les conversations comme lues');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Vous n\'avez pas encore terminé votre message. Voulez-vous partir sans finir?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Vos points gagnés iront automatiquement à');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Mode nuit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Mode léger');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Raccourcis clavier');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Commentaire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Ecrivez quelque chose ici ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Voir le profil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Bloc');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Créer une page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Créer un groupe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Créer un évènement');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Créer une publicité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Créer un blog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Chaque nouveau jour est une chance de changer de vie.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Puisse cet après-midi être léger, béni, éclairé, productif et heureux.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Les soirées sont la manière de la vie de dire que vous êtes plus proche de vos rêves.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Mode jour');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'Telefonnummer existiert bereits.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Wiederherstellen per E-Mail');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Wiederherstellen per SMS');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'Die Telefonnummer wurde nicht gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'Die Telefonnummer ist ungültig oder hat Zeichen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'SMS wiederherstellen wurde erfolgreich gesendet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Transaktionen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Neueste Aktivitäten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Brieftasche aufladen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Anzeigen täglich ausgegeben');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Pro System Transaktionen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'Keine Transaktionen gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Geschätzte Reichweite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Ihr Bestätigungscode lautet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Diese Website verwendet Cookies, um sicherzustellen, dass Sie die beste Erfahrung auf unserer Website erhalten.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Ich habs!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Mehr erfahren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Sack');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Du hast Poked');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Keine Stichel gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Leute, die dich angestupst haben');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Zurückstupsen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Stocherte!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Beliebte Posts & Kommentare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Laden Sie mehr Videos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Laden Sie mehr Fotos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'keine weiteren Videos zu zeigen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'keine weiteren Fotos zu zeigen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Hat dich gestoßen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Geschenk wurde erfolgreich hinzugefügt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Fehler beim Hinzufügen eines Geschenks');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Ein Geschenk senden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Geschenk erfolgreich gesendet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'Sende dir ein Geschenk');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'Meine Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Keine weiteren Posts zum Anzeigen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Tastaturkürzel Hilfe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Scrolle zum nächsten Beitrag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Scrollen Sie zum vorherigen Beitrag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Aufkleber erfolgreich hinzugefügt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Fehler beim Hinzufügen des Aufklebers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'reagierte auf Ihren Posten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Punkte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Meine Punkte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'mein Einkommen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Verdiene %d Punkte, indem du jeden Beitrag magst');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Verdienen Sie %d Punkte, indem Sie einen Beitrag ablehnen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Verdiene %d Punkte, indem du einen Beitrag kommentierst');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Verdienen Sie %d Punkte, indem Sie sich jeden Post fragen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Verdiene %d Punkte, indem du einen neuen Beitrag erstellst');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Verdiene %d Punkte, indem du auf jeden Post reagierst');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Noch keine Reaktionen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Liebe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'Beeindruckend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Traurig');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Wütend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'reagierte auf Ihren Kommentar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'reagierte auf Ihre Antwort');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'reagierte auf {user} <a class="main-color" href="{post}">post</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Kommentar erfolgreich gemeldet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Kommentar melden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Kommentarbericht erfolgreich löschen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Vorgeschlagene Seiten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Vorgeschlagene Gruppen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Nicht überprüft');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Mögen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Liebe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Beeindruckend');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Traurig');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Gemeinsame Freunde');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Keine gemeinsamen Freunde gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'habe deine Geschichte gesehen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'hat deinen Artikel kommentiert');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Bist du sicher, dass du dich unfreundst?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Sitzungen verwalten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Plattform');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Browser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Letzte Aktivität');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Benachrichtigungseinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Benachrichtigen Sie mich, wenn');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'Der Benutzername ist auf der Blacklist und nicht erlaubt, bitte wähle einen anderen Benutzernamen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'Die E-Mail-Adresse ist auf der schwarzen Liste und nicht erlaubt. Bitte wählen Sie eine andere E-Mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'Aktivitäten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'folgt {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'Freunde werden mit {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Lassen Suchmaschinen mein Profil und Beiträge indizieren?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Markieren Sie alle Konversationen als gelesen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Du hast deinen Beitrag noch nicht beendet. Willst du ohne zu beenden gehen?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Ihre gesammelten Punkte werden automatisch an');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Nacht-Modus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Lichtmodus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Tastatürkürzel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Kommentar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Schreib etwas hier ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Profil anzeigen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Block');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Seite erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Gruppe erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Ereignis erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Anzeige erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Blog erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Jeder neue Tag ist eine Chance, dein Leben zu verändern.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Möge heute Nachmittag hell, gesegnet, erleuchtet, produktiv und glücklich sein.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Abende sind die Lebensart zu sagen, dass Sie Ihren Träumen näher sind.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Tagesmodus');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'il numero di telefono Ã¨ giÃ  esistente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Recupera per email');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Recupera per sms');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'Il numero di telefono non Ã¨ stato trovato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'Il numero di telefono non Ã¨ valido o contiene caratteri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recover SMS Ã¨ stato inviato con successo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Le transazioni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Ultime attivitÃ ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Portafoglio Topup');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Annunci spesi ogni giorno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Transazioni di sistema Pro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'Nessuna transazione trovata');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Portata stimata');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Il tuo codice di conferma Ã¨');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Questo sito Web utilizza i cookie per assicurarti di ottenere la migliore esperienza sul nostro sito web.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Fatto!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Per saperne di piÃ¹');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'colpire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Hai poked');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Nessun problema trovato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Persone che ti hanno stuzzicato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Colpisci');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'InfilÃ²!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'poke');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Post e commenti popolari');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Carica altri video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Carica piÃ¹ foto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'non piÃ¹ video da mostrare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'non piÃ¹ foto da mostrare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Ti ho mandato un poke');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Regalo aggiunto con successo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Errore durante l\'aggiunta di un regalo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Manda un regalo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Regalo inviato con successo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'Ti ho mandato un regalo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'I miei PokÃ©');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegramma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Non ci sono piÃ¹ post da mostrare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Guida rapida alla tastiera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Scorri fino al prossimo post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Scorri fino al post precedente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Adesivo aggiunto con successo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Errore durante l\'aggiunta dell\'adesivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'ha reagito al tuo post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Punti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'I miei punti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'I miei guadagni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Guadagna %d punti gradendo qualsiasi post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Guadagna %d punti non amando nessun post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Guadagna %d punti commentando qualsiasi post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Guadagna %d punti chiedendo qualsiasi post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Guadagna %d punti creando nuovi post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Guadagna %d punti reagendo su qualsiasi post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Nessuna reazione ancora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Amore');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'Wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Arrabbiato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'ha reagito al tuo commento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'ha reagito alla tua risposta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'ha reagito a {user} <a class=\"main-color\" href=\"{post}\">post</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Commento riportato con successo.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Segnala commento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Il rapporto di commento cancella con successo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Pagine suggerite');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Gruppi suggeriti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Non verificato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Piace');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Amore');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Amici in comune');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Nessun amico comune trovato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'ho visto la tua storia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'ha commentato il tuo articolo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Sei sicuro di voler disapprovare?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Gestisci sessioni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'piattaforma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Browser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Ultimo attivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Impostazioni di notifica');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Avvisami quando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'Il nome utente Ã¨ nella lista nera e non Ã¨ permesso, per favore scegli un altro nome utente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'L\'indirizzo email Ã¨ nella lista nera e non Ã¨ consentito, per favore scegli un\'altra email.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'attivitÃ ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'sta seguendo {utente}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'diventare amici con {utente}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Permetti ai motori di ricerca di indicizzare il mio profilo e i miei post?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Segna tutte le conversazioni come letti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Non hai ancora finito il tuo post. Vuoi andartene senza finire?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'I tuoi punti guadagnati andranno automaticamente a');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'ModalitÃ  notturna');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'ModalitÃ  luce');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Tasti rapidi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Commento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Scrivi qualcosa qui ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Vedi profilo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Bloccare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Crea pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Creare un gruppo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Crea Evento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Crea annuncio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Crea blog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Ogni nuovo giorno Ã¨ un\'opportunitÃ  per cambiare la tua vita.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Possa questo pomeriggio essere leggero, benedetto, illuminato, produttivo e felice.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Le sere sono il modo in cui la vita ti dice che sei piÃ¹ vicino ai tuoi sogni.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'ModalitÃ  giorno');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'número de telefone já existe.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Recuperar por email');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Recuperar por sms');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'O número de telefone não foi encontrado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'O número de telefone é inválido ou tem caracteres');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recuperar SMS foi enviado com sucesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Transações');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Últimas atividades');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Carteira Topup');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Anúncios gastos diariamente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Transações do sistema Pro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'Nenhuma transação encontrada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Alcance estimado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Seu código de confirmação é');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Este site usa cookies para garantir que você obtenha a melhor experiência em nosso site.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Consegui!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Saber mais');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Cutucar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Você cutucou');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Nenhum poke encontrado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Pessoas que te cutucaram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Puxar de volta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Cutucado!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Puxões');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Posts e comentários populares');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Carregar mais vídeos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Carregar mais fotos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'não há mais vídeos para mostrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'não há mais fotos para mostrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Tocou em você');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Dom adicionar com sucesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Erro ao adicionar um presente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Envie um presente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Presente enviado com sucesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'Te mandei um presente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'Meus puxões');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Não há mais postagens para mostrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Ajuda de atalhos de teclado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Vá até a próxima postagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Role até a postagem anterior');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Etiqueta adicionada com sucesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Erro ao adicionar o adesivo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'reagiu ao seu post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Pontos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Meus pontos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'meus ganhos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Ganhe %d pontos por gostar de qualquer postagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Ganhe %d pontos por não gostar de nenhum post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Ganhe %d pontos comentando qualquer post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Ganhe %d pontos imaginando qualquer postagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Ganhe %d pontos criando nova postagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Ganhe %d pontos ao reagir em qualquer postagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Nenhuma reação ainda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Ame');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'Uau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Bravo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'reagiu ao seu comentário');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'reagiu à sua resposta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'reagiu a {user} <a class="main-color" href="{post}"> postagem </a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Comentário relatado com sucesso.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Reportar comentário');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Comentário comentário excluir com sucesso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Páginas sugeridas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Grupos sugeridos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Não verificado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Gostar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Ame');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Uau');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Amigos em comum');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Nenhum amigo em comum encontrado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'viu sua história');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'comentou no seu artigo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Tem certeza de que quer desamor?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Gerenciar Sessões');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Plataforma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Navegador');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Ativo pela última vez');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Configurações de notificação');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Notifique-me quando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'O nome de usuário está na lista negra e não é permitido, por favor, escolha outro nome de usuário.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'O endereço de e-mail está na lista negra e não é permitido, por favor, escolha outro e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'actividades');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'está seguindo {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'tornar-se amigo de {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Permitir que os mecanismos de pesquisa indexem meu perfil e minhas postagens?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Marcar todas as conversas como lidas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Você ainda não terminou sua postagem. Você quer sair sem terminar?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Seus pontos ganhos irão automaticamente para');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Modo noturno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Modo de luz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Atalhos do teclado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Comente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Escreva alguma coisa aqui ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Ver perfil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Quadra');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Criar página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Criar grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Criar Evento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Criar um anúncio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Criar Blog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Todo novo dia é uma chance de mudar sua vida.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Que esta tarde seja leve, abençoada, iluminada, produtiva e feliz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'As noites são a maneira da vida de dizer que você está mais perto de seus sonhos.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Modo dia');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'номер телефона уже существует.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Восстановление по электронной почте');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Восстановление по SMS');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'Номер телефона не найден');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'Недопустимый номер телефона.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Восстановление SMS отправлено успешно');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'операции');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Последние мероприятия');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'В корзину');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Объявления, потраченные ежедневно');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Про системные транзакции');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'Не найдено транзакций');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Предполагаемый охват');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Ваш код подтверждения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'На этом веб-сайте используются файлы cookie, чтобы вы могли получить лучший опыт на нашем веб-сайте.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Понял!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Выучить больше');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'совать');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Вы ткнули');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Ничего не найдено');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Люди, которые ткнули вас');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Откинуть назад');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'тыкат!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'тыкат');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Популярные сообщения и комментарии');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Загрузить видео');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Загрузить больше фотографий');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'Больше нет видео для показа');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'Больше фотографий, чтобы показать');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Толкнул тебя');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Подарок успешно добавлен');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Ошибка при добавлении подарка');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Отправить подарок');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Подарок успешно отправлен');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'отправил вам подарок');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'Мои поры');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Телеграмма');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Нет больше сообщений для отображения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Краткий справочник по клавиатуре');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Перейдите к следующему сообщению');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Перейдите к предыдущему сообщению');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Стикер добавлен успешно');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Ошибка при добавлении наклейки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'отреагировал на ваш пост');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Точки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Мои очки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'мой заработок');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'зарабатывать %d очков, любя любую запись');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'зарабатывать %d очков, не приветствуя любую должность');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'зарабатывать %d указывает, комментируя любую запись');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'зарабатывать %d очков, задаваясь вопросом о любом сообщении');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'зарабатывать %d путем создания нового сообщения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'зарабатывать %d очков, реагируя на любую должность');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Еще нет реакций');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Люблю');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'WoW');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Грустный');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Сердитый');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'отреагировал на ваш комментарий');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'отреагировал на ваш ответ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'отреагировал на {user} <a class="main-color" href="{post}">после</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Комментарий успешно передан.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Сообщить модератору');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Отчет удалён успешно');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Предлагаемые страницы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Рекомендуемые группы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'непроверенный');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'подобно');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Люблю');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Грустный');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Общие друзья');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Общих друзей не найдено');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'посмотрел ваш рассказ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'прокомментировал вашу статью');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Вы уверены, что хотите недобросовестно?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Управление сеансами');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Платформа');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'браузер');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Последнее посещение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Настройки уведомлений');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Уведомить меня, когда');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'Имя пользователя занесено в черный список и не разрешено, выберите другое имя пользователя.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'Адрес электронной почты занесен в черный список и не разрешен, выберите другое электронное письмо.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'мероприятия');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'начал следовать {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'дружить с {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Разрешить поисковым системам индексировать мой профиль и сообщения?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Отметить все разговоры как прочитанные');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Ты еще не закончил свой пост. Вы хотите уйти, не закончив?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Ваши заработанные очки автоматически перейдут на');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Ночной режим');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Режим освещения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Горячие клавиши');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Комментарий');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Напишите что-нибудь здесь.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Просмотреть профиль');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'блок');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Создать страницу');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Создать группу');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Создать событие');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Создать объявление');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Создать блог');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Каждый новый день - это шанс изменить вашу жизнь.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Пусть сегодня днем светлый, благословенный, просвещенный, продуктивный и счастливый.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Вечера - это способ жизни сказать, что вы ближе к своим мечтам.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Дневной режим');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'número de teléfono ya existe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Recuperar por correo electrónico');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Recuperar por SMS');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'El número de teléfono no se encuentra');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'El número telefónico es inválido');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recuperar SMS ha sido enviado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Actas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Últimas actividades');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Última recarga de billetera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Anuncios gastados diariamente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Transacciones del sistema Pro');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'No se encontraron transacciones');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Alcance estimado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Tu código de confirmación es');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Este sitio web utiliza cookies para garantizar que obtenga la mejor experiencia en nuestro sitio web.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Lo tengo !');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Aprende más');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Meter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'Has pinchado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'No se encontraron golpes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Gente que te pinchó');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Empujar hacia atrás');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'meter!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'meter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Publicaciones populares y comentarios');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Cargar más videos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Cargar más fotos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'No más videos para mostrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'No más fotos para mostrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Te pinchó');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Regalo agregado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Error al agregar el regalo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Enviar un regalo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Regalo enviado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'te envió un regalo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'My Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegrama');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'No hay más publicaciones para mostrar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Ayuda contextual de teclado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Desplazarse a la siguiente publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Desplazarse a la publicación anterior');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Adhesivo agregado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Error al agregar la pegatina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'reaccionado a tu publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Puntos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Mis puntos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'Mis Ganancias');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Ganar %d puntos por dar me gusta a cualquier publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Ganar %d puntos por desagradar cualquier publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Ganar %d puntos al comentar cualquier publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Ganar %d puntos al preguntarse cualquier publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Ganar %d puntos al crear una nueva publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Ganar %d puntos al reaccionar en cualquier publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Aún no hay reacciones');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Amor');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'WoW');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Enojado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'reaccionó a tu comentario');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'reaccionado a tu respuesta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'reaccionado a {user} <a class="main-color" href="{post}">enviar</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Comentario reportado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Informar comentario');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Informe borrado con éxito');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Páginas sugeridas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Grupos sugeridos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Inconfirmado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Me gusta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Amor');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Triste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Amigos en común');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'No se encontraron amigos en común');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'visto tu historia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'comentó tu artículo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', '¿Estás seguro de que quieres unirte?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Administrar Sesiones');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Plataforma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Navegador');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Último Activo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Configuración de las notificaciones');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Notifícame cuando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'El nombre de usuario está en la lista negra y no está permitido, elija otro nombre de usuario.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'La dirección de correo electrónico está en la lista negra y no está permitida, elija otro correo electrónico.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'Ocupaciones');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'comenzó a seguir {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'hacerse amigo de {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', '¿Permitir que los motores de búsqueda indexen mi perfil y mis publicaciones?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Marcar todas las conversaciones como leídas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Aún no has terminado tu publicación. ¿Quieres irte sin terminar?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Tus puntos ganados irán automáticamente a');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Modo nocturno');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Modo de luz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Atajos de teclado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Comentario');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Escribe algo aquí ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Ver perfil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Bloquear');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Crear página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Crea un grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Crear evento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Crear anuncio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Blog creativo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Cada nuevo día es una oportunidad para cambiar tu vida.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Que esta tarde sea luz, bendita, iluminada, productiva y feliz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Las noches son la forma en que la vida dice que estás más cerca de tus sueños.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Modo día');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'telefon numarası zaten mevcut.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'E-posta ile kurtar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Sms ile kurtarma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'Telefon numarası bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'Telefon numarası geçersiz veya karakterleri var');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recover SMS başarıyla gönderildi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'işlemler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Son etkinlikler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Cüzdan Toplaması');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Günlük olarak harcanan reklamlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Pro sistem işlemleri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'İşlem bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Tahmini erişim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Onay kodunuz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'Bu web sitesi, web sitemizde en iyi deneyimi yaşamanızı sağlamak için çerezleri kullanır.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Anladım!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Daha fazla bilgi edin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'dürtme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'poked var');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'Poke bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'Seni düren insanlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Geri dürt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Dürttü!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Dürtmeler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Popüler yazılar ve yorumlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Daha fazla video yükle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Daha fazla fotoğraf yükle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'gösterilecek video yok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'gösterilecek başka fotoğraf yok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Seni dürttü');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Hediye başarıyla eklendi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Hediye eklerken hata oluştu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Hediye gönder');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Hediye başarıyla gönderildi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'Sana bir hediye gönderdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'Benim Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telgraf');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'Gösterilecek başka yayın yok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Klavye Kısayolu Yardımı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Bir sonraki gönderiye git');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Önceki yayına git');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Sticker başarıyla eklendi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Çıkartma eklenirken hata oluştu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'yayına tepki gösterdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'makas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'Puanlarım');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'Kazançlarım');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Herhangi bir gönderiyi beğenerek %d puan kazan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Herhangi bir gönderiyi beğenmediğinizde %d puan kazanın');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Herhangi bir gönderiye yorum yaparak %d puan kazan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Herhangi bir gönderiyi merak ederek %d puan kazanın');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Yeni gönderi oluşturarak %d puan kazanın');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Herhangi bir gönderiye yanıt vererek %d puan kazanın');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'Henüz tepki yok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Aşk');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'Vay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Üzgün');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'kızgın');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'Yorumunuza tepki gösterdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'cevabınıza tepki gösterdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', '{user} <a class=\"main-color\" href=\"{post}\">yayına</a> tepki gösterdi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Yorum başarıyla bildirildi.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Yorumu bildir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Yorum raporu başarıyla silinsin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Önerilen sayfalar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Önerilen gruplar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Doğrulanmamış');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Sevmek');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Aşk');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'vay');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Üzgün');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Ortak arkadaşlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'Karşılıklı arkadaş bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'hikayeni inceledi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'makaleniz hakkında yorum yaptı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Arkadaşlık etmek istediğinden emin misin?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Oturumları Yönet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'platform');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Tarayıcı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Son aktif');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Bildirim ayarları');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Ne zaman bana bildir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'Kullanıcı adı kara listede ve izin verilmiyor, lütfen başka bir kullanıcı adı seçin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'E-posta adresi kara listeye alınmış ve izin verilmemiştir, lütfen başka bir e-posta adresi seçin.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'faaliyetler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', '{user} takip ediyor');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', '{user} ile arkadaş olun');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Arama motorlarının profilimi ve yayınlarımı dizine eklemesine izin verilsin mi?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Tüm konuşmaları okundu olarak işaretle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'Mesajınızı henüz bitirmediniz. Bitirmeden ayrılmak ister misin?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Kazanılan puanlarınız otomatik olarak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Gece modu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Işık modu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Klavye kısayolları');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Yorum Yap');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Burada bir şeyler yaz.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'Profili Görüntüle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Blok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Sayfa oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Grup oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Etkinlik oluşturmak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Reklam oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Blog yarat');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Her yeni günde hayatınızı değiştirmek için bir şans.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'Bu öğleden sonra hafif, kutsanmış, aydınlanmış, üretken ve mutlu olabilir.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Akşamlar yaşamın, hayallerinize daha yakın olduğunuzu söyleme biçimidir.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Gündüz modu');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'phone number already exists.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Recover by email');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Recover by SMS');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'The phone number is not found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'The phone number is invalid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recover SMS has been sent successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Transactions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Latest activities');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Wallet Topup');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Ads spent daily');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Pro system transactions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'No transactions found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Estimated reach');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Your confirmation code is');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'This website uses cookies to ensure you get the best experience on our website.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Got it!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Learn more');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Poke');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'You have poked');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'No pokes found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'People who poked you');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Poke back');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Poked!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Popular posts & comments');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Load more videos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Load more photos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'No more videos to show');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'No more photos to show');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Poked you');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Gift added successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Error while adding the gift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Send a gift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Gift sent successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'sent you a gift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'My Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'No more posts to show');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Keyboard shortcut help');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Scroll to the next post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Scroll to the previous post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Sticker added successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Error while adding the sticker');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'reacted to your post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Points');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'My Points');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'My Earnings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Earn %d points by liking any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Earn %d points by disliking any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Earn %d points by commenting any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Earn %d points by wondering any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Earn %d points by creating a new post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Earn %d points by reacting on any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'No reactions yet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Love');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'WoW');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Sad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Angry');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'reacted to your comment');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'reacted to your reply');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'reacted to {user} <a class="main-color" href="{post}">post</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Comment reported successfully.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Report comment');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Report deleted successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Suggested pages');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Suggested groups');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Unverified');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Like');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Love');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Sad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Mutual Friends');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'No mutual friends found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'viewed your story');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'commented on your article');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Are you sure you want to unfriend?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Manage Sessions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Platform');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Browser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Last active');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Notification Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Notify me when');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'The username is blacklisted and not allowed, please choose another username.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'The email address is blacklisted and not allowed, please choose another email.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'Activities');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'started following {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'become friends with {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Allow search engines to index my profile and posts?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Mark all conversations as read');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'You haven\'t finished your post yet. Do you want to leave without finishing?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Your earned points will automatically go to');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Night mode');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Light mode');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Keyboard shortcuts');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Comment');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Write something here..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'View Profile');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Block');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Create Page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Create Group');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Create Event');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Create Ad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Create Blog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Every new day is a chance to change your life.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'May this afternoon be light, blessed, enlightened, productive and happy.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Evenings are life’s way of saying that you are closer to your dreams.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Day mode');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_exists', 'phone number already exists.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_email', 'Recover by email');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recover_password_by_sms', 'Recover by SMS');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phonenumber_not_found', 'The phone number is not found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'phone_invalid_characters', 'The phone number is invalid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'recoversms_sent', 'Recover SMS has been sent successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'transaction_log', 'Transactions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2latest_activities', 'Latest activities');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2wallettopup', 'Wallet Topup');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2adsspentdaily', 'Ads spent daily');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'v2prosystemtransactions', 'Pro system transactions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_transactions_found', 'No transactions found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'estimated_reach', 'Estimated reach');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirmation_code_is', 'Your confirmation code is');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'website_use_cookies', 'This website uses cookies to ensure you get the best experience on our website.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'got_it', 'Got it!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'learn_more1', 'Learn more');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_user', 'Poke');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'you_have_poked', 'You have poked');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_pokes_found', 'No pokes found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'people_who_poked_you', 'People who poked you');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poke_back', 'Poke back');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'txt_poked', 'Poked!');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pokes', 'Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_posts_comments', 'Popular posts & comments');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_videos', 'Load more videos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'load_more_photos', 'Load more photos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_videos_to_show', 'No more videos to show');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_photos_to_show', 'No more photos to show');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'poked_you', 'Poked you');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_added', 'Gift added successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_gift', 'Error while adding the gift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_a_gift', 'Send a gift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gift_sent_succesfully', 'Gift sent successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'send_gift_to_you', 'sent you a gift');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_pokes', 'My Pokes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'telegram', 'Telegram');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_posts_to_show', 'No more posts to show');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_help', 'Keyboard shortcut help');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_j', 'Scroll to the next post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcut_k', 'Scroll to the previous post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sticker_added', 'Sticker added successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'error_while_add_sticker', 'Error while adding the sticker');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_post', 'reacted to your post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'points', 'Points');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_points', 'My Points');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_earnings', 'My Earnings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_like', 'Earn %d points by liking any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_dislike', 'Earn %d points by disliking any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_comment', 'Earn %d points by commenting any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_wonder', 'Earn %d points by wondering any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_create_post', 'Earn %d points by creating a new post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earn_text_reaction', 'Earn %d points by reacting on any post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_reactions', 'No reactions yet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'love', 'Love');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'haha', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'wow', 'WoW');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sad', 'Sad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'angry', 'Angry');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_comment', 'reacted to your comment');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reacted_to_your_replay', 'reacted to your reply');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_reacted_post', 'reacted to {user} <a class="main-color" href="{post}">post</a>.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_reported', 'Comment reported successfully.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'report_comment', 'Report comment');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment_unreported', 'Report deleted successfully');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_pages', 'Suggested pages');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'suggested_groups', 'Suggested groups');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'unverified', 'Unverified');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Like', 'Like');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Love', 'Love');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_HaHa', 'HaHa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Wow', 'Wow');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'reaction_Sad', 'Sad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mutual_friends', 'Mutual Friends');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_mutual_friends', 'No mutual friends found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'viewed_your_story', 'viewed your story');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'commented_on_blog', 'commented on your article');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'are_you_sure_unfriend', 'Are you sure you want to unfriend?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'manage_sessions', 'Manage Sessions');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'platform', 'Platform');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'browser', 'Browser');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'last_active', 'Last active');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notification_settings', 'Notification Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'notify_me_when', 'Notify me when');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'username_is_banned', 'The username is blacklisted and not allowed, please choose another username.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'email_is_banned', 'The email address is blacklisted and not allowed, please choose another email.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activities', 'Activities');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_following', 'started following {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'activity_is_friend', 'become friends with {user}');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'index_my_page_privacy', 'Allow search engines to index my profile and posts?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'mark_all_as_read', 'Mark all conversations as read');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'havent_finished_post', 'You haven\'t finished your post yet. Do you want to leave without finishing?');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earned_points_goto', 'Your earned points will automatically go to');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'night_mode', 'Night mode');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'light_mode', 'Light mode');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'keyboard_shortcuts', 'Keyboard shortcuts');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'comment', 'Comment');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'write_something_here', 'Write something here..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view_profile', 'View Profile');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'block', 'Block');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_page', 'Create Page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_group', 'Create Group');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_event', 'Create Event');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_ad', 'Create Ad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_blog', 'Create Blog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_morning_quote', 'Every new day is a chance to change your life.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_afternoon_quote', 'May this afternoon be light, blessed, enlightened, productive and happy.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'good_evening_quote', 'Evenings are life’s way of saying that you are closer to your dreams.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'day_mode', 'Day mode');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }
    }

    // chmod general config file
    @chmod("./assets/init.php", 0777);

    // chmod libraries
    @chmod("./libraries", 0777);
    @chmod("./libraries/PayPal", 0777);

    $name = md5(microtime()) . '_updated.php';
    rename('update.php', $name);
}
?>
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
      <meta name="viewport" content="width=device-width, initial-scale=1"/>
      <title>Updating WoWonder</title>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
         @import url('https://fonts.googleapis.com/css?family=Roboto:400,500');
         @media print {
            .wo_update_changelog {max-height: none !important; min-height: !important}
            .btn, .hide_print, .setting-well h4 {display:none;}
         }
         * {outline: none !important;}
         body {background: #f3f3f3;font-family: 'Roboto', sans-serif;}
         .light {font-weight: 400;}
         .bold {font-weight: 500;}
         .btn {height: 52px;line-height: 1;font-size: 16px;transition: all 0.3s;border-radius: 2em;font-weight: 500;padding: 0 28px;letter-spacing: .5px;}
         .btn svg {margin-left: 10px;margin-top: -2px;transition: all 0.3s;vertical-align: middle;}
         .btn:hover svg {-webkit-transform: translateX(3px);-moz-transform: translateX(3px);-ms-transform: translateX(3px);-o-transform: translateX(3px);transform: translateX(3px);}
         .btn-main {color: #ffffff;background-color: #a84849;border-color: #a84849;}
         .btn-main:disabled, .btn-main:focus {color: #fff;}
         .btn-main:hover {color: #ffffff;background-color: #c45a5b;border-color: #c45a5b;box-shadow: -2px 2px 14px rgba(168, 72, 73, 0.35);}
         svg {vertical-align: middle;}
         .main {color: #a84849;}
         .wo_update_changelog {
          border: 1px solid #eee;
          padding: 10px !important;
         }
         .content-container {display: -webkit-box; width: 100%;display: -moz-box;display: -ms-flexbox;display: -webkit-flex;display: flex;-webkit-flex-direction: column;flex-direction: column;min-height: 100vh;position: relative;}
         .content-container:before, .content-container:after {-webkit-box-flex: 1;box-flex: 1;-webkit-flex-grow: 1;flex-grow: 1;content: '';display: block;height: 50px;}
         .wo_install_wiz {position: relative;background-color: white;box-shadow: 0 1px 15px 2px rgba(0, 0, 0, 0.1);border-radius: 10px;padding: 20px 30px;border-top: 1px solid rgba(0, 0, 0, 0.04);}
         .wo_install_wiz h2 {margin-top: 10px;margin-bottom: 30px;display: flex;align-items: center;}
         .wo_install_wiz h2 span {margin-left: auto;font-size: 15px;}
         .wo_update_changelog {padding:0;list-style-type: none;margin-bottom: 15px;max-height: 440px;overflow-y: auto; min-height: 440px;}
         .wo_update_changelog li {margin-bottom:7px; max-height: 20px; overflow: hidden;}
         .wo_update_changelog li span {padding: 2px 7px;font-size: 12px;margin-right: 4px;border-radius: 2px;}
         .wo_update_changelog li span.added {background-color: #4CAF50;color: white;}
         .wo_update_changelog li span.changed {background-color: #e62117;color: white;}
         .wo_update_changelog li span.improved {background-color: #9C27B0;color: white;}
         .wo_update_changelog li span.compressed {background-color: #795548;color: white;}
         .wo_update_changelog li span.fixed {background-color: #2196F3;color: white;}
         input.form-control {background-color: #f4f4f4;border: 0;border-radius: 2em;height: 40px;padding: 3px 14px;color: #383838;transition: all 0.2s;}
input.form-control:hover {background-color: #e9e9e9;}
input.form-control:focus {background: #fff;box-shadow: 0 0 0 1.5px #a84849;}
         .empty_state {margin-top: 80px;margin-bottom: 80px;font-weight: 500;color: #6d6d6d;display: block;text-align: center;}
         .checkmark__circle {stroke-dasharray: 166;stroke-dashoffset: 166;stroke-width: 2;stroke-miterlimit: 10;stroke: #7ac142;fill: none;animation: stroke 0.6s cubic-bezier(0.65, 0, 0.45, 1) forwards;}
         .checkmark {width: 80px;height: 80px; border-radius: 50%;display: block;stroke-width: 3;stroke: #fff;stroke-miterlimit: 10;margin: 100px auto 50px;box-shadow: inset 0px 0px 0px #7ac142;animation: fill .4s ease-in-out .4s forwards, scale .3s ease-in-out .9s both;}
         .checkmark__check {transform-origin: 50% 50%;stroke-dasharray: 48;stroke-dashoffset: 48;animation: stroke 0.3s cubic-bezier(0.65, 0, 0.45, 1) 0.8s forwards;}
         @keyframes stroke { 100% {stroke-dashoffset: 0;}}
         @keyframes scale {0%, 100% {transform: none;}  50% {transform: scale3d(1.1, 1.1, 1); }}
         @keyframes fill { 100% {box-shadow: inset 0px 0px 0px 54px #7ac142; }}
      </style>
   </head>
   <body>
      <div class="content-container container">
         <div class="row">
            <div class="col-md-1"></div>
            <div class="col-md-10">
               <div class="wo_install_wiz">
                 <?php if ($updated == false) { ?>
                  <div>
                     <h2 class="light">Update to v2.0 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                           <li><span class="added">Added</span> digitalocean spaces storage.</li>
                           <li><span class="added">Added</span> poke system.</li>
                           <li><span class="added">Added</span> reaction system (Like, Haha, wow, angry, love).</li>
                           <li><span class="added">Added</span> gift system, users can send each other gifts.</li>
                           <li><span class="added">Added</span> statics and charts to daily ads views / clicks.</li>
                           <li><span class="added">Added</span> Infobip to SMS providers.</li>
                           <li><span class="added">Added</span> stripe to wallet system like on go pro page.</li>
                           <li><span class="added">Added</span> password recovery by phone number.</li>
                           <li><span class="added">Added</span> estimated user reach on create ads page.</li>
                           <li><span class="added">Added</span> bitcoin (coinpayments) payment method for ads and pro system.</li>
                           <li><span class="added">Added</span> Payment Transactions history for wallet topups and pro system, with the ability to turn this feature on or off.</li>
                           <li><span class="added">Added</span> the option to change the phone number from settings page.</li>
                           <li><span class="added">Added</span> popular posts page.</li>
                           <li><span class="added">Added</span> photos & videos sections to profile.</li>
                           <li><span class="added">Added</span> End video chat button.</li>
                           <li><span class="added">Added</span> ability to use percentage in the affiliate system.</li>
                           <li><span class="added">Added</span> telegram to share options.</li>
                           <li><span class="added">Added</span> admin can disable and enable social share links.</li>
                           <li><span class="added">Added</span> more emojies.</li>
                           <li><span class="added">Added</span> shortkey for posts, clicking "j" to scroll to next post.</li>
                           <li><span class="added">Added</span> points system, user can earn points for posts, comments, likes, dislikes, wonders, and reactions. (User can spend points on pro or ads, or he can requst them as dollars).</li>
                           <li><span class="added">Added</span> stickers to chat and messages.</li>
                           <li><span class="added">Added</span> rotate system for images.</li>
                           <li><span class="added">Added</span> Manage developer applications from admin panel.</li>
                           <li><span class="added">Added</span> Report comment to admin.</li>
                           <li><span class="added">Added</span> Advanced search system for custom fields.</li>
                           <li><span class="added">Added</span> Added filter by age, and verfied to the search system.</li>
                           <li><span class="added">Added</span> suggested groups / pages in my groups and my pages.</li>
                           <li><span class="added">Added</span> search system in blog.</li>
                           <li><span class="added">Added</span> auto friend system (Auto follow / friend with new registred users).</li>
                           <li><span class="added">Added</span> the ability to view mutual friends from the profile (friends system).</li>
                           <li><span class="added">Added</span> the ability to know who viewed your stories via notifications.</li>
                           <li><span class="added">Added</span> ReCaptcha check to forgot password form (secuirty).</li>
                           <li><span class="added">Added</span> blog comment notifications.</li>
                           <li><span class="added">Added</span> Email notification when the user's account was deleted.</li>
                           <li><span class="added">Added</span> a warning message before unfriending someone.</li>
                           <li><span class="added">Added</span> session manager (users can view / manage browser / platforms where they are logged in).</li>
                           <li><span class="added">Added</span> notification management system (Users can choose what kind of notifications they want to get).</li>
                           <li><span class="added">Added</span> the ability to add followers to a specific user using IDs, in Admin > Users (Enabled just in follow system).</li>
                           <li><span class="added">Added</span> blacklist system, admin can blacklist emails, usernames, and IP addresses.</li>
                           <li><span class="added">Added</span> activities system, user can view his own activities from his profile (like, dislike, react, wonder, comment, reply, follow, friends).</li>
                           <li><span class="added">Added</span> the ability for a user to choose if their profile should be found/indexed by search engines.</li>
                           <li><span class="added">Added</span> watermark system for posts images (Image is combind to the image).</li>
                           <li><span class="added">Added</span> possibility to save how you want to post (only me, everyone, friends) after refreshing the page.</li>
                           <li><span class="added">Added</span> “MarketPlace”, “Pages” and “Groups” are searchable on google.</li>
                           <li><span class="added">Added</span> HTML editor to announcement textarea in admin panel.</li>
                           <li><span class="added">Added</span> Button to allow users to mark all their conversation as read.</li>
                           <li><span class="added">Added</span> discard option for posts before visiting another page.</li>
                           <li><span class="added">Added</span> find products nearby.</li>
                           <li><span class="added">Added</span> fake users generator in admin panel.</li>
                           <li><span class="added">Added</span> Night / Light mode [Enable / Disable / Default]. </li>
                           <li><span class="changed">Changed</span> Familly list are displayed as follow list (Design).</li>
                           <li><span class="changed">Changed</span> Marketplace desgin.</li>
                           <li><span class="changed">Changed</span> Installation wizard</li>
                           <li><span class="improved">Improved</span> load speed.</li>
                           <li><span class="compressed">Compressed</span> JS files to few less files.</li>
                           <li><span class="fixed">Fixed</span> bugs on API.</li>
                           <li><span class="fixed">Fixed</span> major bugs in the script. (+10)</li>
                           <li><span class="fixed">Fixed</span> 3 security issues.</li>
                        </ul>
                        <p class="hide_print">Note: The update process might take few minutes.</p>
                        <p class="hide_print">Important: If you got any fail queries, please copy them, open a support ticket and send us the details.</p>
                        <p class="hide_print">Most of the features are disabled by default, you can enable them from Admin > Site Settings > Manage Site Features, reaction can be enabled from Settings > Site Sttings.</p><br>
                        <p class="hide_print">Please enter your valid purchase code:</p>
                        <input type="text" id="input_code" class="form-control" placeholder="Your Envato purchase code" style="padding: 10px; width: 50%;"><br>

                        <br>
                             <button class="pull-right btn btn-default" onclick="window.print();">Share Log</button>
                             <button type="button" class="btn btn-main" id="button-update" disabled>
                             Update 
                             <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18">
                                <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path>
                             </svg>
                          </button>
                     </div>
                     <?php }?>
                     <?php if ($updated == true) { ?>
                      <div>
                        <div class="empty_state">
                           <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 52 52">
                              <circle class="checkmark__circle" cx="26" cy="26" r="25" fill="none"/>
                              <path class="checkmark__check" fill="none" d="M14.1 27.2l7.1 7.2 16.7-16.8"/>
                           </svg>
                           <p>Congratulations, you have successfully updated your site. Thanks for choosing WoWonder.</p>
                           <br>
                           <a href="<?php echo $wo['config']['site_url'] ?>" class="btn btn-main" style="line-height:50px;">Home</a>
                        </div>
                     </div>
                     <?php }?>
                  </div>
               </div>
            </div>
            <div class="col-md-1"></div>
         </div>
      </div>
   </body>
</html>
<script>  
var queries = [
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'transaction_log', 'yes'), (NULL, 'coinpayments_secret', ''), (NULL, 'coinpayments_id', ''), (NULL, 'infobip_username', ''), (NULL, 'infobip_password', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'updatev2', 'done')",
    "CREATE TABLE IF NOT EXISTS `Wo_UserAds_Data` ( `id` int(11) NOT NULL,`user_id` int(11) NOT NULL DEFAULT '0', `ad_id` int(11) NOT NULL DEFAULT '0', `clicks` int(15) NOT NULL DEFAULT '0',  `views` int(15) NOT NULL DEFAULT '0', `spend` float unsigned NOT NULL DEFAULT '0', `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_UserAds_Data` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);",
    "ALTER TABLE `Wo_UserAds_Data` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `Wo_Payment_Transactions` (`id` int(11) unsigned NOT NULL, `userid` int(11) unsigned NOT NULL, `kind` varchar(100) NOT NULL, `amount` decimal(11,0) unsigned NOT NULL, `transaction_dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP, `notes` text NOT NULL) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Payment_Transactions` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `Wo_Payment_Transactions` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'amount_percent_ref', '0'), (NULL, 'gift_system', '0');",
    "CREATE TABLE IF NOT EXISTS `Wo_Pokes` ( `id` int(11) NOT NULL, `received_user_id` int(11) NOT NULL DEFAULT '0', `send_user_id` int(11) NOT NULL DEFAULT '0', `dt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Pokes` ADD PRIMARY KEY (`id`), ADD KEY `received_user_id` (`received_user_id`), ADD KEY `user_id` (`send_user_id`);",
    "ALTER TABLE `Wo_Pokes` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `Wo_Gifts` ( `id` int(11) unsigned NOT NULL, `name` varchar(250) DEFAULT NULL, `media_file` varchar(250) NOT NULL, `time` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Gifts` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `Wo_Gifts` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `Wo_User_Gifts` ( `id` int(11) NOT NULL, `from` int(11) NOT NULL DEFAULT '0', `to` int(11) NOT NULL DEFAULT '0', `gift_id` int(11) NOT NULL DEFAULT '0', `time` int(11) DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_User_Gifts` ADD PRIMARY KEY (`id`), ADD KEY `from` (`from`), ADD KEY `to` (`to`), ADD KEY `gift_id` (`gift_id`);",
    "ALTER TABLE `Wo_User_Gifts` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'social_share_twitter', '1'), (NULL, 'social_share_google', '1'), (NULL, 'social_share_facebook', '1'), (NULL, 'social_share_whatsup', '1'), (NULL, 'social_share_pinterest', '1'), (NULL, 'social_share_linkedin', '1'), (NULL, 'social_share_telegram', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'stickers_system', '0'), (NULL, 'dollar_to_point_cost', '1000'), (NULL, 'comments_point', '10'), (NULL, 'likes_point', '5'), (NULL, 'dislikes_point', '2'), (NULL, 'wonders_point', '3'), (NULL, 'reaction_point', '5'), (NULL, 'createpost_point', '15'), (NULL, 'point_allow_withdrawal', '0'), (NULL, 'sticky_video_player', '0');",
    "ALTER TABLE `Wo_Users` ADD `points` INT(11) UNSIGNED NOT NULL DEFAULT '0' ;",
    "ALTER TABLE `Wo_Users` ADD INDEX(`points`);",
    "ALTER TABLE `Wo_Notifications` ADD `comment_id` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `post_id`;",
    "ALTER TABLE `Wo_Notifications` ADD INDEX (`comment_id`) COMMENT '';",
    "ALTER TABLE `Wo_Notifications` ADD `reply_id` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `post_id`;",
    "ALTER TABLE `Wo_Notifications` ADD INDEX (`reply_id`) COMMENT '';",
    "ALTER TABLE `Wo_Activities` ADD `comment_id` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `post_id`;",
    "ALTER TABLE `Wo_Activities` ADD INDEX (`comment_id`) COMMENT '';",
    "ALTER TABLE `Wo_Activities` ADD `reply_id` INT(11) UNSIGNED NULL DEFAULT '0' AFTER `post_id`;",
    "ALTER TABLE `Wo_Activities` ADD INDEX (`reply_id`) COMMENT '';",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'point_level_system', '0');",
    "CREATE TABLE IF NOT EXISTS `Wo_Stickers` ( `id` int(11) unsigned NOT NULL, `name` varchar(250) DEFAULT NULL, `media_file` varchar(250) NOT NULL, `time` int(11) NOT NULL DEFAULT '0') ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Stickers` ADD PRIMARY KEY (`id`);",
    "ALTER TABLE `Wo_Stickers` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "CREATE TABLE IF NOT EXISTS `Wo_Reactions` (  `id` int(11) NOT NULL, `user_id` int(11) unsigned NOT NULL DEFAULT '0', `post_id` int(11) unsigned DEFAULT '0', `comment_id` int(11) unsigned DEFAULT '0',  `replay_id` int(11) unsigned DEFAULT '0',  `reaction` varchar(50) DEFAULT NULL) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_Reactions` ADD PRIMARY KEY (`id`), ADD KEY `post_id` (`post_id`), ADD KEY `user_id` (`user_id`), ADD KEY `idx_reaction` (`reaction`);",
    "ALTER TABLE `Wo_Reactions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'comment_reports', '1');",
    "ALTER TABLE `Wo_Reports` ADD `comment_id` INT(15) UNSIGNED NOT NULL DEFAULT '0' AFTER `post_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'popular_posts', '1');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'auto_friend_users', '');",
    "ALTER TABLE `Wo_Notifications` ADD `blog_id` INT NOT NULL DEFAULT '0' AFTER `thread_id`, ADD `story_id` INT NOT NULL DEFAULT '0' AFTER `blog_id`, ADD INDEX (`blog_id`), ADD INDEX (`story_id`);",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'spaces_key', ''), (NULL, 'spaces_secret', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'space_name', ''), (NULL, 'space_region', '');",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'spaces', '0');",
    "ALTER TABLE `Wo_Posts` ADD `cache` INT NOT NULL DEFAULT '0' AFTER `parent_id`;",
    "ALTER TABLE `Wo_AppsSessions` ADD `platform_details` TEXT NULL DEFAULT NULL AFTER `platform`;",
    "ALTER TABLE `Wo_Users` ADD `notification_settings` VARCHAR(400) NOT NULL DEFAULT 'a:11:{s:7:&quot;e_liked&quot;;i:1;s:8:&quot;e_shared&quot;;i:1;s:10:&quot;e_wondered&quot;;i:0;s:11:&quot;e_commented&quot;;i:1;s:10:&quot;e_followed&quot;;i:1;s:10:&quot;e_accepted&quot;;i:1;s:11:&quot;e_mentioned&quot;;i:1;s:14:&quot;e_joined_group&quot;;i:1;s:12:&quot;e_liked_page&quot;;i:1;s:9:&quot;e_visited&quot;;i:1;s:19:&quot;e_profile_wall_post&quot;;i:1;}' AFTER `e_last_notif`;",
    "ALTER TABLE `Wo_Users` ADD `last_follow_id` INT NOT NULL DEFAULT '0' AFTER `points`;",
    "ALTER TABLE `Wo_Activities` ADD `follow_id` INT NOT NULL DEFAULT '0' AFTER `comment_id`;",
    "ALTER TABLE `Wo_Users` ADD `share_my_data` INT NOT NULL DEFAULT '1' AFTER `last_follow_id`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'watermark', '0');",
    "ALTER TABLE `Wo_Products` ADD `lng` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `currency`, ADD `lat` VARCHAR(100) NOT NULL DEFAULT '0' AFTER `lng`;",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'phonenumber_exists', 'phone number already exists.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'recover_password_by_email', 'Recover by email');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'recover_password_by_sms', 'Recover by sms');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'phonenumber_not_found', 'The phone number is not found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'phone_invalid_characters', 'The phone number is invalid');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'recoversms_sent', 'Recover SMS has been sent successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'transaction_log', 'Transactions');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'v2latest_activities', 'Latest activities');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'v2wallettopup', 'Wallet Topup');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'v2adsspentdaily', 'Ads spent daily');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'v2prosystemtransactions', 'Pro system transactions');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_transactions_found', 'No transactions found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'estimated_reach', 'Estimated reach');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'confirmation_code_is', 'Your confirmation code is');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'website_use_cookies', 'This website uses cookies to ensure you get the best experience on our website.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'got_it', 'Got it!');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'learn_more1', 'Learn more');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'poke_user', 'Poke');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'you_have_poked', 'You have poked');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_pokes_found', 'No pokes found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'people_who_poked_you', 'People who poked you');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'poke_back', 'Poke back');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'txt_poked', 'Poked!');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'pokes', 'Pokes');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'popular_posts_comments', 'Popular posts & comments');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'load_more_videos', 'Load more videos');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'load_more_photos', 'Load more photos');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_more_videos_to_show', 'No more videos to show');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_more_photos_to_show', 'No more photos to show');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'poked_you', 'Poked you');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'gift_added', 'Gift added successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'error_while_add_gift', 'Error while add a gift');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'send_a_gift', 'Send a gift');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'gift_sent_succesfully', 'Gift sent successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'send_gift_to_you', 'sent you a gift');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'my_pokes', 'My Pokes');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'telegram', 'Telegram');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_more_posts_to_show', 'No more posts to show');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'keyboard_shortcut_help', 'Keyboard Shortcut Help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'keyboard_shortcut_j', 'Scroll to the next post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'keyboard_shortcut_k', 'Scroll to the previous post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'sticker_added', 'Sticker added successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'error_while_add_sticker', 'Error while adding the sticker');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'reacted_to_your_post', 'reacted to your post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'points', 'Points');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'my_points', 'My Points');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'my_earnings', 'My Earnings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earn_text_like', 'Earn %d points by liking any post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earn_text_dislike', 'Earn %d points by disliking any post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earn_text_comment', 'Earn %d points by commenting any post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earn_text_wonder', 'Earn %d points by wondering any post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earn_text_create_post', 'Earn %d points by create new post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earn_text_reaction', 'Earn %d points by reacting on any post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_reactions', 'No reactions yet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'love', 'Love');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'haha', 'HaHa');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'wow', 'WoW');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'sad', 'Sad');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'angry', 'Angry');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'reacted_to_your_comment', 'reacted to your comment');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'reacted_to_your_replay', 'reacted to your reply');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'activity_reacted_post', 'reacted to {user} <a class=\"main-color\" href=\"{post}\">post</a>.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'comment_reported', 'Comment reported successfully.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'report_comment', 'Report comment');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'comment_unreported', 'Comment report delete successfully');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'suggested_pages', 'Suggested pages');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'suggested_groups', 'Suggested groups');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'unverified', 'Unverified');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'mutual_friends', 'Mutual Friends');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'no_mutual_friends', 'No mutual friends found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'viewed_your_story', 'viewed your story');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'commented_on_blog', 'commented on your article');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'are_you_sure_unfriend', 'Are you sure you want to unfriend?');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'manage_sessions', 'Manage Sessions');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'platform', 'Platform');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'browser', 'Browser');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'last_active', 'Last active');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'notification_settings', 'Notification Settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'notify_me_when', 'Notify me when');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'username_is_banned', 'The username is blacklisted and not allowed, please choose another username.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'email_is_banned', 'The email address is blacklisted and not allowed, please choose another email.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'activities', 'Activities');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'activity_is_following', 'is following {user}');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'activity_is_friend', 'become friends with {user}');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'index_my_page_privacy', 'Allow search engines to index my profile and posts?');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'mark_all_as_read', 'Mark all conversations as read');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'havent_finished_post', '<?php echo Wo_Secure("You haven't finished your post yet. Do you want to leave without finishing?");?>');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'earned_points_goto', 'Your earned points will automatically go to');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'night_mode', 'Night mode');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'light_mode', 'Light mode');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'keyboard_shortcuts', 'Keyboard shortcuts');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'comment', 'Comment');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'write_something_here', 'Write something here..');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'view_profile', 'View Profile');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'block', 'Block');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'create_page', 'Create Page');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'create_group', 'Create Group');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'create_event', 'Create Event');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'create_ad', 'Create Ad');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'create_blog', 'Create Blog');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'good_morning_quote', 'Every new day is a chance to change your life.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'good_afternoon_quote', 'May this afternoon be light, blessed, enlightened, productive and happy.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'good_evening_quote', 'Evenings are life’s way of saying that you are closer to your dreams.');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`, `english`) VALUES (NULL, 'day_mode', 'Day mode');",
];

$('#input_code').bind("paste keyup input propertychange", function(e) {
    if (isPurchaseCode($(this).val())) {
        $('#button-update').removeAttr('disabled');
    } else {
        $('#button-update').attr('disabled', 'true');
    }
});

function isPurchaseCode(str) {
    var patt = new RegExp("(.*)-(.*)-(.*)-(.*)-(.*)");
    var res = patt.test(str);
    if (res) {
        return true;
    }
    return false;
}

$(document).on('click', '#button-update', function(event) {
    if ($('body').attr('data-update') == 'true') {
        window.location.href = '<?php echo $wo['config']['site_url']?>';
        return false;
    }
    $(this).attr('disabled', true);
    var PurchaseCode = $('#input_code').val();
    $.post('?check', {code: PurchaseCode}, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').html('');
            $('.wo_update_changelog').css({
                background: '#1e2321',
                color: '#fff'
            });
            $('.setting-well h4').text('Updating..');
            $(this).attr('disabled', true);
            RunQuery();
        } else {
            $(this).removeAttr('disabled');
            alert(data.error);
        }
    });
});

var queriesLength = queries.length;
var query = queries[0];
var count = 0;
function b64EncodeUnicode(str) {
    // first we use encodeURIComponent to get percent-encoded UTF-8,
    // then we convert the percent encodings into raw bytes which
    // can be fed into btoa.
    return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
        function toSolidBytes(match, p1) {
            return String.fromCharCode('0x' + p1);
    }));
}
function RunQuery() {
    var query = queries[count];
    $.post('?update', {
        query: b64EncodeUnicode(query)
    }, function(data, textStatus, xhr) {
        if (data.status == 200) {
            $('.wo_update_changelog').append('<li><span class="added">SUCCESS</span> ~$ mysql > ' + query + '</li>');
        } else {
            $('.wo_update_changelog').append('<li><span class="changed">FAILED</span> ~$ mysql > ' + query + '</li>');
        }
        count = count + 1;
        if (queriesLength > count) {
            setTimeout(function() {
                RunQuery();
            }, 100);
        } else {
            $('.wo_update_changelog').append('<li><span class="added">Updating Langauges</span> ~$ languages.sh, Please wait, this might take some time..</li>');
            $.post('?run_lang', {
                update_langs: 'true'
            }, function(data, textStatus, xhr) {
              $('.wo_update_changelog').append('<li><span class="fixed">Finished!</span> ~$ Congratulations! you have successfully updated your site. Thanks for choosing WoWonder.</li>');
              $('.setting-well h4').text('Update Log');
              $('#button-update').html('Home <svg viewBox="0 0 19 14" xmlns="http://www.w3.org/2000/svg" width="18" height="18"> <path fill="currentColor" d="M18.6 6.9v-.5l-6-6c-.3-.3-.9-.3-1.2 0-.3.3-.3.9 0 1.2l5 5H1c-.5 0-.9.4-.9.9s.4.8.9.8h14.4l-4 4.1c-.3.3-.3.9 0 1.2.2.2.4.2.6.2.2 0 .4-.1.6-.2l5.2-5.2h.2c.5 0 .8-.4.8-.8 0-.3 0-.5-.2-.7z"></path> </svg>');
              $('#button-update').attr('disabled', false);
              $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
              $('body').attr('data-update', 'true');
            });
        }
        $(".wo_update_changelog").scrollTop($(".wo_update_changelog")[0].scrollHeight);
    });
}
</script>