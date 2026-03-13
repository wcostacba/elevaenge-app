<?php
// cache
header("Expires: Tue, 01 Jan ".date("Y", time())." 06:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Connection: close");

// codificacao
header("Content-Type: text/html; charset=iso-8859-1");

// fuso
date_default_timezone_set("America/Cuiaba");
?>