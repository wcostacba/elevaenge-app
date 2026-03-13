<?php
// config
require_once("inc_cfg_geral.php");

// reseta
unset($_SESSION);
session_destroy();

redireciona($app_cfg["path_raiz"]."/login");
?>