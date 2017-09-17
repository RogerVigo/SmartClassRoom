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
        $settings->add(new admin_setting_heading('smartclassroommodeditcredentials', get_string('editcredentials', 'smartclassroom'), get_string('editcredentialsextended', 'smartclassroom')));
	$settings->add(new admin_setting_configtext('smartclassroom_api_key', get_string('apikey', 'smartclassroom'),
            get_string('setapikey', 'smartclassroom'), "smart-xunta", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('smartclassroom_secret', get_string('secret', 'smartclassroom'),
            get_string('setsecret', 'smartclassroom'), "gave-chile-moment-wood", PARAM_TEXT));
        $settings->add(new admin_setting_configtext('smartclassroom_clientid', get_string('clientid', 'smartclassroom'),
            get_string('clientid', 'smartclassroom'), "1", PARAM_TEXT));
        
        $settings->add(new admin_setting_heading('smartclassroommodconnectionsettings', get_string('smartclassroommodconnectionsettings', 'smartclassroom'), get_string('smartclassroommodconnectionsettingsextended', 'smartclassroom')));
	$settings->add(new admin_setting_configtext('smartclassroom_oauth', get_string('oauthip', 'smartclassroom'),
            get_string('setoauthip', 'smartclassroom'), "http://gradiant-dev-classroom.smarted.cloud:3065", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('smartclassroom_backoffice', get_string('backofficeip', 'smartclassroom'),
            get_string('setbackofficeip', 'smartclassroom'), "http://wm33.netexlearning.cloud/tdidacta-webapp", PARAM_TEXT));
	$settings->add(new admin_setting_configtext('smartclassroom_scr', get_string('smartclassroomip', 'smartclassroom'),
            get_string('setsmartclassroomip', 'smartclassroom'), "", PARAM_TEXT));
	try{
		$curlResource = curl_init();
	
		curl_setopt($curlResource, CURLOPT_URL, "http://gradiant-dev-classroom.smarted.cloud:3065/api/v1/oauth/token?grant_type=client_credentials");
		$authHeader = 'Authorization: Basic ' . base64_encode('smart-client:gave-chile-moment-wood');
                //Peticion POST
                curl_setopt($curlResource, CURLOPT_POST, true);
                //Header con el authorization
                curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader));
	        //no vuelques la respuesta, devuelvemela en un string
                curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
	        //curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Basic ');
		$resultAsString = curl_exec($curlResource);
	        $resultAsObject = json_decode($resultAsString);
	        $anotherWayOfError = curl_error($curlResource);
		curl_close($curlResource);
		
	}catch(Exception $e){
		
	}
        if (isset($resultAsObject->access_token)) {
            
            /*$settings->add(new admin_setting_configtext('smartclassroom_token_response', 
                                                               get_string('token', 'smartclassroom'),
                                                               get_string('authtoken', 'smartclassroom'), 
                                                               '', PARAM_TEXT));*/
            $settings->add(new admin_setting_configtext('smartclassroom_token', get_string('token', 'smartclassroom'),
                         "<button type=\"button\" class=\"get_token_button\">{$connect}</button>", 
                                 '', PARAM_TEXT)); 
            $settings->add(new admin_setting_heading('smartclassroommodconnectionresult', 
                                                    get_string('smartclassroommodconnectionresult', 'smartclassroom'),
                                                    '<p title="'.$resultAsObject->access_token.'">'.substr($resultAsObject->access_token,50).'</p>'
                                                    )
                          );
                        
        }
                    else if (isset($resultAsObject->error)) 
                                                    $settings->add(new admin_setting_configtext('smartclassroom_token_response', 
                                                                    get_string('token', 'smartclassroom'),
                                                                    get_string('authtoken', 'smartclassroom'),
                                                                    "Error recibido: " . $resultAsObject->error,
                                                                    PARAM_TEXT));
                       /* else if (!$resultAsString)  $settings->add(new admin_setting_configtext('smartclassroom_token_response', 
                                                                        get_string('token', 'smartclassroom'), 
                                                                        get_string('authtoken', 'smartclassroom'),
                                                                        $resultAsString ,
                                                                        PARAM_TEXT));
                            else $settings->add(new admin_setting_configtext('smartclassroom_token_response', 
                                                                        get_string('token', 'smartclassroom'), 
                                                                        get_string('authtoken', 'smartclassroom'),
                                                                        'error irrecuperable: '.$anotherWayOfError,
                                                                        PARAM_TEXT));*/
	
        
}



