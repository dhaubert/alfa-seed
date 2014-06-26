$(function() {

});
//function gerenciador_paginas(url) {
//    $.ajax({
//        type: 'GET',
//        url: './' + url
//    }).done(function(retorno) {
//        $('#main').html("");
//        $('#main').html(retorno);
//    });
//}
//function busca_pagina(alias) {
//    gerenciador_paginas(alias);
//}
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
    if ($('#dados_inmet').hasClass('active')) {
        $("#entrada_clima").val('inmet');
    }
    else {
        $("#entrada_clima").val('csv');
    }

}
function salvar_clima() {

    if ($('#entrada_clima').val() == 'inmet'){
            var estacao_id = $('#estacoes option:selected').val();
            var data_inicial = $('#data_plantio').val();
            $("#mensagem_clima").html("");
            $("#spinner").addClass('fa-spin');
            $("#loading").fadeIn();
            $.ajax({
            data: 'request=salvar_clima_inmet' +
                    '&estacao_id=' + estacao_id +
                    '&data_inicial=' + data_inicial,
                    url: '../controller/requisicoes_ajax.php',
                    type: 'POST',
                    async: 'false'
            }).done(function(retorno) {
            
            retorno = retorno.trim().split("::");
            $("#spinner").removeClass('fa-spin');
            $("#loading").hide();
            if (retorno[0] == "OK") {
                var check_icon = '<i class="fa fa-check" style="font-size: 22px; color: #308E4B;"></i>';
                $("#mensagem_clima").html(check_icon + '&nbsp;' + retorno[1]);
            }
            else {
                    var check_icon = '<i class="fa fa-times" style="font-size: 22px; color: #FF0400;"></i>';
                    $("#mensagem_clima").html(check_icon + '&nbsp;' + retorno[1]);
            }
         });
     }else{
        if ($('#entrada_clima').val() == 'csv') {
            alert("Importando arquivo separado por virgulas");
            $('#importar').submit();
        }
    }

   
}
function atualiza_mapa(estacao_id) {
    $.ajax({
            data: 'request=atualizar_mapa' +
                    '&estacao_id=' + estacao_id, 
                    url: '../controller/requisicoes_ajax.php',
                    type: 'POST',
                    async: 'false'
    }).done(function(retorno) {
//        initialize();
        carregarPontos();
    });
}
function atualiza_tabela(kc_ini, kc_mid, kc_end, gd_ini, gd_mid, gd_dev, gd_late, tbase, tupper){
    $('#kcini').text(kc_ini);
    $('#kcmid').text(kc_mid);
    $('#kcend').text(kc_end);
    $('#gdini').text(gd_ini);
    $('#gdmid').text(gd_mid);
    $('#gddev').text(gd_dev);
    $('#gdlate').text(gd_late);
    $('#tbase').text(tbase);
    $('#tupper').text(tupper);
}

