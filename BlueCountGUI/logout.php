<?php

/**
 * \file logout.php
 * This page does not include the preInc and postInc
 * because it does not render anything, it only destroy
 * the current session and redirect to the default page.
 */

session_start();

include_once("Config.inc");

loadBluePHP();

include_once("BluePHP/Session.inc");

removeCurrentAccess();

header("Location: " . DEFAULTPAGE);

?>

