<?php
mysql_conecta();

// configuracao api
$sql = "
        SELECT
            api.id AS api_id, 
            api_cfg.chave, 
            api_cfg.valor
        FROM api
            INNER JOIN api_cfg ON api.id = api_cfg.api_id
        ORDER BY api.nome ASC, api_cfg.chave ASC";
$query = mysql_query($sql) or mysql_excecao($sql);

$api_cfg = array();

while($res = mysql_fetch_assoc($query)) {
    $api_cfg[$res["api_id"]][$res["chave"]] = $res["valor"];
}

mysql_free_result($query);

mysql_close();
?>