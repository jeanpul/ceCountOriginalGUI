<?php

include_once('preInc.inc');

?>

<div id="main">

<?php 

$str = "<h1>" . myhtmlentities(_("Help")) . "</h1>\n";

$str .= "<h2>". myhtmlentities(_("Documentations")) . "</h2>\n";
$str .= "<ul>\n";
$str .= "<li><a href=\"CertificatInstallation/index.html\" target='_blank' rel='nofollow'>" . 
	myhtmlentities(_("Certificat Installation")) . "</a></li>\n";
$str .= "</ul>\n";

$str .= "<h2>". myhtmlentities(_("Frequently Asked Questions")) . "</h2>\n";
$str .= '<div id="FAQs"/>' . "\n";
$str .= file_get_contents(FAQ_PATH . "/FAQ.html");

$str .= "<p><a href=\"index.php\">" . myhtmlentities(_("Back to first page")) . "</a></p>";

echo $str;
?>


<div id="logopart">
<center>
 <p><img src="Logo.gif"/></p>
<?php
$str = "<p>". myhtmlentities(_("Activity analysis solution")) . "</p>";
echo $str;
?>
</center>
</div>
<div id="foot">
 <hr>
 <center>
 <p>
  <a href="http://www.blueeyevideo.com">www.blueeyevideo.com</a>
 <p>
 </center>
</div>
</div>

</body>
</html>
