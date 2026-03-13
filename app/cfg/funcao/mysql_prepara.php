<?php
function mysql_prepara($s) {
    $s = trim($s);
    $f = "null";
    
    // campo null (como string)
    if($s == "NULL" && ($s != "0")) {
        $f = "null";
        
    // campo null (vazio)
    } elseif((strlen($s) == 0) && ($s != "0")) {
        $f = "null";
    
    // texto entre aspa para o sql
    } else {
        $f = "'".addslashes($s)."'";
    }
        
    return $f;
}
?>