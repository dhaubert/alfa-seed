<?php
include ("../controller/principal.php");
$main = new principal('mapa');
?>
<!doctype html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Alfa Seed é um software desenvolvido por Douglas Haubert para estimativas
              de produtividade para culturas irrigadas, para o trabalho de conclusão de curso de Sistemas de Informação
              na Universidade Federal de Santa Maria.">
        <meta name="author" content="Douglas Henrique Haubert">

        <!-- css -->
        <link href="../css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="../css/font-awesome/css/font-awesome.min.css" media="screen">
        <link href="../css/style.css" rel="stylesheet" type="text/css">
        <link href="../css/alfaseed/jquery-ui-1.10.4.custom.css" rel="stylesheet">
        <link href="../css/uploadfile.min.css" rel="stylesheet">

        <!-- javascript -->
        <script type="text/javascript" src="../js/jquery-2.1.1.js"></script> 
        <!--<script type="text/javascript" src="../js/jquery-1.10.2.js"></script>-->
        <script type="text/javascript" src="../js/bootstrap.js"></script> 
        <script type="text/javascript" src="../js/scripts.js"></script>   

        <script src="../js/jquery-ui-1.10.4.custom.js"></script>


        <script src="../js/jquery.uploadfile.min.js"></script>


        <!-- Maps API Javascript -->
        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <!-- Caixa de informação -->
        <script src="../js/mapas/infobox.js"></script>
        <!-- Agrupamento dos marcadores -->
        <script src="../js/mapas/markerclusterer.js"></script>
        <script src="../js/mapas/mapa.js"></script>
        <title>Alfa Seed Software</title>

    </head>
    <body>
        <nav id="menu" class="navbar navbar-default menu" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <!--                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                        <span class="sr-only">Toggle navigation</span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                        <span class="icon-bar"></span>
                                    </button>-->
                    <a class="navbar-brand" onclick="maximizar();">Alfa Seed Software</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class='active' id="configuracao" >
                            <a href="../configuracao"><i class="fa fa-gear"></i> <?php echo _('Configuração')?></a>
                        </li>
                        <li id="resultados" >
                            <a href="../resultados"><i class="fa fa-tint"></i> <?php echo _('Resultados')?></a>
                        </li>
                        <li id="simulacoes" >
                            <a href="../simualacoes"><i class="fa fa-bar-chart-o"></i> <?php echo _('Simulações')?></a>
                        </li>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
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
                        <div class="container">
                            <div id="clima_inmet" class="row">
                                <div class="col-lg-4">

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
                                                        <?php echo $estacao['municipio']; ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-4">
                                    <!--<div id="mapa" style="width:100%; height:260px; background-color: #308E4B">Mapa</div>-->
                                    <div id="map_canvas" ><?php echo _('Mapa')?></div>
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
                            <form name="importar" id="clima" name="importar">
                                <div id="clima_csv" class="row" style="display:none">
                                    <div class="col-lg-6">
                                        <div id="fileuploader">
                                            <?php echo _('Selecionar arquivo .csv')?>
                                        </div>
                                    </div>
                                </div>
                                <input type="hidden" id="entrada_clima" value="inmet">
                            </form>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="cols-lg-12 center-block" style="margin-top:10px">
                                    <input type="button" class="btn btn-success" onclick="salvar_clima()" value="<?php echo _("Salvar Clima"); ?>">
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <fieldset>
                        <legend>
                            <i class="fa fa-pagelines"></i> <?php echo _("Cultura"); ?>
                        </legend>
                        <!--                <div class="container">
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
                                                    </div>
                                                    <div id="mensagem_clima">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>-->
                        <div class="container">
                            <div id="clima_inmet" class="row">
                                <div class="col-lg-4">
                                    <select multiple id="culturas" name="culturas" class="form-control" >
                                        <?php
                                        $culturas = $main->get_culturas();
                                        foreach ($culturas as $cultura) {
                                            ?>
                                            <option value="<?php echo $cultura['id']; ?>"
                                                    onclick="atualiza_tabela(
                                                                            '<?php echo $cultura['kc_ini']; ?>',
                                                                            '<?php echo $cultura['kc_mid']; ?>',
                                                                            '<?php echo $cultura['kc_end']; ?>',
                                                                            '<?php echo $cultura['gda_ini']; ?>',
                                                                            '<?php echo $cultura['gda_mid']; ?>',
                                                                            '<?php echo $cultura['gda_dev']; ?>',
                                                                            '<?php echo $cultura['gda_late']; ?>',
                                                                            '<?php echo $cultura['temperatura_base']; ?>',
                                                                            '<?php echo $cultura['temperatura_superior']; ?>'
                                                                            );">
                                                        <?php echo utf8_encode($cultura['cultura']); ?>
                                            </option>
                                            <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="col-lg-8">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th><?php echo _('Kc inicial')?></th>
                                                    <th><?php echo _('Kc médio')?></th>
                                                    <th><?php echo _('Kc final')?></th>
                                                    <th><?php echo _('Estágio inicial')?> <?php echo _('(GDA)')?></th>
                                                    <th><?php echo _('Estágio de crescimento')?> <?php echo _('(GDA)')?></th>
                                                    <th><?php echo _('Estágio intermediário')?> <?php echo _('(GDA)')?></th>
                                                    <th><?php echo _('Estágio final')?> <?php echo _('(GDA)')?></th>
                                                    <th><?php echo _('Temperatura base')?> <?php echo _('(Cº)')?></th>
                                                    <th><?php echo _('Temperatura superior')?> <?php echo _('(Cº)')?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="kcini"></td>
                                                    <td id="kcmid"></td>
                                                    <td id="kcend"></td>
                                                    <td id="gdini"></td>
                                                    <td id="gdmid"></td>
                                                    <td id="gddev"></td>
                                                    <td id="gdlate"></td>
                                                    <td id="tbase"></td>
                                                    <td id="tupper"></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="cols-lg-12 center-block" style="margin-top:10px">
                                    <input type="button" class="btn btn-success" onclick="salvar_cultura()" value="<?php echo _("Salvar"); ?>">
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </div>
        <script>
                            $(function() {
                                    initialize();
                                    carregarPontos();
                            $("#fileuploader").uploadFile({
                                url: "configuracao.php",
                                        fileName: "importar",
                                        maxFileSize: "2MB",
                                        maxFileCount: 1,
                                        allowedTypes: "csv",
                                        showFileCounter: false
                                });
                                    $("#div_datepicker").datepicker({
                            onSelect: function(dateText, inst) {
                            $("#data_plantio").val(dateText);
                            },
                                    dateFormat: "dd/mm/yy"
                            });
                                    $("#div_datepicker").datepicker('show');
                            });

        </script>
