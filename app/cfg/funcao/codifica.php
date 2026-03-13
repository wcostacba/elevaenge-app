<?php
function codifica($s) {
    return trim(base64_encode(strrev(base64_encode($s))));
}
?>