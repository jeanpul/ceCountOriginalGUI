<?php

include("Config.inc");

$pageName = "help.php";
$pageLevel = 0;

include("preInc.inc");

$params = $_GET;

$frame = "<div class=\"MainPart\">\n";
$frame .= "<div class=\"ParametersBox\">\n";
$frame .= "<h1>" . myhtmlentities(_("BlueCount Manager documentations")) . "</h1>\n";
$frame .= "<ul>\n";
$frame .= "<li><a target=\"_blank\" rel=\"nofollow\" href=\"/Docs/BlueCountManager_" . $configData["LANG"]["value"] . "/index.html\">".myhtmlentities(_("Inline help"))."</a></li>\n";
$frame .= "<li><a href=\"/Docs/BlueCountManager_" . $configData["LANG"]["value"] . ".pdf\">".myhtmlentities(_("Document PDF"))."</a></li>\n";
$frame .= "</ul>\n";
$frame .= "</div>\n";
$frame .= "</div>\n";

include("postInc.inc");

?>
