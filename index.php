<!doctype html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Alfa Seed é um software desenvolvido por Douglas Haubert para estimativas
              de produtividade para culturas irrigadas, para o trabalho de conclusão de curso de Sistemas de Informação
              na Universidade Federal de Santa Maria.">
        <meta name="author" content="Douglas Henrique Haubert">

        <!-- css -->
        <link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" media="screen">
        <link href="css/style.css" rel="stylesheet" type="text/css">
        <link href="css/alfaseed/jquery-ui-1.10.4.custom.css" rel="stylesheet">
        <link href="css/uploadfile.min.css" rel="stylesheet">

        <!-- javascript -->
        <!--<script type="text/javascript" src="js/jquery-2.1.1.js"></script>--> 
        <script src="js/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="js/bootstrap.js"></script> 
        <script type="text/javascript" src="js/scripts.js"/></script>   
        
        <script src="js/jquery-ui-1.10.4.custom.js"></script>

        
        <script src="js/jquery.uploadfile.min.js"></script>
<!--        <script type="text/javascript"
                src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDLR4BikxQ8gpFOhyN2938LozV7i0qwbfI&sensor=TRUE">
        </script>-->
       
    <title>Alfa Seed Software</title>

</head>
<body>
        <form id="form_parametros" action="../resultados/index.php" method="POST">
            <input type="hidden" id="resultados_estacao" name="resultados_estacao" value="<?php echo $_POST['resultados_estacao']?>"/>
            <input type="hidden" id="resultados_cultura" name="resultados_cultura" value="<?php echo $_POST['resultados_cultura']?>"/>
            <input type="hidden" id="resultados_data_semeadura" name="resultados_data_semeadura" value="<?php echo $_POST['resultados_data_semeadura']?>"/>
        </form>
    <section id="main">

    </section>
    <section class="header_menu">
        <div class="jumbotron jumbotron-ad hidden-print">
            <div class="container">
                <div class="row">
                    <?php if(isset($_POST['resultados_estacao']) && isset($_POST['resultados_cultura'])&& isset($_POST['resultados_data_semeadura'])){ ?>
                    <div class="service col-md-4">
                        <h1>
                            <a onclick="acessa_pagina('configuracao')">
                                <i class="fa fa-gear fa-large"></i><br/>
                                <span class="text"><?php echo _("Configuração"); ?></span>
                            </a>
                        </h1>
                    </div>
                    <div class="service col-md-4">
                        <h1>
                            <a onclick="acessa_pagina('resultados')">
                                    <i class="fa fa-tint fa-large"></i><br/>
                                    <span class="text"><?php echo _("Resultados"); ?></span>
                                </a>
                        </h1>
                    </div>
                    <div class="service col-md-4">
                        <h1>
                            <a onclick="acessa_pagina('graficos')">
                                    <i class="fa fa-bar-chart-o fa-large"></i><br/>
                                    <span class="text"><?php echo _("Simulações"); ?></span>
                                </a>
                        </h1>
                    </div>
                    <?php } 
                    else{?>
                    <div class="service col-md-6">
                        <h1>
                            <a href="configuracao/">
                                    <i class="fa fa-bolt fa-large"></i><br/>
                                    <span class="text"><?php echo _("Iniciar"); ?></span>
                                </a>
                        </h1>
                    </div>
                    <div class="service col-md-6">
                        <h1>
                            <a href="como_funciona.pdf">
                                    <i class="fa fa-question fa-square fa-large"></i><br/>
                                    <span class="text"><?php echo _("Como funciona?"); ?></span>
                            </a>
                        </h1>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </section>
    <section class="footer">

        <div class="container">
            <div class="row">
                <div class="service col-md-12">
                    <h1>
                        <span class="text-logo"><?php echo _("Alfa Seed Software"); ?></span>
                    </h1>

                </div>
            </div>
        </div>

    </section>
</body>
</html>