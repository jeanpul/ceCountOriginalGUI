<?php

/**
 * Calcul du temps d'attente utilisant
 * les données du numbering et un facteur correctif
 * en fonction de l'intervale de valeur
 */
class WaitingTimeProcessing
{
  // waiting time from numbering
  // array ( A, B, C) 
  // A = [ left interval
  // B = right interval [ 
  // C = value returned 
  protected $numberToTime = array();

  var $clang;

  function WaitingTimeProcessing($clang)
  {
    $this->clang = $clang;
  }

  function process($params)
    {      
      // source parameters
      $this->numberToTime = $this->clang->getWaitingTimeProcessingParams($params);

      $values = array();
      $wtimeData = $this->clang->getWaitingTimeData(array("id" => $params["idp"]));
      $numData = $this->clang->getNumberingData(array("id" => $wtimeData[0]["zoneid"], "Entity" => "location"));
      $params["idp"] = $numData["idp"];
      $numValues = $this->clang->getNumberingValues($params);
      $values = $numValues;
      for($i = 0; $i < count($numValues); $i++)
      	{
      	  $values[$i] = $this->getWaitingTimeValueFromNumbering($numValues[$i]);
	}
      return $values;
    }

  function getWaitingTimeValueFromNumbering($n)
  {
    foreach($this->numberToTime as $k => $v)
      {
	if($n["value"] >= $v[0] and ($n["value"] < $v[1] or $v[1] == 0))
	  {
	    $n["value"] = $v[2];
	    return $n;
	  }
      }
    $lastV = end($this->numberToTime);
    $n["value"] = $lastV[1];
    return $n;
  }
}

?>
