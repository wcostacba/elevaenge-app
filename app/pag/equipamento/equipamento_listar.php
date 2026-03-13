<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "7RYPALWG9F";
//valida_permissao($idpagina);

// total registro
$sql = "
        SELECT
            COUNT(equipamento.id) AS qtd_reg
        FROM equipamento
            INNER JOIN equipamento_marca ON equipamento.equipamento_marca_id = equipamento_marca.id
            INNER JOIN equipamento_modelo ON equipamento.equipamento_modelo_id = equipamento_modelo.id
            INNER JOIN centro_resultado_rota ON equipamento.centro_resultado_rota_id = centro_resultado_rota.id
            INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id";
$query = mysql_query($sql) or mysql_excecao($sql);
$qtd_registro = mysql_fetch_assoc($query)["qtd_reg"];
mysql_free_result($query);

// paginacao
require_once("../../inc_paginacao_calc.php");

// registro
$sql = "
        SELECT
            equipamento.id, 
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
            INNER JOIN equipamento_marca ON equipamento.equipamento_marca_id = equipamento_marca.id
            INNER JOIN equipamento_modelo ON equipamento.equipamento_modelo_id = equipamento_modelo.id
            INNER JOIN centro_resultado_rota ON equipamento.centro_resultado_rota_id = centro_resultado_rota.id
            INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id
        ORDER BY equipamento.nome ASC
        LIMIT ".$registro_inicial.",".$por_pagina;
$query_registro = mysql_query($sql) or mysql_excecao($sql);
?>

<!doctype html>
<html>
<head>
    <!-- cfg head -->
    <?php require_once("../../inc_cfg_head.php"); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- body cabecalho -->
        <?php require_once("../../inc_body_cabecalho.php"); ?>

        <!-- conteudo -->
        <div class="content-wrapper pad_conteudo">
            
            <!-- titulo pagina -->
            <section class="content-header pad_header">
                <h1>
                    Equipamentos
                    <small>
                        Encontrado:
                        <?php if($qtd_registro) { ?>
                             <?php echo $qtd_registro; ?> registro<?php if($qtd_registro > 1) { echo "s"; } ?>.
                        <?php } else { ?>
                            Nenhum registro
                        <?php } ?>
                        
                        <?php if($sql_where) { echo " - Resultado <strong>com filtros</strong>"; } ?>
                    </small>
                </h1>
                
                <div class="btn_topo_pag">
                    <a href="#" class="btn btn-success" data-titulo="Novo equipamento" data-pag="<?php echo $app_cfg["path_raiz"]; ?>/pag/equipamento/equipamento_m_adicionar.php" data-modaliframe="1">Novo equipamento</a>
                </div>
            </section>
            
            <!-- pagina -->
            <section class="content container-fluid">
                <!-- conteudo central -->
                <div class="box">
                    <div class="box-body">
                        <?php if($qtd_registro) { ?>
                            <table class="table table-bordered table-hover table-striped table-line-lg">
                                <thead>
                                    <tr>
                                        <th>Nome</th>
                                        <th>Marca</th>
                                        <th>Modelo</th>
                                        <th>Cliente</th>
                                        <th>Centro de resultado</th>
                                        <th>Rota</th>
                                        <th>Supervisor</th>
                                    </tr>
                                </thead>
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
                                        </tr>
                                    <?php } mysql_free_result($query_registro); ?>
                                </tbody>
                            </table>
                        
                        <?php } else { ?>
                            <div class="nenhum_registro">
                                Nenhum equipamento encontrado
                            </div>
                        <?php } ?>
                    </div>
                    
                    <div class="box-footer clearfix">
                        <?php require_once("../../inc_paginacao_rodape.php"); ?>
                    </div>
                </div>
            </section>
            
        </div>
        <!--./content-wrapper-->

        <!-- rodape -->
        <?php require_once("../../inc_body_rodape.php"); ?>

    </div>
    <!--./wrapper-->

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>