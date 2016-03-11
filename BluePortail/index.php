<?php

include_once("preInc.inc");

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

$Menu = array( "index.php?page=welcome" => _("Espace client"),
	       "!statistics" => array( "/BlueCountGUI/index.php" , _("Statistiques") ),
	       "index.php?page=faq" => _("Questions-Réponses"),
	       "index.php?page=contact" => _("Nous contacter"),
	       $LINKS["CSNET"] => _("Déconnexion") );

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

include_once("postInc.inc");

?>
