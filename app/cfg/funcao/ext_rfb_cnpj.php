<?php
function ext_rfb_cnpj($cnpj) {
    /* esta funcao não é critica, então, se der erro, o sistema só alerta, mas continua a execucao */
    
    global $api_cfg, $app_cfg;
    
    // id da api no banco de dados
    $api_id = 3;
    
    // api
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => "https://publica.cnpj.ws/cnpj/".$cnpj,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => "GET"
    ]);

    $response = curl_exec($curl);
    $curl_info = curl_getinfo($curl);
    $curl_erro = curl_error($curl);
    curl_close($curl);

    // api log
    $sql = "INSERT INTO api_log (api_id,endpoint,httpcode,requisicao,resposta,server,dthr) VALUES (".mysql_prepara($api_id).",".mysql_prepara("consulta").",".mysql_prepara($curl_info["http_code"]).",".mysql_prepara(print_r($curl_info, true)).",".mysql_prepara($response).",".mysql_prepara(print_r($_SERVER,true)).",".mysql_prepara(date("Y-m-d H:i:s", time())).")";
    mysql_query($sql) or mysql_excecao($sql);

    // validacao api
    if($curl_info["http_code"] != "200") {
        // nao encontrado
        if($curl_info["http_code"] == "404") {
            $response = array(
                "qtd" => 0,
                "msg" => utf8_encode("Pessoa não encontrada")
            );

        // outros
        } else {
            // padrao vindo da api
            $msg = json_decode($response)->message;
            
            if(empty($msg)) {
                $msg = utf8_encode("A API CNPJ WS está indisponível para consulta");
            }
            
            $response = array(
                "erro" => true,
                "msg" => $msg
            );
        }
        
        $response = json_encode($response);
    }

    return $response;
}
?>