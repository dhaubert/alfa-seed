$(function() {
    helpers();
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
function helpers() {

    $("#help_nome_cultura").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Nome pelo qual a cultura é conhecida'
    });
    $("#help_kc_ini").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Coeficiente de cultura para o estágio inicial'
    });
    $("#help_kc_mid").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Coeficiente de cultura para o estágio intermediário'
    });
    $("#help_kc_end").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Coeficiente de cultura para o estágio final'
    });
    $("#help_gda_ini").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Graus dia acumulados para atingir o estágio inicial (GDA)'
    });
    $("#help_gda_mid").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Graus dia acumulados para atingir o estágio intermediário (GDA)'
    });
    $("#help_gda_dev").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Graus dia acumulados para atingir o estágio de crescimento (GDA)'
    });
    $("#help_gda_late").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Graus dia acumulados para atingir o estágio de maturação (GDA)'
    });
    $("#help_temp_base").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Graus Celcius cujo desenvolvimento da planta é nulo.'
    });
    $("#help_temp_upper").popover({
        placement: 'bottom', // top, bottom, left or right
        html: 'true',
        content: 'Graus Celcius cujo desenvolvimento da planta é máximo.'
    });
    // resultados
    $("#data").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Data de semeadura até o dia atual'
    });
    $("#tar").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Temperatura média diária do ar (em Cº)'
    });
    $("#tmax").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Temperatura máxima diária do ar (em Cº)'
    });
    $("#tmin").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Temperatura min diária do ar (em Cº)'
    });
    $("#umid").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Umidade relativa do ar média diária (em %)'
    });
    $("#umid_max").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Umidade relativa do ar máxima diária (em %)'
    });
    $("#umid_min").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Umidade relativa do ar mínima diária (em %)'
    });
    $("#pressao").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Pressão atmosférica (em hPa)'
    });
    $("#vento").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Velocidade do vento média diária (em m/s)'
    });
    $("#radiacao").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Radiação solar medida (kJ/m²)'
    });
    $("#insolacao").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Número de horas de luz do sol'
    });
    $("#estagio").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Estágio de desenvolvimento da cultura'
    });
    $("#gda").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Graus dia acumulado (em Cº)'
    });
    $("#eto").popover({
        placement: 'top', // top, top, left or right
        html: 'true',
        content: 'Evapotranspiração de referência (em mm/dia)'
    });
    $("#etc").popover({
        placement: 'top', // top, bottom, left or right
        html: 'true',
        content: 'Evapotranspiração da cultura (em mm/dia)'
    });
    $("#kc").popover({
        placement: 'top', // top, bottom, left or right
        html: 'true',
        content: 'Coeficiente de cultura'
    });

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
function carrega_parametros() {
    var estacao_id = $('#resultados_estacao').val();
    if (estacao_id != "") {
        $("#estacoes option[value='" + estacao_id + "']").attr('selected', 'selected');
        var cultura_id = $('#resultados_cultura').val();
    }
    if (cultura_id != "") {
        if (cultura_id > 100) {
            alterna_cultura();
        }
        else {
            $("#culturas option[value='" + cultura_id + "']").attr('selected', 'selected');
        }
    }
    var data = $('#resultados_data_semeadura').val();
    if (data != "") {
        var date_plantio = data.split('-')[2] + '/' + data.split('-')[1] + '/' + data.split('-')[0];
        $('#data_plantio').val(date_plantio);
    }
}
function acessa_pagina(secao) {
    $('#form_parametros').attr('action', '../' + secao + '/index.php');
    $('#form_parametros').submit();
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
function alterna_cultura() {
    $('#sel_cultura').toggleClass('active');
    $('#add_cultura').toggleClass('active');
    $('#tela_sel_cultura').toggle();
    $('#tela_add_cultura').toggle();
    if ($('#sel_cultura').hasClass('active')) {
        $("#entrada_cultura").val('selecao');
    }
    else {
        $("#entrada_cultura").val('adicao');
    }

}

function trim(str)
{
    return str.replace(/^\s+|\s+$/g, "");
}
function salvar_cultura() {
    var retorno = "";
    if ($('#entrada_cultura').val() == 'selecao') {
        $("#resultados_cultura ").val($("#culturas option:selected").val());
        var check_icon = '<i class="fa fa-check" style="font-size: 22px; color: #308E4B;"></i>';
        $("#mensagem_cultura").html(check_icon + '&nbsp;' + 'A cultura foi salva com sucesso.');
    } else {
        if ($('#entrada_cultura').val() == 'adicao') {
            $.ajax({
                data: 'request=salvar_nova_cultura&' +
                        $('#nova_cultura').serialize(),
                url: '../controller/requisicoes_ajax.php',
                type: 'POST',
                async: 'false'
            }).done(function(retorno_) {
                retorno_ = trim(retorno_);
                retorno = retorno_.split('::');
                $("#resultados_cultura").val(retorno[2]);
                if (retorno[0] == "OK") {
                    var check_icon = '<i class="fa fa-check" style="font-size: 22px; color: #308E4B;"></i>';
                    $("#mensagem_cultura").html(check_icon + '&nbsp;' + retorno[1]);
                }
                else {
                    var check_icon = '<i class="fa fa-times" style="font-size: 22px; color: #FF0400;"></i>';
                    $("#mensagem_cultura").html(check_icon + '&nbsp;' + retorno[1]);
                }
            });
        }
    }


}
function salvar_clima() {

    if ($('#entrada_clima').val() == 'inmet') {
        var estacao_id = $('#estacoes option:selected').val();
        $("#resultados_estacao").val(estacao_id);
        var data_inicial = $('#data_plantio').val();
        var data_ = data_inicial.split('/');
        var data_US = data_[2] + '-' + data_[1] + '-' + data_[0];
        $("#resultados_data_semeadura").val(data_US);
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
    } else {
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
        initialize();
        carregarPontos();
    });
}
function atualiza_tabela(kc_ini, kc_mid, kc_end, gd_ini, gd_mid, gd_dev, gd_late, tbase, tupper) {
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

