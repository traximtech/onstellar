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
        $get_config     = file_get_contents('config.php');
        $file_content   = '<?php
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
$sql_db_host = "' . $sql_db_host . '";
// MySQL Database User
$sql_db_user = "' . $sql_db_user . '";
// MySQL Database Password
$sql_db_pass = "' . $sql_db_pass . '";
// MySQL Database Name
$sql_db_name = "' . $sql_db_name . '";

// Site URL
$site_url = "' . $site_url . '"; // e.g (http://example.com)

// Purchase code
$purchase_code = "' . trim($_POST['code']) . '"; // Your purchase code, don\'t give it to anyone. 
?>';
        $config_file    = file_put_contents('config.php', $file_content);
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
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'اكد حسابك');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'تبدو محاولة تسجيل الدخول مختلفة قليلاً عن المعتاد ، وقد يرجع هذا إلى أنك تقوم بتسجيل الدخول من جهاز مختلف أو موقع مختلف.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'لقد أرسلنا لك رسالة إلكترونية تحتوي على رمز التأكيد.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'توثيق ذو عاملين');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'لتسجيل الدخول ، تحتاج إلى التحقق من هويتك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'لقد أرسلنا إليك رمز التأكيد إلى هاتفك وإلى عنوان بريدك الإلكتروني.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'لقد أرسلنا إليك رمز التأكيد إلى عنوان بريدك الإلكتروني.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'لقد أرسلنا إليك رمز التأكيد إلى رقم هاتفك.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'شغّل تسجيل الدخول المكوَّن من خطوتين لتحسين مستوى أمان حسابك ، وبمجرد تشغيله ، ستستخدم كل من كلمة المرور ورمز الأمان المكون من 6 أرقام والمرسلين إلى هاتفك أو بريدك الإلكتروني لتسجيل الدخول.');
        } else if ($value == 'dutch') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Bevestig je account');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Uw inlogpoging lijkt een beetje anders dan anders. Dit zou kunnen zijn omdat u zich aanmeldt vanaf een ander apparaat of een andere locatie.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'We hebben u een e-mail gestuurd met de bevestigingscode.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Twee-factor-authenticatie');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Om u aan te melden, moet u uw identiteit verifiëren.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'We hebben je de bevestigingscode gestuurd naar je telefoon en naar je e-mailadres.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'We hebben je de bevestigingscode gestuurd naar je e-mailadres.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'We hebben je de bevestigingscode naar je telefoonnummer gestuurd.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Schakel authenticatie in 2 stappen in om de beveiliging van uw account te verbeteren. Wanneer u eenmaal bent ingeschakeld, gebruikt u zowel uw wachtwoord als een uit 6 cijfers bestaande beveiligingscode die naar uw telefoon of e-mailadres is gestuurd om in te loggen.');
        } else if ($value == 'french') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Confirmez votre compte');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Votre tentative de connexion semble légèrement différente de celle habituelle. Cela peut être dû au fait que vous vous connectez depuis un autre appareil ou un autre emplacement.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Nous vous avons envoyé un email avec le code de confirmation.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Authentification à deux facteurs');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Pour vous connecter, vous devez vérifier votre identité.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Nous vous avons envoyé le code de confirmation sur votre téléphone et sur votre adresse e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Nous vous avons envoyé le code de confirmation à votre adresse e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Nous vous avons envoyé le code de confirmation sur votre numéro de téléphone.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Activez la connexion en deux étapes pour renforcer la sécurité de votre compte. Une fois activé, vous utiliserez votre mot de passe et un code de sécurité à 6 chiffres envoyé à votre téléphone ou à un courrier électronique pour vous connecter.');
        } else if ($value == 'german') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Bestätigen Sie ihr Konto');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Ihr Anmeldeversuch scheint ein wenig anders als sonst. Dies könnte daran liegen, dass Sie sich von einem anderen Gerät oder einem anderen Standort aus anmelden.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Wir haben Ihnen eine E-Mail mit dem Bestätigungscode gesendet.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Zwei-Faktor-Authentifizierung');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Um sich einzuloggen, müssen Sie Ihre Identität bestätigen.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Wir haben Ihnen den Bestätigungscode an Ihr Telefon und an Ihre E-Mail-Adresse gesendet.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Wir haben Ihnen den Bestätigungscode an Ihre E-Mail-Adresse gesendet.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Wir haben Ihnen den Bestätigungscode an Ihre Telefonnummer gesendet.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Aktivieren Sie die 2-stufige Anmeldung, um die Sicherheit Ihres Kontos zu erhöhen. Nach dem Einschalten verwenden Sie sowohl Ihr Passwort als auch einen 6-stelligen Sicherheitscode, der an Ihr Telefon oder Ihre E-Mail-Adresse gesendet wird, um sich anzumelden.');
        } else if ($value == 'italian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Conferma il tuo account');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Il tuo tentativo di accesso sembra leggermente diverso dal solito. Ciò potrebbe essere dovuto al fatto che stai effettuando l\'accesso da un dispositivo diverso o da un\'altra posizione.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Ti abbiamo inviato un\'e-mail con il codice di conferma.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Autenticazione a due fattori');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Per accedere, è necessario verificare la propria identità.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Ti abbiamo inviato il codice di conferma sul tuo telefono e sul tuo indirizzo email.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Ti abbiamo inviato il codice di conferma al tuo indirizzo email.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Ti abbiamo inviato il codice di conferma al tuo numero di telefono.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Attiva l\'accesso in due passaggi per aumentare la sicurezza del tuo account. Una volta attivato, utilizzerai sia la tua password sia un codice di sicurezza a 6 cifre inviato al tuo telefono o e-mail per accedere.');
        } else if ($value == 'portuguese') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Confirme sua conta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Sua tentativa de login parece um pouco diferente do normal. Isso pode ter ocorrido porque você está fazendo login de um dispositivo diferente ou de um local diferente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Enviamos um e-mail para você com o código de confirmação.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Autenticação de dois fatores');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Para fazer login, você precisa verificar sua identidade.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Enviamos o código de confirmação para o seu telefone e para o seu endereço de e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Nós lhe enviamos o código de confirmação para o seu endereço de e-mail.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Enviamos o código de confirmação para o seu número de telefone.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Ative o login em duas etapas para aumentar o nível de segurança da sua conta. Depois de ativada, você usará a senha e um código de segurança de seis dígitos enviados para o seu telefone ou e-mail para fazer login.');
        } else if ($value == 'russian') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Подтвердите свой аккаунт');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Ваша попытка входа выглядит немного иначе, чем обычно. Это может быть связано с тем, что вы входите с другого устройства или в другое место.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Мы отправили вам электронное письмо с кодом подтверждения.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Двухфакторная аутентификация');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Чтобы войти в систему, вам необходимо подтвердить свою личность.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Мы отправили вам код подтверждения на ваш телефон и на ваш адрес электронной почты.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Мы отправили вам код подтверждения на ваш адрес электронной почты.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Мы отправили вам код подтверждения на ваш номер телефона.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Включите двухэтапный вход для повышения безопасности своей учетной записи. После включения вы будете использовать как свой пароль, так и 6-значный код безопасности, отправленный на ваш телефон или электронную почту, чтобы войти в систему.');
        } else if ($value == 'spanish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Confirme su cuenta');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'El intento de inicio de sesión parece un poco diferente de lo habitual. Esto podría deberse a que se está iniciando desde un dispositivo diferente o desde una ubicación diferente.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Te hemos enviado un correo electrónico con el código de confirmación.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Autenticación de dos factores');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Para iniciar sesión, necesita verificar su identidad.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Le hemos enviado el código de confirmación a su teléfono y a su dirección de correo electrónico.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Le hemos enviado el código de confirmación a su dirección de correo electrónico.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Le hemos enviado el código de confirmación a su número de teléfono.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Active el inicio de sesión en 2 pasos para subir de nivel la seguridad de su cuenta. Una vez que lo haya hecho, utilizará su contraseña y un código de seguridad de 6 dígitos que se le enviará a su teléfono o correo electrónico para iniciar sesión.');
        } else if ($value == 'turkish') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Hesabını onayla');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Oturum açma girişiminiz normalden biraz farklı görünüyor, Bunun nedeni farklı bir cihazdan veya farklı bir konumdan oturum açmanız olabilir.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'Onay kodunu içeren bir e-posta gönderdik.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'İki faktörlü kimlik doğrulama');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'Giriş yapmak için kimliğinizi doğrulamanız gerekir.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'Size onay kodunu telefonunuza ve e-posta adresinize gönderdik.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'Size e-posta adresinize onay kodunu gönderdik.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'Telefon numaranıza onay kodunu gönderdik.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Hesabınızın güvenliğini artırmak için 2 adımlı giriş özelliğini açın. Açtıktan sonra, giriş yapmak için hem şifrenizi hem de telefonunuza veya e-posta adresinize gönderilen 6 basamaklı bir güvenlik kodunu kullanırsınız.');
        }
        
        else if ($value == 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Confirm your account');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Your sign in attempt seems a little different than usual, This could be because you are signing in from a different device or a different location.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'We have sent you an email with the confirmation code.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Two-factor authentication');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'To log in, you need to verify your identity.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'We have sent you the confirmation code to your phone and to your email address.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'We have sent you the confirmation code to your email address.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'We have sent you the confirmation code to your phone number.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Turn on 2-step login to level-up your account\'s security, Once turned on, you\'ll use both your password and a 6-digit security code sent to your phone or email to log in.');
        } else if ($value != 'english') {
            $lang_update_queries[] = Wo_UpdateLangs($value, 'confirm_your_account', 'Confirm your account');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sign_in_attempt', 'Your sign in attempt seems a little different than usual, This could be because you are signing in from a different device or a different location.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'we_have_sent_you_code', 'We have sent you an email with the confirmation code.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor', 'Two-factor authentication');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'to_log_in_two_factor', 'To log in, you need to verify your identity.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_both', 'We have sent you the confirmation code to your phone and to your email address.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_email', 'We have sent you the confirmation code to your email address.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'sent_two_factor_phone', 'We have sent you the confirmation code to your phone number.');
            $lang_update_queries[] = Wo_UpdateLangs($value, 'two_factor_description', 'Turn on 2-step login to level-up your account\'s security, Once turned on, you\'ll use both your password and a 6-digit security code sent to your phone or email to log in.');
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
                     <h2 class="light">Update to v2.0.3 </span></h2>
                     <div class="setting-well">
                        <h4>Changelog</h4>
                        <ul class="wo_update_changelog">
                            <li> [Added] confirmation system when user login from new location. </li>
                            <li> [Added] two-factor authentication system using email or phone. </li>
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
    "UPDATE `Wo_Config` SET `value` = '2.0.3' WHERE `name` = 'script_version'",
    "ALTER TABLE `Wo_Users` ADD `last_login_data` TEXT NULL DEFAULT NULL AFTER `share_my_data`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'login_auth', '1');",
    "ALTER TABLE `Wo_Users` ADD `two_factor` INT NOT NULL DEFAULT '0' AFTER `last_login_data`;",
    "INSERT INTO `Wo_Config` (`id`, `name`, `value`) VALUES (NULL, 'two_factor', '1'), (NULL, 'two_factor_type', 'email');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'confirm_your_account');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sign_in_attempt');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'we_have_sent_you_code');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'two_factor');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'to_log_in_two_factor');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sent_two_factor_both');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sent_two_factor_email');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'sent_two_factor_phone');",
    "INSERT INTO `Wo_Langs` (`id`, `lang_key`) VALUES (NULL, 'two_factor_description');",
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