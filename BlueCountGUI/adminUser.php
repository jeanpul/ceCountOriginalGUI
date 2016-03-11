<?php

include("Config.inc");

$pageName = "adminUser.php";
$pageLevel = ADMINACCESS;

include("preInc.inc");

include_once("adminUserGeneral.php");

$params = array_merge($_POST, $_GET);

$frame = "<div class=\"MainPart\">\n";
$frame .= file_get_contents("adminUserGeneral.template");

$users = $glang->config->getUsersList();

// recover user configuration
if(!isset($params["adminLogin"]))
{
  $params["adminLogin"] = $session["login"];
}

if(!isset($params["section"]))
{
  $params["section"] = "General";
}

$userSession["login"] = $params["adminLogin"];
$userSession["passwd"] = $users[$userSession["login"]]["passwd"];
$userSession["access"] = $users[$userSession["login"]]["access"];

if(isset($params["ActionSave"]))
{
  $msg = "";
  if(isset($params["section"]))
    {
      if($params["section"] == "General")
	{
	  $msg = saveGeneralParameters($userSession, $params);
	}
      else if($params["section"] == "DefaultPage")
	{
	  $msg = saveDefaultPageParameters($userSession, $params);
	}
      else if($params["section"] == "Indicator")
	{
	  $msg = saveIndicatorParameters($userSession, $params);
	}
      else
	{
	  $msg = saveEntitiesParameters($userSession, $params, $params["section"]);
	}
      if(!$msg)
	{
	  $msg = myhtmlentities(_("User preferences saved."));
	}
      // display message to confirm save state
      $frame .= "<div class=\"ParametersBox\">\n";
      $frame .= "<p>$msg";
      $frame .= "<form action=\"adminUser.php\" method=\"post\"><input type=\"hidden\" name=\"section\" value=\"" . $params["section"] . 
	"\" /><input type=\"hidden\" name=\"adminLogin\" value=\"admin\"/>" . getBluePortailInputs() . 
	"<button type=\"submit\">" . myhtmlentities(_("Return to user administration")) . "</button></form></p>\n";
      $frame .= "</div>\n";
    }
}
else
{
  // Title
  $frame .= "<div class=\"AnalysisMenuPart\">\n";
  $frame .= "<div class=\"ParametersBox\">\n";
  $frame .= "<h3>" . myhtmlentities(_("User administration")) . "</h3>";
  // User selection form
  $frame .= "<form action=\"adminUser.php\" method=\"post\" name=\"FormAdminUser\">\n";
  $frame .= "<input type=\"hidden\" name=\"section\" value=\"" . $params["section"] . "\"/>\n";
  $frame .= getBluePortailInputs();
  
  // user selection
  $str = '<select onChange="submit()" name="adminLogin">' . "\n";
  foreach($users as $idx => $v)
    {
      $str .= '<option value="' . $idx . '"';
      if(isset($params["adminLogin"]) and $params["adminLogin"] == $idx)
	{
	  $str .= "selected";     
	}
      $str .= '>' . $idx . '</option>' . "\n";
    }
  $str .= "</select>\n";
  
  $frame .= "<ul><li>" . myhtmlentities(_("User")) .  ": $str</li></ul>\n";
  $frame .= "</div>\n";

  // sub menu that select different sections
  $subMenu = array( "General" => _("General"),
		    "DefaultPage" => _("Default Page"),
		    "Indicator" => _("Analysis"),
		    "door" => _("Door"),
		    "group" => _("Group"),
		    "location" => _("Location"),
		    "area" => _("Area") );		   

  $frame .= getAdminMenu($subMenu, $params);

  $frame .= "</form>\n";
  $frame .= "</div>\n";

  $frame .= "<div class=\"DataPart\">\n";
  $frame .= "<h1>" . myhtmlentities($subMenu[$params["section"]]) . "</h1>\n";

  // now get the user preferences
  $userConfigData = $glang->getConfigData(array( "login" => $userSession["login"],
						 "access" => $userSession["access"],
						 "configSection" => "Config",
						 "userSection" => "User" ) );

  $frame .= "<form action=\"adminUser.php\" " . ($params["section"] == "General" ? "enctype=\"multipart/form-data\"" : "") . " method=\"post\">\n";  

  if($params["section"] == "General")
    {
      // ==== General part ====
      $frame .= getGeneralParameters($userSession, $userConfigData, $params);
    }
  else if($params["section"] == "DefaultPage")
    {
      // ==== Default page part ====
      $frame .= getDefaultPageParameters($userSession, $userConfigData, $params);
    }
  else if($params["section"] == "Indicator")
    {
      // ==== Indicator List ====
      $frame .= getIndicatorParameters($userSession, $userConfigData, $params);
    }
  else
    {
      $frame .= getEntitiesTable($userSession, $params["section"]);
    }

  $frame .= getBluePortailInputs();
  $frame .= "<input type=\"hidden\" name=\"adminLogin\" value=\"". $userSession["login"] . "\"/>\n";
  $frame .= "<input type=\"hidden\" name=\"section\" value=\"" . $params["section"] . "\"/>\n";
  $frame .= "<input type=\"submit\" name=\"ActionSave\" value=\"" . myhtmlentities(_("Save")) . "\" />\n";
  $frame .= "</form>\n";
  $frame .= "</div>\n";
}

$frame .= "</div>\n";

include("postInc.inc");

?>
