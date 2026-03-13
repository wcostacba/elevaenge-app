<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "36B1ACMRF6";
//valida_permissao($idpagina);

// parametro obrigatorio
$form = decodifica($_GET["form"]);
$retornourl = decodifica($_GET["retornourl"]);
$retornofuncao = decodifica($_GET["retornofuncao"]);

// parametro modular
if($form == "cliente") {
    $filho_id = decodifica($_GET["cliente"]);
    
} elseif($form == "supervisor") {
    $filho_id = decodifica($_GET["supervisor"]);

} elseif($form == "usuario") {
    $filho_id = decodifica($_GET["usuario"]);
}

// form
if(decodifica($_POST["ac"]) == "form_pessoaetapa1") {
    // form
    $possui_cpfcnpj = decodifica($_POST["possui_cpfcnpj"]);
    $cpfcnpj = preg_replace("/[^0-9]/", "", $_POST["cpfcnpj"]);
    
    if($possui_cpfcnpj == "S") {
        // validacao
        if(!valida_cpfcnpj($cpfcnpj)) {
            if($retornofuncao) {
                // include do JS se o retorno for baseado em javascript jquery
                echo '<script src="'.$app_cfg["path_raiz"].'/tema/bower_components/jquery/dist/jquery.min.js"></script>';
                
                echo '<script type="text/javascript">';
                echo 'parent.aviso("O CPF/CNPJ informado é inválido");';
                echo 'window.history.back();';
                echo '</script>';
                die();

            } else {
                $_SESSION["aviso"] = "O CPF/CNPJ informado é inválido";
                redireciona($retornourl);
            }
        }
    }
    
    // checa pessoa
    $sql = "
            SELECT
                id
            FROM pessoa
            WHERE cpfcnpj = ".mysql_prepara($cpfcnpj)."
            LIMIT 1";
    $query = mysql_query($sql) or mysql_excecao($sql);
    $qtd_pessoa = mysql_num_rows($query);
    mysql_free_result($query);
    
    if($qtd_pessoa && ($possui_cpfcnpj == "S")) {
        redireciona($app_cfg["path_raiz"]."/pag/pessoa/pessoa_m_editar.php","_self");
    } else {
        redireciona($app_cfg["path_raiz"]."/pag/pessoa/pessoa_m_adicionar_etapa2.php?filho=".codifica($filho_id)."&form=".codifica($form)."&retornourl=".codifica($retornourl)."&retornofuncao=".codifica($retornofuncao)."&cpfcnpj=".$cpfcnpj,"_self");
    }
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
                    <div class="col-xs-6">
                        <label>Possui CPF/CNPJ?</label>
                        <select class="form-control" name="possui_cpfcnpj" required>
                            <option value="" selected disabled></option>
                            
                            <option value="<?php echo codifica("S"); ?>">Sim</option>
                            <option value="<?php echo codifica("N"); ?>">Não</option>
                        </select>
                    </div>
                    
                    <div class="col-xs-6">
                        <label>CPF/CNPJ</label>
                        <input type="text" class="form-control" name="cpfcnpj" pattern="[0-9]*" inputmode="numeric" required>
                    </div>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("form_pessoaetapa1"); ?>" id="ac" name="ac" required>
            </form>
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("select[name='possui_cpfcnpj']").focus();
            }, 500);
                        
            // tipo
            $("select[name='possui_cpfcnpj']").change(function() {
                if($(this).find(":selected").val() == "<?php echo codifica("S"); ?>") {
                    $("input[name='cpfcnpj']").attr("required", "required");
                } else {
                    $("input[name='cpfcnpj']").removeAttr("required");
                }
            });
        });
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>