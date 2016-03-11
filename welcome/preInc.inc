<?php

include_once('Config.inc');

loadBluePHP();

include_once("BluePHP/Languages.inc");
include_once("BluePHP/Forms.inc");

setLanguage();

include_once('headerPart.inc');

$LINKS = array( "BluePortail" => "https://comptage.arobaseinfo.net/BluePortail/",
		"DocCertificat" => "CertificatInstallation/index.html",
		"BlueEyeVideo" => "http://www.blueeyevideo.com" );

?>
