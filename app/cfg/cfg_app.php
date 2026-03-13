<?php
mysql_conecta();

// configuracao geral sistema
$sql = "
        SELECT
            chave, 
            valor
        FROM app_cfg
        ORDER BY chave ASC";
$query = mysql_query($sql) or mysql_excecao($sql);

$app_cfg = array();

while($res = mysql_fetch_assoc($query)) {
    $app_cfg[$res["chave"]] = $res["valor"];
}

mysql_free_result($query);

mysql_close();

// sessao dinamico
$app_cfg["token"] = hash("sha256",$ambiente_cfg["bd_banco"].$app_cfg["id"]);
?>