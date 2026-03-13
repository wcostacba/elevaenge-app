<?php
function url_remove_arg($req,$arg_remove = false,$concat = false) {
    $req = explode("?",trim($req));
    $arg_remove = trim($arg_remove);
    
    $url = $req[0];
    $arg = $req[1];
    
    // remove todos os argumentos
    if(!$arg_remove) {
        $arg = false;
    }
    
    if($arg) {
        if(strpos($arg, $arg_remove) !== false) {
            $t = explode("&", $arg);

            foreach($t as $k => $v) {
                if((strpos($v, $arg_remove) !== false) || empty($v)) {
                    unset($t[$k]);
                }
            }

            $arg = implode("&", $t);
        }
    }
    
    // monta url sem o argumento removido
    if($arg) {
        $url = $url."?".$arg;
    }
    
    // acrescenta ? ou & no final
    if($concat) {
        if($arg) {
            $url = $url."&";
        } else {
            $url = $url."?";
        }
    }
    
    return $url;
}
?>