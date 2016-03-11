<?php

/**
 * Calcul du temps d'attente utilisant
 * les données du numbering et du comptage.
 * Calcul le plus basique :
 * T = Numbering / flux
 */
class WaitingTimeProcessing
{
  var $clang;

  function WaitingTimeProcessing($clang)
  {
    $this->clang = $clang;
  }

  function process($params = "")
    {      
      $params["valueKind"] = "waitingTime";
      return $this->clang->getValues($params);
    }
}

?>