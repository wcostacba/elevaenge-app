<?php
function mascara_cpfcnpj($s) {
    // config
    $s = preg_replace("/[^0-9]/", "", $s);
    $tam = strlen($s);
    $f = null;
    
    // cpf
    if($tam == 11) {
        if(valida_cpfcnpj($s)) {
            $f = sprintf("%d%d%d.%d%d%d.%d%d%d-%d%d",...str_split($s));
        }
        
    // cnpj
    } elseif($tam == 14) {
        $f = sprintf("%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d",...str_split($s));
        
    // invalido
    } else {
        $f = $s;
    }
    
    return $f;
}
?>