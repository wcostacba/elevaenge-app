<aside class="main-sidebar">
    <section class="sidebar">
        <!-- menu -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU</li>
                        
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-gear"></i> <span>Cadastro</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li>
                        <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/contrato_tipo/contrato_tipo_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Tipos de contrato</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/centro_resultado_rota/centro_resultado_rota_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Rotas</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/equipamento_marca/equipamento_marca_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Marcas do equipamento</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/equipamento_modelo/equipamento_modelo_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Modelos do equipamento</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/equipamento/equipamento_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Equipamentos</span></a>
                    </li>
                    <li>
                        <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/usuario/usuario_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Usuários</span></a>
                    </li>
                </ul>
            </li>
            
            <li class="treeview">
                <a href="#">
                    <i class="fa fa-share"></i> <span>Sync</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Sankhya
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">
                            <li>
                                <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/cliente/cliente_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Clientes</span></a>
                            </li>
                            <li>
                                <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/supervisor/supervisor_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Supervisores</span></a>
                            </li>
                            <li>
                                <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/centro_resultado/centro_resultado_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Centros de resultado</span></a>
                            </li>
                            <li>
                                <a href="<?php echo $app_cfg["path_raiz"]; ?>/pag/contrato/contrato_listar.php" class="btloading"><i class="fa fa-circle-thin"></i> <span>Contratos</span></a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            
        </ul>
        <!-- /.sidebar-menu -->
    </section>
</aside>

<script type="text/javascript">
    // mantem o menu aberto de acordo com a url
    $(".sidebar-menu").find("a").each(function() {
        if(($(this).attr("href") == "<?php echo url_remove_arg($_SERVER["REQUEST_URI"]); ?>") && ($(this).attr("href") != "#")) {
            var menu_nivel1 = $(this).parent().parent().parent();
            var menu_nivel2 = $(this).parent().parent().parent().parent().parent();

            $(this).parent().addClass("active");

            if(menu_nivel1.hasClass("treeview")) {
                menu_nivel1.addClass("active");
            }
            
            if(menu_nivel2.hasClass("treeview")) {
                menu_nivel2.addClass("active");
            }
        }
    });
</script>