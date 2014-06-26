var map;
var idInfoBoxAberto;
var infoBox = [];
var markers = [];
function initialize() {

    var latlng = new google.maps.LatLng(-18.8800397, -47.05878999999999);

    var options = {
        zoom: 1,
        center: latlng,
//        mapTypeId: google.maps.MapTypeId.SATELLITE
        mapTypeId: google.maps.MapTypeId.MAP
    };

    map = new google.maps.Map(document.getElementById("map_canvas"), options);
}

function abrirInfoBox(id, marker) {
    if (typeof (idInfoBoxAberto) == 'number' && typeof (infoBox[idInfoBoxAberto]) == 'object') {
        infoBox[idInfoBoxAberto].close();
    }

    infoBox[id].open(map, marker);
    idInfoBoxAberto = id;
}

function carregarPontos() {
    $.getJSON('../js/mapas/pontos.json', function(pontos) {

        var latlngbounds = new google.maps.LatLngBounds();

        $.each(pontos, function(index, ponto) {

            var marker = new google.maps.Marker({
                position: new google.maps.LatLng(ponto.latitude, ponto.longitude),
                title: "<p>" + ponto.municipio + "</p>",
                icon: '../images/marcador-verde.png',
            });
            var latitude_ = (ponto.latitude*100)/100;
            var longitude_ = (ponto.longitude*100)/100;
            var altitude_ = (ponto.altitude*100)/100;
            var myOptions = {
                content: "<p>Município: " + ponto.municipio + "</p>" +
                        "<p>ID: " + ponto.estacao_id + "</p>" +
                        "<p>Latitude: " + latitude_.toFixed(4) + "</p>" +
                        "<p>Longitude: " +longitude_.toFixed(4) + "</p>" +
                        "<p>Altitude: " + altitude_.toFixed(1) + "</p>",
                pixelOffset: new google.maps.Size(-150, 0)
            };

            infoBox[ponto.estacao_id] = new InfoBox(myOptions);
            infoBox[ponto.estacao_id].marker = marker;

            infoBox[ponto.estacao_id].listener = google.maps.event.addListener(marker, 'click', function(e) {
                abrirInfoBox(ponto.estacao_id, marker);
            });

            markers.push(marker);

            latlngbounds.extend(marker.position);

        });

        var markerCluster = new MarkerClusterer(map, markers);

        map.fitBounds(latlngbounds);

    });
}
