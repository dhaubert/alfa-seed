<?php
include ("./controller/principal.php");
$main = new principal();
//print_r($_FILES);
//if (isset($_POST['action']) && $_POST['action'] == 'importar') {
//    echo "lendo_csv";
//    print_r($_FILES);
//
////    $arquivo = fopen('enapet_inscritos.csv', 'r');
//    die;
//}
ob_start();
?>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <fieldset>
                <legend>
                    <i class="fa fa-sun-o"></i> Clima
                </legend>
                <div class="container">
                    <div class="row">
                        <div class="col-lg-3">
                            <div class="btn-group ">
                                <input onclick="alterna_clima()" id="dados_inmet" class="btn btn-success active" type="button" value="<?php echo _("Dados INMET"); ?>"/>
                                <input onclick="alterna_clima()" id="dados_csv" class="btn btn-success" type="button" value="<?php echo _("Importar arquivo"); ?>"/>
                            </div>
                        </div>
                        <div class="col-lg-9" >
                            <div id="loading" style="display: none">
                                    <i class="fa fa-spinner" id="spinner" style="font-size: 22px; color: #308E4B;"></i>
                                    <?php echo '&nbsp;' . _("Obtendo dados..."); ?>
                                </div>
                            <div id="mensagem_clima">
                            </div>
                        </div>
                    </div>
                </div>
                <hr />
                <form name="clima" id="clima" name="clima">
                    <div class="container">
                        <div id="clima_inmet" class="row">
                            <div class="col-lg-3">

                                <select multiple id="estacoes" name="estacoes" class="form-control" >
                                    <?php
                                    $estacoes = $main->get_estacoes();
                                    foreach ($estacoes as $estacao) {
                                        ?>
                                        <option value="<?php echo $estacao['estacao_id']; ?>"
                                                onclick="atualiza_mapa(
                                                                    '<?php echo $estacao['estacao_id']; ?>',
                                                                    '<?php echo $estacao['latitude']; ?>',
                                                                    '<?php echo $estacao['longitude']; ?>',
                                                                    '<?php echo $estacao['altitude']; ?>');">
                                                    <?php echo utf8_encode($estacao['municipio']); ?>
                                        </option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-lg-5">
                                <div id="map_canvas" style="width:100%; height:260px; background-color: #308E4B">Mapa</div>
                            </div>
                            <div class="col-lg-4 center-block">
                                <div class="input-group has-success">
                                    <span class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                    <input id="data_plantio" type="text" class="form-control" placeholder="<?php echo _("Data de plantio"); ?>">
                                    <div id="div_datepicker" ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div id="clima_csv" class="row" style="display:none">
                            <div class="col-lg-8">
                                <p>Ler arquivo csv </p> <input type="file" name="importar_csv" id="importar_csv"/>
                                <input type="hidden" id="action" name="action" value='importar'/>
                                <input type="button" onclick="enviar_arquivo();" 
                                       class="btn btn-success" value="<?php echo _("Enviar"); ?>"/>
                            </div>
                        </div>
                    </div>
                    <div class="container">
                        <div class="row">
                            <div class="cols-lg-12 center-block" style="margin-top:10px">
                                <input type="button" class="btn btn-success" onclick="salvar_clima()" value="<?php echo _("Salvar"); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </fieldset>
        </div>
    </div>
</div>
<script>
    $(function() {
//        $('#data_plantio').datepicker({
//            dateFormat: "dd/mm/yy",
//            showAnim: "fold",
//            startDate: "now"
//        });
//        $('#data_plantio').datepicker("show");
//        $('#data_plantio').datepicker("getDate");

        $("#div_datepicker").datepicker({
            onSelect: function(dateText, inst) {
                $("#data_plantio").val(dateText);
            },
            dateFormat: "dd/mm/yy",
        });
        $("#div_datepicker").datepicker('show');
    });
</script>
<?php
echo ob_get_clean();
?>