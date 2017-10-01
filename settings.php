<?php

defined('MOODLE_INTERNAL') || die;

/* PARA GESTIONAR EL DESPLEGABLE EN LA ZONA DE ADMIN */

$ADMIN->add('modsettings', new admin_category('modsmartclassroomfolder', new lang_string('pluginname', 'mod_smartclassroom'))); //Con esto decimos que queremos una carpeta -> ver lib/adminlib.php
$settings = new admin_settingpage($section, get_string('modulename', 'mod_smartclassroom'), 'moodle/site:config', false); //Si ponemos una carpeta, es necesario tener una nueva settings page -> ver lib/adminlib.php

/* LA PÁGINA EN SÍ */

if ($ADMIN->fulltree) {

    /************************/
    /* RECUPERAMOS VALORES  */
    /************************/
    
    $apikey = $DB->get_record('config', array('name' => "smartclassroom_api_key"), '*');
	 $apisecret = $DB->get_record('config', array('name' => "smartclassroom_secret"), '*');
	 $customerID = $DB->get_record('config', array('name' => "smartclassroom_clientid"), '*');$customerID = $customerID->value;
	 $authIP = $DB->get_record('config', array('name' => "smartclassroom_oauth"), '*');
	 $backofficeIP = $DB->get_record('config', array('name' => "smartclassroom_backoffice"), '*');
	 $accessToken = $DB->get_record('config', array('name' => "smartclassroom_token_response"), '*');

    /****************************/
    /* FIELDSET DE CREDENCIALES */
    /****************************/
    
    $settings->add(new admin_setting_heading('smartclassroommodeditcredentials', get_string('editcredentials', 'smartclassroom'), get_string('editcredentialsextended', 'smartclassroom')));
    $settings->add(new admin_setting_configtext('smartclassroom_api_key', get_string('apikey', 'smartclassroom'),
                    get_string('setapikey', 'smartclassroom'), "smarted-xunta", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('smartclassroom_secret', get_string('secret', 'smartclassroom'),
                    get_string('setsecret', 'smartclassroom'), "agreed-settled-MONEY-nerve", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('smartclassroom_clientid', get_string('clientid', 'smartclassroom'),
                    get_string('clientid', 'smartclassroom'), "1", PARAM_TEXT));

    /******************************************************************/
    /* INTENTAMOS LA CONEXIÓN PARA OBTENER EL TOKEN DE ADMINISTRACIÓN */
    /******************************************************************/


    try {
        $curlResource = curl_init();

        curl_setopt($curlResource, CURLOPT_URL, $authIP->value."/api/v1/oauth/token?grant_type=client_credentials");
        $authHeader = 'Authorization: Basic ' . base64_encode($apikey->value.':'.$apisecret->value);

        //Configuramos Peticion POST
        curl_setopt($curlResource, CURLOPT_POST, true);
        //Configuramos el Header con el authorization Basic
        curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader));
        //No vuelques la respuesta, devuelvemela en un string
        curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);

		  //Ejecutamos el curl
        $resultAsString = curl_exec($curlResource);
        
        //Pasamos a objeto la cadena resultado
        $resultAsObject = json_decode($resultAsString);
        
        //Obtenemos si hay error
        $anotherWayOfError = curl_error($curlResource);
        
        //Cerramos curl
        curl_close($curlResource);
        
    } catch (Exception $e) {
        
    }
    
    //Nos quedamos con el token de sesión para comprobar si es válido
    if (isset($resultAsObject->access_token)) $tokenSession = $resultAsObject->access_token;

	 //Implementamos input para su guardado     
    $settings->add(new admin_setting_configtext('smartclassroom_token_response', get_string('authtoken', 'smartclassroom'),
              get_string('token', 'smartclassroom'), '', PARAM_TEXT,100));
         
    //Informamos del token recibido para introducirlo manualmente en el input
    //o del error si ha habido algún problema
    if (isset($resultAsObject->access_token)) {

			$settings->add(new admin_setting_heading('smartclassroommodconnectionresult',
                        get_string('smartclassroommodconnectionresult', 'smartclassroom'),
                        '<p title="' . $tokenSession . '" style="max-width:50%;word-break: break-all;"><span style="color:darkGreen;">Correcta</span>:<br> ' . $tokenSession . '</p>'
                )
        );
    } else {
    
        $settings->add(new admin_setting_heading('smartclassroommodconnectionresult',
                        get_string('smartclassroommodconnectionresult', 'smartclassroom'),
                        '<p style="color:red" title="Error">Error recibido: ' . $resultAsObject->status . ' ' . $resultAsObject->error . ' ' . $resultAsObject->message . '</p>'
                )
        );
        
    
    }
    
    $filters = array();

		/**************************************************************/
    	/* SECCIÓN PARA LA CONFIGURACIÓN DE LAS IPS DE LOS SERVIDORES */
    	/**************************************************************/


 	 $settings->add(new admin_setting_heading('smartclassroommodconnectionsettings', get_string('smartclassroommodconnectionsettings', 'smartclassroom'), get_string('smartclassroommodconnectionsettingsextended', 'smartclassroom')));
    $settings->add(new admin_setting_configtext('smartclassroom_oauth', get_string('oauthip', 'smartclassroom'),
                    get_string('setoauthip', 'smartclassroom'), "http://gradiant-dev-classroom.smarted.cloud:3065", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('smartclassroom_backoffice', get_string('backofficeip', 'smartclassroom'),
                    get_string('setbackofficeip', 'smartclassroom'), "http://vm33.netexlearning.cloud", PARAM_TEXT));
    $settings->add(new admin_setting_configtext('smartclassroom_scr', get_string('smartclassroomip', 'smartclassroom'),
                    get_string('setsmartclassroomip', 'smartclassroom'), "", PARAM_TEXT));
   
		/********************************************************************************************/
    	/* INTENTAMOS LA CONEXIÓN PARA OBTENER LOS METADATOS PARA LOS FILTROS PRIMARIO Y SECUNDARIO */
    	/********************************************************************************************/

    try {
        	$curlResource2 = curl_init();

        	curl_setopt($curlResource2, CURLOPT_URL, $backofficeIP->value."/mvc/rest/v1/customers/".$customerID."/bookmetadatas");
			$authHeader = 'Authorization: Bearer ' . $accessToken->value;

        //Peticion GET
        curl_setopt($curlResource2, CURLOPT_HTTPGET, true);
        //Header con el authorization
        curl_setopt($curlResource2, CURLOPT_HTTPHEADER, array($authHeader));
        //no vuelques la respuesta, devuelvemela en un string
        curl_setopt($curlResource2, CURLOPT_RETURNTRANSFER, true);

		  //Ejecutamos el curl
        $resultAsString2 = curl_exec($curlResource2);
        
        //Pasamos a objeto la cadena resultado
        $resultAsObject2 = json_decode($resultAsString2);

        //Obtenemos si hay error
        $anotherWayOfError2 = curl_error($curlResource2);
        
        //Cerramos curl        
        curl_close($curlResource2);


        if (!empty($resultAsObject2))
            foreach ($resultAsObject2 as $element) {
                $filters[$element->id] = $element->name;
            }
        
    } catch (Exception $e) {
        
    }

	/*  DE HABER ERROR, INFORMAMOS, SINO, MOSTRAMOS SELECTORES DE FILTROS */   
   
    if  (isset($resultAsObject2->error)){
            $settings->add(new admin_setting_heading('smartclassroommodconnectionresult2',
                        get_string('smartclassroommodconnectionresult2', 'smartclassroom'),
                        '<p style="color:red" title="Error">Error recibido: ' . $resultAsObject2->error . '</p>'
                )
        );
        
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

/* MÁS IMPLEMENTACIONES PARA EL PANEL DE ADMINISTRACIÓN */
$ADMIN->add('modsmartclassroomfolder', $settings);
$ADMIN->add('modsmartclassroomfolder', new admin_externalpage('smartclassroomschools', get_string('smartclassroomschools', 'mod_smartclassroom'), "$CFG->wwwroot/mod/smartclassroom/schools.php"));
$settings = null;

