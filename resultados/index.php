<?php
include_once('../controller/principal.php');

$main = new principal();
$estacao_id = $_POST['resultados_estacao'];
$cultura_id = $_POST['resultados_cultura'];
$cultura = $main->get_culturas($cultura_id);
$estacao = $main->get_estacoes($estacao_id);
$data_inicial = $_POST['resultados_data_semeadura'] ;
$data_final = date('Y-m-d');
$resultados = $main->busca_resultados($cultura_id, $estacao_id, $data_inicial, $data_final);
?>
<!doctype html>
<html lang = "pt_BR">
    <head>
        <meta charset = "utf-8">
        <meta name = "description" content = "Alfa Seed é um software desenvolvido por Douglas Haubert para estimativas
              de produtividade para culturas irrigadas, para o trabalho de conclusão de curso de Sistemas de Informação
              na Universidade Federal de Santa Maria.">
        <meta name = "author" content = "Douglas Henrique Haubert">

        <!--css -->
        <link href = "../css/bootstrap/bootstrap.css" rel = "stylesheet" type = "text/css">
        <link rel = "stylesheet" href = "../css/font-awesome/css/font-awesome.min.css" media = "screen">
        <link href = "../css/style.css" rel = "stylesheet" type = "text/css">
        <link href = "../css/alfaseed/jquery-ui-1.10.4.custom.css" rel = "stylesheet">
        <link href = "../css/uploadfile.min.css" rel = "stylesheet">

        <!--javascript -->
        <script type = "text/javascript" src = "../js/jquery-2.1.1.js"></script> 
        <script type="text/javascript" src="../js/bootstrap.js"></script> 
        <script type="text/javascript" src="../js/scripts.js"></script>   

        <script src="../js/jquery-ui-1.10.4.custom.js"></script>

        <title>Alfa Seed Software - <?php echo _('Resultados') ?></title>

    </head>
    <body>
        <form id="form_parametros" action="../resultados/index.php" method="POST">
            <input type="hidden" id="resultados_estacao" name="resultados_estacao" value="<?php echo $_POST['resultados_estacao'] ?>"/>
            <input type="hidden" id="resultados_cultura" name="resultados_cultura" value="<?php echo $_POST['resultados_cultura'] ?>"/>
            <input type="hidden" id="resultados_data_semeadura" name="resultados_data_semeadura" value="<?php echo $_POST['resultados_data_semeadura'] ?>"/>
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
                        <li  id="configuracao" >
                            <a onclick="acessa_pagina('configuracao');"><i class="fa fa-gear"></i> <?php echo _('Configuração') ?></a>
                        </li>
                        <li class='active' id="resultados" >
                            <a onclick="acessa_pagina('resultados');"><i class="fa fa-tint"></i> <?php echo _('Resultados') ?></a>
                        </li>
                        <li id="graficos" >
                            <a onclick="acessa_pagina('graficos');"><i class="fa fa-bar-chart-o"></i> <?php echo _('Gráficos') ?></a>
                        </li>
                </div><!-- /.navbar-collapse -->
            </div><!-- /.container-fluid -->
        </nav>
        <?php
        if (!isset($_POST['resultados_data_semeadura']) || !isset($_POST['resultados_estacao']) || !isset($_POST['resultados_cultura'])) {
            ?>
            <div class="container">
                <div class="row ">
                    <div class="col-md-12" style="font-size: 18px"> 
                        <?php echo _('Não existem dados suficientes') ?>. <?php echo _('Visite a aba') ?> <a href="../configuracao/"><?php echo _('Configuração') ?></a>
                    </div>
                </div>
            </div>
            <?php die;
        } ?>

        <div class="container">
            <div class="row ">
                <div class="col-md-12" style="font-size: 18px">
                    <div class="col-xs-4">
                        <i class="fa fa-pagelines verde"></i>  <?php echo _('Cultura') . ': ' . $cultura[0]['cultura']; ?>
                    </div>
                    <div class="col-xs-4">
                        <i class="fa fa-location-arrow verde"></i>  <?php echo _('Município') . ': ' . $estacao[0]['municipio']; ?>
                    </div>
                    <div class="col-xs-4">
                        <i class="fa fa-calendar verde"></i>  <?php echo _('Período') . ': ' . date('d/m/Y', strtotime($data_inicial)) . ' ' . _("a") . ' ' . date('d/m/Y', strtotime($data_final)); ?>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-condensed"  id="tab_header_clone" style="display: none;">
                            <thead id="resultados_header_clone">
                                <tr id="resultados_header">
                                    <th  style="width: 95px"><?php echo _('Data') ?></th>
                                    <th  style="width: 60px" class="clima"><?php echo _('Temp. Ar') ?></th>
                                    <th  style="width: 70px" class="clima"><?php echo _('Temp. Mínima') ?></th>
                                    <th    style="width: 70px" class="clima"><?php echo _('Temp. Máxima') ?></th>
                                    <th    style="width: 80px" class="clima"><?php echo _('Umidade') ?></th>
                                    <th    style="width: 70px" class="clima"><?php echo _('Umidade Mínima') ?></th>
                                    <th    style="width: 70px" class="clima"><?php echo _('Umidade Máxima') ?></th>
                                    <th    style="width: 70px" class="clima"><?php echo _('Pressão') ?></th>
                                    <th    style="width: 65px" class="clima"><?php echo _('Vel. Vento') ?></th>
                                    <th    style="width: 80px" class="clima"><?php echo _('Radiação') ?></th>
                                    <th    style="width: 42px" class="clima"><?php echo _('Luz Solar') ?></th>
                                    <th    style="width: 130px"><?php echo _('Estágio') ?></th>
                                    <th    style="width: 88px"><?php echo _('GDA') ?></th>
                                    <th    style="width: 65px"><?php echo _('ETo') ?></th>
                                    <th    style="width: 56px"><?php echo _('Kc') ?></th>
                                    <th    style="width: 64px"><?php echo _('Etc') ?></th>
                                </tr>
                            </thead>
                        </table>
                        <table class="table table-condensed">
                            <thead>
                                <tr id="resultados_header">
                                    <th style="width: 95px"><?php echo _('Data') ?>
                                        <i class="fa fa-question-circle" id="data" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 60px" class="clima"><?php echo _('Temp. Ar') ?>
                                        <br/><i class="fa fa-question-circle" id="tar" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 70px" class="clima"><?php echo _('Temp. Mínima') ?>
                                        <i class="fa fa-question-circle" id="tmin" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 70px" class="clima"><?php echo _('Temp. Máxima') ?>
                                        <i class="fa fa-question-circle" id="tmax" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 80px" class="clima"><?php echo _('Umidade') ?>
                                        <i class="fa fa-question-circle" id="umid" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 70px" class="clima"><?php echo _('Umidade Mínima') ?>
                                        <i class="fa fa-question-circle" id="umid_min" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 70px" class="clima"><?php echo _('Umidade Máxima') ?>
                                        <i class="fa fa-question-circle" id="umid_max" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 70px" class="clima"><?php echo _('Pressão') ?>
                                        <i class="fa fa-question-circle" id="pressao" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 65px" class="clima"><?php echo _('Vel. Vento') ?>
                                        <br/><i class="fa fa-question-circle" id="vento" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 80px" class="clima"><?php echo _('Radiação') ?>
                                        <i class="fa fa-question-circle" id="radiacao" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 42px" class="clima"><?php echo _('Luz Solar') ?>
                                        <i class="fa fa-question-circle" id="insolacao" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 130px"><?php echo _('Estágio') ?>
                                        <i class="fa fa-question-circle" id="estagio" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 88px"><?php echo _('GDA') ?>
                                        <i class="fa fa-question-circle" id="gda" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 65px"><?php echo _('ETo') ?>
                                        <i class="fa fa-question-circle" id="eto" data-trigger="hover"></i>
                                    </th>
                                    <th  style="width: 56px"><?php echo _('Kc') ?>
                                        <i class="fa fa-question-circle" id="kc" data-trigger="hover"></i>
                                    </th>
                                    <th style="width: 64px"><?php echo _('Etc') ?>
                                        <i class="fa fa-question-circle" id="etc" data-trigger="hover" ></i>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
<?php foreach ($resultados as $resultado) { ?>
                                    <tr <? echo $main->busca_cor_resultado($resultado); ?>>
                                        <td><?php echo date('d/m/Y', strtotime($resultado['data'])); ?></td>
                                        <td class="clima"><?php echo round($resultado['temperatura_ar'], 1) ?></td>
                                        <td class="clima"><?php echo $resultado['temperatura_minima'] ?></td>
                                        <td class="clima"><?php echo $resultado['temperatura_maxima'] ?></td>
                                        <td class="clima"><?php echo round($resultado['umidade'], 1) ?></td>
                                        <td class="clima"><?php echo $resultado['umidade_minima'] ?></td>
                                        <td class="clima"><?php echo $resultado['umidade_maxima'] ?></td>
                                        <td class="clima"><?php echo round($resultado['pressao'], 2) ?></td>
                                        <td class="clima"><?php echo round($resultado['vento'], 2) ?></td>
                                        <td class="clima"><?php echo round($resultado['radiacao'], 2) ?></td>
                                        <td class="clima"><?php echo $resultado['insolacao'] ?></td>
                                        <td><?php echo $main->busca_estagio($resultado['estagio']) ?></td>
                                        <td><?php echo $resultado['GD_acumulado'] ?></td>
                                        <td><?php echo round($resultado['eto'], 2) ?></td>
                                        <td><?php echo round($resultado['etc'] / $resultado['eto'], 2) ?></td>
                                        <td><?php echo round($resultado['etc'], 2) ?></td>
                                    </tr>
<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(function() {
                $(document).scroll(function() {
                    var y = $(window).scrollTop();
                    var limite = 120;
                    if (y > limite) {
                        //                        $("#resultados_header").clone().appendTo('#resultados_header_clone');
                        //                        $('#resultados_header_clone').html($("#resultados_header").clone());
                        $("#tab_header_clone").css({'position': 'fixed', 'margin-top': '-120px', 'background-color': '#404040'});
                        $("#tab_header_clone").fadeIn();
                    } else {
                        $("#tab_header_clone").css('position', 'relative');
                        $("#tab_header_clone").hide();
                    }
                });

            });
        </script>
    </body>
</html>
