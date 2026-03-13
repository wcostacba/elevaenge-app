<?php
function mysql_conecta() {
    global $ambiente_cfg;
    
    $con = @mysql_connect($ambiente_cfg["bd_host"], $ambiente_cfg["bd_usuario"], $ambiente_cfg["bd_senha"]) or mysql_excecao("mysql_connect()");
    mysql_select_db($ambiente_cfg["bd_banco"], $con) or mysql_excecao("mysql_select_db()");

    return $con;
}
?>