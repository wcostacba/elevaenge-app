<?php
function redireciona($url,$destino = "_parent") {
    global $fixo_cfg;
    
	echo '<script type="text/javascript">';
	echo "window.open('".$url."','".$destino."');";
	echo "</script>";
    
    echo "<center>";
    echo '<img src="'.$fixo_cfg["path_raiz"].'/cfg/img/loading2.gif" height="20"><br><small>Aguarde</small>';
    echo "</center>";
	
	die();
}
?>