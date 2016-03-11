<?php

include_once("IndicatorFactory.inc");

function getEntitiesTable($session, $entity)
{
  global $glang;

  $counters = $glang->getCountersIds( array( "login" => $session["login"],
					     "access" => $session["access"],
					     "Entity" => $entity,
					     "includeAll" => true ) );

  if($entity == "door" or $entity == "group")
    {
      $prefix = "f_";
    }
  else
    {
      $prefix = "z_";
    }

  // table header
  $userTable = "<table>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("System name")) . 
    "</th><th>" . myhtmlentities(_("Current name")) . 
    "</th><th>" . myhtmlentities(_("Display")) . "</th></tr>\n";
  $str = "";
  foreach($counters as $k => $v)
    {
      $str .= "<tr><th>" . myhtmlentities($v["oname"]) . "</th>";
      $str .= "<td><input type=\"text\" name=\"" . $prefix . $k . "_name\" value=\"" . myhtmlentities($v["name"]) . "\" />";
      $str .= "<input type=\"hidden\" name=\"" . $prefix . $k . "_id\" value=\"$k\" /></td>";	  
      $checked = $v["enabled"] ? "checked" : "";
      $str .= "<td><input type=\"checkbox\" name=\"" . $prefix . $k . "_enabled\" $checked /></td>";
      $str .= "</tr>\n";
    }
  $userTable .= $str;
  $userTable .= "</table>\n";

  return $userTable;
}

function saveEntitiesParameters($userSession, $params, $entity)
{
  global $glang;

  if($entity == "door" or $entity == "group")
    {
      $table = "Flows";
      $prefix = "f_";
    }
  else
    {
      $table = "Zones";
      $prefix = "z_";
    }

  //  var_dump($params);

  // a user entity defined parameters must start with a prefix
  // then end with "_id" for the unique id
  // and end with "_name" for the name parameter value
  // and "_enabled" for where it should be displayed or not
  //  $e = $glang->clang->getCountersIds(array("Entity" => $entity));
  if($entity == "location")
    {
      $e = $glang->clang->getLocations();
    }
  else if($entity == "area")
    {
      $e = $glang->clang->getLocationAreas();
    }
  else if($entity == "door")
    {
      $e = $glang->clang->getDoors();
    }
  else 
    {
      $e = $glang->clang->getDoorGroups();
    }

  $glang->config->updateConfigData($userSession,
				   createConfigPairsFromName($prefix, $params, $e),
				   $table . "Name");
  $glang->config->updateConfigData($userSession,
				   createConfigPairsFromEnabled($prefix, $params, $e),
				   $table . "Enabled");
  return "";
}

// returns an array containing
// all pair values which will be used
// to update the configuration table of a particular section.
// the specified array contains pair values indexed by a string in the format :
// PRE_$name_POST where PRE and POST are constant strings
// exemple : f_5_enabled => f_ represents a flow type, 5 is the flow name (unique id) and
// _enabled is the property.
function createConfigPairsFromName($pre, $params, $index)
{
  $post = "_name";
  $prel = strlen($pre);
  $postl = strlen($post);
  $res = array();
  foreach($index as $k => $v)
    {
      if(isset($params[$pre . $k . $post]))
	{
	  $res[$k] = $params[$pre . $k . $post];
	}
    }
  return $res;
}

function createConfigPairsFromEnabled($pre, $params, $index)
{
  $post = "_enabled";
  $prel = strlen($pre);
  $postl = strlen($post);
  $res = array();
  foreach($index as $k => $v)
    {
      $res[$k] = isset($params[$pre . $k . $post]) ? "true" : "false";
    }
  return $res;
}

function saveGeneralParameters($userSession, $params)
{
  global $glang;

  if(isset($params["u_Account"]) and $params["u_login"] != "admin")
    {
      $params["u_access"] = 1;
    }
  else
    {
      $params["u_access"] = 0;
    }

  // check if we have to update the login passwd data
  if(!updateUserValues(array( "login" => $params["u_login"],
			      "prevLogin" => $params["u_prevLogin"],
			      "passwd" => $params["u_passwd"],
			      "access" => $params["u_access"])))
  {
    return myhtmlentities(_("This login already exist. Please use a different one."));
  }
  
  if($params["u_login"] != $params["u_prevLogin"])
    { 
      // if the login change, then we have to update all the configuration
      // section and replace the login
      $sections = $glang->config->getSectionsList();

      foreach($sections as $k => $v)
	{
	  $glang->config->changeConfigOwner($params["u_prevLogin"], $params["u_login"], $k);
	}
    }
  
  $params["login"] = $params["u_login"];

  $userSession["login"] = $params["login"];
  $userSession["passwd"] = $params["u_passwd"];


  // update the language and session duration values
  $glang->config->updateConfigData($userSession, array( "Account" => isset($params["u_Account"]) ? "enabled" : "disabled",
							"LANG" => $params["u_LANG"],
							"SESSIONTIME" => $params["u_SESSIONTIME"] ) );

  // update the logo value
  if(isset($_FILES) and $_FILES["LOGO"]["name"])
    {
      if(is_uploaded_file($_FILES["LOGO"]["tmp_name"]))
	{
	  // check resolution and size
	  if($_FILES["LOGO"]["size"] <= MAXLOGOSIZE)
	    {
	      $infos = array();
	      exec('identify -format "%w:%h:%e" ' . $_FILES["LOGO"]["tmp_name"], $infos);
	      if(count($infos))
		{
		  list($w, $h, $format) = split(":", $infos[0]);
		  if($w <= MAXLOGOWIDTH and $h <= MAXLOGOHEIGHT)
		    {
		      $userConfigData = $glang->getConfigData(array( "login" => $userSession["login"],
								     "access" => $userSession["access"],
								     "configSection" => "Config",
								     "userSection" => "User" ) );
		      exec('convert ' . $_FILES["LOGO"]["tmp_name"] . " " . $userConfigData['LOGO_ABSOLUTE_PATH']['value']);
		    }
		  else
		    {
		      return myhtmlentities(_("The logo image resolution is too high %wx%h instead of maximum"))." " . MAXLOGOWIDTH . "x" . MAXLOGOHEIGHT . ".";
		    }
		}
	      else
		{
		  return myhtmlentities(_("The logo file is not an image or it is not in a supported format (jpeg, gif, png, pnm)."));
		}
	    }
	  else
	    {
	      return myhtmlentities(_("The logo file is too big"))." (" . round($_FILES["LOGO"]["size"] / 1024) . myhtmlentities(_("kb")). " " . 
		myhtmlentities(_("instead of maximum")). " " . round(MAXLOGOSIZE / 1024) . myhtmlentities(_("kb")).").";
	    }
	}
      else
	{
	  return myhtmlentities(_("The logo could not be update maybe because of its size or its format."));
	}
    }
  
  return "";
}

function getGeneralParameters($userSession, $userConfigData, $params)
{
  $userTable = "<table>\n";

  // account enabled
  if($userSession["login"] == "admin")
    {
      $str = "<input type=\"hidden\" name=\"u_Account\" value=\"enabled\" />";
    }
  else
    {
      $checked = $userSession["access"] > 0 ? "checked" : "";
      $str = "<input type=\"checkbox\" name=\"u_Account\" $checked>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Account enabled")) . " : </th><td>$str</td></tr>\n";
    }

  // login
  $str = "<input name=\"u_login\" type=\"text\" size=\"16\" maxlength=\"16\" value=\"" . $userSession["login"];
  $editMode = "";
  if($userSession["login"] == "admin")
    {
      $editMode = "disabled";
    }
  $str .= "\" $editMode>\n";
  $str .= "<input type=\"hidden\" name=\"u_prevLogin\" value=\"" . $userSession["login"] . "\">\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Login")) . " : </th><td>$str</td></tr>\n";
  
  // passwd
  $str = "<input name=\"u_passwd\" type=\"password\" size=\"16\" maxlength=\"16\" value=\"" . $userSession["passwd"] . "\">\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Password")) . " : </th><td>$str</td></tr>\n";
  
  // access
  
  //  var_dump($userConfigData);

  $admin = $userConfigData["Access"]["access"] > 1 ? "selected" : "";
  $consult = $userConfigData["Access"]["access"] < 2 ? "selected" : "";
  $str = "<select name=\"u_Access\" disabled><option value=\"Admin\" $admin>" . myhtmlentities(_("Admin")) . "</option><option value=\"Consult\" $consult>" . myhtmlentities(_("Consult")) . "</option></select>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Access")) . " : </th><td>$str</td></tr>\n";
  
  // language
  $fr = $userConfigData["LANG"]["value"] == "fr_FR" ? "selected" : "";
  $en = $userConfigData["LANG"]["value"] == "en_GB" ? "selected" : "";
  $es = $userConfigData["LANG"]["value"] == "es_ES" ? "selected" : "";
  $jp = $userConfigData["LANG"]["value"] == "ja_JP.utf8" ? "selected" : "";
  $str = "<select name=\"u_LANG\">" . 
	"<option value=\"fr_FR\" $fr>" . myhtmlentities(_("French")) . "</option>" . 
	"<option value=\"en_GB\" $en>" . myhtmlentities(_("English")) . "</option>" .
	"<option value=\"es_ES\" $es>" . myhtmlentities(_("Spanish")) . "</option>" . 
	//"<option value=\"ja_JP.utf8\" $jp>" . myhtmlentities(_("Japanese")) . "</option>" . 
	"</select>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Language")) . " : </th><td>$str</td></tr>\n";
  
  // session time
  $inf = $userConfigData["SESSIONTIME"]["value"] == 0 ? "selected" : "";
  $one = $userConfigData["SESSIONTIME"]["value"] == 3600 ? "selected" : "";
  $eight = $userConfigData["SESSIONTIME"]["value"] == 28800 ? "selected" : "";
  $day = $userConfigData["SESSIONTIME"]["value"] == 86400 ? "selected" : "";
  $str = "<select name=\"u_SESSIONTIME\"><option value=\"0\" $inf>" . myhtmlentities(_("Unlimited")) . "</option>" .
    "<option value=\"3600\" $one>" . myhtmlentities(_("1 Hour")) . "</option>" .
    "<option value=\"28800\" $eight>" . myhtmlentities(_("8 Hours")) . "</option>" .
    "<option value=\"86400\" $day>" . myhtmlentities(_("1 Day")) . "</option>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Session duration")) . " : </th><td>$str</td></tr>\n";

  // logo
  $userTable .=  "<tr><th>" . myhtmlentities(_("Logo")) . " <span style=\"font-size: smaller;\">(".myhtmlentities(_("max.")). " " . MAXLOGOWIDTH . "x" . MAXLOGOHEIGHT . "/" . 
    round(MAXLOGOSIZE / 1024) . myhtmlentities(_("kb")). ")</span>" . " : </th><td>";
  $userTable .= "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"" . MAXLOGOSIZE . "\">\n" . 
    "<input type=\"file\" name=\"LOGO\" /></td></tr>\n";

  $userTable .= "</table>\n";  

  return $userTable;
}

function saveDefaultPageParameters($userSession, $params)
{
  global $glang;

  // update the language and session duration values
  $glang->config->updateConfigData($userSession, array( "SITE_TITLE" => $params["u_SITE_TITLE"],
							"AnalysisSelection" => isset($params["u_AnalysisSelection"]) ? "enabled" : "disabled",
							"EntitiesSelection" => isset($params["u_EntitiesSelection"]) ? "enabled" : "disabled",
							"Analysis" => $params["u_Analysis"],
							"Step" => $params["u_Step"],
							"Entity" => $params["u_Entity"],
							"id" => $params["u_id"] ) );
  return "";
}

function getDefaultPageParameters($userSession, $userConfigData, $params)
{
  global $glang;
  global $clientBluePortail;

  $params["Analysis"] = $userConfigData["Analysis"]["value"];
  $indicator = getIndicator($glang, $params);

  $userTable = "<table>\n";
  $str = "<input type=\"text\" name=\"u_SITE_TITLE\" value=\"" . $userConfigData["SITE_TITLE"]["value"] . "\">";
  $userTable .= "<tr><th>" . myhtmlentities(_("Site title")) . " : </th><td>$str</td></tr>\n";
  $checked = $userConfigData["AnalysisSelection"]["value"] == "enabled" ? "checked" : "";
  $str = "<input type=\"checkbox\" name=\"u_AnalysisSelection\" $checked>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Analysis box")) . " : </th><td>$str</td></tr>\n";
  
  // entities selection
  $checked = $userConfigData["EntitiesSelection"]["value"] == "enabled" ? "checked" : "";
  $str = "<input type=\"checkbox\" name=\"u_EntitiesSelection\" $checked>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Entities box")) . " : </th><td>$str</td></tr>\n";
  
  // default page parameters
  // analysis
  $str = getSelectFromArray("u_Analysis", 
			    // FAB : ON DOIT POUVOIR UTILISER LE TABLEAU ENTITIES ANALYSIS DIRECTOS
			    array ( "flows" => _("General") . " => " . _("In/Out flows"),
				    "visitors" => _("General") . " => " . _("Visitors"),
				    "occupancy" => _("General") . " => " . _("Occupancy"),
				    "numbering" => _("General") . " => " . _("Numbering"),
				    "waitingTime" => _("General") . " => " . _("Waiting Time"),
				    "flowDoorComp" => _("Comparisons") . " => " . _("In/Out flows"),
				    "visLocComp" => _("Comparisons") . " => " . _("Visitors"),
				    "numberingLocComp" => _("Comparisons") . " => " . _("Numbering")),
			    $userConfigData["Analysis"]["value"],
			    "onChange=\"changeIndicator($clientBluePortail);\" id=\"SelectAnalysis\"");
  $userTable .= "<tr><th>" . myhtmlentities(_("Analysis")) . " : </th><td>$str</td></tr>\n";
  
  // step
  $str = getSelectFromArray("u_Step",
			    $indicator->getAvailableSteps(),
			    $userConfigData["Step"]["value"]);
  $userTable .= "<tr><th>" . myhtmlentities(_("Step")) . " : </th><td>$str</td></tr>\n";
  
  // entities parameters
  $str = getSelectFromArray("u_Entity", 
			    $indicator->getAvailableEntities(),
			    $userConfigData["Entity"]["value"],
			    "onChange=\"changeEntity($clientBluePortail);\" id=\"SelectEntity\"");
  $userTable .= "<tr><th>" . myhtmlentities(_("Entity")) . " : </th><td>$str</td></tr>\n";
  
  // entities list
  $analysis = $userConfigData["Analysis"]["value"];
  $entity = $userConfigData["Entity"]["value"];
  $id = $userConfigData["id"]["value"];
  
  $entitiesList = $indicator->getEntitiesList($entity);
  $str = "<select name=\"u_id\" id=\"SelectEntityList\">\n";
  foreach($entitiesList as $idx => $v)
    {
      $str .= "<option value=\"" . $v["id"] . "\"";
      if($id == $v["id"])
	{
	  $str .= " selected";
	}
      $str .= ">" . myhtmlentities($v["name"]) . "</option>\n";
    }
  $str .= "</select>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_($indicator->getName())) . " : </th><td>$str</td></tr>\n";
  $userTable .= "</table>\n";

  return $userTable;
}

function saveIndicatorParameters($userSession, $params)
{
  global $glang;
  global $Analysis;

  // a user indicator defined parameters must start with "i_"
  $glang->config->updateConfigData($userSession, 
				   createConfigPairsFromEnabled("i_", $params, $Analysis), 
				   "IndicatorsEnabled");
  return "";
}

function getIndicatorParameters($userSession, $userConfigData, $params)
{
  global $glang;
  // FAB : ON DOIT POUVOIR UTILISER LE TABLEAU ENTITIES ANALYSIS DIRECTOS
  global $Analysis;

  $userTable = "<table>\n";
  $userTable .= "<tr><th>" . myhtmlentities(_("Indicator name")) . "</th><th>" . _("Display") . "</th></tr>\n";
  $indicatorList = $glang->getConfigData(array( "login" => $userSession["login"],
						"access" => $userSession["access"],
						"configSection" => "IndicatorsEnabled",
						"userSection" => "User" ) );
  $str = "";
  foreach($indicatorList as $k => $v)
    {
      $str .= "<tr><th>" . myhtmlentities(_($Analysis[$k])) . "</th>";
      $checked = "";
      if($v["value"] == "true")
	{
	  $checked = "checked";
	}
      $str .= "<td><input type=\"checkbox\" name=\"i_" . $k . "_enabled\" $checked /></td>";
      $str .= "</tr>\n";
    }
  $userTable .= $str;
  $userTable .= "</table>\n";

  return $userTable;
}

function getAdminMenu($sections, $params)
{
  $str = "<div class=\"SectionsMenu\">\n";
  $str .= "<h3>" . myhtmlentities(_("Section")) ." : " . myhtmlentities($sections[$params["section"]]) . "</h3>\n";
  $str .= "<ul class=\"Section\">\n";
  foreach($sections as $k => $v)
    {
      if($params["section"] == $k)
	{
	  $str .= "<li class=\"Entry\"><u>" . myhtmlentities($v) . "</u></li>\n";
	}
      else
	{
	  $str .= "<li class=\"Entry\"><a href=\"#\" onClick=\"javascript:sectionChanged('$k');\">" . myhtmlentities($v) . "</a></li>\n";
	}
    }
  $str .= "</ul>\n";
  $str .= "</div>\n";
  return $str;
}

?>
