<?php

class Application
{
  var $mainWindow = false;

  function Application($mainWindow)
  {
    $this->mainWindow = $mainWindow;
  }

  function render()
  {
    include_once("templates/Application.template");
  }
}

?>