<!doctype html>
<html lang="pt_BR">
    <head>
        <meta charset="utf-8">
        <meta name="description" content=""/>
        <!-- css -->

        <link href="css/bootstrap/bootstrap.css" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="css/font-awesome/css/font-awesome.min.css" media="screen">
        <link href="css/style.css" rel="stylesheet" type="text/css">

        <!-- javascript -->
        
        <script type="text/javascript" src="js/jquery-2.1.1.js"></script> 
        <script type="text/javascript" src="js/bootstrap.js"></script> 
        <script type="text/javascript" src="js/scripts.js"/></script>   

    <title>Alfa Seed Software</title>

</head>
<body>
<!--    <nav id="menu" class="navbar navbar-default" role="navigation">
    <div class="collapse navbar-collapse" >
        <a href="index.php" class="navbar-brand">Alfa Seed Software</a>
        <a href="#configuracao" class="navbar-link"> Configuração</a>
        <a href="#configuracao" class="navbar-link"> Resultados</a>
        <a href="#configuracao" class="navbar-link"> Parâmetros</a>
    </div>
    </nav>-->
<nav id="menu" class="navbar navbar-default menu" role="navigation">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar">teste</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" onclick="maximizar();">Alfa Seed Software</a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li id="configuracao" >
            <a onclick="busca_pagina('configuracao.php');"><i class="fa fa-gear"></i> Configuração</a>
        </li>
        <li id="resultados" >
            <a onclick="busca_pagina('resultados.php');"><i class="fa fa-tint"></i> Resultados</a>
        </li>
        <li id="simulacoes" >
            <a onclick="busca_pagina('simulacoes.php');"><i class="fa fa-bar-chart-o"></i> Simulações</a>
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
      <form class="navbar-form navbar-left" role="search">
        <div class="form-group">
          <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="submit" class="btn btn-default">Submit</button>
      </form>
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
<section id="main"></section>
    <section class="header_menu">
        
        <div class="jumbotron jumbotron-ad hidden-print">
            <div class="container">

                <div class="row">
                    <div class="service col-md-4">
                        <h1>
                            <a onclick="minimizar('<?php echo _("Configuração"); ?>', 'configuracao')">
                                <i class="fa fa-gear fa-large"></i><br/>
                                <span class="text"><?php echo _("Configuração"); ?></span>
                            </a>
                        </h1>
                    </div>
                    <div class="service col-md-4">
                        <h1>
                            <a <a onclick="minimizar('<?php echo _("Resultados"); ?>', 'resultados')">
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

        <!-- <i class="fa fa-leaf fa-5x "></i><br/> -->
                        <span class="text-logo"><?php echo _("Alfa Seed Software"); ?></span>

                    </h1>

                </div>
            </div>
        </div>

    </section>
</body>
</html>