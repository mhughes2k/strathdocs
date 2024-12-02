<?php

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2024112900.01;               // If version == 0 then module will not be installed
$plugin->requires  = 2010031900;      // Requires this Moodle version
$plugin->maturity  = MATURITY_ALPHA;
$plugin->cron      = 0;               // Period for cron to check this module (secs)
$plugin->component = 'local_strathdocs'; // To check on upgrade, that module sits in correct place
