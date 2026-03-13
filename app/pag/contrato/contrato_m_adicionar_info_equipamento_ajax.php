<?php
require_once("../../inc_cfg_geral.php");

mysql_conecta();

// vetor
$vetor_equipamento = $_SESSION["contrato_equipamento_vinculo"];
$qtd_equipamento = sizeof($vetor_equipamento);

// equipamento
if($qtd_equipamento) {
    $sql = "
            SELECT
                equipamento.id AS equipamento_id, 
                equipamento.nome, 
                equipamento_marca.nome AS equipamento_marca_nome, 
                equipamento_modelo.nome AS equipamento_modelo_nome, 
                (
                    SELECT
                        pessoa.nome_publico
                    FROM cliente
                        INNER JOIN pessoa ON cliente.pessoa_id = pessoa.id
                    WHERE cliente.id = equipamento.cliente_id
                ) AS cliente_nome_publico, 
                centro_resultado_rota.nome AS centro_resultado_rota_nome, 
                (
                    SELECT
                        pessoa.nome_publico
                    FROM supervisor
                        INNER JOIN pessoa ON supervisor.pessoa_id = pessoa.id
                    WHERE supervisor.id = centro_resultado_rota.supervisor_id
                ) AS supervisor_nome_publico, 
                centro_resultado.nome AS centro_resultado_nome
            FROM equipamento
                LEFT JOIN contrato_equipamento ON equipamento.id = contrato_equipamento.equipamento_id
                INNER JOIN equipamento_marca ON equipamento.equipamento_marca_id = equipamento_marca.id
                INNER JOIN equipamento_modelo ON equipamento.equipamento_modelo_id = equipamento_modelo.id
                INNER JOIN centro_resultado_rota ON equipamento.centro_resultado_rota_id = centro_resultado_rota.id
                INNER JOIN centro_resultado ON centro_resultado_rota.centro_resultado_id = centro_resultado.id
            WHERE contrato_equipamento.contrato_id IS NULL AND equipamento.id IN (".implode(",",$vetor_equipamento).")
            GROUP BY equipamento.id
            ORDER BY equipamento.nome ASC";
    $query_registro = mysql_query($sql) or mysql_excecao($sql);
}
?>

<?php if($qtd_equipamento) { ?>
    <table class="table table-bordered table-hover table-striped table-line-lg">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Cliente</th>
                <th>CR</th>
                <th>Rota</th>
                <th>Supervisor</th>
            </tr>
        </thead>
        <tbody>
            <?php while($res_registro = mysql_fetch_assoc($query_registro)) { ?>
                <tr>
                    <td><?php echo $res_registro["nome"]; ?></td>
                    <td><?php echo $res_registro["equipamento_marca_nome"]; ?></td>
                    <td><?php echo $res_registro["equipamento_modelo_nome"]; ?></td>
                    <td><?php echo $res_registro["cliente_nome_publico"]; ?></td>
                    <td><?php echo $res_registro["centro_resultado_nome"]; ?></td>
                    <td><?php echo $res_registro["centro_resultado_rota_nome"]; ?></td>
                    <td><?php echo $res_registro["supervisor_nome_publico"]; ?></td>
                </tr>
            <?php } mysql_free_result($query_registro); ?>
        </tbody>
    </table>

<?php } else { ?>
    <table class="table table-bordered table-striped table-condensed">
        <tbody>
            <tr>
                <td>Nenhum equipamento vinculado</td>
            </tr>
        </tbody>
    </table>
<?php } ?>

<div class="text-right">
    <a href="#" class="btn btn-xs btn-success btloading" data-titulo="Vincular equipamento" data-pag="<?php echo $app_cfg["path_raiz"]; ?>/pag/equipamento/equipamento_contrato_m_vincular.php?token=<?php echo codifica($token); ?>" data-modaliframe="2" data-tam="grande" data-btnok="false">Vincular equipamento</a>
</div>