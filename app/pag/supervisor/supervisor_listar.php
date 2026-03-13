<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "7X2JO6VH5J";
//valida_permissao($idpagina);

// INICIO FILTRO

    $sql_where = array();

    // filtro palavra
    $filtro_palavra = trim(addslashes(base64_decode($_GET["fpalavra"])));

    if($filtro_palavra) {
        $_SESSION["QS00RMGWGA_fpalavra"] = codifica($filtro_palavra);
    } else {
        $filtro_palavra = decodifica($_SESSION["QS00RMGWGA_fpalavra"]);
    }

    if(($filtro_palavra) && ($filtro_palavra != "limpar")) {
        $t_fpalavra = explode(" ",$filtro_palavra);

        $t_where = array();

        foreach($t_fpalavra as $p) {
            $t_where[] = "pessoa.nome_completo LIKE '%".$p."%'";
        }

        $sql_where[] = "(".implode(") AND (",$t_where).")";

    } else {
        unset($filtro_palavra);
    }

    // filtro geral
    if(sizeof($sql_where)) {
        $sql_where = "WHERE ".implode(" AND ",$sql_where);
    } else {
        unset($sql_where);
    }

// FIM FILTRO

// total registro
$sql = "
        SELECT
            COUNT(supervisor.id) AS qtd_reg
        FROM supervisor
            INNER JOIN pessoa ON supervisor.pessoa_id = pessoa.id
        ".$sql_where;
$query = mysql_query($sql) or mysql_excecao($sql);
$qtd_registro = mysql_fetch_assoc($query)["qtd_reg"];
mysql_free_result($query);

// paginacao
require_once("../../inc_paginacao_calc.php");

// registro
$sql = "
        SELECT
            supervisor.id AS supervisor_id, 
            pessoa.id AS pessoa_id, 
            pessoa.cpfcnpj, 
            pessoa.nome_completo, 
            pessoa.nome_publico
        FROM supervisor
            INNER JOIN pessoa ON supervisor.pessoa_id = pessoa.id
        ".$sql_where."
        ORDER BY pessoa.nome_completo ASC
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
                    Supervisores
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
                    <!-- procurar -->
                    <div class="input-group header_box_procurar">
                        <input type="text" class="form-control" value="<?php echo $filtro_palavra; ?>" name="fpalavra">
                        <span class="input-group-btn">
                            <button id="fbtn" type="button" class="btn bg-purple btloading">Procurar</button>
                        </span>
                    </div>

                    <script type="text/javascript">
                        $(document).ready(function() {
                            // botao enter teclado no input
                            $("input[name='fpalavra']").keyup(function(e) {
                                if(e.keyCode === 13) {
                                    $("#fbtn").click();
                                }
                            });

                            // clique botao procurar
                            $("#fbtn").click(function() {
                                var p = btoa($.trim($("input[name='fpalavra']").val()));

                                if(p.length) {
                                    window.open("<?php echo url_remove_arg($_SERVER["REQUEST_URI"],"fpalavra",true); ?>fpalavra=" + p,"_self");
                                } else {
                                    window.open("<?php echo url_remove_arg($_SERVER["REQUEST_URI"],"fpalavra",true); ?>fpalavra=<?php echo base64_encode("limpar"); ?>","_self");
                                }
                            });
                        });
                    </script>
                    
                    <a href="#" class="btn btn-success" data-titulo="Novo supervisor" data-pag="<?php echo $app_cfg["path_raiz"]; ?>/pag/pessoa/pessoa_m_adicionar_etapa1.php?form=<?php echo codifica("supervisor"); ?>&retornourl=<?php echo codifica($_SERVER["REQUEST_URI"]); ?>" data-modaliframe="2" data-btnok="Avançar">Novo supervisor</a>
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
                                        <th>Nome completo</th>
                                        <th>Nome público</th>
                                        <th>CPF/CNPJ</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($res_registro = mysql_fetch_assoc($query_registro)) { ?>
                                        <tr>
                                            <td><?php echo $res_registro["nome_completo"]; ?></td>
                                            <td><?php echo $res_registro["nome_publico"]; ?></td>
                                            <td><?php echo mascara_cpfcnpj($res_registro["cpfcnpj"]); ?></td>
                                            <td class="lgex">
                                                <div class="text-right">
                                                    <div class="btn-group">
                                                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ações <span class="caret"></span></button>
                                                        <ul class="dropdown-menu dropdown-menu-right">
                                                            
                                                            <li class="dropdown-header">Menu</li>
                                                            <li><a href="#" class="btloading" data-titulo="Editar supervisor" data-pag="<?php echo $app_cfg["path_raiz"]; ?>/pag/pessoa/pessoa_m_editar.php?pessoa=<?php echo codifica($res_registro["pessoa_id"]); ?>&form=<?php echo codifica("supervisor"); ?>&retornourl=<?php echo codifica($_SERVER["REQUEST_URI"]); ?>" data-modaliframe="2"><i class="fa fa-pencil"></i> Editar supervisor</a></li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php } mysql_free_result($query_registro); ?>
                                </tbody>
                            </table>
                        
                        <?php } else { ?>
                            <div class="nenhum_registro">
                                Nenhum supervisor encontrado
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
        
        <script type="text/javascript">
            $(document).ready(function() {
                setTimeout(function () {
                    $("input[name='fpalavra']").focus();
                }, 500);
            });
        </script>

        <!-- rodape -->
        <?php require_once("../../inc_body_rodape.php"); ?>

    </div>
    <!--./wrapper-->

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>