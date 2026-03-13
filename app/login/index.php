<?php require_once("../inc_cfg_geral_nologin.php"); ?>

<!doctype html>
<html lang="pt-BR">
<head>
    <title><?php echo $app_cfg["titulo_app"]; ?> Login</title>
    <meta charset="iso-8859-1">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <link rel="stylesheet" href="<?php echo $app_cfg["path_raiz"]; ?>/login/components/font-awesome-4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo $app_cfg["path_raiz"]; ?>/login/css/googlefonts.css">
    <link rel="stylesheet" href="<?php echo $app_cfg["path_raiz"]; ?>/login/css/style.css">
    
    <script src="<?php echo $app_cfg["path_raiz"]; ?>/login/js/jquery.min.js"></script>
</head>
<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-7 col-lg-5">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center"> <span class="fa fa-user-o"></span></div>
                        <h3 class="text-center mb-4">Login</h3>
                        
                        <?php require_once("notificacao.php"); ?>
                        
                        <form action="<?php echo $app_cfg["path_raiz"]; ?>/login/validar.php" method="post" class="login-form">
                            <div class="form-group">
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="Usuário" aria-label="Usuário" aria-describedby="basic-addon2" name="Pvg8RVfyRhZAp0RejEgR" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="basic-addon2">@elevaenge.com.br</span>
                                    </div>
                                </div>
                                
                            </div>
                            <div class="form-group d-flex">
                                <input type="password" class="form-control" placeholder="Senha" name="dZi0VplI7ohAkkt6iu7N" required>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="form-control btn btn-primary rounded submit px-3">Entrar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("input[name='Pvg8RVfyRhZAp0RejEgR']").focus();
            }, 500);
        });
    </script>
    
    <!-- js -->
    <script src="<?php echo $app_cfg["path_raiz"]; ?>/login/js/popper.js"></script> 
    <script src="<?php echo $app_cfg["path_raiz"]; ?>/login/js/bootstrap.min.js"></script> 
    <script src="<?php echo $app_cfg["path_raiz"]; ?>/login/js/main.js"></script>
</body>
</html>