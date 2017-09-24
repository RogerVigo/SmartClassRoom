<?php

defined('MOODLE_INTERNAL') || die;

$ADMIN->add('modsettings', new admin_category('modsmartclassroomfolder', new lang_string('pluginname', 'mod_smartclassroom'))); //Con esto decimos que queremos una carpeta -> ver lib/adminlib.php

$settings = new admin_settingpage($section, get_string('modulename', 'mod_smartclassroom'), 'moodle/site:config', false); //Si ponemos una carpeta, es necesario tener una nueva settings page -> ver lib/adminlib.php

if ($ADMIN->fulltree) {
    
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
   /* try {
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
    } catch (Exception $e) {
        
    }*/
    if (isset($resultAsObject->access_token)) {

        /* $settings->add(new admin_setting_configtext('smartclassroom_token_response', 
          get_string('token', 'smartclassroom'),
          get_string('authtoken', 'smartclassroom'),
          '', PARAM_TEXT)); */
        $settings->add(new admin_setting_configtext('smartclassroom_token', get_string('token', 'smartclassroom'),
                        "<button type=\"button\" class=\"get_token_button\">{$connect}</button>",
                        '', PARAM_TEXT));
        $settings->add(new admin_setting_heading('smartclassroommodconnectionresult',
                        get_string('smartclassroommodconnectionresult', 'smartclassroom'),
                        '<p title="' . $resultAsObject->access_token . '">' . substr($resultAsObject->access_token, 0, 50) . '</p>'
                )
        );
    } else if (isset($resultAsObject->error))
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
      PARAM_TEXT)); */
    $filters = array();

    try {
        $curlResource2 = curl_init();

        curl_setopt($curlResource2, CURLOPT_URL, "http://vm33.netexlearning.cloud/mvc/rest/v1/customers/1/bookmetadatas");
//		$authHeader = 'Authorization: Basic ' . base64_encode('smart-client:gave-chile-moment-wood');
        //Peticion GET
        curl_setopt($curlResource2, CURLOPT_HTTPGET, true);
        //Header con el authorization
        //               curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader));
        //no vuelques la respuesta, devuelvemela en un string
        curl_setopt($curlResource2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlResource2, CURLOPT_HTTPHEADER, array('Accept: text/plain'));
        $resultAsString2 = curl_exec($curlResource2);
        $resultAsObject2 = json_decode($resultAsString2);
        $anotherWayOfError2 = curl_error($curlResource2);
        curl_close($curlResource2);

        if (!empty($resultAsObject2))
            foreach ($resultAsObject2 as $element) {
                $filters[$element->id] = $element->name;
            }

       
    } catch (Exception $e) {
        
    }
   /* if  (isset($resultAsObject2)){
            $settings->add(new admin_setting_configtext('smartclassroom_filters',
                            get_string('smartclassroomfilters', 'smartclassroom'),
                            get_string('smartclassroomfilters', 'smartclassroom'),
                            print_r($filters,true),
                            PARAM_TEXT));
            $setting->add(new admin_setting_configselect('smartclassroom_primaryfilter',
                            get_string('smartclassroomprimaryfilter', 'smartclassroom'),
                            get_string('smartclassroomprimaryfilter', 'smartclassroom'),
                            '', array()));
            
        }*/
    if  (!isset($resultAsObject2)){
            $settings->add(new admin_setting_configtext('smartclassroom_filters',
                            get_string('smartclassroomfilters', 'smartclassroom'),
                            get_string('smartclassroomfilters', 'smartclassroom'),
                            "Error recibido: ",
                            PARAM_TEXT));
        } else {
            $settings->add(new admin_setting_heading('smartclassroom_filters',
                            get_string('smartclassroomfilters', 'smartclassroom'),
                            get_string('smartclassroomfilters', 'smartclassroom')));

            $settings->add(new admin_setting_configselect('smartclassroom_primaryfilter',
                            get_string('smartclassroomprimaryfilter', 'smartclassroom'),
                            get_string('smartclassroomprimaryfilter', 'smartclassroom'),
                            '', $filters));

            $settings->add(new admin_setting_configselect('smartclassroom_secondaryfilter',
                            get_string('smartclassroomsecondaryfilter', 'smartclassroom'),
                            get_string('smartclassroomsecondaryfilter', 'smartclassroom'),
                            '', $filters));
        }
}

$ADMIN->add('modsmartclassroomfolder', $settings); // Tell core we already added the settings structure.
$settings = null;
$ADMIN->add('modsmartclassroomfolder', new admin_externalpage('smartclassroomschools', get_string('smartclassroomschools', 'mod_smartclassroom'), "$CFG->wwwroot/mod/smartclassroom/schools.php"));
//new moodle_url('/mod/smartclassroom/archivo.php',array parámetros de envío) Te lo dejo aqui escrito por si la forma de la url no te vale y necesitas pasar parametros o whatever
