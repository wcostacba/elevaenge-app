<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "8GRYZJZ0O8";
//valida_permissao($idpagina);

// vetor
$vetor_equipamento = $_SESSION["contrato_equipamento_vinculo"];
$qtd_equipamento = sizeof($vetor_equipamento);

// acao
if(decodifica($_GET["ac"]) == "novo_equipamentovinculo") {
    // form
    $equipamento = decodifica($_GET["equipamento"]);

    // adiciona no vetor
    $_SESSION["contrato_equipamento_vinculo"][] = $equipamento;
    
    // retorno
    echo '<script src="'.$app_cfg["path_raiz"].'/tema/bower_components/jquery/dist/jquery.min.js"></script>';
    echo '<script type="text/javascript">';
    echo '
        $(document).ready(function() {
            parent.$("#modaliframe1").find("iframe")[0].contentWindow.info_equipamento();
            parent.$("#modaliframe2").modal("hide");
        });
    ';
    echo '</script>';
    die();
}

if($qtd_equipamento) {
    $sql_where = "AND equipamento.id NOT IN (".implode(",",$vetor_equipamento).")";
}

// registro
$sql = "
        SELECT
            equipamento.id AS equipamento_id, 
            equipamento.nome, 
            equipamento_marca.nome AS equipamento_marca_nome, 
            equipamento_modelo.nome AS equipamento_modelo_nome, 
            (
                SELECT
                    pessoa.nome_publico
                FROM cliente
                    INNER JOIN pessoa ON cliente.pessoa_id = pessoa.id
                WHERE cliente.id = equipamento.cliente_id
            ) AS cliente_nome_publico, 
            centro_resultado_rota.nome AS centro_resultado_rota_nome, 
            (
                SELECT
                    pessoa.nome_publico
                FROM supervisor
                    INNER JOIN pessoa ON supervisor.pessoa_id = pessoa.id
                WHERE supervisor.id = centro_resultado_rota.supervisor_id
            ) AS supervisor_nome_publico, 
            centro_resultado.nome AS centro_resultado_nome
        FROM equipamento
            LEFT JOIN contrato_equipamento ON equipamento.id = contrato_equipamento.equipamento_id
            INNER JOIN equipamento_marca ON equipamento.equipamento_marca_id = equipamento_marca.id
            INNER JOIN equipamento_modelo ON equipamento.equipamento_modelo_id = equipamento_modelo.id
            INNER JOIN centro_resultado_rota ON equipamento.centro_resultado_rota_id = centro_resultado_rota.id
            INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id
        WHERE contrato_equipamento.contrato_id IS NULL ".$sql_where."
        GROUP BY equipamento.id
        ORDER BY equipamento.nome ASC";
$query_registro = mysql_query($sql) or mysql_excecao($sql);
$qtd_registro = mysql_num_rows($query_registro);
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
            
            <table class="table table-bordered table-hover table-striped table-line-lg">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Marca</th>
                        <th>Modelo</th>
                        <th>Cliente</th>
                        <th>CR</th>
                        <th>Rota</th>
                        <th>Supervisor</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <?php if($qtd_registro) { ?>
                    <tbody>
                        <?php while($res_registro = mysql_fetch_assoc($query_registro)) { ?>
                            <tr>
                                <td><?php echo $res_registro["nome"]; ?></td>
                                <td><?php echo $res_registro["equipamento_marca_nome"]; ?></td>
                                <td><?php echo $res_registro["equipamento_modelo_nome"]; ?></td>
                                <td><?php echo $res_registro["cliente_nome_publico"]; ?></td>
                                <td><?php echo $res_registro["centro_resultado_nome"]; ?></td>
                                <td><?php echo $res_registro["centro_resultado_rota_nome"]; ?></td>
                                <td><?php echo $res_registro["supervisor_nome_publico"]; ?></td>
                                <td class="lgex"><a href="<?php echo $_SERVER["REQUEST_URI"]; ?>&equipamento=<?php echo codifica($res_registro["equipamento_id"]); ?>&ac=<?php echo codifica("novo_equipamentovinculo"); ?>" class="btn btn-xs btn-success btloading">Vincular</a></td>
                            </tr>
                        <?php } mysql_free_result($query_registro); ?>
                    </tbody>
                
                <?php } else { ?>
                    <tbody>
                        <tr>
                            <td colspan="8">
                                <div class="nenhum_registro">
                                    Nenhum equipamento encontrado
                                </div>
                            </td>
                        </tr>
                    </tbody>
                
                <?php } ?>
            </table>
            
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {

        });
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>