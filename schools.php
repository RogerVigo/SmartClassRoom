<?php

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');

global $PAGE;

admin_externalpage_setup('smartclassroomschools');

// functionality like processing form submissions goes here
$tablaColes = '<table class="flexible admintable generaltable"><thead><th class="header c0">Colegio</th><th class="header c0">Colegio ID</th><th class="header c1">Estado</th><th class="header c2">LTI Key</th><th class="header c3">LTI Secret</th><th class="header c4">Registrar</th></thead>';
$colegios = array(array('Los Sauces', 'Los-Sauce-ID', 'Activo', 'LTI-KEY', 'LTI-PASS'),array('Maristas', 'Maristas-ID', 'Activo', 'LTI-KEY', 'LTI-PASS'),array('Franciscanos', 'Franciscanos-ID', 'Activo', 'LTI-KEY', 'LTI-PASS'),array('Pintor Laxeiro', 'Laxeiro-ID', 'Activo', 'LTI-KEY', 'LTI-PASS'));

echo $OUTPUT->header();
// your HTML goes here
$PAGE->requires->js('/mod/smartclassroom/schools.js?date='.time());

$beginning = '<div id="adminsettings"><fieldset>';
$end = '</fieldset></div>';

$content = $tablaColes;
foreach ($colegios as $coles)
{
	$content .= '<tr><td class="cell name">'.$coles[0].'</td><td class="cell schoolCode">'.$coles[1].'</td><td class="cell">'.$coles[2].'</td><td class="cell">'.$coles[3].'</td><td class="cell ltiPass">'.$coles[4].'</td><td class="cell"><button type="button" class="registrar '.$coles[1].'">Registrar</button></td></tr>';   
}

$content .= "</table>";

echo $beginning.$content.$js.$end;
echo $OUTPUT->footer();


