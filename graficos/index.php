<?php
include_once('../controller/principal.php');
$main = new principal();
$estacao_id = $_POST['resultados_estacao'];
$cultura_id = $_POST['resultados_cultura'];
$solo_id = $_POST['resultados_solo'];
$data_inicial = $_POST['resultados_data_semeadura'];

$cultura = $main->get_culturas($cultura_id);
$estacao = $main->get_estacoes($estacao_id);
$data_final = date('Y-m-d');

$resultados = $main->busca_resultados($solo_id, $cultura_id, $estacao_id, $data_inicial, $data_final);
list($series_kc, $min_kc, $max_kc) = $main->serialize_kc($resultados, 'kc', 0);
list($series_chuvas, $min_chuvas, $max_chuvas) = $main->serialize($resultados, 'chuva', 0);
list($series_chuvas_ef, $min_chuvas_ef, $max_chuvas_ef) = $main->serialize($resultados, 'chuva_efetiva', 0);
list($series_vento, $min_vento, $max_vento) = $main->serialize($resultados, 'vento', 0);
list($series_temperatura, $min_temperatura, $max_temperatura) = $main->serialize($resultados, 'temperatura_ar', 0);
list($series_umidade, $min_umidade, $max_umidade) = $main->serialize($resultados, 'umidade', 0);
list($series_eto, $min_eto, $max_eto) = $main->serialize($resultados, 'eto', 0);
list($series_etc, $min_etc, $max_etc) = $main->serialize($resultados, 'etc', 0);
list($series_irrig, $min_irrig, $max_irrig) = $main->serialize($resultados, 'irrigacao', 0);
list($series_gda, $min_gda, $max_gda) = $main->serialize_gda($resultados);
list($series_raiz, $min_raiz, $max_raiz) = $main->serialize($resultados, 'prof_raiz', 0);
//echo "<pre>";
//print_r($series_raiz);
//echo "</pre>";
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

        <!--javascript -->
        <script type = "text/javascript" src = "../js/jquery-2.1.1.js"></script> 
        <script type="text/javascript" src="../js/bootstrap.js"></script> 
        <script type="text/javascript" src="../js/scripts.js"></script>   

        <!-- Highcharts 4.0.1-->
        <script src="../js/jquery-ui-1.10.4.custom.js"></script>
        <script src="../js/Highcharts-4.0.1/js/highcharts.js"></script>
        <script src="../js/Highcharts-4.0.1/js/modules/exporting.js"></script>

        <title>Alfa Seed Software - <?php echo _('Gráficos') ?></title>

    </head>
    <body>
        <form id="form_parametros" action="../resultados/index.php" method="POST">
            <input type="hidden" id="resultados_estacao" name="resultados_estacao" value="<?php echo $_POST['resultados_estacao'] ?>"/>
            <input type="hidden" id="resultados_cultura" name="resultados_cultura" value="<?php echo $_POST['resultados_cultura'] ?>"/>
            <input type="hidden" id="resultados_data_semeadura" name="resultados_data_semeadura" value="<?php echo $_POST['resultados_data_semeadura'] ?>"/>
            <input type="hidden" id="resultados_solo" name="resultados_solo" value="<?php echo $_POST['resultados_solo'] ?>"/>
        </form>
        <nav id="menu" class="navbar navbar-default menu" role="navigation">
            <div class="container-fluid">
                <!-- Brand and toggle get grouped for better mobile display -->
                <div class="navbar-header">
                    <a class="navbar-brand" onclick="acessa_pagina('alfaseed');">Alfa Seed Software</a>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li  id="configuracao" >
                            <a onclick="acessa_pagina('configuracao');"><i class="fa fa-gear"></i> <?php echo _('Configuração') ?></a>
                        </li>
                        <li id="resultados" >
                            <a onclick="acessa_pagina('resultados');"><i class="fa fa-tint"></i> <?php echo _('Resultados') ?></a>
                        </li>
                        <li class='active' id="graficos" >
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
            <?php
            die;
        }
        ?>
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
            <div class="row ">
                <div class="col-md-12">
                    <div id="grafico_raiz" ></div>
                </div>
            </div>
            <div class="row ">
                <div class="col-md-12">
                    <div id="grafico_temperatura" ></div>
                </div>
            </div>
            <hr/>
            <div class="row ">
                <div class="col-md-12">
                    <div id="grafico_kc" ></div>
                </div>
            </div> 
            <hr/>
            <div class="row ">
                <div class="col-md-12">
                    <div id="grafico_irrig" ></div>
                </div>
            </div> 
            <hr/>
            

        </div>
        <hr/>
        <!--<script src="../js/Highcharts-4.0.1/js/themes/dark-unica.js"></script>-->
        <script>
            $(function() {
                $(function() {
                    Highcharts.setOptions({
                        lang: {
                            months: ['<?php echo _('Janeiro') ?>', '<?php echo _('Fevereiro') ?>', '<?php echo _('Março') ?>',
                                '<?php echo _('Abril') ?>', '<?php echo _('Maio') ?>', '<?php echo _('Junho') ?>',
                                '<?php echo _('Julho') ?>', '<?php echo _('Agosto') ?>', '<?php echo _('Setembro') ?>',
                                '<?php echo _('Outubro') ?>', '<?php echo _('Novembro') ?>', '<?php echo _('Dezembro') ?>'],
                            weekdays: ['<?php echo _('Domingo') ?>', '<?php echo _('Segunda-feira') ?>', '<?php echo _('Terça-feira') ?>',
                                '<?php echo _('Quarta-feira') ?>', '<?php echo _('Quinta-feira') ?>', '<?php echo _('Sexta-Feira') ?>',
                                '<?php echo _('Sábado') ?>']
                        }
                    });
                    $('#grafico_kc').highcharts({
                        chart: {
                            zoomType: 'xy'
                        },
                        title: {
                            text: 'Curva do coeficiente de cultura'
                        },
                        subtitle: {
                            text: '<?php echo $cultura[0]['cultura'] ?>'
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: {// don't display the dummy year
                                month: '%e. %b',
                                year: '%b'
                            },
                            title: {
                                text: 'Data'
                            },
                        },
                        yAxis: [{// Primary yAxis
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: '#308E4B'
                                    }
                                },
                                title: {
                                    text: '<?php echo _('Coeficiente de cultura') ?>',
                                    style: {
                                        color: '#308E4B'
                                    }
                                },
                                min: <?php echo round($min_kc, 2) ?>,
                                max: <?php echo round($max_kc, 2) ?>
                            }, {// Secondary yAxis
                                title: {
                                    text: '<?php echo _('Acúmulo Térmico') ?>',
                                    style: {
                                        color: Highcharts.getOptions().colors[3]
                                    }
                                },
                                min: <?php echo $min_gda ?>,
                                max: <?php echo $max_gda ?>,
                                labels: {
                                    format: '{value} <?php echo _('GDA') ?>',
                                    style: {
                                        color: Highcharts.getOptions().colors[3]
                                    }
                                },
                                opposite: true
                            }],
                        tooltip: {
                            shared: true
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'left',
                            x: 120,
                            verticalAlign: 'top',
                            y: 0,
                            floating: true,
                            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                        },
                        series: [
                            {
                                name: '<?php echo _('Acúmulo Térmico (GDA)') ?>',
                                type: 'column',
                                yAxis: 1,
                                data: [<?php echo $series_gda; ?>],
                                color: Highcharts.getOptions().colors[3],
                                tooltip: {
                                    valueSuffix: ' GDA'
                                }
                            }, {
                                name: '<?php echo _('Coeficiente de cultura') ?> (KC)',
                                type: 'spline',
                                data: [<?php echo $series_kc; ?>],
                                color: '#308E4B',
                                marker: {
                                    enabled: true,
                                },
//                                tooltip: {
//                                    valueSuffix: ' mm'
//                                }


                            }]
                    });
                    $('#grafico_raiz').highcharts({
                        chart: {
                            zoomType: 'xy'
                        },
                        title: {
                            text: 'Curva do coeficiente de cultura'
                        },
                        subtitle: {
                            text: '<?php echo $cultura[0]['cultura'] ?>'
                        },
                        xAxis: {
                            type: 'datetime',
                            dateTimeLabelFormats: {// don't display the dummy year
                                month: '%e. %b',
                                year: '%b'
                            },
                            title: {
                                text: 'Data'
                            },
                        },
                        yAxis: [{// Primary yAxis
                                labels: {
                                    format: '{value}',
                                    style: {
                                        color: '#308E4B'
                                    }
                                },
                                title: {
                                    text: '<?php echo _('Coeficiente de cultura') ?>',
                                    style: {
                                        color: '#308E4B'
                                    }
                                },
                                min: <?php echo round($min_raiz, 2) ?>,
                                max: <?php echo round($max_raiz, 2) ?>
                            }, {// Secondary yAxis
                                title: {
                                    text: '<?php echo _('Acúmulo Térmico') ?>',
                                    style: {
                                        color: Highcharts.getOptions().colors[3]
                                    }
                                },
                                min: <?php echo $min_gda ?>,
                                max: <?php echo $max_gda ?>,
                                labels: {
                                    format: '{value} <?php echo _('GDA') ?>',
                                    style: {
                                        color: Highcharts.getOptions().colors[3]
                                    }
                                },
                                opposite: true
                            }],
                        tooltip: {
                            shared: true
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'left',
                            x: 120,
                            verticalAlign: 'top',
                            y: 0,
                            floating: true,
                            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                        },
                        series: [
                            {
                                name: '<?php echo _('Acúmulo Térmico (GDA)') ?>',
                                type: 'column',
                                yAxis: 1,
                                data: [<?php echo $series_gda; ?>],
                                color: Highcharts.getOptions().colors[3],
                                tooltip: {
                                    valueSuffix: ' GDA'
                                }
                            }, {
                                name: '<?php echo _('Coeficiente de cultura') ?> (KC)',
                                type: 'spline',
                                data: [<?php echo $series_raiz; ?>],
                                color: '#308E4B',
                                marker: {
                                    enabled: true,
                                },
//                                tooltip: {
//                                    valueSuffix: ' mm'
//                                }


                            }]
                    });
                });
//                 $(function () {
                $('#grafico_temperatura').highcharts({
                    chart: {
                        zoomType: 'xy'
                    },
                    title: {
                        text: '<?php echo _('Dados meteorológicos diários') ?>'
                    },
                    subtitle: {
                        text: '<?php echo $estacao[0]['municipio']; ?>'
                    },
                    xAxis: {
                        type: 'datetime',
                        dateTimeLabelFormats: {// don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        },
                        title: {
                            text: '<?php echo _('Data') ?>'
                        },
                    },
                    yAxis: [{// Primary yAxis
                            labels: {
                                format: '{value}°C',
                                style: {
                                    color: Highcharts.getOptions().colors[3]
                                }
                            },
                            title: {
                                text: '<?php echo _('Temperatura') ?>',
                                style: {
                                    color: Highcharts.getOptions().colors[3]
                                }
                            },
                            opposite: true

                        }, {// Secondary yAxis
                            gridLineWidth: 0,
                            title: {
                                text: '<?php echo _('Chuvas') ?>',
                                style: {
                                    color: '#308E4B',
                                }
                            },
                            labels: {
                                format: '{value} mm',
                                style: {
                                    color: '#308E4B',
                                }
                            }
                        }, {// Secondary yAxis
                            gridLineWidth: 0,
                            title: {
                                text: '<?php echo _('Umidade') ?>',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            },
                            labels: {
                                format: '{value} %',
                                style: {
                                    color: Highcharts.getOptions().colors[0]
                                }
                            }
//                             }, {// Secondary yAxis
//                            gridLineWidth: 0,
//                            title: {
//                                text: '<?php // echo _('Evapotranspiração de referência')          ?>',
//                                style: {
//                                    color: '#308E4B'
//                                }
//                            },
//                            labels: {
//                                format: '{value} mm/d',
//                                style: {
//                                    color: '#308E4B'
//                                }
//                            }
                        }, {// Tertiary yAxis
                            gridLineWidth: 1,
                            title: {
                                text: '<?php echo _('Velocidade do vento') ?>',
                                style: {
                                    color: '#3D3D3D',
                                }
                            },
                            max: 5,
                            labels: {
                                format: '{value} m/s',
                                style: {
                                    color: '#3D3D3D',
                                }
                            },
                            opposite: true
                        }],
                    tooltip: {
                        shared: true
                    },
                    legend: {
                        layout: 'horizontal',
                        align: 'left',
                        x: 0,
                        verticalAlign: 'bottom',
                        y: 20,
                        floating: true,
                        backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
                    },
                    series: [{
                            name: '<?php echo _('Chuvas') ?>',
                            type: 'column',
                            yAxis: 1,
                            data: [<?php echo $series_chuvas; ?>],
                            color: '#308E4B',
                            tooltip: {
                                valueSuffix: ' mm'
                            },
                        }, {
                            name: '<?php echo _('Umidade') ?>',
                            type: 'spline',
                            yAxis: 2,
                            data: [<?php echo $series_umidade; ?>],
                            color: Highcharts.getOptions().colors[0],
                            marker: {
                                enabled: false
                            },
                            dashStyle: 'shortdot',
                            tooltip: {
                                valueSuffix: ' %'
                            }
//                        }, {
//                            name: '<?php // echo _('Evapotranspiração de Referência (ETo)')          ?>',
//                            type: 'spline',
//                            yAxis: 3,
//                            data: [<?php // echo $series_eto;          ?>],
//                            color: '#308E4B',
//                            marker: {
//                                fillColor: "#FFFFFF", 
//                                enabled: true,
//                                radius: 0, 
//                                lineWidth: 2
//                            },
//                            dashStyle: 'solid',
//                            tooltip: {
//                                valueSuffix: ' mm/d'
//                            }
                        }, {
                            name: '<?php echo _('Velocidade do vento') ?>',
                            type: 'column',
                            yAxis: 3,
                            color: '#3D3D3D',
                            data: [<?php echo $series_vento; ?>],
                            marker: {
                                enabled: false
                            },
//                            dashStyle: 'shortdot',
                            tooltip: {
                                valueSuffix: ' m/s'
                            }


                        }, {
                            name: '<?php echo _('Temperatura') ?>',
                            type: 'spline',
//                            yAxis: 1,
                            color: Highcharts.getOptions().colors[3],
                            data: [<?php echo $series_temperatura; ?>],
                            dashStyle: 'shortdot',
                            marker: {
                                enabled: false
                            },
                            tooltip: {
                                valueSuffix: ' °C'
                            }
                        }]
                });
                $('#grafico_irrig').highcharts({
                    chart: {
                        type: 'column',
                        zoomType: 'xy'
                    },
                    title: {
                        text: '<?php echo _('Necessidade hídrica da cultura') ?>'
                    },
                    xAxis: {
                        type: 'datetime',
                        dateTimeLabelFormats: {// don't display the dummy year
                            month: '%e. %b',
                            year: '%b'
                        },
                        title: {
                            text: '<?php echo _('Data') ?>'
                        },
                    },
                    max: 25,
                    credits: {
                        enabled: false
                    },
                    tooltip: {
                        shared: true
                    },
                    series: [{
                            title: '<?php echo _('Água')?>',
                            name: '<?php echo _('Precipitações efetivas') ?>',
                            color: '#308E4B',
                            marker: {
                                enabled: false,
                            },
                            tooltip: {
                                valueSuffix: ' mm'
                            },
                            data: [<?php echo $series_chuvas_ef; ?>]
                        }, {
                            name: '<?php echo _('Irrigação Requerida') ?>',
                            data: [<?php echo $series_irrig; ?>],
                            tooltip: {
                                valueSuffix: ' mm'
                            },
                        }, {
                            color: Highcharts.getOptions().colors[3],
                            name: '<?php echo _('Evapotranspiração da cultura') ?>',
                            data: [<?php echo $series_etc; ?>],
                            tooltip: {
                                valueSuffix: ' mm'
                            },
                        }]
                });
            });


        </script>
    </body>
</html>
