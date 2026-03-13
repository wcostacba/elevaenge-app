<?php
$aviso = trim($_SESSION["aviso"]);

unset($_SESSION["aviso"]);
?>
<!-- aviso -->
<div class="box_aviso hidden"></div>

<script type="text/javascript">
    <?php if($aviso) { ?>
        $(document).ready(function() {
            aviso("<?php echo addslashes(preg_replace("/\r|\n/", "", $aviso)); ?>");
        });
    <?php } ?>

    function aviso(m) {
        $(".box_aviso").append('<div class="alert alert-danger alert-dismissible"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><h4><i class="icon fa fa-check"></i> Aviso!</h4>' + m + '.</div>');
        
        // efeito aparecendo
        $(".box_aviso").removeClass("hidden").hide(0).fadeIn("normal");
        
        $(".box_aviso .alert-danger").on("closed.bs.alert", function () {
            $(this).parent().addClass("hidden");
            $(this).parent().html("");
        })
    }
</script>