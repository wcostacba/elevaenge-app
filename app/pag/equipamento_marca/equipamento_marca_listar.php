<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "QRZN9DPSXB";
//valida_permissao($idpagina);

// total registro
$sql = "
        SELECT
            COUNT(id) AS qtd_reg
        FROM equipamento_marca";
$query = mysql_query($sql) or mysql_excecao($sql);
$qtd_registro = mysql_fetch_assoc($query)["qtd_reg"];
mysql_free_result($query);

// paginacao
require_once("../../inc_paginacao_calc.php");

// registro
$sql = "
        SELECT
            id AS equipamento_marca_id, 
            nome
        FROM equipamento_marca
        ORDER BY nome ASC
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
                    Marcas do equipamento
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
                    <a href="#" class="btn btn-success" data-titulo="Nova marca do equipamento" data-pag="<?php echo $app_cfg["path_raiz"]; ?>/pag/equipamento_marca/equipamento_marca_m_adicionar.php" data-modaliframe="1" data-tam="pequeno">Nova marca</a>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($res_registro = mysql_fetch_assoc($query_registro)) { ?>
                                        <tr>
                                            <td><?php echo $res_registro["nome"]; ?></td>
                                        </tr>
                                    <?php } mysql_free_result($query_registro); ?>
                                </tbody>
                            </table>
                        
                        <?php } else { ?>
                            <div class="nenhum_registro">
                                Nenhuma marca encontrada
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