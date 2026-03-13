<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// acesso
$idpagina = "YN4Q5ZZ044";
//valida_permissao($idpagina);

// parametro
$pessoa_id = decodifica($_GET["pessoa"]);
//$cod_paciente = decodifica($_GET["paciente"]);
$form = decodifica($_GET["form"]);
$retornofuncao = decodifica($_GET["retornofuncao"]);
$retornourl = decodifica($_GET["retornourl"]);

// pessoa
$sql = "
        SELECT
            cpfcnpj,
            nome_completo, 
            nome_rfb, 
            nome_publico, 
            dt_nascimento, 
            sexo
        FROM pessoa
        WHERE id = ".mysql_prepara($pessoa_id)."
        LIMIT 1";
$query = mysql_query($sql) or mysql_excecao($sql);
$res_pessoa = mysql_fetch_assoc($query);
$qtd_pessoa = mysql_num_rows($query);
mysql_free_result($query);

if(empty($qtd_pessoa)) {
    if($retornofuncao) {
        echo '<script src="'.$app_cfg["path_raiz"].'/tema/bower_components/jquery/dist/jquery.min.js"></script>';
        echo '<script type="text/javascript">';
        echo 'parent.aviso("Pessoa não encontrada com o código informado");';
        echo 'parent.$("#modaliframe2").modal("hide");';
        echo '</script>';
        die();

    } else {
        $_SESSION["aviso"] = "Pessoa não encontrada com o código informado";
        redireciona($retornourl);
    }
}

if(empty($res_pessoa["nome_rfb"])) {
    $flag_validar_rfb = true;
} else {
    $flag_validar_rfb = false;
}

// telefone
$sql = "
        SELECT
            telefone.ddi, 
            telefone.ddd, 
            telefone.numero,
            telefone.numero_completo
        FROM pessoa_telefone
	       INNER JOIN telefone ON pessoa_telefone.telefone_id = telefone.id
        WHERE pessoa_telefone.situacao = 'ativo' AND pessoa_telefone.pessoa_id = ".mysql_prepara($pessoa_id)."
        LIMIT 1";
$query = mysql_query($sql) or mysql_excecao($sql);
$res_telefone = mysql_fetch_assoc($query);
mysql_free_result($query);

// email
$sql = "
        SELECT
            email.endereco
        FROM pessoa_email
	       INNER JOIN email ON pessoa_email.email_id = email.id
        WHERE pessoa_email.situacao = 'ativo' AND pessoa_email.pessoa_id = ".mysql_prepara($pessoa_id)."
        LIMIT 1";
$query = mysql_query($sql) or mysql_excecao($sql);
$res_email = mysql_fetch_assoc($query);
mysql_free_result($query);

// endereco
$sql = "
        SELECT
            endereco.cep, 
            endereco.logradouro, 
            endereco.numero, 
            endereco.complemento, 
            endereco.bairro, 
            cidade.id AS cidade_id, 
            estado.id AS estado_id
        FROM pessoa_endereco
            INNER JOIN endereco ON pessoa_endereco.endereco_id = endereco.id
            INNER JOIN cidade ON endereco.cidade_id = cidade.id
            INNER JOIN estado ON cidade.estado_id = estado.id
        WHERE pessoa_endereco.situacao = 'ativo' AND pessoa_endereco.pessoa_id = ".mysql_prepara($pessoa_id)."
        LIMIT 1";
$query = mysql_query($sql) or mysql_excecao($sql);
$res_endereco = mysql_fetch_assoc($query);
mysql_free_result($query);

if(stripos($form, "responsavel_") !== false) {
    $sql = "
            SELECT
                cod_responsavel
            FROM responsavel
            WHERE cod_pessoa = ".mysql_prepara($pessoa_id)."
            LIMIT 1";
    $query = mysql_query($sql) or mysql_excecao($sql);
    $res_responsavel = mysql_fetch_assoc($query);
    mysql_free_result($query);
    
    $cod_responsavel = $res_responsavel["cod_responsavel"];
}

if($form == "responsavel_financeiro") {
    $sql = "
            SELECT
                cod_paciente_resp, 
                cod_grau_parentesco
            FROM paciente_resp
            WHERE cod_paciente = ".mysql_prepara($cod_paciente)." AND cod_responsavel = ".mysql_prepara($res_responsavel["cod_responsavel"])." AND cod_paciente_resp_tipo = 1
            LIMIT 1";
    $query = mysql_query($sql) or mysql_excecao($sql);
    $res_responsavel_financeiro = mysql_fetch_assoc($query);
    mysql_free_result($query);
    
    $responsavel_parentesco = $res_responsavel_financeiro["cod_grau_parentesco"];
    
} elseif($form == "responsavel_fiscal") {
    $sql = "
            SELECT
                cod_paciente_resp, 
                cod_grau_parentesco
            FROM paciente_resp
            WHERE cod_paciente = ".mysql_prepara($cod_paciente)." AND cod_responsavel = ".mysql_prepara($res_responsavel["cod_responsavel"])." AND cod_paciente_resp_tipo = 2
            LIMIT 1";
    $query = mysql_query($sql) or mysql_excecao($sql);
    $res_responsavel_fiscal = mysql_fetch_assoc($query);
    mysql_free_result($query);
    
    $responsavel_parentesco = $res_responsavel_financeiro["cod_grau_parentesco"];

} elseif($form == "profissional") {
    $sql = "
            SELECT
                cargo_id
            FROM profissional
            WHERE pessoa_id = ".mysql_prepara($pessoa_id)."
            LIMIT 1";
    $query = mysql_query($sql) or mysql_excecao($sql);
    $res_profissional = mysql_fetch_assoc($query);
    mysql_free_result($query);
}

// form
if(decodifica($_POST["ac"]) == "atualiza_pessoa") {
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
    
    $parentesco = decodifica($_POST["parentesco"]);
    $cargo = decodifica($_POST["cargo"]);
    
    // include do JS se o retorno for baseado em javascript jquery
    if($retornofuncao) {
        echo '<script src="'.$app_cfg["path_raiz"].'/tema/bower_components/jquery/dist/jquery.min.js"></script>';
    }
    
    // validacao
    if((empty($nome_completo)) || (empty($nome_publico)) || (!valida_data($dt_nascimento))) {
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
    
    if($flag_validar_rfb) {
        $update_campo_rfb = "nome_rfb = ".mysql_prepara($nome_rfb).",";
    }
    
    // pessoa
    $sql = "
            UPDATE pessoa SET 
                nome_completo = ".mysql_prepara($nome_completo).",
                ".$update_campo_rfb."
                nome_publico = ".mysql_prepara($nome_publico).",
                dt_nascimento = ".mysql_prepara($dt_nascimento).",
                sexo = ".mysql_prepara($sexo)."
            WHERE id = ".mysql_prepara($pessoa_id)."
            LIMIT 1";
    mysql_query($sql) or mysql_excecao($sql);

    mysql_log($sql);
    
    // telefone
    if($numero_tel_completo != $res_telefone["numero_completo"]) {
        // antigo
        if($res_telefone["numero_completo"]) {
            $sql = "
                    UPDATE pessoa_telefone SET
                        situacao = 'inativo'
                    WHERE pessoa_id = ".mysql_prepara($pessoa_id);
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
        }
        
        // novo
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
    }
    
    // email
    if($email != $res_email["endereco"]) {
        // antigo
        if($res_email["endereco"]) {
            $sql = "
                    UPDATE pessoa_email SET
                        situacao = 'inativo'
                    WHERE pessoa_id = ".mysql_prepara($pessoa_id);
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
        }
        
        // novo
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
    }
    
    // endereco
    if(($cep != $res_endereco["cep"]) || ($logradouro != $res_endereco["logradouro"]) || ($numero_end != $res_endereco["numero"]) || ($complemento != $res_endereco["complemento"]) || ($bairro != $res_endereco["bairro"]) || ($cidade != $res_endereco["cidade_id"])) {
        // antigo
        if($res_endereco["cep"]) {
            $sql = "
                    UPDATE pessoa_endereco SET
                        situacao = 'inativo'
                    WHERE pessoa_id = ".mysql_prepara($pessoa_id);
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
        }
        
        // novo
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
    }

    // tipo do formulario - responsavel
    if((stripos($form, "responsavel_") !== false) && (empty($res_responsavel["cod_responsavel"]))) {
        $sql = "INSERT INTO responsavel (cod_pessoa) VALUES (".mysql_prepara($pessoa_id).")";
        mysql_query($sql) or mysql_excecao($sql);
        $cod_responsavel = mysql_insert_id();

        mysql_log($sql);
    }
        
    // tipo do formulario - financeiro
    if($form == "responsavel_financeiro") {
        if($res_responsavel_financeiro["cod_paciente_resp"]) {
            $sql = "
                UPDATE paciente_resp SET
                    cod_grau_parentesco = ".mysql_prepara($parentesco)."
                WHERE cod_paciente_resp = ".mysql_prepara($res_responsavel_financeiro["cod_paciente_resp"])."
                LIMIT 1";
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
            
        } else {
            $sql = "INSERT INTO paciente_resp (cod_paciente,cod_responsavel,cod_paciente_resp_tipo,cod_grau_parentesco) VALUES (".mysql_prepara($cod_paciente).",".mysql_prepara($cod_responsavel).",1,".mysql_prepara($parentesco).")";
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
        }
        
    } elseif($form == "responsavel_fiscal") {
        if($res_responsavel_fiscal["cod_paciente_resp"]) {
            $sql = "
                UPDATE paciente_resp SET
                    cod_grau_parentesco = ".mysql_prepara($parentesco)."
                WHERE cod_paciente_resp = ".mysql_prepara($res_responsavel_fiscal["cod_paciente_resp"])."
                LIMIT 1";
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
            
        } else {
            $sql = "INSERT INTO paciente_resp (cod_paciente,cod_responsavel,cod_paciente_resp_tipo,cod_grau_parentesco) VALUES (".mysql_prepara($cod_paciente).",".mysql_prepara($cod_responsavel).",2,".mysql_prepara($parentesco).")";
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
        }
    
    } elseif($form == "profissional") {
        if($cargo != $res_profissional["cargo_id"]) {
            $sql = "
                    UPDATE profissional SET
                        cargo_id = ".mysql_prepara($cargo)."
                    WHERE pessoa_id = ".mysql_prepara($pessoa_id)."
                    LIMIT 1";
            mysql_query($sql) or mysql_excecao($sql);

            mysql_log($sql);
        }
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
if(empty($flag_validar_rfb)) {
    $api_rfb_retorno = $res_pessoa["nome_rfb"];
    
} elseif($res_pessoa["cpfcnpj"]) {
    $api_rfb = ext_rfb_cpf($res_pessoa["cpfcnpj"]);
    $api_rfb = json_decode($api_rfb, true);

    if($api_rfb["code"] == "200") {
        $api_rfb = array(
            "nome" => utf8_encode($api_rfb["data"]["nome"]),
            "dt_nascimento" => $api_rfb["data"]["data_nascimento"],
            "sexo" => $api_rfb["data"]["genero"]
        );

        $api_rfb_retorno = $api_rfb["nome"];

    } else {
        $api_rfb_retorno = utf8_decode($api_rfb["msg"]);
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
                        <input type="text" class="form-control" value="<?php echo mascara_cpfcnpj($res_pessoa["cpfcnpj"]); ?>" readonly>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Consulta na Receita Federal</label>
                        <input type="text" class="form-control" name="nome_rfb" value="<?php echo $api_rfb_retorno; ?>" readonly>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Nome completo</label>
                        <input type="text" class="form-control" name="nome_completo" value="<?php echo $res_pessoa["nome_completo"]; ?>" required>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Nome público</label>
                        <input type="text" class="form-control" name="nome_publico" value="<?php echo $res_pessoa["nome_publico"]; ?>" required>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Data de nascimento</label>
                        <input type="date" class="form-control" name="dt_nascimento" value="<?php echo $res_pessoa["dt_nascimento"]; ?>" required>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Gênero</label>
                        <select class="form-control" name="sexo" required>
                            <option value="" selected disabled></option>
                            
                            <option value="<?php echo codifica("M"); ?>" <?php if($res_pessoa["sexo"] == "M") { echo "selected"; } ?>>Masculino</option>
                            <option value="<?php echo codifica("F"); ?>" <?php if($res_pessoa["sexo"] == "F") { echo "selected"; } ?>>Feminino</option>
                        </select>
                    </div>
                    
                    <?php if($flag_validar_rfb) { ?>
                        <div class="form-group col-xs-12">
                            <div class="fieldset_form">Informações na Receita Federal</div>
                        </div>

                        <div class="form-group col-xs-6">
                            <label>Data de nascimento</label>
                            <input type="date" class="form-control" value="<?php echo $api_rfb["dt_nascimento"]; ?>" readonly>
                        </div>

                        <div class="form-group col-xs-6">
                            <label>Sexo</label>
                            <input type="text" class="form-control" value="<?php if($api_rfb["sexo"] == "M") { echo "Masculino"; } elseif($api_rfb["sexo"] == "F") { echo "Feminino"; } ?>" readonly>
                        </div>
                    <?php } ?>
                    
                    <div class="form-group col-xs-12">
                        <hr class="hr_divisao_form">
                        <div class="fieldset_form">Informações de contato</div>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Telefone</label> <span><small>(DDI + DDD + Número)</small></span>
                        <div class="input-group">
                            <span class="input-group-addon">+</span>
                            <input type="number" class="form-control" style="width: 25%;" value="<?php echo $res_telefone["ddi"]; ?>" name="ddi">
                            <input type="number" class="form-control" style="width: 25%;" value="<?php echo $res_telefone["ddd"]; ?>" name="ddd">
                            <input type="text" class="form-control" style="width: 50%;" value="<?php echo $res_telefone["numero"]; ?>" name="numero_tel">
                        </div>
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>E-mail</label>
                        <input type="email" class="form-control" name="email" value="<?php echo $res_email["endereco"]; ?>">
                    </div>                    
                    
                    <div class="form-group col-xs-12">
                        <hr class="hr_divisao_form">
                        <div class="fieldset_form">Informações do endereço</div>
                    </div>

                    <div class="form-group col-xs-6">
                        <label>CEP</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="cep" value="<?php echo $res_endereco["cep"]; ?>" pattern="[0-9]*" inputmode="numeric">
                            <span class="input-group-btn">
                                <button type="button" name="btn_consultar_endereco" class="btn btn-default"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </div>

                    <div class="form-group col-xs-6">
                        <label>Logradouro</label>
                        <input type="text" class="form-control" value="<?php echo $res_endereco["logradouro"]; ?>" name="logradouro">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Número</label>
                        <input type="text" class="form-control" value="<?php echo $res_endereco["numero"]; ?>" name="numero_end">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Complemento</label>
                        <input type="text" class="form-control" value="<?php echo $res_endereco["complemento"]; ?>" name="complemento">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Bairro</label>
                        <input type="text" class="form-control" value="<?php echo $res_endereco["bairro"]; ?>" name="bairro">
                    </div>
                    
                    <div class="form-group col-xs-6">
                        <label>Cidade</label>
                        <select class="form-control" name="cidade">
                            <option value="" selected disabled></option>

                            <?php
                            // cidade
                            $sql = "
                                    SELECT
                                        id AS cidade_id,
                                        ibge,
                                        nome
                                    FROM cidade
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_cidade = mysql_fetch_assoc($query)) {
                                $selected = false;
                                
                                if($res_cidade["cidade_id"] == $res_endereco["cidade_id"]) {
                                    $selected = "selected";
                                }
                                
                                echo '<option value="'.codifica($res_cidade["cidade_id"]).'" data-ibge="'.$res_cidade["ibge"].'" '.$selected.'>'.$res_cidade["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                    
                    <div class="<?php if($form) { echo "form-group"; } ?> col-xs-6">
                        <label>Estado</label>
                        <select class="form-control" name="estado">
                            <option value="" selected disabled></option>

                            <?php
                            // estado
                            $sql = "
                                    SELECT
                                        id AS estado_id,
                                        uf,
                                        nome
                                    FROM estado
                                    ORDER BY nome ASC";
                            $query = mysql_query($sql) or mysql_excecao($sql);

                            while($res_estado = mysql_fetch_assoc($query)) {
                                $selected = false;
                                
                                if($res_estado["estado_id"] == $res_endereco["estado_id"]) {
                                    $selected = "selected";
                                }
                                
                                echo '<option value="'.codifica($res_estado["estado_id"]).'" data-uf="'.$res_estado["uf"].'" '.$selected.'>'.$res_estado["nome"].'</option>';
                            }

                            mysql_free_result($query);
                            ?>
                        </select>
                    </div>
                
                    <?php if(stripos($form, "responsavel_") !== false) { ?>
                        <div class="form-group col-xs-12">
                            <hr class="hr_divisao_form">
                            <div class="fieldset_form">Informações do responsável</div>
                        </div>

                        <div class="col-xs-6">
                            <label>Grau de parentesco</label>
                            <select class="form-control" name="parentesco" required>
                                <option value="" selected disabled></option>

                                <?php
                                // grau parentesco
                                $sql = "
                                        SELECT
                                            cod_grau_parentesco, 
                                            nome_grau_parentesco
                                        FROM grau_parentesco
                                        ORDER BY nome_grau_parentesco ASC";
                                $query = mysql_query($sql) or mysql_excecao($sql);

                                while($res_grau = mysql_fetch_assoc($query)) {
                                    $selected = false;
                                
                                    if($res_grau["cod_grau_parentesco"] == $responsavel_parentesco) {
                                        $selected = "selected";
                                    }

                                    echo '<option value="'.codifica($res_grau["cod_grau_parentesco"]).'" '.$selected.'>'.$res_grau["nome_grau_parentesco"].'</option>';
                                }

                                mysql_free_result($query);
                                ?>
                            </select>
                        </div>
                    
                    <?php } elseif($form == "profissional") { ?>
                        <div class="form-group col-xs-12">
                            <hr class="hr_divisao_form">
                            <div class="fieldset_form">Informações do profissional</div>
                        </div>

                        <div class="col-xs-6">
                            <label>Cargo</label>
                            <select class="form-control" name="cargo" required>
                                <option value="" selected disabled></option>

                                <?php
                                // cargo
                                $sql = "
                                        SELECT
                                            id AS cargo_id,
                                            nome
                                        FROM cargo
                                        ORDER BY nome ASC";
                                $query = mysql_query($sql) or mysql_excecao($sql);

                                while($res_cargo = mysql_fetch_assoc($query)) {
                                    $selected = false;
                                
                                    if($res_cargo["cargo_id"] == $res_profissional["cargo_id"]) {
                                        $selected = "selected";
                                    }

                                    echo '<option value="'.codifica($res_cargo["cargo_id"]).'" '.$selected.'>'.$res_cargo["nome"].'</option>';
                                }

                                mysql_free_result($query);
                                ?>
                            </select>
                        </div>
                    
                    <?php } ?>
                </div>
            
                <button type="submit" class="hidden"></button>
                <input type="hidden" value="<?php echo codifica("atualiza_pessoa"); ?>" id="ac" name="ac" required>
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