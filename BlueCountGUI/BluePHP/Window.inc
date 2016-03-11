<?php

class Window
{
  var $title = false;
  var $content = false;
  var $winParams = array();

  function Window($title, $content)
    {
      $this->title = $title;
      $this->content = $content;
    }

  function getTitle()
  {
    return $this->title;
  }

  function getParams()
  {
    return $this->winParams;
  }

  function setParams($winParams)
  {
    $this->winParams = $winParams;
  }

  function setParam($name, $value)
  {
    $this->winParams[$name] = $value;
  }

  function setTitle($title)
  {
    $this->title = $title;
  }

  function setContent($content)
  {
    $this->content = $content;
  }

  function render()
  {
    include_once("templates/" . $this->content . ".template");
  }
}

?>
