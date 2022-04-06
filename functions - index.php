
<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"> </script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"> </script>
<script src="https://cdn.jsdelivr.net/npm/heatmapjs@2.0.2/heatmap.js"></script>
<script src="https://cdn.jsdelivr.net/npm/leaflet-heatmap@1.0.0/leaflet-heatmap.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.js"></script>


<script>
//--------upload file script
   $(document).ready(function(){
   	$("#upload").click(function(){
   		var formData = new FormData(this.form);

   		$.ajax({
   			url: './upload.php',
   			type: 'POST',
   			data: formData,
   			contentType: false,
   			cache: false,
   			processData:false,
   			success:function(response) {
   				$("#result").html(response);
   			}
   		});

   	});
   });
</script>


<script>

		//add tiles sto xarti kai optikopoihisi
		 var map = L.map('mapid', {drawControl: true})

	//	map._initPathRoot();
	//	map._updatePathViewport();
		var osmUrl = "https://tile.openstreetmap.org/{z}/{x}/{y}.png";
		var osmAttrib =
		  'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
		var osm = new L.TileLayer(osmUrl, { attribution: osmAttrib });
		map.addLayer(osm);
		map.setView([38.246242, 21.7350847],16);
		var markersLayer = new L.LayerGroup();
		var polygonLayer = new L.LayerGroup();
		map.addLayer(markersLayer); // markers layers (den xriazete [?])
		map.addLayer(polygonLayer); // polygon layer


			// heatmap settings
		let cfg = {
		  // radius should be small ONLY if scaleRadius is true (or small radius is intended)
		  // if scaleRadius is false it will be the constant radius used in pixels
		  "radius": 10,
		  "maxOpacity": 0.6,
		  // scales the radius based on map zoom
		  "scaleRadius": false,
		  // if set to false the heatmap uses the global maximum for colorization
		  // if activated: uses the data maximum within the current map boundaries
		  //   (there will always be a red spot with useLocalExtremas true)
		  "useLocalExtrema": false,
		  // which field name in your data represents the latitude - default "lat"
		  latField: 'lat',
		 // which field name in your data represents the longitude - default "lng"
		  lngField: 'lng',
		  // which field name in your data represents the data value - default "value"

		};

		var heatmapLayer =  new HeatmapOverlay(cfg); // heatmap layer





	//epilogi shmeiou (poligonoy)
		var drawPolygonButton = document.getElementById('drawPolygon');
		var stopDrawButton = document.getElementById('stopDraw');
		var getDataButton = document.getElementById('getData');
		var currentPolygon = {color:"red", fillColor:
		"red"}; //Empty object to be used later;
		var poligona = [];	//ARRAY POY APOTHIKEVI TIS SINTETAGMENES KATHENOS APO TON POLIGONON


		//ARXISE NA SXEDIAZIS POLIGONO
		drawPolygonButton.addEventListener('click', function(){
		currentPolygon = new L.polygon({color:"red", fillColor:
		"red"}).addTo(map);
		map.on('click', addLatLngToPolygon);
		polygonLayer.addLayer(currentPolygon);

		});


		//FINISH DRAW KOYMPI
		stopDraw.addEventListener('click', function(e){
//       polygonLayer.addLayer(currentPolygon);
		map.off('click', addLatLngToPolygon);

		//APOTHIKEVI TIS SINTETAGMENES TOY TELEFTEOY POLIGONOY POY SXEDIASTIKE XORIS NA KANW OVERWRITE TO PROIGOYMENO
		var sintetagmenez = currentPolygon.getLatLngs();
		e.preventDefault();
		poligona.push(sintetagmenez);
		console.log(poligona);
		});


		// STELNEI TO ARRAY ME TIS SINTETAGMENES OLON TON POLIGONON STO cords.php gia na gini elegxos me ton upload.
		getDataButton.addEventListener('click', function(e){
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
		});


	// SINARTISI P VAZEI SINTEGMENES STA POLIGONA
	function addLatLngToPolygon(clickEventData){
		currentPolygon.addLatLng(clickEventData.latlng);
	}



</script>

<script>
//-----pigenei sto get_data.php(epilegei ta latitudes kai ta longtitudes apo tin B.D.) ta emfanizi sto heatmap
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
</script>

<script>



//------clear map apo poligona kai heatmap points
	function clear_func(){
		map.eachLayer(function (layer) {
		//	markersLayer.clearLayers();
			map.removeLayer(heatmapLayer);
			map.removeLayer(polygonLayer);
		});
	}


//anazitisi sintetagmenon analoga me Έτος,μήνας,ημέρα,ώρα,δραστηριότητα

	var meroi = [];
	var ores = [];

	var heatmeroi = [];
   $(function() {
	  $.getJSON("search.php", function (data) {
		 for (var x = 0; x < data.length; x++) {                                                            // ayto prepei na trexei
				var locations = new L.LatLng(data[x].latitude/10000000, data[x].longtitude/10000000);      //tan ginei upload to json
				var times = data[x].timestamp;																//i vasi dedomenon prota
				meroi.push(locations);
				ores.push(times);
			}
		});
//			console.log(meroi);
//			console.log(ores);
	 $('input[name="datess"]').daterangepicker({
         singleDatePicker: true,
         showDropdowns: true,
         minYear: 2009,
         autoUpdateInput: false,
         maxYear: parseInt(moment().format('YYYY'),10)

      },

      function(start, end, label) {
			var imer = start.format('DD/MM/YYYY');
			console.log(imer);
			for (var v = 0; v < ores.length; v++) {
				if (ores[v].includes(imer)){
					map.addLayer(heatmapLayer);
					heatmeroi.push(meroi[v]);
					var heatmapcords = {data:heatmeroi};
				}
			}
			heatmapLayer.setData(heatmapcords);
			console.log(heatmapcords);
			 heatmeroi = [];

		});
	});

	var tpSelectbox = new tui.TimePicker('#timepicker-selectbox', {
		initialHour: 24,
		initialMinute: 24,
		inputType: 'selectbox',
		format:'HH:MM',
		 showMeridiem: false
	});

</script>

<script>

   /* When the user clicks on the button,
   toggle between hiding and showing the dropdown content */
   function myFunction() {
     document.getElementById("myDropdown").classList.toggle("show");
   }

   function filterFunction() {
     var input, filter, ul, li, a, i;
     input = document.getElementById("myInput");
     filter = input.value.toUpperCase();
     div = document.getElementById("myDropdown");
     a = div.getElementsByTagName("a");
     for (i = 0; i < a.length; i++) {
      txtValue = a[i].textContent || a[i].innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
         a[i].style.display = "";
      } else {
         a[i].style.display = "none";
         }
      }
   }

	var test_meroi = [];
	var test_ores = [];
	var test_act = [];
	var test_conf = [];
	var test_heatmeroi = [];

	$.getJSON("activities.php", function (xz) {
		for (var z = 0; z < xz.length; z++) {
			var topothes = new L.LatLng(xz[z].latitude/10000000, xz[z].longtitude/10000000);
			var act_times = xz[z].Activity_Timestamp;
			var act = xz[z].Activity_Type;
			var conf = xz[z].Activity_Confidence;

			test_meroi.push(topothes);
			test_ores.push(act_times);
			test_act.push(act);
			test_conf.push(conf);
		}
	});
	console.log([test_meroi]);
	console.log([test_ores]);
	console.log([test_act]);
	console.log([test_conf]);

   function testFunction(){
		for (i=0; i<test_meroi.length; i++){
			if (test_act[i]=="IN_RAIL_VEHICLE"){
				if (test_ores[i]==test_ores[i+1]) {
					if (test_conf[i] > test_conf[i+1]){
						map.addLayer(heatmapLayer);
						test_heatmeroi.push(test_meroi[i]);
						var heatmapcords = {data:test_heatmeroi};
					}
				}
			}
		}
		heatmapLayer.setData(heatmapcords);
		console.log(heatmapcords);
		test_heatmeroi = [];
	}


</script>

</body>
</html>
