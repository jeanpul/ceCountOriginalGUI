<?php

$pageName = "CountingGeneric.php";

include("preInc.inc");

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

// Used with AJAX
// communication to transmit the login and access
// values in order to retrieve user config parameters
// without creating session, this is also
// used by the BlueCountGUILang into the functions
// array parameters
$params["login"] = $session["login"];
$params["access"] = $session["access"];

$userEntities = new UserEntities($glang, $params);

// if we have to set the user default indicator
$useConfigData = false;

// select the default indicator if not set
if(!isset($params["Analysis"]))
{
  $params["Analysis"] = $configData["Analysis"]["value"];
  $useConfigData = true;
}

// use only 1 indicator so set its id here
$params["Indicator"] = 0;

// create the indicator from the factory
if(isset($ExtraParams))
  {
    $params = array_merge($params, $ExtraParams);
  }
$indicator = getIndicator($glang, $params);
if($useConfigData)
{
  $indicator->setUserConfigParameters($configData);
}
// Set the default parameters if arguments
// are missing
$indicator->setDefaultParameters();

$frame = "";

// for each indicators displays the parameters into an input
$frame .= "<div class=\"MainPart\" id=\"MainPart_" . $indicator->getId() . "\">\n";
$frame .= getIndicatorMainPartContents($indicator);

// initialisations of calendar instance, 
// must be called one time and not be included
// in Indicators.php or IndicatorData.php where
// Ajax is used because this function is called in
// updateIndicator
$frame .= "<script type=\"text/javascript\">InitCalendar(\"" . $indicator->getId() . "\");</script>\n";
$frame .= "<script type=\"text/javascript\">createIndicatorInterval(\"" . $indicator->getId() . "\");</script>\n";

$frame .= "</div>\n";

//$params["Indicator"] = "1";
//$frame .= "<div class=\"MainPart\" id=\"" . $params["Indicator"] . "_MainPart\">\n";
//$frame .= getIndicatorMainPartContents($params);
//$frame .= "<script type=\"text/javascript\">InitCalendar(\"" . $params["Indicator"] . "\");</script>\n";
//$frame .= "</div>\n";

include_once("postInc.inc");

?>
