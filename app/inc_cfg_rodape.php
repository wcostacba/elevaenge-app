<!-- js obrigatorio -->

<!-- Bootstrap 3.3.7 -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- AdminLTE App -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/dist/js/adminlte.min.js"></script>

<!-- select2 -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/select2/dist/js/select2.full.min.js"></script>
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/select2/dist/js/i18n/pt-BR.js"></script>

<!-- date -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<!-- iCheck -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/plugins/iCheck/icheck.min.js"></script>

<!-- >>>>> js opcional -->

<!-- InputMask -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/inputmask/jquery.inputmask.js"></script>
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/inputmask/jquery.inputmask.date.extensions.js"></script>
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/inputmask/jquery.inputmask.extensions.js"></script>

<!-- InputMaskMoney -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/inputmaskmoney/jquery.maskMoney.min.js"></script>

<!-- Slimscroll -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>

<!-- FastClick -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/fastclick/lib/fastclick.js"></script>

<!-- uploadifive -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/uploadifive/jquery.uploadifive.js"></script>

<!-- UI (sortable) -->
<script src="<?php echo $app_cfg["path_raiz"]; ?>/tema/bower_components/jquery-ui-1.14.1.sortable/jquery-ui.min.js"></script>

<!-- controle geral -->
<script type="text/javascript">
    $(document).ready(function() {
        // tooltip
        $("[data-toggle='tooltip']").tooltip();
        
        // desabilitar link
        $(".disabled").click(function(e) {
            e.preventDefault();
        });
        
        // botao copiar
        $(document).on("click","a[name='btn_copiar']",function() {
            $("#"+$(this).data("id")).select();
            document.execCommand("copy");
        });
        
        // modal dentro iframe (outro modal) - necessario essa chamada aqui porque o include do modal não está dentro do iframe
        $("body").on("click", "a[data-modaliframe='2'],button[data-modaliframe='2'],a[data-modaliframe='3'],button[data-modaliframe='3'],a[data-modaliframe='4'],button[data-modaliframe='4']", function() {
            parent.modaliframe($(this));
        });
        
        <?php
        // calcular tempo geral script
        $apptimeexec_f = microtime(TRUE);
        $apptimeexec_t = $apptimeexec_f-$apptimeexec_i;
        $apptimeexec_t = number_format($apptimeexec_t, 6)."s";
        ?>
        
        // exec
        var elexec = $("#apptimeexec");
        if(elexec.length == 0) { elexec = parent.$("#apptimeexec"); }        
        if(elexec.length) { elexec.html("<?php echo $apptimeexec_t; ?>"); }
    });
</script>