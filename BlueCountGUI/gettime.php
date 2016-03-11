<?php

include_once('Config.inc');

loadBluePHP();

include_once("BluePHP/Connect.inc");
include_once("BlueCountGUILang.inc");
include_once("BluePHP/Languages.inc");

if(!isset($_GET))
{
  $params = array();
}
else
{
  $params = $_GET;
}

$glang = new BlueCountGUILang();

$configSharedData = $glang->getSharedConfigData();

$configData = $glang->getConfigData(array( "login" => $params["login"],
					   "access" => $params["access"],
					   "configSection" => "Config",
					   "userSection" => "User" ) );

date_default_timezone_set($glang->getTimeZone());

setLanguage($configData["LANG"]["value"]);

function getTime()
{
  return strftime(_("%A %e %B %Y %H:%M"));
}


echo getTime();

?>
