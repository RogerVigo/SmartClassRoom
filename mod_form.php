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
        global $CFG;

        $mform = $this->_form;

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

        $mform->addElement('header', 'unittype', get_string('unittype', 'smartclassroom'));

        //$mform->addElement('static', 'label2', 'smartclassroomsetting2', 'Your smartclassroom fields go here. Replace me!');
	$mform->addElement('select', 'course', get_string('course', 'smartclassroom'), array(
		"1primary" => get_string("firstprimary", 'smartclassroom'),
		"2primary" => get_string("secondprimary", 'smartclassroom'),
		"3primary" => get_string("thirdprimary", 'smartclassroom'),
		"4primary" => get_string("fourthprimary", 'smartclassroom'),
		"5primary" => get_string("fifthprimary", 'smartclassroom'),
		"6primary" => get_string("sixthprimary", 'smartclassroom'),
		"1secundary" => get_string("firstsecundary", 'smartclassroom'),
		"2secundary" => get_string("secondsecundary", 'smartclassroom'),
		"3secundary" => get_string("thirdsecundary", 'smartclassroom'),
		"4secundary" => get_string("fourthsecundary", 'smartclassroom'))
	);
	$mform->addElement('select', 'book', get_string('book', 'smartclassroom'), array(
		"maths" => get_string("maths", 'smartclassroom'),
		"spanish" => get_string("spanish", 'smartclassroom'),
		"english" => get_string("english", 'smartclassroom'),
		"science" => get_string("science", 'smartclassroom'),
	));
	$mform->addElement('select', 'unity', get_string('unity', 'smartclassroom'), array(
		"first" => get_string("first", 'smartclassroom'),
		"second" => get_string("second", 'smartclassroom'),
		"third" => get_string("third", 'smartclassroom'),
	));

        // Add standard grading elements.
        $this->standard_grading_coursemodule_elements();

        // Add standard elements, common to all modules.
        $this->standard_coursemodule_elements();

        // Add standard buttons, common to all modules.
        $this->add_action_buttons();
    }
}
