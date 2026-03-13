<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "CK9LIIG67I";
//valida_permissao($idpagina);

// acao
if(decodifica($_POST["ac"]) == "novo_contrato") {
    // form
    $nome = trim(addslashes($_POST["nome"]));
    
    // vetor
    $vetor_equipamento = $_SESSION["contrato_equipamento_vinculo"];
    $qtd_equipamento = sizeof($vetor_equipamento);
    
    // valida   
    if(empty($nome)) {
        $_SESSION["aviso"] = array("O nome é inválido");
        redireciona($app_cfg["path_raiz"]."/pag/contrato/contrato_listar.php");
    
    } elseif(empty($qtd_equipamento)) {
        $_SESSION["aviso"] = array("Nenhum equipamento vinculado");
        redireciona($app_cfg["path_raiz"]."/pag/contrato/contrato_listar.php");
    }
	
    // transacao
    $sql = "BEGIN";
    mysql_query($sql) or mysql_excecao($sql);
            
    $sql = "INSERT INTO contrato (nome) VALUES (".mysql_prepara($nome).")";
    mysql_query($sql) or mysql_excecao($sql);
    $contrato_id = mysql_insert_id();
    
    foreach($vetor_equipamento as $v) {
        $sql = "INSERT INTO contrato_equipamento (contrato_id,equipamento_id) VALUES (".mysql_prepara($contrato_id).",".mysql_prepara($v).")";
        mysql_query($sql) or mysql_excecao($sql);
    }
    
    mysql_log($sql);
        
    // transacao
    $sql = "COMMIT";
    mysql_query($sql) or mysql_excecao($sql);
    
    redireciona($app_cfg["path_raiz"]."/pag/contrato/contrato_listar.php");
}
?>

<!doctype html>
<html>
<head>
    <!-- cfg head -->
    <?php require_once("../../inc_cfg_head.php"); ?>
</head>

<body>
    <div id="conteudo">
        
        <!-- conteudo -->
        <div class="box-body">
            <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
                <div class="row">
                    <div class="form-group col-xs-6">
                        <label>Nome</label>
                        <input type="text" class="form-control" name="nome" required>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Tipo de contrato</label>
                        <select class="form-control" name="contrato_tipo" required>
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        id AS contrato_tipo_id,
                                        nome
                                    FROM contrato_tipo
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_contrato_tipo = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_contrato_tipo["contrato_tipo_id"]).'">'.$res_contrato_tipo["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="col-xs-12">
                        <label>Equipamentos</label>
                        <div id="box_info_equipamento">&nbsp;</div>
                    </div>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("novo_contrato"); ?>" id="ac" name="ac" required>
            </form>
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("input[name='nome']").focus();
            }, 500);
            
            info_equipamento();
        });
        
        function info_equipamento() {
            var b = $("#box_info_equipamento");
            
            $.ajax({
                url: "<?php echo $app_cfg["path_raiz"]; ?>/pag/contrato/contrato_m_adicionar_info_equipamento_ajax.php?token=<?php echo codifica($token); ?>",
                cache: false,
                dataType: "html",
                
            }).done(function(data) {
                b.html("");
                b.html(data);
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                parent.aviso("erro","Ocorreu uma falha ao carregar as informações do equipamento (Erro: "+textStatus+" - "+errorThrown+")");
                
            }).always(function() {
                ajuste_altura();
            });
        }
        
        function ajuste_altura() {
            if(typeof timer_altura !== "undefined") {
                clearTimeout(timer_altura);
            }
            
            timer_altura = setTimeout(function() {
                var h = $("#conteudo").height();
                if(h == 0) { h = 150; }
                parent.$("#modaliframe1").find("iframe").css("height", h + "px");
            }, 200);
        }
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>