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
    <nav id="menu" class="navbar navbar-default menu" role="navigation">
        <div class="container-fluid">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" onclick="maximizar();">Alfa Seed Software</a>
            </div>

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li id="configuracao" >
                        <a href='configuracao/'><i class="fa fa-gear"></i> Configuração</a>
                    </li>
                    <li id="resultados" >
                        <a href='resultados/'><i class="fa fa-tint"></i> Resultados</a>
                    </li>
                    <li id="simulacoes" >
                        <a href='simulacoes/'><i class="fa fa-bar-chart-o"></i> Simulações</a>
                    </li>
                    <!--        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                              <ul class="dropdown-menu">
                                <li><a href="#">Action</a></li>
                                <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li>
                                <li class="divider"></li>
                                <li><a href="#">Separated link</a></li>
                                <li class="divider"></li>
                                <li><a href="#">One more separated link</a></li>
                              </ul>
                            </li>-->
                </ul>
                <!--      <form class="navbar-form navbar-left" role="search">
                        <div class="form-group">
                          <input type="text" class="form-control" placeholder="Search">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                      </form>-->
                <!--      <ul class="nav navbar-nav navbar-right">
                        <li><a href="#">Link</a></li>
                        <li class="dropdown">
                          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <b class="caret"></b></a>
                          <ul class="dropdown-menu">
                            <li><a href="#">Action</a></li>
                            <li><a href="#">Another action</a></li>
                            <li><a href="#">Something else here</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Separated link</a></li>
                          </ul>
                        </li>
                      </ul>-->
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->
    </nav>
    <section id="main">

    </section>
    <section class="header_menu">
        <div class="jumbotron jumbotron-ad hidden-print">
            <div class="container">
                <div class="row">
                    <div class="service col-md-4">
                        <h1>
                            <!--<a  onclick="minimizar('<?php echo _("Configuração"); ?>', 'configuracao')">-->
                            <a  href='configuracao/' onclick="minimizar('<?php echo _("Configuração"); ?>', 'configuracao')">
                                <i class="fa fa-gear fa-large"></i><br/>
                                <span class="text"><?php echo _("Configuração"); ?></span>
                            </a>
                        </h1>
                    </div>
                    <div class="service col-md-4">
                        <h1>
                             <a href='resultados/'>
                                    <i class="fa fa-tint fa-large"></i><br/>
                                    <span class="text"><?php echo _("Resultados"); ?></span>
                                </a>
                        </h1>
                    </div>
                    <div class="service col-md-4">
                        <h1>
                            <a <a onclick="minimizar('<?php echo _("Simulações"); ?>', 'simulacoes')">
                                    <i class="fa fa-bar-chart-o fa-large"></i><br/>
                                    <span class="text"><?php echo _("Simulações"); ?></span>
                                </a>
                        </h1>
                    </div>
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