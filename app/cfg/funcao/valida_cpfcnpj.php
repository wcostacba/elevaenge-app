<?php
function valida_cpfcnpj($s) {
    // somente numero
    $s = preg_replace("/[^0-9]/", "", $s);
    $tam = strlen($s);
    
    if(($tam !== 11) && ($tam !== 14)) {
        return false;
    }

    // valida cpf
    if($tam == 11) {
        for($t = 9; $t < 11; $t++) {
            for($d = 0, $c = 0; $c < $t; $c++) {
                $d += $s{$c} * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if($s{$c} != $d) {
                return false;
            }
        }
    }

    return true;
}
?>