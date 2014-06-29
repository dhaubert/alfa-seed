<?php
$host = 'localhost';
$user = 'alfaseed';
$password = 'alfaseedUFSM2014';

@$conection = mysql_connect($host, $user, $password);
mysql_select_db('alfaseed');
if(!$conection){
    echo _("Não foi possível obter uma conexão com o banco de dados");
    die;
}


