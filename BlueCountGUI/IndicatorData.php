<?php

$pageName = "Indicators.php";

include_once('Config.inc');

loadBluePHP();

include_once("BluePHP/Connect.inc");
include_once("BluePHP/Forms.inc");
include_once("BlueCountGUILang.inc");
include_once("BluePHP/DateOps.inc");

include_once("BluePHP/Languages.inc");

include_once('CommonOps.inc');

include_once("IndicatorFactory.inc");

include_once("UserEntities.inc");

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

// Change the TimeZone for Date and Time operations
// to match the BTopLocalServer TimeZone
date_default_timezone_set($glang->getTimeZone());

setLanguage($configData["LANG"]["value"]);

translateArrayTerms();

$userEntities = new UserEntities($glang, $params);

if(isset($ExtraParams))
  {
    $params = array_merge($params, $ExtraParams);
  }
$indicator = getIndicator($glang, $params);
$indicator->setDefaultParameters();

echo $indicator->getDataContents();

$glang->close();

?>
