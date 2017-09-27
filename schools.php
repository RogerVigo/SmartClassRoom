<?php

require(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once($CFG->libdir.'/adminlib.php');

global $PAGE, $DB;

admin_externalpage_setup('smartclassroomschools');

// functionality like processing form submissions goes here
$customerID = $DB->get_record('config',array('name' => "smartclassroom_clientid"),'*');
$token = $DB->get_record('config',array('name' => "smartclassroom_token_response"),'*');

$tablaColes = '<table class="flexible admintable generaltable" data-customerID="'+ $customerID->value +'" data-token="'+ $token->value +'" style="text-align:center"><thead><th class="header c0">Colegio</th><th class="header c0">Colegio ID</th><th class="header c1">Estado</th><th class="header c2">LTI Key</th><th class="header c3">LTI Secret</th><th class="header c4">Registrar</th></thead>';
$colegios = array(array('Los Sauces', '-', '<img id="imgState0" src="pix/i/warning.png">', '-', '-'),array('Maristas', '-', '<img id="imgState1"  src="pix/i/warning.png">', '-', '-'),array('Franciscanos', '-', '<img id="imgState2" src="pix/i/warning.png">', '-', '-'),array('Pintor Laxeiro', '-', '<img id="imgState3" src="pix/i/warning.png">', '-', '-'));

echo $OUTPUT->header();
// your HTML goes here
$PAGE->requires->js('/mod/smartclassroom/schools.js?date='.time());

$beginning = '<div id="adminsettings"><fieldset>';
$end = '</fieldset></div>';

$content = $tablaColes;
$index = 0;
foreach ($colegios as $coles)
{
	$content .= '<tr><td class="cell name">'.$coles[0].'</td><td class="cell schoolCode">'.$coles[1].'</td><td class="cell">'.$coles[2].'</td><td class="cell">'.$coles[3].'</td><td class="cell ltiPass">'.$coles[4].'</td><td class="cell"><button type="button" class="registrar" data-value="'.$index.'">Registrar</button></td></tr>';
	$index++;   
}

$content .= "</table>";

echo $beginning.$content.$js.$end;
echo $OUTPUT->footer();


