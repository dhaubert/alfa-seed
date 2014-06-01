$(function() {
//    initialize();

});
function gerenciador_paginas(url) {
    $.ajax({
        type: 'GET',
        url: './' + url
    }).done(function(retorno) {
        $('#main').html("");
        $('#main').append(retorno);
    });
}
function busca_pagina(alias) {
    gerenciador_paginas(alias);
}
function enviar_arquivo() {
    alert('ajax');
//      var formData = new FormData($('#clima'));
    var fileInput = document.getElementById('importar');
    var file = fileInput.files[0];
    var formData = new FormData();
    formData.append('file', file);
    var xhr = new XMLHttpRequest();
    xhr.open('POST', './configuracao.php', true);
    xhr.send(formData);
//      $.ajax({
//         type: 'POST',
//         data: formData,
//         url: './configuracao.php',
//         enctype: 'multipart/form-data'
////         data: $('#clima').serialize()
//        
////         dataType : 'json',
//        
//      }).done(function(retorno){
//          alert(retorno);
//      });

}
function minimizar(menu, alias) {
    $(".header_menu").animate({height: '45px', opacity: '0'});
    $(".footer").animate({height: '45px', opacity: '0'});
    $("#menu").fadeIn(1000);
    $('a').removeClass('active');
    $("#" + alias).addClass('active');
    busca_pagina(alias + '.php');
}
function maximizar() {
    $(".header_menu").animate({height: '100%', opacity: '1'});
    $(".footer").animate({height: '45px', opacity: '1'});
    $("#menu").fadeOut(1000);
}
function alterna_clima() {
    $('#dados_inmet').toggleClass('active');
    $('#dados_csv').toggleClass('active');
    $('#clima_inmet').toggle();
    $('#clima_csv').toggle();

}
function salvar_clima() {
    var estacao_id = $('#estacoes option:selected').val();
    var data_inicial = $('#data_plantio').val();
    $("#spinner").addClass('fa-spin');
    $("#loading").fadeIn();
    $("#mensagem_clima").html("");
    $.ajax({
        data: 'request=salvar_clima' +
                '&estacao_id=' + estacao_id +
                '&data_inicial=' + data_inicial,
        url: './controller/requisicoes_ajax.php',
        type: 'POST',
        async: 'false'
    }).done(function(retorno) {
        retorno = retorno.split("::");
        $("#spinner").removeClass('fa-spin');
        $("#loading").hide();
        if (retorno[0] == "OK") {
            var check_icon = '<i class="fa fa-check" style="font-size: 22px; color: #308E4B;"></i>';
            $("#mensagem_clima").html(check_icon + '&nbsp;' + retorno[1]);
        }
        else{
            if(retorno[0] == "ERRO"){
                var check_icon = '<i class="fa fa-times" style="font-size: 22px; color: #FF0400;"></i>';
                $("#mensagem_clima").html(check_icon + '&nbsp;' + retorno[1]);
            }
        }

    });
}
function atualiza_mapa(estacao_id, latitude, longitude, altitude) {
    $("#map_canvas").text(estacao_id + ", " + latitude + ", " + longitude + ", " + altitude);
}
// GOOGLE MAPS
function initialize() {
    console.log("iniciando mapa...");
    var mapOptions = {
        center: new google.maps.LatLng(-34.397, 150.644),
        zoom: 8,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map($('#map_canvas'),
            mapOptions);
}
