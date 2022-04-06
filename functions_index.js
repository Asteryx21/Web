//pigenei sto get_data.php(epilegei ta latitudes kai ta longtitudes apo tin B.D.) ta emfanizi sto heatmap
var topothesies = [];
function getCordinates() {
   map.addLayer(heatmapLayer);
   $.getJSON("get_data.php", function (data) {
   for (var i = 0; i < data.length; i++) {
      var location = new L.LatLng(data[i].latitude/10000000, data[i].longtitude/10000000);
      topothesies.push(location);
      var heatcords = {data: topothesies};
   }
   heatmapLayer.setData(heatcords);
   console.log(heatcords);

   });
   var topothesies = [];
}

// clear map apo poligona kai heatmap points
function clear_func(){
      map.eachLayer(function (layer) {
      //	markersLayer.clearLayers();
         map.removeLayer(heatmapLayer);
         map.removeLayer(polygonLayer);

      });
}


var currentPolygon = {color:"red", fillColor:
"red"}; //Empty object to be used later;
var poligona = [];	//ARRAY POY APOTHIKEVI TIS SINTETAGMENES KATHENOS APO TON POLIGONON


//ARXISE NA SXEDIAZIS POLIGONO
function drawPolygon(){
   currentPolygon = new L.polygon({color:"red", fillColor:
   "red"}).addTo(map);
   map.on('click', addLatLngToPolygon);
   polygonLayer.addLayer(currentPolygon);
}


//FINISH DRAW KOYMPI
function stopDraw(){
map.off('click', addLatLngToPolygon);

//APOTHIKEVI TIS SINTETAGMENES TOY TELEFTEOY POLIGONOY POY SXEDIASTIKE XORIS NA KANW OVERWRITE TO PROIGOYMENO
var sintetagmenez = currentPolygon.getLatLngs();

poligona.push(sintetagmenez);
console.log(poligona);
}


// STELNEI TO ARRAY ME TIS SINTETAGMENES OLON TON POLIGONON STO cords.php gia na gini elegxos me ton upload.
function getData(){
var sintetagmenezz = poligona;
const poligono = JSON.stringify(sintetagmenezz);
console.log(poligono);
            $.ajax({
               url: './cords.php',
               type: 'POST',
               data: {sint : poligono},
               cache: false,

               success:function(result) {
               $("#result1").html(result);

               }
            });
}


// SINARTISI P VAZEI SINTEGMENES STA POLIGONA
function addLatLngToPolygon(clickEventData){
currentPolygon.addLatLng(clickEventData.latlng);
}
