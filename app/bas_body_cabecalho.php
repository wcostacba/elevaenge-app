<header class="main-header">
    <!-- titulo -->
    <a href="<?php echo $app_cfg["path_raiz"]; ?>" class="logo">
        <span class="logo-mini"><b><?php echo $app_cfg["titulo_app_curto"]; ?></b></span>
        <span class="logo-lg"><b><?php echo $app_cfg["titulo_app"]; ?></b></span>
    </a>

    <!-- topo -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Alterar navegação</span>
        </a>

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- opcoes -->                
                <li>
                    <a href="#"><?php echo decodifica($_SESSION["lg_".$app_cfg["id"]."_nome_publico"]); ?></a>
                </li>
                
                <li>
                    <a href="<?php echo $app_cfg["path_raiz"]; ?>/sair.php" class="btn-danger"><i class="fa fa-sign-out"></i> Sair</a>
                </li>
            </ul>
        </div>
    </nav>
</header>