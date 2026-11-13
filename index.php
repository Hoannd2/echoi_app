<?php

function xy_start() {
    //Note: If run_by = 'INSTANT', set XY_SAME_SESSIONTIME to 1 (at config.php) to keep same application id when user refreshs the tab.
    XYSystem::run('/applications/Sample', true, true, "", array("url" => "applications/Sample/themes/images/splash.png", "runOn" => "XY"), "SEND-MESSAGE");
}

/*********************************************/
//Start XY client.
require_once("xy.php");
