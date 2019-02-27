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
        $check = json_decode($file, true);
    } else {
        $check = array(
            'status' => 'SUCCESS',
            'url' => $siteurl,
            'code' => $check
        );
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
    } else {
        $data['status'] = 400;
        $data['error']  = $code['ERROR_NAME']; //'Invalid or expired purchase code, or this purchase code is not allowed to be installed on this domain, if you think you get this message by mistake, please contact us.';
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
        $data['error']  = mysqli_error($sqlConnect);
    }
    header("Content-type: application/json");
    echo json_encode($data);
    exit();
}
if (!empty($_POST['update_langs'])) {
    $query = mysqli_query($sqlConnect, "UPDATE `Wo_Config` SET `value` = '" . time() . "' WHERE `name` = 'last_update'");
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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'حملاتي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'محفظتى');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'احصائيات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'اختر الجمهور');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'إطلاق الإعلان');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'تدفع عن كل نقرة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'ادفع لكل انطباع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'استكشاف أحدث المقالات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'البحث عن المقالات ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'المؤلفون');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'تغذية الأخبار');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'مكالمة فيديو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'فتح في علامة تبويب جديدة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'غير اللون');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'صورة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'صوت');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'ملصقات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'توقف');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'دعنا نساعدك في حل مشكلتك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'معلومات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'وجدت المستخدمين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'يلعب المستخدمون');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'العاب شعبية');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'ماذا يحدث');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'خصم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'اختر خطتك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'طرق الدفع الموثوقة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'المدفوعات الآمنة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'إعدادات المجموعة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'إعدادات الصفحة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'إعدادات المستخدم');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'الأمان');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'أرباح');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'فتح الأصلي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'لا توجد تعليقات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'مسح المحادثة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'صور');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'المواضيع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'نوع البحث');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'قسم البحث');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'شاهد الآن');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'منتج جديد');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'آخر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'سعر منخفض');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'سعر مرتفع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'رأي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'يشترى');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'تواصل اجتماعي');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'تخطى');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'اختر صورة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'تحميل الصور');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'إنشاء استطلاع');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'رفع فيديو');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'إنشاء منشور');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'سجل صوت');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'مشاعر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'بيع المنتج');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'تحميل الملفات');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'قصص');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'خلق قصة جديدة');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'لا مزيد من وجهات النظر');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'ما الذي يجري');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Mijn campagnes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Mijn portemonnee');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Stats');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Kies Doelgroep');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Advertentie lanceren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Betaal per klik');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Betaal per indruk');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Ontdek de nieuwste artikelen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Zoeken naar artikelen ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'auteurs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Nieuwsfeed');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Video-oproep');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Openen in nieuw tabblad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Verander kleur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Beeld');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Stem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'stickers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Hou op');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Laat ons u helpen met het oplossen van uw probleem.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'info');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'gebruikers gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'gebruikers spelen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Populaire spellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Wat is er gaande');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Korting');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Kies je plan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Betrouwbare betaalmethoden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Veilige betalingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Groepsinstellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Pagina-instellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Gebruikersinstellingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Veiligheid');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'verdiensten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Open het origineel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Geen reacties gevonden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Verwijder gesprek');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Afbeeldingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Onderwerpen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Zoektype');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Zoek sectie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Kijk nu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Nieuw product');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Laatste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Prijs laag');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Prijs hoog');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Uitzicht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Kopen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Gemeenschap');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Overspringen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Kies Afbeelding');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'upload afbeeldingen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Maak peiling');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Upload video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Maak bericht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Spraak opnemen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Gevoelens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Verkoop product');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Upload bestanden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'verhalen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Maak een nieuw verhaal');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Geen zicht meer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Wat is er aan de hand');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Mes campagnes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Mon portefeuille');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Statistiques');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Choisissez l\'audience');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Annonce de lancement');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Payer avec un clic');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Pay Per Impression');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Explorez les derniers articles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Rechercher des articles ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Auteurs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Fil d\'actualité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Appel vidéo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Ouvrir dans un nouvel onglet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Changer de couleur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Image');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Voix');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Des autocollants');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Arrêtez');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Laissez-nous vous aider à résoudre votre problème.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Info');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'utilisateurs trouvés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'utilisateurs jouant');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Jeux populaires');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Que ce passe-t-il');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Remise');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Choisissez votre plan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Modes de paiement approuvés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Paiements sécurisés');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Paramètres de groupe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Paramètres de page');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Paramètres utilisateur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Sécurité');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Gains');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Ouvrir l\'original');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Aucun commentaire trouvé');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Supprimer la conversation');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Images');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Les sujets');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Type de recherche');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Section de recherche');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Regarde maintenant');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Nouveau produit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Dernier');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Prix ​​bas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Prix ​​haut');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Vue');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Acheter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Communauté');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Sauter');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Choisir une image');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Importer des images');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Créer un sondage');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Télécharger une video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Créer un post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Enregistrer la voix');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Sentiments');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Vendre un produit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Télécharger des fichiers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Histoires');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Créer une nouvelle histoire');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Pas plus de vues');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Que se passe-t-il');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Meine Kampagnen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Mein Geldbeutel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Statistiken');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Wählen Sie Zielgruppe');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Anzeige starten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Pay Per Click');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Pay Per Impression');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Entdecken Sie die neuesten Artikel');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Artikel suchen ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Autoren');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Neuigkeiten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Videoanruf');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'In neuem Tab öffnen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Farbe ändern');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Bild');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Stimme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Aufkleber');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Halt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Lassen Sie sich von uns bei der Lösung Ihres Problems unterstützen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Info');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'Benutzer gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'spielende Benutzer');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Beliebte Spiele');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Was ist los');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Rabatt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Wähle deinen Plan aus');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Vertrauenswürdige Zahlungsmethoden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Sichere Zahlungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Gruppeneinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Seiteneinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Benutzereinstellungen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Sicherheit');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Verdienste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Original öffnen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Keine Kommentare gefunden');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Konversation löschen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Bilder');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Themen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Suchtyp');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Suchbereich');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Schau jetzt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Neues Produkt');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Neueste');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Preis niedrig');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Preis hoch');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Aussicht');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Kaufen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Gemeinschaft');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Überspringen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Bild auswählen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Bilder hochladen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Umfrage erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Video hochladen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Beitrag erstellen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Aufnahmestimme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Gefühle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Produkt verkaufen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Daten hochladen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Geschichten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Erstelle eine neue Geschichte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Keine weiteren Ansichten');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Was ist los');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Le mie campagne');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Il mio portafoglio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Statistiche');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Scegli il pubblico');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Avvia annuncio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Pay per click');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Paga per impressione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Esplora gli ultimi articoli');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Cerca articoli ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'autori');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Notizie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Video chiamata');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Apri in una nuova scheda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Cambia colore');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Immagine');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Voce');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Adesivi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Stop');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Lascia che ti aiutiamo a risolvere il tuo problema.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Informazioni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'utenti trovati');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'utenti che giocano');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Giochi popolari');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Cosa sta succedendo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Sconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Scegli il tuo piano');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Metodi di pagamento affidabili');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Pagamenti sicuri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Impostazioni di gruppo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Impostazioni della pagina');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Impostazioni utente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Sicurezza');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'guadagni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Apri originale');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Nessun commento trovato');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Cancella la conversazione');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'immagini');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Temi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Tipo di ricerca');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Sezione di ricerca');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Guarda ora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Nuovo prodotto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Più recente');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Prezzo basso');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Prezzo alto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'vista');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Acquistare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Comunità');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Salta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Scegli immagine');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Carica immagini');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Crea sondaggio');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Carica video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Crea un post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Registra la voce');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'sentimenti');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Vendere il prodotto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Caricare files');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Storie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Crea una nuova storia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Niente più visualizzazioni');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Cosa sta succedendo');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Minhas Campanhas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Minha carteira');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Estatísticas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Escolher Audiência');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Anúncio de lançamento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Pago por clique');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Pay Per Impression');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Explore os artigos mais recentes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Procure por artigos ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Autores');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Notícias');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Video chamada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Abrir em nova aba');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Mudar cor');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Imagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Voz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Adesivos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Pare');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Deixe-nos ajudar você a resolver seu problema.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Info');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'usuários encontrados');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'usuários jogando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Jogos Populares');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'O que está acontecendo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Desconto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Escolha seu plano');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Métodos de pagamento confiáveis');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Pagamentos Seguros');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Configurações de Grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Configurações de página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Configurações do usuário');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Segurança');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Ganhos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Abrir original');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Nenhum comentário encontrado');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Apagar conversa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Imagens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Tópicos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Tipo de pesquisa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Seção de pesquisa');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Assista agora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Novo produto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Mais recentes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Preço baixo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Preço alto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Visão');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Comprar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Comunidade');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Pular');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Escolher imagem');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Enviar imagens');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Criar enquete');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Envio vídeo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Criar post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Gravar voz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Sentimentos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Vender produto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Fazer upload de arquivos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Histórias');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Crie uma nova história');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Não há mais vistas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'O que está acontecendo');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Мои Кампании');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Мой бумажник');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Статистика');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Выберите аудиторию');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Запустить объявление');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Оплата за клик');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Оплата за показ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Ознакомьтесь с последними статьями');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Поиск статей ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Авторы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Новостная лента');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Видеозвонок');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Открыть в новой вкладке');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Сменить цвет');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Образ');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'голос');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Наклейки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Стоп');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Позвольте нам помочь вам решить вашу проблему.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Информация');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'пользователи нашли');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'пользователи играют');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Популярные игры');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Что происходит');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'скидка');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Выберите свой план');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Доверенные способы оплаты');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Безопасные платежи');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Настройки группы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Настройки страницы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Пользовательские настройки');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Безопасность');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'прибыль');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Открыть оригинал');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Комментариев не найдено');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Удалить беседу');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Изображений');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'темы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Тип поиска');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Раздел поиска');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Смотри');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Новый продукт');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Самый последний');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Низкая цена');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Высокая цена');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Посмотреть');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'купить');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'сообщество');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Пропускать');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Выберите изображение');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'загрузить изображения');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Создать опрос');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Загрузить видео');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Создать пост');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Запись голоса');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Чувства');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Продать товар');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Загрузить файлы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Рассказы');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Создать новую историю');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Нет больше просмотров');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Что здесь происходит');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Mis campañas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Mi billetera');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Estadísticas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Elegir audiencia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Anuncio de lanzamiento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Pago por clic');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Pago por impresión');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Explora los últimos artículos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Búsqueda de artículos ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Autores');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Noticias');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Videollamada');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Abrir en una pestaña nueva');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Cambiar el color');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Imagen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Voz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Pegatinas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Detener');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Permítanos ayudarle a resolver su problema.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Información');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'usuarios encontrados');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'usuarios jugando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Juegos populares');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Qué esta pasando');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Descuento');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Elige tu plan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Métodos de pago de confianza');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Pagos seguros');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Ajustes de grupo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Configuración de página');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Ajustes de usuario');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Seguridad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Ganancias');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Abrir original');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'No se encontraron comentarios');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Eliminar la conversación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Imágenes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Los temas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Tipo de búsqueda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Sección de búsqueda');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Ver ahora');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Nuevo producto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Último');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Precio bajo');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Precio alto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Ver');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Comprar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Comunidad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Omitir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Elegir imagen');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Subir imagenes');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Crear encuesta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Subir video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Crear publicación');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Grabar voz');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Sentimientos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Vender producto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Subir archivos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Cuentos');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Crear nueva historia');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'No mas vistas');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Que esta pasando');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'Kampanyalarım');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'Cüzdanım');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'İstatistikleri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Kitle Seç');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Reklamı Başlat');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Tıklama başına ödeme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Gösterim Başına Ödeme');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'En son makaleleri keşfedin');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Makaleleri arayın ..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Yazarlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'Haber akışı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Görüntülü arama');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Yeni sekmede aç');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Rengi değiştir');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'görüntü');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'ses');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Çıkartma');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'durdurmak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Sorununuzu çözmenize yardımcı olalım.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Bilgi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'kullanıcılar bulundu');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'oynayan kullanıcılar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Popüler Oyunlar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'Ne oluyor');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'İndirim');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Planını Seç');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Güvenilir ödeme yöntemleri');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Güvenli ödemeler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Grup ayarları');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Sayfa Ayarları');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'Kullanıcı ayarları');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Güvenlik');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Kazanç');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Orijinali aç');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'Yorum bulunamadı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Konuşmayı sil');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Görüntüler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Başlıklar');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Arama Tipi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Arama bölümü');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'İzle şimdi');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'Yeni ürün');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'son');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Fiyat Düşük');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Fiyat yüksek');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'Görünüm');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'satın almak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Topluluk');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'atlamak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Resim Seç');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Resim Yükle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Anket Yarat');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Video yükle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Gönderi oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Ses kaydı');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'duygular');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Ürün satmak');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Dosyaları yükle');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Hikayeler');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Yeni hikaye oluştur');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'Başka görüntü yok');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'Ne oluyor');
        } else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'My Campaigns');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'My Wallet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Stats');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Choose Audience');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Launch Ad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Pay Per Click');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Pay Per Impression');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Explore the latest articles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Search for articles..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Authors');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'News Feed');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Video call');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Open in new tab');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Change color');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Image');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Voice');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Stickers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Stop');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Let us help you solve your issue.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Info');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'users found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'users playing');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Popular Games');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'What\'s happening');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Discount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Pick your Plan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Trusted payment methods');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Secure payments');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Group Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Page Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'User Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Security');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Earnings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Open original');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'No comments found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Delete Conversation');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Images');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Topics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Search type');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Search section');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Watch Now');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'New Product');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Latest');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Price Low');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Price High');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'View');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Buy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Community');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Skip');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Choose Image');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Upload Images');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Create Poll');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Upload Video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Create post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Record voice');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Feelings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Sell product');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Upload Files');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Stories');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Create new story');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'No more views');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'What is going on');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_campaigns', 'My Campaigns');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'my_wallet', 'My Wallet');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stats', 'Stats');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_audience', 'Choose Audience');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'launch_ad', 'Launch Ad');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_clicks', 'Pay Per Click');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pay_per_imprssions', 'Pay Per Impression');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'explore_latest_articles', 'Explore the latest articles');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_for_article', 'Search for articles..');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'authors', 'Authors');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'news_feed', 'News Feed');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'video_call', 'Video call');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_in_new_tab', 'Open in new tab');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'change_color', 'Change color');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'image', 'Image');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'voice', 'Voice');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'gif', 'GIF');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stickers', 'Stickers');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stop', 'Stop');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'contact_help', 'Let us help you solve your issue.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'info', 'Info');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_found', 'users found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'users_playing', 'users playing');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'popular_games', 'Popular Games');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_happening', 'What\'s happening');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'discount', 'Discount');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'pick_your_plan', 'Pick your Plan');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'trusted_payment_methods', 'Trusted payment methods');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'secure_payments', 'Secure payments');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'group_settings', 'Group Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'page_settings', 'Page Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'user_setting', 'User Settings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'security', 'Security');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'earnings', 'Earnings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'open_original', 'Open original');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_comments_found', 'No comments found');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'delete_conversation', 'Delete Conversation');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'images', 'Images');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'topics', 'Topics');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_type', 'Search type');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'search_section', 'Search section');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'watch_now', 'Watch Now');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'new_product', 'New Product');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'latest', 'Latest');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_low', 'Price Low');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'price_high', 'Price High');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'view', 'View');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'buy', 'Buy');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'community', 'Community');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'skip', 'Skip');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'choose_image', 'Choose Image');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_images', 'Upload Images');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_poll', 'Create Poll');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_video', 'Upload Video');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_post', 'Create post');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'record_voice', 'Record voice');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'feelings', 'Feelings');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sell_product', 'Sell product');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'upload_files', 'Upload Files');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'stories', 'Stories');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'create_story', 'Create new story');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'no_more_views', 'No more views');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'whats_going', 'What is going on');
        }
    }
    if (!empty($lang_update_queries)) {
        foreach ($lang_update_queries as $key => $query) {
            $sql = mysqli_query($sqlConnect, $query);
        }
    }
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
                     <h2 class="light">Update to v2.1 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                            <li> [Added] new theme, "sunshine".</li>
                            <li> [Added] new story system for both themes. </li>
                            <li> [Added] filter system on marketplace, on the new theme. </li>
                            <li> [Added] hashtag posts count, on new theme.</li>
                            <li> [Fixed] few bugs.</li>
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
    "UPDATE `Wo_Config` SET `value` = '2.1' WHERE `name` = 'script_version'",
    "UPDATE `Wo_Config` SET `value` = '<?php echo time(); ?>' WHERE `name` = 'last_update'",
    "CREATE TABLE `Wo_Story_Seen` (`id` int(11) NOT NULL AUTO_INCREMENT,`user_id` int(11) NOT NULL DEFAULT '0',`story_id` int(11) NOT NULL DEFAULT '0',`time` varchar(20) NOT NULL DEFAULT '0',PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;",
    "ALTER TABLE `Wo_UserStory` CHANGE `expire` `expire` VARCHAR(100) NULL DEFAULT '';",
    "ALTER TABLE `Wo_UserStoryMedia` CHANGE `expire` `expire` VARCHAR(100) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT '';",
    "ALTER TABLE `Wo_Movies` ADD `rating` VARCHAR(11) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '1' AFTER `views`;",
    "ALTER TABLE `Wo_Story_Seen` ADD INDEX(`user_id`);",
    "ALTER TABLE `Wo_Story_Seen` ADD INDEX(`story_id`);",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'my_campaigns');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'my_wallet');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'stats');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'choose_audience');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'launch_ad');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pay_per_clicks');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pay_per_imprssions');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'explore_latest_articles');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'search_for_article');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'authors');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'news_feed');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'video_call');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'open_in_new_tab');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'change_color');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'image');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'voice');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'gif');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'stickers');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'stop');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'contact_help');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'info');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'users_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'users_playing');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'popular_games');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'whats_happening');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'discount');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'pick_your_plan');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'trusted_payment_methods');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'secure_payments');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'group_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'page_settings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'user_setting');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'security');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'earnings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'open_original');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_comments_found');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'delete_conversation');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'images');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'topics');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'search_type');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'search_section');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'watch_now');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'new_product');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'latest');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'price_low');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'price_high');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'view');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'buy');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'community');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'skip');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'choose_image');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'upload_images');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_poll');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'upload_video');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_post');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'record_voice');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'feelings');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sell_product');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'upload_files');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'stories');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'create_story');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'no_more_views');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'whats_going');",
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