<?php

 require(dirname(dirname(dirname(__FILE__))).'/config.php');
 require_once($CFG->libdir.'/adminlib.php');
    //admin_settingpage_setup('schools');
  // functionality like processing form submissions goes here
  
    echo $OUTPUT->header();
    
    
    print_r($tablaColes);
    // your HTML goes here
    echo $OUTPUT->footer();
