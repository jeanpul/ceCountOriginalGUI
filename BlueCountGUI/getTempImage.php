<?php

if(isset($_GET) and isset($_GET["src"]))
{
  $src = $_GET["src"];

  $handle = fopen($src, "rb");
  if($handle)
    {
      $output = fread($handle, filesize($src));
      if($output)
	{
	  // Set the content-type
	  header("Content-type: image/jpeg");
	  header("Pragma: no-cache");
	  header("Expires: 0");      
	  print $output;
	}
      unlink($src);
    }
}

?>