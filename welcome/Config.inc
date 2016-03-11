<?php

define('LANG', "fr_FR");
define('TEXT_DOMAIN', "BluePortailWelcome");

if(!defined("LANG_CHARSET"))
  {
    define('LANG_CHARSET', "UTF-8");
  }

/**
 * Used by BluePHP to display accessible url links into
 * its directories. Mainly for graphics elements into
 * the BluePHP/Graphics directory. For example
 * BluePHP/Graphics/toto.gif will be replaced
 * by BLUEPHPROOT/BluePHP/Graphics/toto.gif
 * An empty value means accessible directly from root /
 */
define ('BLUEPHPROOT', "");

/**
 * Changes default path directory to include
 * automatically files from BluePHP library
 */
function loadBluePHP()
{
  ini_set('include_path', ini_get('include_path') . ":/var/www/html/:/var/www/");
}

define('FAQ_PATH', "/usr/share/doc/blueEye/FAQs/" . LANG . "/");

?>
