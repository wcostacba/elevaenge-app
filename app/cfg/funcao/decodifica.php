<?php
function decodifica($s) {
    return trim(base64_decode(strrev(base64_decode($s))));
}
?>