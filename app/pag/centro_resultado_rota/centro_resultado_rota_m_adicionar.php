<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "LZ716TF4YD";
//valida_permissao($idpagina);

// acao
if(decodifica($_POST["ac"]) == "novo_centroresultadorota") {
    // form
    $nome = trim(addslashes($_POST["nome"]));
    $centro_resultado = decodifica($_POST["centro_resultado"]);
    $supervisor = decodifica($_POST["supervisor"]);
    
    // valida   
    if(empty($nome)) {
        $_SESSION["aviso"] = array("O nome é inválido");
        redireciona($app_cfg["path_raiz"]."/pag/centro_resultado_rota/centro_resultado_rota_listar.php");
    }
	
    // transacao
    $sql = "BEGIN";
    mysql_query($sql) or mysql_excecao($sql);
            
    $sql = "INSERT INTO centro_resultado_rota (centro_resultado_id,supervisor_id,nome) VALUES (".mysql_prepara($centro_resultado).",".mysql_prepara($supervisor).",".mysql_prepara($nome).")";
    mysql_query($sql) or mysql_excecao($sql);
    
    mysql_log($sql);
        
    // transacao
    $sql = "COMMIT";
    mysql_query($sql) or mysql_excecao($sql);
    
    redireciona($app_cfg["path_raiz"]."/pag/centro_resultado_rota/centro_resultado_rota_listar.php");
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
                    <div class="form-group col-xs-12">
                        <label>Centro de resultado</label>
                        <select class="form-control" name="centro_resultado" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        id AS centro_resultado_id,
                                        nome
                                    FROM centro_resultado
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_centro_resultado = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_centro_resultado["centro_resultado_id"]).'">'.$res_centro_resultado["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group col-xs-12">
                        <label>Supervisor</label>
                        <select class="form-control" name="supervisor" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        supervisor.id AS supervisor_id, 
                                        pessoa.nome_publico
                                    FROM supervisor
                                        INNER JOIN pessoa ON supervisor.pessoa_id = pessoa.id
                                    ORDER BY pessoa.nome_publico ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_supervisor = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_supervisor["supervisor_id"]).'">'.$res_supervisor["nome_publico"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-xs-12">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("novo_centroresultadorota"); ?>" id="ac" name="ac" required>
            </form>
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("select[name='centro_resultado']").focus();
            }, 500);
        });
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>