<?php
function valida_login() {
    global $app_cfg;
    
	if($_SESSION["lg_".$app_cfg["id"]."_token"] != $app_cfg["token"]) {
		redireciona($app_cfg["path_raiz"]."/login");
	}
}
?>