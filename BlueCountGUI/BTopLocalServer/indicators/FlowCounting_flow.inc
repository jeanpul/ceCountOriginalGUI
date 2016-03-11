<?php

/**
 * Calcul par default,
 * retourne directement les donnees de la base
 */
class FlowCountingProcessing
{
  var $clang;

  function FlowCountingProcessing($clang)
  {
    $this->clang = $clang;
  }

  function process($params = "")
  {
    $params["valueKind"] = "counting";
    return $this->clang->getValues($params);
  }
}

?>
