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
 * Provides code to be executed during the module uninstallation
 *
 * @see uninstall_plugin()
 *
 * @package    mod_smartclassroom
 * @copyright  2016 Your Name <your@email.address>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

/**
 * Custom uninstallation procedure
 */
function xmldb_smartclassroom_uninstall() {
 global $CFG, $DB;
 
 $DB->execute('DELETE FROM moodlenetex.mdl_config WHERE mdl_config.name LIKE "%smartclassroom%";');
 $DB->execute('DELETE FROM moodlenetex.mdl_config_plugin WHERE mdl_config_plugin.name LIKE "%smartclassroom%";');
 
 $root_path = "$CFG->dirroot/mod/smartclassroom/"
 
 delete_plugin($root_path);
 rmdir($root_path);
    return true;
}

function delete_plugin($dir){
 if ($handle = opendir($dir)) {
  while (($file = readdir($handle)) !== false){
   if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
    unlink($file);
   }else if(is_dir($dir.$file)){
    delete_plugin($dir);
    rmdir($file);
   }
  }
 }

