<footer class="main-footer">
    <div class="pull-right hidden-xs">
        Exec: <span id="apptimeexec">.</span> - Versão: <?php echo $app_cfg["ver_num"]; ?> - BD: <?php echo $ambiente_cfg["bd_banco"]; ?>
    </div>
    
    <?php if($ambiente_cfg["tipo"] != "producao") { ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $(".main-footer").find(".pull-right").addClass("ambienteteste");
                
                setInterval(function() {
                    $(".main-footer").find(".pull-right").fadeIn(300).fadeOut(500);
                }, 1000);
            });
        </script>
    <?php } ?>
    
    <strong>Copyright &copy; <?php echo $_SESSION["lg_ano"]; ?> <a href="#"><?php echo $app_cfg["titulo_app"]; ?></a>.</strong> Direitos reservados.
</footer>