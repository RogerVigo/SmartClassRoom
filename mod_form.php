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

require_once($CFG->dirroot . '/course/moodleform_mod.php');

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
        global $CFG, $PAGE, $DB;

        $mform = $this->_form;
        $step = optional_param('step', 0, PARAM_INT);

        $section = optional_param('section', 0, PARAM_INT);
        $course = optional_param('course', 0, PARAM_INT);
        $return = optional_param('return', 0, PARAM_INT);
        $sr = optional_param('sr', 0, PARAM_INT);

      	$primaryfilter = $DB->get_records('config', array('name' => "smartclassroom_primaryfilter"),'', 'name,value');
        $secondaryfilter = $DB->get_records('config', array('name' => "smartclassroom_secondaryfilter"),'', 'name,value');
       
        try {
            $curlResource = curl_init();

            curl_setopt($curlResource, CURLOPT_URL, "http://vm33.netexlearning.cloud/mvc/rest/v1/customers/1/bookmetadatas");
//		$authHeader = 'Authorization: Basic ' . base64_encode('smart-client:gave-chile-moment-wood');
            //Peticion GET
            curl_setopt($curlResource, CURLOPT_HTTPGET, true);
            //Header con el authorization
            //               curl_setopt($curlResource, CURLOPT_HTTPHEADER, array($authHeader));
            //no vuelques la respuesta, devuelvemela en un string
            curl_setopt($curlResource, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Accept: text/plain'));
            $resultAsString = curl_exec($curlResource);
            $resultAsObject = json_decode($resultAsString);
            $anotherWayOfError = curl_error($curlResource);
            curl_close($curlResource);
            $filters = array();
            $primaryValues = array();
            $secondaryValues = array();
            if (!empty($resultAsObject)){
                foreach ($resultAsObject as $element) {
                    $filters[$element->id] = $element->metadataValues;
                }
                foreach ($filters[$primaryfilter->value] as $element){
                    $primaryValues[$element->id] = $element->name;                    
                }
                foreach ($filters[$secondaryfilter->value] as $element){
                    $secondaryValues[$element->id] = $element->name;                    
                }
            }
            
        } catch (Exception $e) {
            
        }



        $nextstep = $step + 1;
        /* echo new moodle_url('/course/modedit.php',
          array('add' => 'smartclassroom',
          'section' => $section,
          'course' => $course,
          'return' => $return,
          'sr' => $sr,
          'step' => $nextstep)
          ); */

        if ($step != 2) {

            $mform->updateAttributes(array('action' => new moodle_url('/course/modedit.php',
                        array('add' => 'smartclassroom',
                            'section' => $section,
                            'course' => $course,
                            'return' => $return,
                            'sr' => $sr,
                            'step' => $nextstep)
                )
                    )
            );
        }


        // Adding the "general" fieldset, where all the common settings are showed.
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field.
        $mform->addElement('text', 'name', get_string('smartclassroomname', 'smartclassroom'), array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'smartclassroomname', 'smartclassroom');

        // Adding the standard "intro" and "introformat" fields.
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }

        // Adding the rest of smartclassroom settings, spreading all them into this fieldset
        // ... or adding more fieldsets ('header' elements) if needed for better logic.
        // ----------------------------------------------------------------------
        $mform->addElement('header', 'unittype', get_string('unittype', 'smartclassroom'));

        //$mform->addElement('static', 'label2', 'smartclassroomsetting2', 'Your smartclassroom fields go here. Replace me!');
       /* $mform->addElement('select', 'scrcourse', get_string('selectcourse', 'smartclassroom'), array(
            "" => get_string("selectcourse", 'smartclassroom'),
            "firstprimary" => get_string("firstprimary", 'smartclassroom'),
            "secondprimary" => get_string("secondprimary", 'smartclassroom'),
            "thirdprimary" => get_string("thirdprimary", 'smartclassroom'),
            "fourthprimary" => get_string("fourthprimary", 'smartclassroom'),
            "fifthprimary" => get_string("fifthprimary", 'smartclassroom'),
            "sixthprimary" => get_string("sixthprimary", 'smartclassroom'),
            "firstsecundary" => get_string("firstsecundary", 'smartclassroom'),
            "secondsecundary" => get_string("secondsecundary", 'smartclassroom'),
            "thirdsecundary" => get_string("thirdsecundary", 'smartclassroom'),
            "fourthsecundary" => get_string("fourthsecundary", 'smartclassroom'))
        );*/
        $mform->addElement('select', 'scrcourse', get_string('selectcourse', 'smartclassroom'),$primaryValues);
        /*$mform->addElement('select', 'scrbook', get_string('selectbook', 'smartclassroom'), array(
            "" => get_string("selectbook", 'smartclassroom'),
            "maths" => get_string("maths", 'smartclassroom'),
            "spanish" => get_string("spanish", 'smartclassroom'),
            "english" => get_string("english", 'smartclassroom'),
            "science" => get_string("science", 'smartclassroom'),
        ));*/
        $mform->addElement('select', 'scrbook', get_string('selectbook', 'smartclassroom'),$secondaryValues);
        $mform->addElement('select', 'scrunit', get_string('selectunit', 'smartclassroom'), array(
            "" => get_string("selectunit", 'smartclassroom'),
            "first" => get_string("first", 'smartclassroom'),
            "second" => get_string("second", 'smartclassroom'),
            "third" => get_string("third", 'smartclassroom'),
        ));

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        //MODO NATIVO
        $mform->addElement('header', 'nativemodeheader', get_string('nativemode', 'smartclassroom'));

        $mform->addElement('select', 'nativemode', get_string('nativemode', 'smartclassroom'), array(
            "" => get_string("selectnative", 'smartclassroom'),
            "0" => get_string("nativo", 'smartclassroom'),
            "1" => get_string("ventana", 'smartclassroom'),
        ));
        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        $mform->addElement('header', 'nextheader', get_string('nextheader', 'smartclassroom'));

        switch ($step) {

            case 0:
            case 1:$mform->addElement('submit', 'next', get_string('next', 'smartclassroom'));
                break;
            case 2: $this->add_action_buttons();
                break;
            default:break;
        }
        // Add standard buttons, common to all modules.
    }

}
