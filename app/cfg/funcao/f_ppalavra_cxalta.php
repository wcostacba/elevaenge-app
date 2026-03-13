<?php
function f_ppalavra_cxalta($s, $encoding = "ISO-8859-1") {
    // dados
    $s = mb_strtolower(trim($s), $encoding);
    $s = mb_convert_case($s, MB_CASE_TITLE, $encoding);
    
    return $s;
}
?>