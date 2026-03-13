<?php
// calcular tempo geral script
$apptimeexec_i = microtime(TRUE);

session_start();

require_once("cfg/parametro.php");
require_once("cfg/cfg_ambiente.php");
require_once("cfg/cfg_fixo.php");

require_once("cfg/funcao.php");
require_once("cfg/cfg_app.php");
require_once("cfg/cfg_api.php");

valida_login();
?>