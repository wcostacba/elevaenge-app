<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "3KLWQMIUDM";
//valida_permissao($idpagina);

// total registro
$sql = "
        SELECT
            COUNT(centro_resultado_rota.id) AS qtd_reg
        FROM centro_resultado_rota
            INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id";
$query = mysql_query($sql) or mysql_excecao($sql);
$qtd_registro = mysql_fetch_assoc($query)["qtd_reg"];
mysql_free_result($query);

// paginacao
require_once("../../inc_paginacao_calc.php");

// registro
$sql = "
        SELECT
            centro_resultado_rota.id AS centro_resultado_rota_id,
            centro_resultado_rota.nome, 
            centro_resultado.nome AS centro_resultado_nome, 
            (
                SELECT
                    pessoa.nome_publico
                FROM supervisor
                    INNER JOIN pessoa ON supervisor.pessoa_id = pessoa.id
                WHERE supervisor.id = centro_resultado_rota.supervisor_id
            ) AS supervisor_nome_publico
        FROM centro_resultado_rota
            INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id
        ORDER BY centro_resultado_rota.nome ASC
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
                    Rotas
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
                    <a href="#" class="btn btn-success" data-titulo="Nova rota" data-pag="<?php echo $app_cfg["path_raiz"]; ?>/pag/centro_resultado_rota/centro_resultado_rota_m_adicionar.php" data-modaliframe="1" data-tam="pequeno">Nova rota</a>
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
                                        <th>Centro de resultado</th>
                                        <th>Supervisor</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($res_registro = mysql_fetch_assoc($query_registro)) { ?>
                                        <tr>
                                            <td><?php echo $res_registro["nome"]; ?></td>
                                            <td><?php echo $res_registro["centro_resultado_nome"]; ?></td>
                                            <td><?php echo $res_registro["supervisor_nome_publico"]; ?></td>
                                        </tr>
                                    <?php } mysql_free_result($query_registro); ?>
                                </tbody>
                            </table>
                        
                        <?php } else { ?>
                            <div class="nenhum_registro">
                                Nenhuma rota encontrada
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