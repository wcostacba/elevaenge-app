<?php
require_once("../inc_cfg_geral_nologin.php");

// config
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");

$metodo = $_SERVER["REQUEST_METHOD"];

// validacao
if($metodo != "GET") {
    $http_code = 404;
    
    $response = array(
        "erro" => true,
        "msg" => utf8_encode("Método não encontrado")
    );
    
// sem erro
} else {
    mysql_conecta();
    
    $sql = "
            SELECT
                cliente.id AS cliente_id, 
                pessoa.cpfcnpj, 
                pessoa.nome_completo, 
                pessoa.nome_publico
            FROM cliente
                INNER JOIN pessoa ON cliente.pessoa_id = pessoa.id
            ORDER BY pessoa.nome_completo ASC";
    $query = mysql_query($sql) or mysql_excecao($sql);
    $qtd = mysql_num_rows($query);
    
    $data = array();
    
    while($res = mysql_fetch_assoc($query)) {
        $data[] = array(
            "id" => $res["cliente_id"],
            "cpfcnpj" => $res["cpfcnpj"],
            "nome_completo" => utf8_encode($res["nome_completo"]),
            "nome_publico" => utf8_encode($res["nome_publico"])
        );
    }
    
    mysql_free_result($query);
    
    mysql_close();
    
    $http_code = 200;
    
    $response = array(
        "qtd" => $qtd,
        "data" => $data
    );
}

// saida
http_response_code($http_code);
echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>