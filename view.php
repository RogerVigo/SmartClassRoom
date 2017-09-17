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
 * Prints a particular instance of smartclassroom
 *
 * You can have a rather longer description of the file as well,
 * if you like, and it can span multiple lines.
 *
 * @package    mod_smartclassroom
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course_module ID, or
$sid  = optional_param('s', 0, PARAM_INT);  // ... smartclassroom instance ID - it should be named as the first character of the module.

if ($id) {
    $cm         = get_coursemodule_from_id('smartclassroom', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $smartclassroom  = $DB->get_record('smartclassroom', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($sid) {
    $smartclassroom  = $DB->get_record('smartclassroom', array('id' => $sid), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $smartclassroom->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('smartclassroom', $smartclassroom->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$params = array(
        'context' => context_module::instance($cm->id),
        'objectid' => $smartclassroom->id,
    );
/*$event = \mod_smartclassroom\event\course_module_viewed::create($params);
$event->add_record_snapshot('smartclassroom', $smartclassroom);
//$event->add_record_snapshot($PAGE->cm->modname, $smartclassroom);
$event->trigger();*

// Print the page header.

$PAGE->set_url('/mod/smartclassroom/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($smartclassroom->name));
$PAGE->set_heading(format_string($course->fullname));

/*
 * Other things you may want to set - remove if not needed.
 * $PAGE->set_cacheable(false);
 * $PAGE->set_focuscontrol('some-html-id');
 * $PAGE->add_body_class('smartclassroom-'.$somevar);
 */

// Output starts here.
echo $OUTPUT->header();

// Conditions to show the intro can change to look for own settings or whatever.
if ($smartclassroom->intro) {
    echo $OUTPUT->box(format_module_intro('smartclassroom', $smartclassroom, $cm->id), 'generalbox mod_introbox', 'smartclassroomintro');
}
/*
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
		if (isset($resultAsObject->access_token)) 
                        $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                get_string('token', 'smartclassroom'),
                                                                get_string('authtoken', 'smartclassroom'), 
                                                                $resultAsObject->access_token, 
                                                                PARAM_TEXT));
                    else if (isset($resultAsObject->error)) 
                                                    $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                    get_string('token', 'smartclassroom'),
                                                                    get_string('authtoken', 'smartclassroom'),
                                                                    "Error recibido: " . $resultAsObject->error,
                                                                    PARAM_TEXT));
                        else if (!$resultAsString)  $settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
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
		$settings->add(new admin_setting_configtext('SmartClassRoom_Token_Response', 
                                                                        get_string('token', 'smartclassroom'), 
                                                                        get_string('authtoken', 'smartclassroom'),
                                                                        'error irrecuperable exception: '.$anotherWayOfError,
                                                                        PARAM_TEXT));
	}

*/



// Replace the following lines with you own code.
echo $OUTPUT->heading('Yay! It works!');

echo $OUTPUT->heading('Se ha pedido el curso: ');
echo '<p>'.get_string($smartclassroom->scrunit,'smartclassroom').'</p>';
echo $OUTPUT->heading('Se ha pedido el libro: ');
echo '<p>'.get_string($smartclassroom->scrbook,'smartclassroom').'</p>';
echo $OUTPUT->heading('Se ha pedido la unidad: ');
echo '<p>'.get_string($smartclassroom->scrcourse,'smartclassroom').'</p>';

echo $OUTPUT->heading('Se mostrara al preguntar al servidor');

// Finish the page.
echo $OUTPUT->footer();
