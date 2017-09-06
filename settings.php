<?php

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    $connect = get_string('connect', 'smartclassroom');
    $template = "
<script type=\"text/javascript\">
//<![CDATA[
console.log('aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa');
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
            get_string('setapikey', 'smartclassroom'), "smart-xunta", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('SmartClassRoom_Secret', get_string('secret', 'smartclassroom'),
            get_string('setsecret', 'smartclassroom'), "gave-chile-moment-wood", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('SmartClassRoom_OAuth', get_string('oauthip', 'smartclassroom'),
            get_string('setoauthip', 'smartclassroom'), "", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('SmartClassRoom_Backoffice', get_string('backofficeip', 'smartclassroom'),
            get_string('setbackofficeip', 'smartclassroom'), "", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('SmartClassRoom_SCR', get_string('smartclassroomip', 'smartclassroom'),
            get_string('setsmartclassroomip', 'smartclassroom'), "", PARAM_TEXT));
	try{
		$curlResource = curl_init();
	
		curl_setopt($curlResource, CURLOPT_URL, "http://gradiant-dev-classroom.smarted.cloud:3065/api/v1/oauth/token?grant_type=client_credentials");
		$authHeader = 'Authorization: Basic ' . base64_encode('smart-client:gave-chile-moment-wood');
                //Header con el authorization y aceptamos json
                curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader,'Accept: application/json'));
	        //no vuelques la respuesta, devuelvemela en un string
                curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
	        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ');
		$resultAsString = curl_exec($curlResource);
	        $resultAsArray = json_decode($resultAsString);
	        $anotherWayOfError = curl_error($curlResource);
		curl_close($curlResource);
		/*if (isset($resultAsArray['access_token'])) 
                        $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                get_string('token', 'smartclassroom'),
                                                                get_string('authtoken', 'smartclassroom'), 
                                                                $resultAsArray['access_token'], 
                                                                PARAM_TEXT));
                    else if (isset($resultAsArray['error'])) 
                                                    $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                    get_string('token', 'smartclassroom'),
                                                                    get_string('authtoken', 'smartclassroom'),
                                                                    "Error recibido: " . $resultAsArray['error'],
                                                                    PARAM_TEXT));
                        else*/ if (!$resultAsString)  $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                        get_string('token', 'smartclassroom'), 
                                                                        get_string('authtoken', 'smartclassroom'),
                                                                        $resultAsString ,
                                                                        PARAM_TEXT));
                            else $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                        get_string('token', 'smartclassroom'), 
                                                                        get_string('authtoken', 'smartclassroom'),
                                                                        'error irrecuperable: '.$anotherWayOfError,
                                                                        PARAM_TEXT));
	
	}catch(Exception $e){
		
	}
        $settings->add(new admin_setting_configtext('SmartClassRoom_Token', get_string('token', 'smartclassroom'),
            "<button type=\"button\" class=\"get_token_button\">{$connect}</button>", "", PARAM_TEXT));
}



