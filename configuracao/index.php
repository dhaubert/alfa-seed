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
        <!--<script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyC2-QtJ8cLWCUy26UEllikxqA_s6D3vRF8&sensor=false"></script>-->
        <script src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
        <!-- Caixa de informação -->
        <script src="../js/mapas/infobox.js"></script>
        <!-- Agrupamento dos marcadores -->
        <script src="../js/mapas/markerclusterer.js"></script>
        <script src="../js/mapas/mapa.js"></script>
        <title>Alfa Seed Software - <?php echo _('Configuração')?></title>

    </head>
    <body>
        <form id="form_parametros" action="../resultados/index.php" method="POST">
            <input type="hidden" id="resultados_estacao" name="resultados_estacao" value="<?php echo $_POST['resultados_estacao']?>"/>
            <input type="hidden" id="resultados_cultura" name="resultados_cultura" value="<?php echo $_POST['resultados_cultura']?>"/>
            <input type="hidden" id="resultados_data_semeadura" name="resultados_data_semeadura" value="<?php echo $_POST['resultados_data_semeadura']?>"/>
        </form>
        <nav id="menu" class="navbar navbar-default menu" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="/index.php">Alfa Seed Software</a>
                </div>
                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class='active  ' id="configuracao" >
                            <a onclick="acessa_pagina('configuracao');"><i class="fa fa-gear"></i> <?php echo _('Configuração') ?></a>
                        </li>
                        <li id="resultados" >
                            <a onclick="acessa_pagina('resultados');"><i class="fa fa-tint"></i> <?php echo _('Resultados') ?></a>
                        </li>
                        <li id="graficos" >
                            <a onclick="acessa_pagina('graficos');"><i class="fa fa-bar-chart-o"></i> <?php echo _('Gráficos') ?></a>
                        </li>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <fieldset>
                        <legend>
                            <i class="fa fa-sun-o"></i> Clima
                        </legend>
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="btn-group ">
                                        <input onclick="alterna_clima()" id="dados_inmet" class="btn btn-success active" type="button" value="<?php echo _("Baixar dados"); ?>"/>
                                        <input onclick="alterna_clima()" id="dados_csv" class="btn btn-success" type="button" value="<?php echo _("Importar arquivo"); ?>"/>
                                    </div>
                                </div>
                                <div class="col-xs-9" >
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
                                <div class="col-xs-4">

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
                                <div class="col-xs-4">
                                    <!--<div id="mapa" style="width:100%; height:260px; background-color: #308E4B">Mapa</div>-->
                                    <div id="map_canvas" ><?php echo _('Mapa') ?></div>
                                </div>
                                <div class="col-xs-4 center-block">
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
                                    <div class="col-xs-6">
                                        <div id="fileuploader">
                                            <?php echo _('Selecionar arquivo csv') ?>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <?php echo _('Nome para a estação') . ':' ?>
                                        <input type="text" id="nome_estacao" class="form-control"/>
                                        <?php echo _('Município') . ':' ?>
                                        <input type="text" id="nome_estacao" class="form-control"/>
                                        <?php echo _('Latitude') . ':' ?>
                                        <input type="text" id="latitude" placeholder="<?php echo _('ex.') ?>: -99.99" class="form-control"/>
                                        <?php echo _('Longitude') . ':' ?>
                                        <input type="text" id="longitude" placeholder="<?php echo _('ex.') ?>: -99.99" class="form-control"/>
                                        <?php echo _('Altitude') . ':' ?>
                                        <input type="text" id="altitude" placeholder="<?php echo _('ex.') ?>: 999.99" class="form-control"/>
                                    </div>
                                </div>
                                <input type="hidden" id="entrada_clima" value="inmet">
                            </form>
                        </div>
                        <div class="container">
                            <div class="row">
                                <div class="cols-lg-12 center-block" style="margin-top:10px">
                                    <input type="button" class="btn btn-success" onclick="salvar_clima()" value="<?php echo _("Salvar dados meteorológicos"); ?>">
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    <fieldset>
                        <legend>
                            <i class="fa fa-pagelines"></i> <?php echo _("Cultura"); ?>
                        </legend>
                        <div class="container">
                            <div class="row">
                                <div class="col-xs-3">
                                    <div class="btn-group ">
                                        <input onclick="alterna_cultura()" id="sel_cultura" class="btn btn-success active" type="button" value="<?php echo _("Selecionar"); ?>"/>
                                        <input onclick="alterna_cultura()" id="add_cultura" class="btn btn-success" type="button" value="<?php echo _("Adicionar"); ?>"/>
                                    </div>
                                </div>
                                <div class="col-xs-9">
                                    <div id="mensagem_cultura"></div>
                                </div>
                            </div>
                        </div>
                        <hr />
                        <div class="container">
                            <div id="tela_sel_cultura" class="row">
                                <div class="col-xs-4">
                                    <select multiple id="culturas" name="culturas" class="form-control" >
                                        <option value="0" selected=""> <?php echo _('Selecione a cultura') ?>... </option>
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
                                <div class="col-xs-8">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th><?php echo _('Kc inicial') ?></th>
                                                    <th><?php echo _('Kc médio') ?></th>
                                                    <th><?php echo _('Kc final') ?></th>
                                                    <th><?php echo _('Estágio inicial') ?> <?php echo _('(GDA)') ?></th>
                                                    <th><?php echo _('Estágio de crescimento') ?> <?php echo _('(GDA)') ?></th>
                                                    <th><?php echo _('Estágio intermediário') ?> <?php echo _('(GDA)') ?></th>
                                                    <th><?php echo _('Estágio final') ?> <?php echo _('(GDA)') ?></th>
                                                    <th><?php echo _('Temperatura base') ?> <?php echo _('(Cº)') ?></th>
                                                    <th><?php echo _('Temperatura superior') ?> <?php echo _('(Cº)') ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td id="kcini">-</td>
                                                    <td id="kcmid">-</td>
                                                    <td id="kcend">-</td>
                                                    <td id="gdini">-</td>
                                                    <td id="gdmid">-</td>
                                                    <td id="gddev">-</td>
                                                    <td id="gdlate">-</td>
                                                    <td id="tbase">-</td>
                                                    <td id="tupper">-</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="container">
                            <div id="tela_add_cultura" class="row" style="display:none">
                                <div class="col-xs-12">
                                    <div class="table-responsive">
                                        <form name="nova_cultura" id="nova_cultura" >
                                            <table class="table table-condensed">
                                                <thead>
                                                    <tr>
                                                        <th><?php echo _('Nome da cultura') ?> 
                                                            <i class="fa fa-question-circle" id="help_nome_cultura" data-trigger="hover"></i>
                                                        </th>
                                                        <th><?php echo _('Kc inicial') ?>
                                                            <i class="fa fa-question-circle" id="help_kc_ini" data-trigger="hover"  ></i>
                                                        </th>
                                                        <th><?php echo _('Kc médio') ?>
                                                            <i class="fa fa-question-circle" id="help_kc_mid" data-trigger="hover"></i>
                                                        </th>
                                                        <th><?php echo _('Kc final') ?>
                                                            <i class="fa fa-question-circle" id="help_kc_end" data-trigger="hover"></i></th>
                                                        <th><?php echo _('Estágio inicial') ?>  
                                                            <i class="fa fa-question-circle" id="help_gda_ini" data-trigger="hover"></i></th>
                                                        <th><?php echo _('Estágio de crescimento') ?>  
                                                            <i class="fa fa-question-circle" id="help_gda_dev" data-trigger="hover"></i></th>
                                                        <th><?php echo _('Estágio intermediário') ?>  
                                                            <i class="fa fa-question-circle" id="help_gda_mid" data-trigger="hover"></i></th>
                                                        <th><?php echo _('Estágio final') ?> 
                                                            <i class="fa fa-question-circle" id="help_gda_late" data-trigger="hover"></i></th>
                                                        <th><?php echo _('Temperatura base') ?> <?php echo _('(Cº)') ?>
                                                            <i class="fa fa-question-circle" id="help_temp_base" data-trigger="hover"></i></th>
                                                        <th><?php echo _('Temperatura superior') ?> <?php echo _('(Cº)') ?>
                                                            <i class="fa fa-question-circle" id="help_temp_upper" data-trigger="hover"></i></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td id="nome_cultura"><input type="text" class="form-control" id="nome_cultura" name="nome_cultura" placeholder="<?php echo _('Ex.') ?>: <?php echo _('Milho') ?>"/></td>
                                                        <td id="kcini"><input type="text" class="form-control" id="kc_ini" name="kc_ini" placeholder="0.2 a 1.0"/></td>
                                                        <td id="kcmid"><input type="text" class="form-control" id="kc_mid" name="kc_mid" placeholder="0.9 a 1.25"/></td>
                                                        <td id="kcend"><input type="text" class="form-control" id="kc_end" name="kc_end" placeholder="0.3 a 1.0"/></td>
                                                        <td id="gdini"><input type="text" class="form-control" id="gda_ini" name="gda_ini" placeholder="Ex.: 100"/></td>
                                                        <td id="gddev"><input type="text" class="form-control" id="gda_dev" name="gda_dev" placeholder="Ex.: 500"/></td>
                                                        <td id="gdmid"><input type="text" class="form-control" id="gda_mid" name="gda_mid" placeholder="Ex.: 1000"/></td>
                                                        <td id="gdlate"><input type="text" class="form-control" id="gda_late" name="gda_late" placeholder="Ex.: 2000"/></td>
                                                        <td id="tbase"><input type="text" class="form-control" id="temp_base" name="temp_base" placeholder="Ex.: 10.5"/></td>
                                                        <td id="tupper"><input type="text" class="form-control" id="temp_superior" name="temp_superior" placeholder="Ex.: 30.5"/></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" id="entrada_cultura" value="selecao">
                            </form>
                        </div>

                        <div class="container">
                            <div class="row">
                                <div class="cols-lg-12 center-block" style="margin-top:10px">
                                    <input type="button" class="btn btn-success" onclick="salvar_cultura()" value="<?php echo _("Salvar cultura"); ?>">
                                </div>
                            </div>
                        </div>

                    </fieldset>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-lg-12 center-block" style="margin-top:10px">
                    <input type="button" class="btn btn-large btn-success" onclick="acessa_pagina('resultados')" value="<?php echo _("Calcular resultados"); ?>">
                    &nbsp;<input type="button" class="btn btn-large btn-success" onclick="acessa_pagina('graficos')" value="<?php echo _("Visualizar Gráficos"); ?>">
                </div>
            </div>
        </div>
        <script>
            $(function() {
                carrega_parametros();
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
    </body>
</html>