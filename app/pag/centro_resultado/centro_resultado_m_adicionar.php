<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "QZ35YHV2MV";
//valida_permissao($idpagina);

// acao
if(decodifica($_POST["ac"]) == "novo_centroresultado") {
    // form
    $nome = trim(addslashes($_POST["nome"]));
    
    // valida   
    if(empty($nome)) {
        $_SESSION["aviso"] = array("O nome é inválido");
        redireciona($app_cfg["path_raiz"]."/pag/centro_resultado/centro_resultado_listar.php");
    }
	
    // transacao
    $sql = "BEGIN";
    mysql_query($sql) or mysql_excecao($sql);
            
    $sql = "INSERT INTO centro_resultado (nome) VALUES (".mysql_prepara($nome).")";
    mysql_query($sql) or mysql_excecao($sql);
    
    mysql_log($sql);
        
    // transacao
    $sql = "COMMIT";
    mysql_query($sql) or mysql_excecao($sql);
    
    redireciona($app_cfg["path_raiz"]."/pag/centro_resultado/centro_resultado_listar.php");
}
?>

<!doctype html>
<html>
<head>
    <!-- cfg head -->
    <?php require_once("../../inc_cfg_head.php"); ?>
</head>

<body>
    <div id="conteudo">
        
        <!-- conteudo -->
        <div class="box-body">
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <div class="row">
                    <div class="col-xs-12">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("novo_centroresultado"); ?>" id="ac" name="ac" required>
            </form>
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("input[name='nome']").focus();
            }, 500);
        });
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>