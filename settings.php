<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $connect = get_string('connect', 'smartclassroom');
    $template = "
<button type=\"button\" class=\"get_token_button\">{$connect}</button>
<script type=\"text/javascript\">
//<![CDATA[
YUI().use('node', 'io', 'dump', 'json-parse', 'io-xdr', function(Y){
	Y.one('.get_token_button').on('click', function(e){
	var config = {
		method: 'POST',
		headers: {
			Authorization: 'Basic ' + btoa('smart-client:gave-chile-moment-wood')
		},
		on: {
			success: function(){
				console.log(\"Exito!\");
			},
			failure: function(){
				console.log(\"Error!\");
			}    			
		},
		xdr: {
			use: 'native'
		}
	}
	
	var url = 'http://gradiant-dev-classroom.smarted.cloud:3065/api/v1/oauth/token?grant_type=client_credentials';
	Y.io(url, config);
	});
});
//]]
</script>
";    
    
	$settings->add(new admin_setting_configtext('SmartClassRoom_API_key', get_string('apikey', 'smartclassroom'),
            get_string('setapikey', 'smartclassroom'), "smart-xunta", PARAM_STR));
	$settings->add(new admin_setting_configtext('SmartClassRoom_Secret', get_string('secret', 'smartclassroom'),
            get_string('setsecret', 'smartclassroom'), "gave-chile-moment-wood", PARAM_STR));
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


