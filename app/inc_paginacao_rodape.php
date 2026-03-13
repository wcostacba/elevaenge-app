<small>
    <?php if($qtd_registro) { ?>
        Página <?php echo $pagina; ?> de <?php echo $qtd_pagina; ?> - Mostrando <?php echo $registro_inicial+1; ?> a <?php echo $registro_final; ?> de <?php echo $qtd_registro; ?> registro<?php if($qtd_registro > 1) { echo "s"; } ?>.
    <?php } else { ?>
        Nenhum registro
    <?php } ?>
</small>

<?php if($qtd_registro) { ?>
    <ul class="pagination no-margin pull-right">
        <?php $url_pag = url_remove_arg($_SERVER["REQUEST_URI"],"pag",true); ?>                            

        <?php $pag_dis = ""; if($pagina == 1) { $pag_dis = 'class="disabled"'; } ?>
        <li <?php echo $pag_dis; ?>><a href="<?php echo $url_pag; ?>pag=<?php echo codifica($pagina-1); ?>">Anterior</a></li>

        <?php
        $pag_i = 1;
        $pag_f = $qtd_pagina;

        if($qtd_pagina > 7) {
            if(($pagina-3) < 1) {
                $pag_i = 1;
            } else {
                $pag_i = $pagina-3;
            }

            if(($pagina+3) > $qtd_pagina) {
                $pag_i = $qtd_pagina-6;
            }

            $pag_f = $pag_i+6;
        }

        for($i=$pag_i;$i<=$pag_f;$i++) {
            $pag_at = "";

            if($pagina == $i) { $pag_at = 'class="active"'; }

            echo '<li '.$pag_at.'><a href="'.$url_pag.'pag='.codifica($i).'">'.$i.'</a></li>';
        }
        ?>

        <?php $pag_dis = ""; if($pagina == $qtd_pagina) { $pag_dis = 'class="disabled"'; } ?>
        <li <?php echo $pag_dis; ?>><a href="<?php echo $url_pag; ?>pag=<?php echo codifica($pagina+1); ?>">Próximo</a></li>
    </ul>
<?php } ?>