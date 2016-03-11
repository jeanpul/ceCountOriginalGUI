<?php

include_once('ConfigGlobal.inc');

loadBluePHP();

include_once("BluePHP/Languages.inc");
include_once("BluePHP/Forms.inc");


define ('BLUEPORTAILROOT', "/home/DATA/BluePortail/clients/" );

$clientBluePortail = false;
if(isset($_REQUEST) and isset($_REQUEST["clientBluePortail"]))
{
  $clientBluePortail = $_REQUEST["clientBluePortail"];
  include_once(BLUEPORTAILROOT . "/" . $clientBluePortail . "/BluePortail/Config.inc");
}
//else
//{
//  die("Error : No client id. Please returns <a href=\"http://www.countingsolutions.net/\">here</a>.");
//}

if(!defined(LANG))
{
  define('LANG', "fr_FR");
}
setLanguage();

$css = "styles/BPclient.css";

// check if the page should be in HTTPS or HTTP
$isHTTPS = false;
if($_SERVER["SERVER_NAME"] == "www.countingsolutions.net" or $_SERVER["HTTP_X_FORWARDED_HOST"] == "www.countingsolutions.net")
{
  // in that case every links
  // with http in it must be replaced by https
  $isHTTPS = true;
}

include_once("headerPart.inc");

$LINKS = array( "CSNET" => "http://www.countingsolutions.net/" );

?>

