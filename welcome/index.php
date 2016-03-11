
<?php

include_once('preInc.inc');

?>

<div class="ShadowLeft">
<div class="ShadowRight">
<div class="MiddlePart">
<div class="Bars">

<?php

$page = "welcome";

if(isset($_GET["page"]))
{
  $page = $_GET["page"];
}

$Menu = array("index.php?page=welcome" => _("Nos Offres"),
	      /*	      "index.php?page=btopbox" => _("B-Top Box"), */
	      "index.php?page=faq" => _("Questions-Réponses"),
	      /*	      "index.php?page=tarifs" => _("Tarifs et Conditions"), */
	      $LINKS["BluePortail"]  => _("Espace client") );


echo formMenu($Menu, "index.php?page=" . $page);

?>

</div>

<?php

include_once($page . "_" . LANG . ".inc");

?>

</div>
</div>
</div>

<?php

include_once('postInc.inc');

?>

