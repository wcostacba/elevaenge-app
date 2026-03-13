<?php
function mysql_excecao($sql) {
    global $app_cfg, $fixo_cfg;
    
    $code_erro = mysql_errno();
    
    echo $sql;
    
    //ver-$fixo_cfg["cmd_inline"]
    
    if(stripos($app_cfg["mysql_ignora_erro"], $code_erro) === false) {
        
        $conteudo = array();
        $conteudo[] = date("Y-m-d H:i:s", time());
        $conteudo[] = $_SERVER["REMOTE_ADDR"];
        $conteudo[] = $_SERVER["REQUEST_URI"];
        $conteudo[] = decodifica($_SESSION["lg_cod_profissional_usuario"]);
        $conteudo[] = decodifica($_SESSION["lg_login"]);
        $conteudo[] = $sql;
        $conteudo[] = $code_erro;
        $conteudo[] = mysql_error();

        // config
        $raiz = $fixo_cfg["path_raiz"]."/aviso_erro.php";
        $pasta = $fixo_cfg["path_erro"];
        
        $tkop = date("YmdHis", time())."_".explode(".", microtime(true))[0]."_".str_replace(".", "", uniqid(rand(),true))."-".$code_erro;
        
        // txt
        $fp = fopen($pasta."/mysql/".$tkop.".txt", "a");
        fwrite($fp, implode("@#@\n",$conteudo));
        fclose($fp);

        $_SESSION["aviso_tipo"] = "erro";
        $_SESSION["aviso"] = "Erro na operaчуo do banco de dados (".$code_erro.")";
        //redireciona($raiz);

    }
    
    die();
}
?>