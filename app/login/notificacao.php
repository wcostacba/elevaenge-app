<?php
// controle
$notificacao_msg = trim($_SESSION["notificacao_msg"]);

// apaga
unset($_SESSION["notificacao"]);

if($notificacao_msg) {
?>
    <div class="row" id="notificacao_box">
        <div class="col-sm-12">
            <div class="alert alert-danger alert-dismissible">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                <strong>Erro!</strong> <?php echo $notificacao_msg; ?>
            </div>
        </div>
	</div>
    
    <script type="text/javascript" >	
		$(document).ready(function() {
			// remove a div
			$(".alert").find("a").click(function() {
				$("#notificacao_box").remove();
			});
		});
	</script>
<?php } ?>