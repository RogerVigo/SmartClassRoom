<?php

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('smartclassroomschools');

// functionality like processing form submissions goes here
$tablaColes = '<table><thead><th>Colegio</th><th>Estado</th><th>LTI Key</th><th>LTI Secret</th><th>Registrar</th></thead>';
$colegios = array('Los Sauces','Maristas','Franciscanos','Pintor Laxeiro');

echo $OUTPUT->header();
// your HTML goes here
$beginning = '<div id="adminsettings"><fieldset>';
$end = '</fieldset></div>';

$content = $tablaColes;
foreach ($colegios as $coles)
{
	$content .= '<tr><td>'.$coles.'</td><td></td><td></td><td></td><td></td></tr>';   
}

$content .= "</table>";

echo $beginning.$content.$end;
echo $OUTPUT->footer();


