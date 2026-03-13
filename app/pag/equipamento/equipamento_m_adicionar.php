<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "LZ716TF4YD";
//valida_permissao($idpagina);

// acao
if(decodifica($_POST["ac"]) == "novo_equipamento") {
    // form
    $nome = trim(addslashes($_POST["nome"]));
    $equipamento_marca = decodifica($_POST["equipamento_marca"]);
    $equipamento_modelo = decodifica($_POST["equipamento_modelo"]);
    $cliente = decodifica($_POST["cliente"]);
    $centro_resultado_rota = decodifica($_POST["centro_resultado_rota"]);
    
    // valida   
    if(empty($nome)) {
        $_SESSION["aviso"] = array("O nome é inválido");
        redireciona($app_cfg["path_raiz"]."/pag/equipamento/equipamento_listar.php");
    }
	
    // transacao
    $sql = "BEGIN";
    mysql_query($sql) or mysql_excecao($sql);
            
    $sql = "INSERT INTO equipamento (nome,equipamento_marca_id,equipamento_modelo_id,centro_resultado_rota_id,cliente_id) VALUES (".mysql_prepara($nome).",".mysql_prepara($equipamento_marca).",".mysql_prepara($equipamento_modelo).",".mysql_prepara($centro_resultado_rota).",".mysql_prepara($cliente).")";
    mysql_query($sql) or mysql_excecao($sql);
    
    mysql_log($sql);
        
    // transacao
    $sql = "COMMIT";
    mysql_query($sql) or mysql_excecao($sql);
    
    redireciona($app_cfg["path_raiz"]."/pag/equipamento/equipamento_listar.php");
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
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    
                    <div class="form-group col-xs-12">
                        <label>Marca</label>
                        <select class="form-control" name="equipamento_marca" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        id AS equipamento_marca_id,
                                        nome
                                    FROM equipamento_marca
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_equipamento_marca = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_equipamento_marca["equipamento_marca_id"]).'">'.$res_equipamento_marca["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group col-xs-12">
                        <label>Modelo</label>
                        <select class="form-control" name="equipamento_modelo" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        id AS equipamento_modelo_id,
                                        nome
                                    FROM equipamento_modelo
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_equipamento_modelo = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_equipamento_modelo["equipamento_modelo_id"]).'">'.$res_equipamento_modelo["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="form-group col-xs-12">
                        <label>Cliente</label>
                        <!--<select class="form-control select2" style="width: 100%;">-->
                        <select class="form-control select2" name="cliente" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        cliente.id AS cliente_id, 
                                        pessoa.nome_publico
                                    FROM cliente
                                        INNER JOIN pessoa ON cliente.pessoa_id = pessoa.id
                                    ORDER BY pessoa.nome_publico ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_cliente = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_cliente["cliente_id"]).'">'.$res_cliente["nome_publico"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-xs-12">
                        <label>Rota</label>
                        <select class="form-control" name="centro_resultado_rota" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        centro_resultado_rota.id AS centro_resultado_rota_id, 
                                        centro_resultado_rota.nome, 
                                        centro_resultado.nome AS centro_resultado_nome
                                    FROM centro_resultado_rota
                                        INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id
                                    ORDER BY centro_resultado_rota.nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_centro_resultado_rota = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_centro_resultado_rota["centro_resultado_rota_id"]).'">'.$res_centro_resultado_rota["nome"].' ('.$res_centro_resultado_rota["centro_resultado_nome"].')</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("novo_equipamento"); ?>" id="ac" name="ac" required>
            </form>
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("select[name='centro_resultado']").focus();
            }, 500);
            
            $(".select2").select2()
        });
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>