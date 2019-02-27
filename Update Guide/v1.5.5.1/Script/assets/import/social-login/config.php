<?php 
$LoginWithConfig = array(
    'callback' => $config['site_url'] . '/login-with.php?provider=' . $provider,

    'providers' => array(
        "Google" => array(
			"enabled" => true,
			"keys" => array("id" => $config['googleAppId'], "secret" => $config['googleAppKey']),
		),
		"Facebook" => array(
			"enabled" => true,
			"keys" => array("id" => $config['facebookAppId'], "secret" => $config['facebookAppKey']),
			"scope" => "email",
			"trustForwarded" => false
		),
		"Twitter" => array(
			"enabled" => true,
			"keys" => array("key" => $config['twitterAppId'], "secret" => $config['twitterAppKey']),
			"includeEmail" => true
		),
		"LinkedIn" => array(
			"enabled" => true,
			"keys" => array("key" => $config['linkedinAppId'], "secret" => $config['linkedinAppKey'])
		),
		"Vkontakte" => array(
			"enabled" => true,
			"keys" => array("id" => $config['VkontakteAppId'], "secret" => $config['VkontakteAppKey'])
		),
		"Instagram" => array(
			"enabled" => true,
			"keys" => array("id" => $config['instagramAppId'], "secret" => $config['instagramAppkey'])
		),
    ),
);
?>