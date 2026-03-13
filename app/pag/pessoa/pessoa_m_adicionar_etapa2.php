<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "QGY5IH0WQ7";
//valida_permissao($idpagina);

// parametro obrigatorio
$form = decodifica($_GET["form"]);
$retornourl = decodifica($_GET["retornourl"]);
$retornofuncao = decodifica($_GET["retornofuncao"]);
$cpfcnpj = preg_replace("/[^0-9]/", "", $_GET["cpfcnpj"]);

// parametro modular
$filho_id = decodifica($_GET["filho"]);

// form
if(decodifica($_POST["ac"]) == "novo_pessoaetapa2") {
    // form
    $nome_rfb = trim(addslashes($_POST["nome_rfb"]));
    $nome_completo = trim(addslashes($_POST["nome_completo"]));
    $nome_publico = trim(addslashes($_POST["nome_publico"]));
    $dt_nascimento = trim(addslashes($_POST["dt_nascimento"]));
    $sexo = decodifica($_POST["sexo"]);
    
    $ddi = preg_replace("/[^0-9]/", "", $_POST["ddi"]);
    $ddd = preg_replace("/[^0-9]/", "", $_POST["ddd"]);
    $numero_tel = preg_replace("/[^0-9]/", "", $_POST["numero_tel"]);
    $email = trim($_POST["email"]);
    
    $cep = preg_replace("/[^0-9]/", "", $_POST["cep"]);
    $logradouro = trim(addslashes($_POST["logradouro"]));
    $numero_end = trim(addslashes($_POST["numero_end"]));
    $complemento = trim(addslashes($_POST["complemento"]));
    $bairro = trim(addslashes($_POST["bairro"]));
    $cidade = decodifica($_POST["cidade"]);
    
    // include do JS se o retorno for baseado em javascript jquery
    if($retornofuncao) {
        echo '<script src="'.$app_cfg["path_raiz"].'/tema/bower_components/jquery/dist/jquery.min.js"></script>';
    }
    
    // validacao
    if((empty($nome_completo)) || (empty($nome_publico)) || ($dt_nascimento && !valida_data($dt_nascimento))) {
        echo '<script type="text/javascript">';
        echo 'parent.aviso("Algum campo da pessoa está em branco ou preenchido incorretamente");';
        echo 'window.history.back();';
        echo '</script>';
        die();
            
    } elseif(($ddi_tel) && ((empty($ddd_tel)) || (empty($num_tel)))) {
        echo '<script type="text/javascript">';
        echo 'parent.aviso("Algum campo do telefone está em branco ou preenchido incorretamente");';
        echo 'window.history.back();';
        echo '</script>';
        die();
    
    } elseif(($cep) && ((empty($logradouro)) || (empty($bairro)))) {
        echo '<script type="text/javascript">';
        echo 'parent.aviso("Algum campo do endereço está em branco ou preenchido incorretamente");';
        echo 'window.history.back();';
        echo '</script>';
        die();
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
    
    if($qtd_pessoa) {
        echo '<script type="text/javascript">';
        echo 'parent.aviso("Já existe uma pessoa cadastrada com o CPF/CNPJ");';
        echo 'window.history.back();';
        echo '</script>';
        die();
    }
    
    // INICIO VALIDAÇÃO TELEFONE (NECESSARIO PRA DEIXAR O TELEFONE UNICO POR PESSOA)
    
    if($ddi && $ddd && $numero_tel) {
        // ajuste telefone contato - brasil
        if($ddi == "55") {
            if(strlen($numero_tel) == 8) {
                if(($numero_tel[0] == "8") || ($numero_tel[0] == "9")) {
                    $numero_tel_com9d = "9".$numero_tel;
                }

            } elseif(strlen($numero_tel) == 9) {
                $numero_tel_com9d = $numero_tel;
                $numero_tel = substr($numero_tel, 1);
            }

        // internacional
        } else {
            unset($ddd);
        }

        $numero_tel_completo = $ddi.$ddd.$numero_tel;

        if($numero_tel_com9d) {
            $numero_tel_completo_com9d = $ddi.$ddd.$numero_tel_com9d;    
        }
    }
    
    $sql = "
            SELECT
                telefone.id AS telefone_id,
                (
                    SELECT
                        COUNT(pessoa_telefone.telefone_id)
                    FROM pessoa_telefone
                    WHERE pessoa_telefone.pessoa_id <> ".mysql_prepara($pessoa_id)." AND pessoa_telefone.telefone_id = telefone.id
                ) AS qtd_pessoa
            FROM telefone
            WHERE telefone.numero_completo = ".mysql_prepara($numero_tel_completo)." OR telefone.numero_completo_com9d = ".mysql_prepara($numero_tel_completo_com9d);
    $query = mysql_query($sql) or mysql_excecao($sql);
    $res_chk_telefone = mysql_fetch_assoc($query);
    mysql_free_result($query);
    
    if($res_chk_telefone["qtd_pessoa"]) {
        echo '<script type="text/javascript">';
        echo 'parent.aviso("Já existe uma pessoa com o mesmo telefone");';
        echo 'window.history.back();';
        echo '</script>';
        die();
    }
    
    // FIM VALIDAÇÃO TELEFONE
    
    // INICIO VALIDAÇÃO EMAIL (NECESSARIO PRA DEIXAR O EMAIL UNICO POR PESSOA)
    
    if($email) {
        $sql = "
                SELECT
                    email.id AS email_id,
                    (
                        SELECT
                            COUNT(pessoa_email.email_id)
                        FROM pessoa_email
                        WHERE pessoa_email.pessoa_id <> ".mysql_prepara($pessoa_id)." AND pessoa_email.email_id = email.id
                    ) AS qtd_pessoa
                FROM email
                WHERE email.endereco = ".mysql_prepara($email);
        $query = mysql_query($sql) or mysql_excecao($sql);
        $res_chk_email = mysql_fetch_assoc($query);
        mysql_free_result($query);

        if($res_chk_email["qtd_pessoa"]) {
            echo '<script type="text/javascript">';
            echo 'parent.aviso("Já existe uma pessoa com o mesmo e-mail");';
            echo 'window.history.back();';
            echo '</script>';
            die();
        }
    }
    
    // FIM VALIDAÇÃO EMAIL
    
    // transacao
    $sql = "BEGIN";
    mysql_query($sql) or mysql_excecao($sql);
    
    // pessoa
    $sql = "INSERT INTO pessoa (cpfcnpj,nome_rfb,nome_completo,nome_publico,dt_nascimento,sexo) VALUES (".mysql_prepara($cpfcnpj).",".mysql_prepara($nome_rfb).",".mysql_prepara($nome_completo).",".mysql_prepara($nome_publico).",".mysql_prepara($dt_nascimento).",".mysql_prepara($sexo).")";
    mysql_query($sql) or mysql_excecao($sql);
    $pessoa_id = mysql_insert_id();

    mysql_log($sql);
    
    // telefone
    if($numero_tel_completo) {
        if(empty($res_chk_telefone["telefone_id"])) {
            $sql = "INSERT INTO telefone (ddi,ddd,numero,numero_completo,numero_com9d,numero_completo_com9d) VALUES (".mysql_prepara($ddi).",".mysql_prepara($ddd).",".mysql_prepara($numero_tel).",".mysql_prepara($numero_tel_completo).",".mysql_prepara($numero_tel_com9d).",".mysql_prepara($numero_tel_completo_com9d).")";
            mysql_query($sql) or mysql_excecao($sql);
            $telefone_id = mysql_insert_id();

            mysql_log($sql);

        } else {
            $telefone_id = $res_chk_telefone["telefone_id"];
        }

        // vinculo
        $sql = "INSERT INTO pessoa_telefone (pessoa_id,telefone_id,situacao) VALUES (".mysql_prepara($pessoa_id).",".mysql_prepara($telefone_id).",'ativo')";
        mysql_query($sql) or mysql_excecao($sql);

        mysql_log($sql);
    }
    
    // email
    if($email) {
        if(empty($res_chk_email["email_id"])) {
            $sql = "INSERT INTO email (endereco) VALUES (".mysql_prepara($email).")";
            mysql_query($sql) or mysql_excecao($sql);
            $email_id = mysql_insert_id();

            mysql_log($sql);

        } else {
            $email_id = $res_chk_email["email_id"];
        }

        // vinculo
        $sql = "INSERT INTO pessoa_email (pessoa_id,email_id,situacao) VALUES (".mysql_prepara($pessoa_id).",".mysql_prepara($email_id).",'ativo')";
        mysql_query($sql) or mysql_excecao($sql);

        mysql_log($sql);
    }
    
    // endereço
    if($cep) {
        $sql = "
                SELECT
                    id AS endereco_id
                FROM endereco
                WHERE cep = ".mysql_prepara($cep)." AND logradouro = ".mysql_prepara($logradouro)." AND numero = ".mysql_prepara($numero_end)." AND complemento = ".mysql_prepara($complemento)." AND bairro = ".mysql_prepara($bairro)." AND cidade_id = ".mysql_prepara($cidade);
        $query = mysql_query($sql) or mysql_excecao($sql);
        $res_chk_endereco = mysql_fetch_assoc($query);
        mysql_free_result($query);

        if(empty($res_chk_endereco["endereco_id"])) {
            $sql = "INSERT INTO endereco (cidade_id,cep,logradouro,numero,complemento,bairro) VALUES (".mysql_prepara($cidade).",".mysql_prepara($cep).",".mysql_prepara($logradouro).",".mysql_prepara($numero_end).",".mysql_prepara($complemento).",".mysql_prepara($bairro).")";
            mysql_query($sql) or mysql_excecao($sql);
            $endereco_id = mysql_insert_id();

            mysql_log($sql);

        } else {
            $endereco_id = $res_chk_endereco["endereco_id"];
        }

        // vinculo
        $sql = "INSERT INTO pessoa_endereco (pessoa_id,endereco_id,situacao) VALUES (".mysql_prepara($pessoa_id).",".mysql_prepara($endereco_id).",'ativo')";
        mysql_query($sql) or mysql_excecao($sql);

        mysql_log($sql);
    }
    
    // tipo do formulario (filho)
    if($form == "cliente") {
        $sql = "INSERT INTO cliente (pessoa_id) VALUES (".mysql_prepara($pessoa_id).")";
        mysql_query($sql) or mysql_excecao($sql);

        mysql_log($sql);
        
    } elseif($form == "supervisor") {
        $sql = "INSERT INTO supervisor (pessoa_id) VALUES (".mysql_prepara($pessoa_id).")";
        mysql_query($sql) or mysql_excecao($sql);

        mysql_log($sql);
    
    } elseif($form == "usuario") {
        $sql = "INSERT INTO usuario (pessoa_id) VALUES (".mysql_prepara($pessoa_id).")";
        mysql_query($sql) or mysql_excecao($sql);

        mysql_log($sql);
    }
    
    // transacao
    $sql = "COMMIT";
    mysql_query($sql) or mysql_excecao($sql);
    
    // ok
    if($retornofuncao) {
        echo '<script type="text/javascript">';
        echo '
            $(document).ready(function() {
                parent.$("#modaliframe1").find("iframe")[0].contentWindow.'.$retornofuncao.'();
                parent.$("#modaliframe2").modal("hide");
            });
        ';
        echo '</script>';
        die();
        
    } else {
        redireciona($retornourl);
    }
}

// consulta pessoa rfb
if($cpfcnpj) {
    $tam = strlen($cpfcnpj);
    
    // cpf
    if($tam == 11) {
        $api_rfb = ext_rfb_cpf($cpfcnpj);
        $api_rfb = json_decode($api_rfb, true);

        if($api_rfb["code"] == "200") {
            $api_rfb = array(
                "nome" => utf8_encode($api_rfb["data"]["nome"]),
                "nome_publico" => utf8_encode($api_rfb["data"]["nome"]),
                "dt_nascimento" => $api_rfb["data"]["data_nascimento"],
                "sexo" => $api_rfb["data"]["genero"]
            );

            $api_rfb_retorno = $api_rfb["nome"];

        } else {
            $api_rfb_retorno = utf8_decode($api_rfb["msg"]);
        }
                
    // cnpj
    } elseif($tam == 14) {
        $api_rfb = ext_rfb_cnpj($cpfcnpj);
        $api_rfb = json_decode($api_rfb, true);

        if($api_rfb["estabelecimento"]["nome_fantasia"]) {
            $api_rfb = array(
                "nome" => f_ppalavra_cxalta(utf8_encode($api_rfb["razao_social"])),
                "nome_publico" => f_ppalavra_cxalta(utf8_encode($api_rfb["estabelecimento"]["nome_fantasia"])),
                "cep" => utf8_encode($api_rfb["estabelecimento"]["cep"]),
                "numero_end" => utf8_encode($api_rfb["estabelecimento"]["numero"])
            );

            $api_rfb_retorno = $api_rfb["nome"];

        } else {
            $api_rfb_retorno = utf8_decode($api_rfb["msg"]);
        }
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
                    <div class="form-group col-xs-12">
                        <div class="fieldset_form">Informações básicas</div>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>CPF/CNPJ</label>
                        <input type="text" class="form-control" value="<?php echo mascara_cpfcnpj($cpfcnpj); ?>" readonly>
                    </div>

                    <div class="form-group col-xs-6">
                        <label>Consulta na Receita Federal</label>
                        <input type="text" class="form-control" name="nome_rfb" value="<?php echo $api_rfb_retorno; ?>" readonly>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Nome completo</label>
                        <input type="text" class="form-control" name="nome_completo" value="<?php echo $api_rfb["nome"]; ?>" required>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Nome público</label>
                        <input type="text" class="form-control" name="nome_publico" value="<?php echo $api_rfb["nome_publico"]; ?>" required>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Data de nascimento</label>
                        <input type="date" class="form-control" name="dt_nascimento" value="<?php echo $api_rfb["dt_nascimento"]; ?>">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Sexo</label>
                        <select class="form-control" name="sexo">
                            <option value="" selected disabled></option>
                            
                            <option value="<?php echo codifica("M"); ?>" data-sexo="M" <?php if($api_rfb["sexo"] == "M") { echo "selected"; } ?>>Masculino</option>
                            <option value="<?php echo codifica("F"); ?>" data-sexo="F" <?php if($api_rfb["sexo"] == "F") { echo "selected"; } ?>>Feminino</option>
                        </select>
                    </div>
                    
                    <div class="form-group col-xs-12">
                        <hr class="hr_divisao_form">
                        <div class="fieldset_form">Informações de contato</div>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Telefone</label> <span><small>(DDI + DDD + Número)</small></span>
                        <div class="input-group">
                            <span class="input-group-addon">+</span>
                            <input type="number" class="form-control" style="width: 25%;" name="ddi">
                            <input type="number" class="form-control" style="width: 25%;" name="ddd">
                            <input type="text" class="form-control" style="width: 50%;" name="numero_tel">
                        </div>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>E-mail</label>
                        <input type="email" class="form-control" name="email">
                    </div>                    
                    
                    <div class="form-group col-xs-12">
                        <hr class="hr_divisao_form">
                        <div class="fieldset_form">Informações do endereço</div>
                    </div>

                    <div class="form-group col-xs-6">
                        <label>CEP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="cep" pattern="[0-9]*" inputmode="numeric" value="<?php echo $api_rfb["cep"]; ?>">
                            <span class="input-group-btn">
                                <button type="button" name="btn_consultar_endereco" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group col-xs-6">
                        <label>Logradouro</label>
                        <input type="text" class="form-control" name="logradouro">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Número</label>
                        <input type="text" class="form-control" name="numero_end" value="<?php echo $api_rfb["numero_end"]; ?>">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Complemento</label>
                        <input type="text" class="form-control" name="complemento">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Bairro</label>
                        <input type="text" class="form-control" name="bairro">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Cidade</label>
                        <select class="form-control" name="cidade">
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        id AS cidade_id,
                                        ibge,
                                        nome
                                    FROM cidade
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_cidade = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_cidade["cidade_id"]).'" data-ibge="'.$res_cidade["ibge"].'">'.$res_cidade["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="<?php if(stripos($form, "responsavel_") !== false) { echo "form-group"; } ?> col-xs-6">
                        <label>Estado</label>
                        <select class="form-control" name="estado">
                            <option value="" selected disabled></option>

                            <?php
                            $sql = "
                                    SELECT
                                        id AS estado_id,
                                        uf,
                                        nome
                                    FROM estado
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_estado = mysql_fetch_assoc($query)) {
                                echo '<option value="'.codifica($res_estado["estado_id"]).'" data-uf="'.$res_estado["uf"].'">'.$res_estado["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("novo_pessoaetapa2"); ?>" id="ac" name="ac" required>
            </form>
        </div>
        <!-- ./conteudo -->
        
    </div>
    
    <script type="text/javascript">
        $(document).ready(function() {
            setTimeout(function () {
                $("input[name='nome_completo']").focus();
            }, 500);
            
            // ajustes modal
            var mo = parent.$("#modaliframe2");
            var bt = mo.find(".btn-success");
            bt.html("Salvar");
            bt.attr("disabled", false);
                        
            // consulta endereco
            $("button[name='btn_consultar_endereco']").click(function() {
                consultar_endereco();
            });
        });
        
        function consultar_endereco() {
            // cfg
            var v = $("input[name='cep']").val();
            
            parent.loading();
            
            $.ajax({
                url: "<?php echo $app_cfg["path_raiz"]; ?>/pag/app_consulta/endereco_consultar.php?cep="+v,
                cache: false,
                dataType: "json",
                
            }).done(function(r) {
                // sem erro
                if(typeof r.msg === "undefined") {
                    $("input[name='logradouro']").val(r.data.logradouro);
                    $("input[name='numero_end']").val("");
                    $("input[name='complemento']").val(r.data.complemento);
                    $("input[name='bairro']").val(r.data.bairro);
                    $("select[name='cidade'] option[data-ibge='"+r.data.ibge+"']").prop("selected", true);
                    $("select[name='estado'] option[data-uf='"+r.data.uf+"']").prop("selected", true);
                    
                // com erro
                } else {
                    parent.aviso(r.msg);
                    
                    // limpa form
                    $("input[name='logradouro']").val("");
                    $("input[name='numero_end']").val("");
                    $("input[name='complemento']").val("");
                    $("input[name='bairro']").val("");
                }
                
            }).fail(function(jqXHR, textStatus, errorThrown) {
                parent.aviso("Ocorreu uma falha ao consultar o endereço (Erro: "+textStatus+" - "+errorThrown+")");
                
                // limpa form
                $("input[name='logradouro']").val("");
                $("input[name='numero_end']").val("");
                $("input[name='complemento']").val("");
                $("input[name='bairro']").val("");
                
            }).always(function() {
                parent.loading_fecha();
            });
        }
    </script>

    <!-- cfg rodape -->
    <?php require_once("../../inc_cfg_rodape.php"); ?>
</body>
</html>