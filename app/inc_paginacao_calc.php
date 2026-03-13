<?php
$pagina = isset($_GET["pag"]) ? decodifica($_GET["pag"]) : 1;
$por_pagina = $app_cfg["paginacao_qtd_por_pag"];
$qtd_pagina = ceil($qtd_registro / $por_pagina);
if(($pagina > $qtd_pagina) || ($pagina < 1)) { $pagina = 1; }
$registro_inicial = ($pagina - 1) * $por_pagina;
$registro_final = $registro_inicial + $por_pagina;
if($registro_final > $qtd_registro) { $registro_final = $qtd_registro; }
?>