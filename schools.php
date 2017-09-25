<?php

 require(dirname(dirname(dirname(__FILE__))).'/config.php');
 require_once($CFG->libdir.'/adminlib.php');
    //admin_settingpage_setup('schools');
  // functionality like processing form submissions goes here
  
    echo $OUTPUT->header();
    
    $tablaColes = '<table><thead><th>Colegio</th><th>Estado</th><th>LTI Key</th><th>LTI Secret</th><th>Registrar</th></thead>';
    $colegios = array('Los Sauces','Maristas','Franciscanos','Pintor Laxeiro');
    foreach ($colegios as $coles)
    {
		$tablaColes .= '<td>'.$coles.'</td><td></td><td></td><td></td><td></td>';   
    }
    p($tablaColes);
    print_r($tablaColes);
    // your HTML goes here
    echo $OUTPUT->footer();
