<!-- loading -->
<div class="loading">
    <img src="<?php echo $app_cfg["path_raiz"]; ?>/cfg/img/loading.gif">
    <p>
        <span id="loading_msg">&nbsp;</span>
    </p>
</div>

<script type="text/javascript">
    // fechar div ao carregadar
    $(document).ready(function() {
        loading_fecha();
        
        // chamada loading
        $(".btloading").click(function() {
            loading();
        });
    });
    
    // abre div manualmente
    function loading(m = "") {
        $(".loading").removeClass("hidden");

        if((m.length < 1) || (m == false)) {
            m = "Aguarde, processando sua solicitação.";
        }
        
        $("#loading_msg").html(m);
    }
    
    // fecha div manualmente
    function loading_fecha() {
        $(".loading").addClass("hidden");
        $("#loading_msg").find("p").html("");
    }
</script>