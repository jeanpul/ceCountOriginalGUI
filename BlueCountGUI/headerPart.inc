<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "<?php echo $isHTTPS ? "https://" : "http://"; ?>www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="<?php echo $isHTTPS ? "https://" : "http://"; ?>www.w3.org/1999/xhtml" xml:lang="en">
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" ></meta>
<meta http-equiv="Keywords" content="Blue Eye Video, Blue Count" ></meta>
<meta http-equiv="Description" content="Blue Count HTML Interface." ></meta>
<link rev="made" href="mailto:fabien.pelisson@blueeyevideo.com" ></link>
<link rel="stylesheet" type="text/css" href="<?php  echo $css; ?>" ></link>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/AnchorPosition.js"; ?>"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/PopupWindow.js"; ?>"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/date.js"; ?>"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/CalendarPopup.js"; ?>"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/Utilities.js"; ?>"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/XmlHttp.js"; ?>"></script>
<script type="text/javascript" language="JavaScript" src="<?php echo BLUEPHPROOT . "/BluePHP/js/BlueCountLang.js"; ?>"></script> 
<script type="text/javascript" language="JavaScript" src="jscripts/Analysis.js" ></script>
<link rel="stylesheet" type="text/css" href="jscalendar-1.0/calendar-blue.css" ></link>
<script type="text/javascript" language="JavaScript" src="jscalendar-1.0/calendar.js"></script>
<script type="text/javascript" language="JavaScript" src=<?php echo "\"jscalendar-1.0/lang/calendar-$lang.js\">"; ?></script>
<script type="text/javascript" language="JavaScript" src="jscalendar-1.0/calendar-setup.js"></script>
<script type="text/javascript" language="JavaScript" src="jscripts/Calendar.js"></script>
<script type="text/javascript" language="JavaScript" src="jscripts/NTP.js"></script>
<link rel="shortcut icon" href="/BlueSystem/styles/favicon.ico" type="image/vnd.microsoft.icon" ></link>
<link rel="icon" href="/BlueSystem/styles/favicon.ico" type="image/vnd.microsoft.icon" ></link>
<?php echo isset($headerExt) ? $headerExt : ""; ?>
</head>
<body>
<div class="MainContent">
