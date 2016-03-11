<?php

require_once("Window.inc");
require_once("Forms.inc");

class ModuleInterface extends Window
{
  var $params = array( "module" => false,
		       "name" => false,
		       "pagename" => false,
		       "titles" => array( "list" => false, 
					  "edit" => false,
					  "new" => false ));

  function ModuleInterface($params)
  {
    parent::Window("ModuleInterface", "ModuleInterface");  
    $this->params = $params;
  }

  function setName($name)
  {
    $this->params["name"] = $name;
  }

  function getName()
  {
    return $this->params["name"];
  }

  function setModule($module)
  {
    $this->params["module"] = $module;
  }

  function getModule()
  {
    return $this->params["module"];
  }

  function getFormInput($params)
  {
    return $this->getHiddenInput($this->getModule()->getKey(), $params[$this->getModule()->getKey()]);
  }

  function editEntry($params)
  {
    $params = array_merge($params, $this->getModule()->getEntry($params));
    return $this->getEditTitle($this->getName()) . $this->getTableInput($params);
  }

  function newEntry($params)
  {
    $params = array_merge($params, $this->getModule()->getEntry($params));
    return $this->getNewTitle($this->getName()) . $this->getTableInput($params);    
  }

  function insertEntry($params)
  {
    return $this->getInsertMsg($this->getModule()->newEntry($params));
  }

  function updateEntry($params)
  {
    return $this->getUpdateMsg($this->getModule()->setEntry($params));
  }

  function deleteEntry($params)
  {
    return $this->getDeleteMsg($this->getModule()->delEntry($params));
  }

  function getEditTitle($name)
  {
    return "<h2>Edit an existing $name</h2>\n";
  }

  function getNewTitle($name)
  {
    return "<h2>Add a new $name</h2>\n";
  }

  function getInsertMsg($res)
  {
    return $res ? "<h2>Entry inserted.</h2>\n" : "<h2>Error during insertion.</h2>\n";
  }
  
  function getUpdateMsg($res)
  {
    return $res ? "<h2>Entry updated.</h2>\n" : "<h2>Error during update.</h2>\n";
  }

  function getDeleteMsg($res)
  {
    return $res ? "<h2>Entry deleted.</h2>\n" : "<h2>Error during deletion.</h2>\n";
  }

  function getHiddenInput($name, $value)
  {
    return getInput(array( "type" => "hidden",
			   "name" => $name,
			   "value" => $value ));
  }

  function handleAction($params, $page, $extraInputs = "")
  {
    $str = "";
    $params = array_merge( array( "action" => "new" ),
			   $params);   

    if($params["action"] == "new")
      {
	$str = getForm(getInput(array( "type" => "hidden", "name" => "action", "value" => "insert" )) .
		       $extraInputs . 
		       $this->newEntry($params) .
		       getInput(array( "type" => "submit", "value" => "Apply" )),
		       array( "method" => "get", "action" => $page, "name" => "action" ));
	
      }
    else if($params["action"] == "edit")
      {
	$str = getForm(getInput(array( "type" => "hidden", "name" => "action", "value" => "update" )) .
		       $extraInputs .
		       $this->getFormInput($params) . 
		       $this->editEntry($params) .
		       getInput(array( "type" => "submit", "value" => "Apply" )),
		       array( "method" => "get", "action" => $page ));     
      }
    else if($params["action"] == "insert")
      {
	$str = $this->insertEntry($params);
      }
    else if($params["action"] == "update")
      {
	$str = $this->updateEntry($params);
      }
    else if($params["action"] == "delete")
      {
	$str = $this->deleteEntry($params);
      }

    return $str;
  }

  protected function getModuleTableList($params, $pagename)
  {
    $pageData = array_merge( array( "query" => "" ), parse_url($pagename) );
    $create = "<a href=\"" . $pageData["path"] . "?action=new" . 
      "&" . $pageData["query"] . "\">" . $params["ctitle"] . "</a>\n";
    $str = $create;
    $str .= "<table border=\"1\">\n";
    $str .= "<tr>";
    foreach($params["titles"] as $k => $v)
      {
	$str .= "<th>$v</th>";
      }
    // add edit and delete actions titles
    $str .= "<th>" . _("Edit") . "</th><th>" . _("Delete") . "</th></tr>\n";
    $data = $this->getModule()->getEntries();
    foreach($data as $k => $v)
      {
	$str .= "<tr>";
	foreach($params["cols"] as $l => $w)
	  {
	    $str .= "<td>" . $v[$w] . "</td>";
	  }
	// add edit and delete actions links
	$keyparam = $this->getModule()->getKeyCond($v, "&");
	$str .= "<td><a href=\"" . $pageData["path"] . "?action=edit&$keyparam&" . 
	  $pageData["query"] . "\">X</a></td><td><a href=\"" . 
	  $pageData["path"] . "?action=delete&$keyparam&" . 
	  $pageData["query"] . "\">X</a></td>";
	$str .= "</tr>\n";
      }
    $str .= "</table>\n";
    $str .= $create;
    return $str;
  }
}

?>
