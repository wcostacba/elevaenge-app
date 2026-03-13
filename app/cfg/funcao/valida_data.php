<?php
function valida_data($dt) {
    $dt = explode("-",trim($dt));
    
    if(checkdate($dt[1], $dt[2], $dt[0])) {
        return true;
    } else {
        return false;
    }
}
?>