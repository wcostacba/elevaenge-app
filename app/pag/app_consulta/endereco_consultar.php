<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// parametro
$formato = trim(addslashes($_GET["formato"]));
$cep = preg_replace("/[^0-9]/", "", $_GET["cep"]);

// validacao
if(strlen($cep) != 8) {
    $response = array(
        "erro" => true,
        "msg" => utf8_encode("O CEP informado é inválido")
    );

// sem erro
} else {
    $response = ext_cep($cep);
    $response = json_decode($response, true);
    
    if($response["cep"]) {
        $response = array(
            "qtd" => 1,
            "data" => $response
        );
        
        // checa cidade no bd
        $sql = "
                SELECT
                    id
                FROM cidade
                WHERE ibge = ".mysql_prepara($response["data"]["ibge"])."
                LIMIT 1";
        $query_cidade = mysql_query($sql) or mysql_excecao($sql);
        $res_cidade = mysql_fetch_assoc($query_cidade);
        $qtd_cidade = mysql_num_rows($query_cidade);
        mysql_free_result($query_cidade);
        
        if(empty($qtd_cidade)) {
            $sql = "INSERT INTO cidade (estado_id,ibge,nome) VALUES ((SELECT id FROM estado WHERE estado.uf = ".mysql_prepara($response["data"]["uf"])." LIMIT 1),".mysql_prepara($response["data"]["ibge"]).",".mysql_prepara(utf8_decode($response["data"]["localidade"])).")";
            mysql_query($sql) or mysql_excecao($sql);
            
            mysql_log($sql);
        }
        
    } else {
        $response = array(
            "qtd" => 0,
            "msg" => utf8_encode("Endereço não encontrado com o CEP informado")
        );
    }
}

// formato array
if($formato == "array") {
    
    
// formato json (padrao)
} else {
    echo json_encode($response);
}
?>