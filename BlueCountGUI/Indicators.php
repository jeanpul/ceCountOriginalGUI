<?php

/**
 * Return a new indicator frame that contains
 * the indicator parameter and the indicator statistic
 * \params login An existing login name used to retrieve User parameters
 * \params access Access level for BlueCountGUILang
 * \params Indicator Indicator frame id used to encapsulate the returned data
 * \params Analysis Analysis wanted (visitors, flows, ...)
 */

function checkParams($paramName, $params)
{
  if(!isset($params[$paramName]))
    {
      die("Indicators.php : missing parameter " . $paramsName);
    }
  return $params[$paramName];
}

// check parameters
if(!isset($_GET))
  {
    die("Indicators.php : missing parameters");
  }

$params = array("login" => checkParams("login", $_GET),
		"access" => checkParams("access", $_GET),
		"Indicator" => checkParams("Indicator", $_GET),
		"Analysis" => checkParams("Analysis", $_GET));

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

$glang = new BlueCountGUILang();

$configSharedData = $glang->getSharedConfigData();

$configData = $glang->getConfigData(array( "login" => $params["login"],
					   "access" => $params["access"],
					   "configSection" => "Config",
					   "userSection" => "User" ) );

// user private indicators
$indicatorData = $glang->getConfigData(array( "login" => $params["login"],
					      "access" => $params["access"],
					      "configSection" => "IndicatorsEnabled",
					      "userSection" => "User" ) );

setLanguage($configData["LANG"]["value"]);

// Change the TimeZone for Date and Time operations
// to match the BTopLocalServer TimeZone
date_default_timezone_set($glang->getTimeZone());

translateArrayTerms();

$userEntities = new UserEntities($glang, $params);

if(isset($ExtraParams))
  {
    $params = array_merge($params, $ExtraParams);
  }
$indicator = getIndicator($glang, $params);
$indicator->setDefaultParameters();

$frame = getIndicatorMainPartContents($indicator);

echo $frame;

$glang->close();

?>
