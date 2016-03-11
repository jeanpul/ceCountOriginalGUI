<?php

function createInput($params)
{
  $params = array_merge(array( "type" => "text", "name" => "", "id" => "",
			       "value" => "", "readonly" => false, "extra" => ""),
			$params);
  return "<input type=\"" . $params["type"] . "\" id=\"" . $params["id"] . "\" name=\"" . $params["name"] . "\" value=\"" .
    $params["value"] . "\" " . ($params["readonly"] ? "readonly=\"readonly\"" : "") . 
    " " . $params["extra"] . "/>\n";
}

function createSubmit($data)
{
  return createInput(array("type" => "submit", "value" => $data, "name" => "submit" ));
}

function createInputText($data, $params)
{
  $params = array_merge( array( "readonly" => false,
				"extra" => false ),
			 $params );

  $readonly = "";
  if($params["readonly"])
    {
      $readonly = "Readonly";
    }

  $extra = "";
  if($params["extra"])
    {
      $extra = $params["extra"];
    }

  $str = "<input type=\"text\" name=\"" . $params["name"] . "\" value=\"" . $data . 
    "\" $readonly $extra/>\n";

  return $str;
}

function createInputTextArea($data, $params)
{
  $extra = "";
  if($params["extra"])
    {
      $extra = $params["extra"];
    }

  return "<textarea name=\"" . $params["name"] . "\" rows=\"" . $params["rows"] . 
    "\" cols=\"" . $params["cols"] . "\" $extra>" . $data . "</textarea>\n";
}

function createSelect($data, $params)
{
  $params = array_merge( array( "readonly" => false,
				"extra" => false,
				"default" => "" ),
			 $params );

  $isSimpleArrayData = $params["key"] == false;
  $columns = $isSimpleArrayData ? false : explode(',', $params["value"]);

  $extra = "";
  if($params["extra"])
    {
      $extra = $params["extra"];
    }

  $str = "<select name=\"" . $params["name"] . "\" $extra>\n";
  foreach($data as $k => $v)
    {
      $selected = "";
      $value = $isSimpleArrayData ? $v : $v[$params["key"]];
      if($params["default"] == $value)
	{
	  $selected .= " selected";
	}
      $str .= "<option value=\"$value\" $selected>";
      if($isSimpleArrayData)
	{
	  $str .= $value;
	}
      else
	{
	  foreach($columns as $c)
	    {
	      $str .= utf8_encode($v[$c]) . " ";
	    }
	}
      $str .= "</option>\n";
    }
  $str .= "</select>\n";
  return $str;
}

function createTableRow($data, $params = array())
{
  $params = array_merge( array( "type" => "td" ),
			 $params );
  $str = "<tr>";
  foreach($data as $k => $v)
    {
      $str .= "<" . $params["type"] . ">" . $v . "</" . $params["type"] . ">";
    }
  $str .= "</tr>\n";
  return $str;
}

function createTableHeaderRow($data, $params = array())
{
  return createTableRow($data, array_merge($params, array( "type" => "th" )));
}

function createAction($f, $params, $action = "onChange")
{
  $fcall = $action . "='$f(";
  $firstComa = true;
  foreach($params as $k => $v)
    {
      if($firstComa)
	{
	  $firstComa = false;
	}
      else
	{
	  $fcall .= ",";
	}
      $fcall .= "\"$v\"";
    }
  $fcall .= ")'";
  return $fcall;
}

function createInputDate($data, $params)
{
  $jsvar = $params["name"] . "_js";
  $idanchor = $params["name"] . "_anchor";

  $str = '<script language="JavaScript">' . "\n";
  $str .= 'var ' . $jsvar . ' = new CalendarPopup();' . "\n";
  $str .= '</script>' . "\n";
  $str .= '<input type="text" name="' . $params["name"] . '" value="' . $data . '" size="12" />' . "\n";
  $str .= '<a href="#" onClick="' . $jsvar . '.select(document.forms[0].' .
    $params["name"] . ",'" . $idanchor . "','" . $params["format"] . "'); " . 
    'return false;" " id="' . $idanchor . '" name="calendar">'. $params["title"] . '</a>';
  return $str;
}

function createForm($data, $params)
{
  $params = array_merge(array( "method" => "get", "action" => "",
			       "name" => "default" ),
			$params);
  return "<form method=\"" . $params["method"] . "\" action=\"" . $params["action"] . "\"" . 
    " name=\"" . $params["name"] . "\">\n" . $data . "</form>\n";
}

?>
