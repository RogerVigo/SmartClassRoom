<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * The main smartclassroom configuration form
 *
 * It uses the standard core Moodle formslib. For more info about them, please
 * visit: http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * @package    mod_smartclassroom
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 *
 * @package    mod_smartclassroom
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_smartclassroom_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {
        global $CFG, $PAGE, $DB, $COURSE;


 			/*************************************************************************/
        	// Si step está a 1, ya hemos confirmado la unidad que queremos, así que
        	// crearemos si no existe el type de lti y la actividad lti correspondiente
        	// redireccionando tras ello a la página del curso
        	/*************************************************************************/
         $step = optional_param('step', 0, PARAM_INT);

			if ($step > 1) {
			
			   require_once($CFG->dirroot.'/lib/accesslib.php');
			   require_once($CFG->dirroot.'/mod/lti/locallib.php');
			   require_once($CFG->dirroot.'/mod/lti/lib.php');
			   
			   $cuaternarySelected = optional_param('url', 0, PARAM_TEXT);
			   $unitName = optional_param('unitName', 0, PARAM_TEXT);
			   $course = optional_param('course', 0, PARAM_INT);
			   $section = optional_param('section', 0, PARAM_INT);
			   $nativeMode = optional_param('nativemode', 0, PARAM_INT);

			   $ltiRecord = lti_get_tool_by_url_match($cuaternarySelected, $course, LTI_TOOL_STATE_ANY);

				//Si no existe el tipo LTI lo insertamos junto al lti types config

			   if (!$ltiRecord) {

  					
	  				$tooltype = new stdClass();
					$toolconfig = new stdClass();
	  				
	  				$tooltype->state = LTI_TOOL_STATE_CONFIGURED;

	   			$toolconfig->lti_toolurl = $cuaternarySelected;
	   			$toolconfig->lti_typename = 'Tipo '.$unitName;
	   			
	   			$toolconfig->lti_resourcekey = '1';
					$toolconfig->lti_password = 'password';
					$toolconfig->lti_launchcontainer = ($nativeMode == 0) ? 2 : 4;
					$toolconfig->lti_customparameters = "";
					$toolconfig->lti_sendname = 1;
					$toolconfig->lti_sendemailaddr = 1;
					$toolconfig->lti_acceptgrades = 1;
					$toolconfig->lti_servicesalt = uniqid('',true);
					$toolconfig->lti_organizationid = '';
					$toolconfig->lti_organizationurl = '';
					$toolconfig->lti_forcessl = 0;
					$toolconfig->lti_coursevisible = 0;
						 
					$ltiTypeId = lti_add_type($tooltype, $toolconfig);
				}
				
				/********************************/ 
				/* CREACIÓN DE LA ACTIVIDAD LTI */
				/********************************/
				
				$lti = new stdClass();
				$lti->course = $course;
				$lti->name = $unitName;
				if ($ltiRecord) $lti->typeid = $ltiRecord->id;
					else $lti->typeid = $ltiTypeId;
				$lti->toolurl = $cuaternarySelected;

				$lti->instructorchoicesendname = 1;
				$lti->instructorchoicesendemailaddr = 1;
				$lti->instructorchoiceacceptgrades = 1;
				$lti->launchcontainer = ($nativeMode == 0) ? 2 : 4;
				//$lti->resourcekey = '1';
				//$lti->password = 'password';
				$lti->servicesalt = uniqid('',true);
				$lti->icon = '/mod/smartclassroom/pix/icon.gif';
				
				$lti->id = lti_add_instance($lti,'');
				
				/******************************/ 
				/* CREACIÓN DEL COURSE MODULE */
				/******************************/
				
				$mod = new stdClass();
			   $mod->course = $course;
			   $mod->module = $DB->get_field('modules', 'id', array('name'=>'lti'));
			   $mod->instance = $lti->id;
			   $mod->idnumber = '';
			   $mod->section = $section;
			   include_once("$CFG->dirroot/course/lib.php");
			   if (! $mod->coursemodule = add_course_module($mod) ) {
			       echo $OUTPUT->notification("Could not add a new course module to the course '" . $course . "'");
			       return false;
			   }
			   
			   /************************************************************/ 
				/* AÑADIMOS EL CM A LA SEQUENCE DE LA SECCIÓN EN DONDE ESTÁ */
				/************************************************************/
				
			   course_add_cm_to_section($course, $mod->coursemodule, $section);

			   /*************************************/ 
				/* AÑADIMOS EL REGISTRO DEL CONTEXTO */
				/*************************************/
			   
			   $coursePath = $DB->get_field('context', 'id', array('instanceid'=>$course,'contextlevel' => 50));
			  //$record = context::insert_context_record('70', $lti->id,'/1/3/'.$coursePath );
			   $record = new stdClass();
		        $record->contextlevel = '70';
		        $record->instanceid   = $mod->coursemodule;
		        $record->depth        = 0;
		        $record->path         = null; //not known before insert
		
		        $record->id = $DB->insert_record('context', $record);
		
	            $record->path = '/1/3/'.$coursePath.'/'.$record->id;
	            $record->depth = substr_count($record->path, '/');
	            $DB->update_record('context', $record);
		
					//Incrementamos la revisión del course para que recargue la cache con el elemento activity nuevo 		
		
					increment_revision_number('course','cacherev',2);
		
				/* PARA ENGAÑAR SOBRE LA APLICACIÓN QUE ESTAMOS INSERTANDO. */
		      $PAGE->requires->js_init_call('initFakeHidden');  
		      
				/* UNA VEZ CREADA, VOLVEMOS A LA PÁGINA DEL CURSO */
				redirect(new moodle_url('/course/view.php',array('id' => $course)));
			}       
			
		  /* SI ESTAMOS EN EL PASO 0, INICIAMOS EL DESPLIEGUE DE LA CONFIGURACIÓN */
		  
        $mform = $this->_form;
        
        //desactivamos la comprobación de que si modificamos algo nos pregunte si queremos abandonar la página
        $mform->disable_form_change_checker();


        $section = optional_param('section', 0, PARAM_INT);
        $course = optional_param('course', 0, PARAM_INT);
        $return = optional_param('return', 0, PARAM_INT);
        $sr = optional_param('sr', 0, PARAM_INT);
  		  $nativeMode = optional_param('nativemode', 0, PARAM_INT);
        
        $primarySelected = optional_param('scrprimary', 0, PARAM_INT);
        $secondarySelected = optional_param('scrsecondary', 0, PARAM_INT);
        $terciarySelected = optional_param('scrterciary', 0, PARAM_INT);

        
        $primaryFilterName = "";
        $secondaryFilterName = "";


			/* HARDCODEAMOS EL ID DEL SCHOOL EN ESPERA DE METERLO EN LA SESIÓN CUANDO ESTEMOS EN MODO XUNTA */        
        $schoolID = 1;
 			
 			/*OBTENEMOS LOS METADATOS DE LOS FILTROS*/
 		  $primaryfilter = $DB->get_records('config', array('name' => 'smartclassroom_primaryfilter')); 			 			
        $secondaryfilter = $DB->get_records('config', array('name' => "smartclassroom_secondaryfilter"),'', 'name,value');
        
        /* REGISTRAMOS EL ASSET */
        $PAGE->requires->js('/mod/smartclassroom/smartclassroom.js?date='.time());


		  /*************************************************************************/
        // Adding the "general" fieldset, where all the common settings are showed.
        /*************************************************************************/
        /*NO IMPLEMENTAMOS NADA EN ESPERA DE DECISIONES*/
        
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
	      /*  $mform->addElement('text', 'name', get_string('smartclassroomname', 'smartclassroom'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'smartclassroomname', 'smartclassroom');*/

        // Adding the standard "intro" and "introformat" fields.
   	   /*  if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }*/

 			/***********************************************************************************/
        	// FieldSet tipo de unidad. 
        	// Step = 0 -> Seleccionamos Filtros Primario y Secundario
        	// Step = 1 -> Buscamos libro y unidad
        	/***********************************************************************************/
        	
		/********************************************************************************************/
    	/* INTENTAMOS LA CONEXIÓN PARA OBTENER LOS METADATOS PARA LOS FILTROS PRIMARIO Y SECUNDARIO */
    	/* ASÍ COMO LOS ELEMENTOS DE PRIMER Y SEGUNDO NIVEL													  */
    	/********************************************************************************************/
	
	
		$customerID = $DB->get_record('config', array('name' => "smartclassroom_clientid"), '*');$customerID = $customerID->value;
		
	 	$authIP = $DB->get_record('config', array('name' => "smartclassroom_oauth"), '*');
	 	
	 	$backofficeIP = $DB->get_record('config', array('name' => "smartclassroom_backoffice"), '*');
            
	 	$accessToken = $DB->get_record('config', array('name' => "smartclassroom_token_response"), '*');

        try {
            $curlResource = curl_init();

            curl_setopt($curlResource, CURLOPT_URL, $backofficeIP->value.'/mvc/rest/v1/customers/'.$customerID.'/bookmetadatas');
   			$authHeader = 'Authorization: Bearer ' . $accessToken->value;


            //Peticion GET
            curl_setopt($curlResource, CURLOPT_HTTPGET, true);
            //Header con el authorization
            curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader));
            //no vuelques la respuesta, devuelvemela en un string
            curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
            
            $resultAsString = curl_exec($curlResource);
            $resultAsObject = json_decode($resultAsString);
            $anotherWayOfError = curl_error($curlResource);
            curl_close($curlResource);



				/********************************************/
				/* CREAMOS Y COMPONEMOS LOS ARRAYS DE DATOS */
				/********************************************/
				            
            $filters = array();
            $primaryValues = array();
            $secondaryValues = array();
            if (!empty($resultAsObject)){
                foreach ($resultAsObject as $nivel0) {
                	$filters[$nivel0->id]['name'] = $nivel0->name;
                    $filters[$nivel0->id]['nivel1'] = $nivel0->metadataValues;
                }
                foreach ($filters[current($primaryfilter)->value]['nivel1'] as $element){
                    $primaryValues[$element->id] = $element->name;         

                }
                foreach ($filters[current($secondaryfilter)->value]['nivel1'] as $element){
                    $secondaryValues[$element->id] = $element->name;
                    
                }
                $primaryFilterName = $filters[current($primaryfilter)->value]['name'];
                $secondaryFilterName = $filters[current($secondaryfilter)->value]['name'];
            }
            
        } catch (Exception $e) {
				print_r($e);
            
        }
         //Ordenamos 
	        if (isset($anotherWayOfError)) $errorFilters = $anotherWayOfError;
	        if (isset($primaryValues) && $primaryValues != null) asort($primaryValues);
	        if (isset($secondaryValues) && $secondaryValues != null) asort($secondaryValues);
           
       	/***************************************************/
    		/* INTENTAMOS LA CONEXIÓN PARA OBTENER LOS LIBROS	*/ 
    		/*	FILTRADOS POR EL NIVEL PRIMARIO Y EL SECUNDARIO.*/
    		/* CON ELLOS, LAS UNIDADES TAMBIÉN PARA NO TENER 	*/
    		/* QUE HACER OTRA PETICIÓN	  								*/
    		/***************************************************/
	
               
        	if ($step > 0) {		
   		try {
            $curlResource = curl_init();

            curl_setopt($curlResource, CURLOPT_URL, $backofficeIP->value."/mvc/rest/v1/schools/".$schoolID."/books?metadata=".$primarySelected.",".$secondarySelected);
   			$authHeader = 'Authorization: Bearer ' . $accessToken->value;
            
            //Peticion GET
            curl_setopt($curlResource, CURLOPT_HTTPGET, true);
            
            //Header con el authorization
            curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader));

            //no vuelques la respuesta, devuelvemela en un string
            curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);

            $resultAsString = curl_exec($curlResource);
            $resultAsObject = json_decode($resultAsString);
            $anotherWayOfError = curl_error($curlResource);
            curl_close($curlResource);

            $filters = array();
            $units = array();
            $terciaryValues = array();
            $cuaternaryValues = array();


				/* COMPONEMOS LOS ARRAYS PARA LOS SELECTORES*/
				
            if (!empty($resultAsObject)){
            	$books = current($resultAsObject);
            	
                foreach ($books as $book) {
                	  //$id = current($book->_id);
                	  $id = $book->course->id;
                	  //print_r(current($book->course->title));
                	  ////echo '<br><br>';
                    $filters[$id]['title'] = current($book->course->title);
                    foreach ($book->auOrBlock as $unit) {
                    		$nuevaUnidad = array();
							$nuevaUnidad['id'] = current($unit->_id);
                    		$nuevaUnidad['title'] = current($unit->title);
                    		$nuevaUnidad['url'] = $unit->url;
                    		$filters[$id]['units'][] = $nuevaUnidad;	
                    } 

                }

                	foreach ($filters as $key => $element){

                    $terciaryValues[$key] = $element['title'];
                    foreach ($element['units'] as $unit) {
                    		$nuevaUnidad = array();
                    		$nuevaUnidad['title'] = $unit['title'];
								$nuevaUnidad['url'] = $unit['url'];
                    		$cuaternaryValues[$key][] = $nuevaUnidad; 
                    		 
                    	
                    }
                                        
                    
                }
                
                /*
                foreach ($filters[current($secondaryfilter)->value] as $element){
                    $secondaryValues[$element->id] = $element->name;                    
                }*/
            }
            
        } catch (Exception $e) {
            
        }
       /* VINCULAMOS PHP CON JAVASCRIPT */	
       $PAGE->requires->js_init_call('initSelectsContainers', array(json_encode($terciaryValues),json_encode($cuaternaryValues)), true);
      
		}
		  
        $nextstep = $step + 1;
           
       
        
        	/******************************************/
    		/* INSERTAMOS FIELDSET CON HEADER PARA LA	*/ 
    		/*	SELECCIÓN DE UNIDAD							*/
    		/******************************************/
	
        $mform->addElement('header', 'unittype', get_string('unittype', 'smartclassroom'));

  			if ($errorFilters !== false)
  			{
  					//FILTRO PRIMARIO
			 	  $selectP = $mform->addElement('select', 'scrprimary', 
			 	  											get_string('selection', 'smartclassroom') . ' ' . $primaryFilterName,
			 	  											array('' => get_string('selection', 'smartclassroom') . ' ' . $primaryFilterName)+$primaryValues);
			 	  
		        
			 	  if ($step > 0) $selectP->setSelected($primarySelected);
		      
		      	//FILTRO SECUNDARIO
		        $selectS = $mform->addElement('select', 'scrsecondary', 
		        											get_string('selection', 'smartclassroom') . ' ' . $secondaryFilterName,
		        											array('' => get_string('selection', 'smartclassroom') . ' ' . $secondaryFilterName)+$secondaryValues);
		        											
					if ($step > 0) $selectS->setSelected($secondarySelected);
					        
		        $mform->addElement('html','<div><button type="button" id="nextButton" onclick="FiltraLibros(1);">Buscar libros</button></div>');
		        
		        
				  if ($step > 0){
				  
				  		//FILTRO TERCIARIO
						$selectT = $mform->addElement('select', 'scrterciary', 
																get_string('selectbook', 'smartclassroom'),
																array('' => get_string('selectbook', 'smartclassroom'))+ $terciaryValues, 
																array('onchange' => 'javascript:fillCuaternary()'));
				  
						/* if ($step > 2) $selectT->setSelected($terciarySelected);*/
				  
				  		//FILTRO CUATERNARIO
						$selectC = $mform->addElement('select', 'scrcuaternary', 
																get_string('selectunit', 'smartclassroom'), 
																array('' => get_string('selectunit', 'smartclassroom')),
																array());
			  
						/* if ($step > 2) $selectC->setSelected($cuaternarySelected);*/
						
			        $mform->addElement('html','<div><button type="button" id="createLTIButton" onclick="CreaActivity(2);">Crear Actividad</button></div>');
			        
				  }
		
  			} //INFORMAMOS DE ERRORES EN CASO DE HABERLOS
  			else {
  				
  				$mform->addElement('html',"<div><p>".$errorFilters."</p></div>");
  				$mform->addElement('html',"<div><p>".$resultAsString."</p></div>");
  			}
  			//EL FIELDSET DE UNIDADES LO DEJAMOS EXTENDIDO
  			 $mform->setExpanded('unittype');
  			 
		   // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();
        
        //MODO NATIVO
        $mform->addElement('header', 'nativemodeheader', get_string('nativemode', 'smartclassroom'));

        $selectNative = $mform->addElement('select', 'nativemode', get_string('nativemode', 'smartclassroom'), array(
						"" => get_string("selectnative",'smartclassroom'),
				                "0" => get_string("nativo", 'smartclassroom'),
						"1" => get_string("ventana", 'smartclassroom'),
			));
			 $selectNative->setSelected($nativeMode);
        
        // Elementos comunes estándar
        $this->standard_coursemodule_elements();

       
        
    }
}
