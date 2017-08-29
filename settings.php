<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $connect = get_string('connect', 'smartclassroom');
    $template = "<button class=\"get_token_button\">{$connect}</button>";    
    
	$settings->add(new admin_setting_configtext('SmartClassRoom_API_key', get_string('apikey', 'smartclassroom'),
            get_string('setapikey', 'smartclassroom'), "", PARAM_STR));
	$settings->add(new admin_setting_configtext('SmartClassRoom_Secret', get_string('secret', 'smartclassroom'),
            get_string('setsecret', 'smartclassroom'), "", PARAM_STR));
	$settings->add(new admin_setting_configtext('SmartClassRoom_OAuth', get_string('oauthip', 'smartclassroom'),
            get_string('setoauthip', 'smartclassroom'), "", PARAM_STR));
	$settings->add(new admin_setting_configtext('SmartClassRoom_Backoffice', get_string('backofficeip', 'smartclassroom'),
            get_string('setbackofficeip', 'smartclassroom'), "", PARAM_STR));
	$settings->add(new admin_setting_configtext('SmartClassRoom_SCR', get_string('smartclassroomip', 'smartclassroom'),
            get_string('setsmartclassroomip', 'smartclassroom'), "", PARAM_STR));
	$settings->add(new admin_setting_configtext('SmartClassRoom_Token', get_string('token', 'smartclassroom'),
            get_string('authtoken', 'smartclassroom'), "", PARAM_STR));
    $settings->add(new admin_setting_heading("token_section", "", $template));              
}


