<?php

include_once('Config.inc');

loadBluePHP();

include_once("BlueCountGUILang.inc");

$glang = new BlueCountGUILang();

date_default_timezone_set($glang->getTimeZone());

$params = array();

if(isset($_GET))
{
  $params = $_GET;
}

if(!isset($params["function"]))
{
  $params["function"] = "version";
}

$elts = $glang->callFunction($params["function"], $params);

// arrays could contains
// recursively arrays
function print_key($k, $v)
{
  $str = "";
  if(is_array($v))
    {
      $str .= "<key>$k</key>\n";
      $str .= print_array_keys($v);
    }
  else
    {
      $str .= "<$k>$v</$k>\n";
    }
  return $str;
}

function print_array_keys($a)
{
  $str = "<array>\n";
  foreach($a as $k => $v)
    {
      $str .= print_key($k, $v);
    }
  $str .= "</array>\n";
  return $str;
}

// returns array elements
// in the form :
// <array>
// <key>TEXT</key> 
// <array>
// ...
// <id>ID1</id>
// <name>NAME1</name>
// ...
// </array>
// <array>
// <id>ID2</id>
// <name>NAME2</name>
// ...
// </array>
// ...
// </array>
// where array is on the form
// array( TEXT => array( "id" => ID1, "name" => NAME1 ), ... );
if(is_array($elts))
{
  header("Content-Type: text/xml");
  echo print_array_keys($elts);
}

$glang->close();

?>

