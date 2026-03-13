<?php
function mysql_log($cmd) {    
    global $app_cfg;
    
    // tipo
    if(stripos($cmd, "UPDATE") !== false) {
        $tipo = "UPDATE";        
        
    } elseif(stripos($cmd, "INSERT") !== false) {
        $tipo = "INSERT";

    } elseif(stripos($cmd, "SELECT") !== false) {
        $tipo = "SELECT";
    
    } elseif(stripos($cmd, "DELETE") !== false) {
        $tipo = "DELETE";
        
    } else {
        $tipo = "OUTROS";
    }
    
    $sql = "INSERT INTO mysql_log (usuario_id,tipo,pag,cmd,dthr,ip) VALUES (".mysql_prepara(decodifica($_SESSION["lg_".$app_cfg["id"]."_usuario_id"])).",".mysql_prepara($tipo).",".mysql_prepara($_SERVER["REQUEST_URI"]).",".mysql_prepara($cmd).",".mysql_prepara(date("Y-m-d H:i:s", time())).",".mysql_prepara($_SERVER["REMOTE_ADDR"]).")";
    mysql_query($sql) or mysql_excecao($sql);
    $id = mysql_insert_id();
        
    return $id;
}
?>