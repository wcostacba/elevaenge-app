<?php
require_once("../inc_cfg_geral_nologin.php");

// dados
$login = trim(addslashes($_POST["Pvg8RVfyRhZAp0RejEgR"]));
$senha = trim(addslashes($_POST["dZi0VplI7ohAkkt6iu7N"]));

// validacao
if(empty($login) || empty($senha)) {
    $notificacao_msg = "Credencial inválida";

// checa credencial
} else {
    mysql_conecta();
    
	// usuario
	$sql = "
            SELECT
                usuario.id AS usuario_id, 
                usuario.nivel_acesso_id, 
                usuario.salt, 
                usuario.iteracao, 
                usuario.senha, 
                pessoa.id AS pessoa_id, 
                pessoa.nome_completo, 
                pessoa.nome_publico
            FROM usuario
                INNER JOIN pessoa ON usuario.pessoa_id = pessoa.id
            WHERE usuario.situacao = 'ativo' AND usuario.login = ".mysql_prepara($login)."    
            LIMIT 1";
	$query = mysql_query($sql) or mysql_excecao($sql);
	$res_usuario = mysql_fetch_assoc($query);
	mysql_free_result($query);
    
    mysql_close();
	
	// check senha
	$senha = hash("sha256",$senha.$res_usuario["salt"]);
	
	for($i=2;$i<=$res_usuario["iteracao"];$i++) {
		$senha = hash("sha256",$senha);
	}
    
	if($senha == $res_usuario["senha"]) {
        // sessao
        $_SESSION["lg_".$app_cfg["id"]."_token"] = $app_cfg["token"];
        $_SESSION["lg_".$app_cfg["id"]."_nome_publico"] = codifica($res_usuario["nome_publico"]);
        $_SESSION["lg_".$app_cfg["id"]."_usuario_id"] = codifica($res_usuario["usuario_id"]);
        
        /*
        $_SESSION["lg_login"] = codifica($login);
        $_SESSION["lg_cod_profissional"] = codifica($res_usuario["usuario_id"]);
        $_SESSION["lg_cod_nivel_acesso"] = codifica($res_usuario["nivel_acesso_id"]);
        $_SESSION["lg_cod_pessoa"] = codifica($res_usuario["pessoa_id"]);
        $_SESSION["lg_nome_completo"] = codifica($res_usuario["nome_completo"]);
        */
        
        $_SESSION["lg_ano"] = date("Y", time());
        
        redireciona($app_cfg["path_raiz"]."/index.php");
        
    } else {
		$notificacao_msg = "Informações incorretas";
    }
}

// reseta
unset($_SESSION);
session_destroy();

// notificacao
session_start();

$_SESSION["notificacao_msg"] = $notificacao_msg;
redireciona($app_cfg["path_raiz"]."/login/index.php");
?>