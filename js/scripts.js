$(function(){
//    $('.minimizar').click
    
    
    
});
function gerenciador_paginas(url){
    $.ajax({
        type: 'GET',
        url: './' + url 
    }).done(function(retorno){
        $('#main').html("");
        $('#main').append(retorno);
    });
}
function busca_pagina(alias){
    gerenciador_paginas(alias);
}
function enviar_arquivo(){
    alert('ajax');
      var formData = new FormData($('#clima'));
      var fileInput = document.getElementById('importar');
      var file = fileInput.files[0];
      formData.append('file', file);
      $.ajax({
         type: 'POST',
         data: formData,
         url: './configuracao.php',
         enctype: 'multipart/form-data'
//         data: $('#clima').serialize()
        
//         dataType : 'json',
        
      }).done(function(retorno){
          alert(retorno);
      });
   
}
function minimizar(menu, alias){
    $(".header_menu").animate({height:'45px', opacity: '0'}, 1000);
    $(".footer").animate({height:'45px', opacity: '0'}, 1000);
    $("#menu").fadeIn(3000);
    $('a').removeClass('active');
    $("#"+alias).addClass('active');
    busca_pagina(alias+ '.php');
}
function maximizar(){
    $(".header_menu").animate({height:'100%', opacity: '1'});
    $(".footer").animate({height:'45px', opacity: '1'});
    $("#menu").fadeOut(1000);
}

