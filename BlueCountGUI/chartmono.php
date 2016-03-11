<?php
include_once('Config.inc');

loadBluePHP();

if(isset($_GET["data"]))
{
  $xmlData = file_get_contents($_GET["data"]);
  if($xmlData)
    {
      echo $xmlData;
      // FAB: ATTENTION ON EFFACE LE FICHIER DE DONNEES
      //unlink($_GET["data"]);
    }
  else
    {
      echo "<chart></chart>\n";
    }
}

?>

