<?php require_once("inc_cfg_geral.php"); ?>

<!doctype html>
<html>
<head>
    <!-- cfg head -->
    <?php require_once("inc_cfg_head.php"); ?>
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <!-- body cabecalho -->
        <?php require_once("inc_body_cabecalho.php"); ?>

        <!-- conteudo -->
        <div class="content-wrapper pad_conteudo">

            <div class="logomarca_index">
                <img src="<?php echo $app_cfg["path_raiz"]; ?>/cfg/img/<?php echo $app_cfg["logomarca_index"]; ?>">
            </div>
            
        </div>
        <!--./content-wrapper-->

        <!-- body rodape -->
        <?php require_once("inc_body_rodape.php"); ?>

    </div>
    <!--./wrapper-->

    <!-- cfg rodape -->
    <?php require_once("inc_cfg_rodape.php"); ?>
</body>
</html>