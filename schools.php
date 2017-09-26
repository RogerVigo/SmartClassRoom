<?php

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');

admin_externalpage_setup('smartclassroomschools');

// functionality like processing form submissions goes here
$tablaColes = '<table class="flexible admintable generaltable"><thead><th class="header c0">Colegio</th><th class="header c1">Estado</th><th class="header c2">LTI Key</th><th class="header c3">LTI Secret</th><th class="header c4">Registrar</th></thead>';
$colegios = array('Los Sauces','Maristas','Franciscanos','Pintor Laxeiro');

echo $OUTPUT->header();
// your HTML goes here
$beginning = '<div id="adminsettings"><fieldset>';
$end = '</fieldset></div>';

$content = $tablaColes;
foreach ($colegios as $coles)
{
	$content .= '<tr><td class="cell">'.$coles.'</td><td class="cell"></td><td class="cell"></td><td class="cell"></td><td class="cell"></td></tr>';   
}

$content .= "</table>";

echo $beginning.$content.$end;
echo $OUTPUT->footer();


