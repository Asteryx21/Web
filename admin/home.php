<?php
	include('../functions.php');

	if (!isAdmin()) {
		$_SESSION['msg'] = "You must log in first";
		header('location: ../login.php');
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="admin_style.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
	<body>
        
        <input type='button' value='Dashboard' class="dash_btn", onclick="document.location.href='dash_board.php'" />

		<div id="mySidebar" style= "z-index:1;" class="sidebar">

			<a  href="javascript:void(0)" class="closebtn" onclick="closeNav2()">⯇</a>

            <input type="text" name="datess" value="" placeholder="Choose Date"/>
			<input type="text" name="time_picker" id="time_picker" value="" placeholder="Choose Time" />
			<select id="myDropdown"  multiple="multiple" >
				<option> Choose an activity </option>
			</select>
			<input type="button" id="but1" value="Apply" onclick="listboxresult()"/>
        	<button class="show_history_btn" onclick="getCordinates()">Show all my history!</button>
			
            <div>
                    <button onclick="clear_func()" class="clear_map_btn">Clear map</button>
                
                    <form class="download_json" method="post"     action="export_json.php">
						<input type="submit" name="export" value="JSON" class="btn btn-i" />
					</form>
                
                    <form class="download_button" method="post" action="export_csv.php">
                    <input type="submit" name="export" value="CSV" class="btn btn-i" />
					</form>
                    <form class="xml" method="post" action="export_xml.php">
                    <input type="submit" name="export" value="XML" class="btn btn-i" />
					</form>
					
                
				</div>


				<!-- logged in user information -->
				<div class="profile_info">
						<?php  if (isset($_SESSION['user'])) : ?>
						<strong><?php echo $_SESSION['user']['username']; ?></strong>
						<small>
							<i  style="color: #fff;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i>
                            <br>
							<a href="home.php?logout='1'">
									 Logout
							</a>
						</small>
						<?php endif ?>
				</div>

		</div>
		<div id="main">
			<button class="openbtn" style="border-radius: 25px;" onclick="openNav2()">☰</button>
		</div>
		<div id="mapadmin" style="z-index:0"></div>

<!--	<p id="result"></p> -->

		<script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"> </script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"> </script>
		<script src="https://cdn.jsdelivr.net/npm/heatmapjs@2.0.2/heatmap.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/leaflet-heatmap@1.0.0/leaflet-heatmap.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>

		<script>
            
		/*old*/	function openNav() {
			  document.getElementById("mySidebar").style.width = "250px";
			  document.getElementById("main").style.marginLeft = "250px";
			}

			function closeNav2() {
			            var z = document.getElementById("main");
              if (z.style.display === "none") {
                z.style.display = "block";
                  document.getElementById("mySidebar").style.width = "0px";
			  document.getElementById("main").style.marginLeft = "0px";
              } else {
                z.style.display = "none";
                  document.getElementById("mySidebar").style.width = "0px";
			  document.getElementById("main").style.marginLeft = "0px";
              }
			}
            function openNav2() {
              var z = document.getElementById("main");
              if (z.style.display === "none") {
                z.style.display = "block";
                  document.getElementById("mySidebar").style.width = "250px";
			  document.getElementById("main").style.marginLeft = "250px";
              } else {
                z.style.display = "none";
                  document.getElementById("mySidebar").style.width = "250px";
			  document.getElementById("main").style.marginLeft = "250px";
              }
            }
          /*toggle sidebad button*/
            function myFunction() {
                document.getElementById("mySidebar").classList.toggle("show");
            }
        
		</script>
		<script>

			//add tiles sto xarti kai optikopoihisi
			var map = L.map('mapadmin', {drawControl: true, zoomControl: false})
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
			map.addLayer(polygonLayer); // polygon layer


			//add zoom control with your options
			L.control.zoom({
				position:'topright'
			}).addTo(map);


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
				//	valueField: 'count'

			};

			var heatmapLayer =  new HeatmapOverlay(cfg); // heatmap layer


		</script>

		<script>

			//pigenei sto get_data.php(epilegei ta latitudes kai ta longtitudes apo tin B.D.) ta emfanizi sto heatmap

			function getCordinates() {
				var topothesies = [];
				map.addLayer(heatmapLayer);
				$.getJSON("get_data_admin.php", function (data) {
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


			// clear map apo  heatmap points
			function clear_func(){
				map.eachLayer(function (layer) {
					map.removeLayer(heatmapLayer);
				});
			}


			//anazitisi sintetagmenon analoga me Έτος,μήνας,ημέρα,ώρα


			var ores = []; //timestamps se morfi human frieldy
			var meroi = [];
			var heatmeroi = [];
			var ores_for_fra=[];
			var ores2 = []; //timestamps se timestamp morfi
			var ores3 = [];

			$.getJSON("search_admin.php", function (data) {
				for (var x = 0; x < data.length; x++) {
					var locations = new L.LatLng(data[x].latitude/10000000, data[x].longtitude/10000000);
					var times = moment.unix(data[x].timestamp/1000).format("DD/MM/YYYY");
					var times3 = moment.unix(data[x].timestamp/1000).format("H:mm");
					var times2 = data[x].timestamp;
					ores2.push(times2);
					meroi.push(locations);
					ores.push(times);
					ores3.push(times3);
				}
			});


			//console.log(meroi);
			
			//DATE PICKER

		 $('input[name="datess"]').daterangepicker({
			showDropdowns: true,
			minYear: 2009,
			autoUpdateInput: false,
			maxYear: parseInt(moment().format('YYYY'),10),
			 locale: {
				  
			  }
		  },function(start, end, label) {
				var imer1 = start.format('DD/MM/YYYY');
				var imer2 = end.format('DD/MM/YYYY');
				var date_range = [];
					var startDate = new Date(start.format('YYYY-MM-DD')); //YYYY-MM-DD
					var endDate = new Date(end.format('YYYY-MM-DD')); //YYYY-MM-DD

					var getDateArray = function(start, end) {
						var arr = new Array();
						var dt = new Date(start);
						while (dt <= end) {
							arr.push(new Date(dt));
							dt.setDate(dt.getDate() + 1);
						}
						return arr;
					}

					var dateArr = getDateArray(startDate, endDate);

					// Output
					
		
					for (var i = 0; i < dateArr.length; i++) {
						
						 date_range.push(moment(dateArr[i]).format("DD/MM/YYYY"));
					}
						console.log(date_range);
						for (var i =0; i<date_range.length; i++) {
							for (var v = 0; v < ores.length; v++) { 
							
								if (ores[v].includes(date_range[i])  ){
									map.addLayer(heatmapLayer);
									heatmeroi.push(meroi[v]);
									ores_for_fra.push(ores2[v]);
									var heatmapcords = {data:heatmeroi};
								}
							}
						}
							if (heatmeroi.length==0){
									alert('no locations found the given time');
								}
							heatmapLayer.setData(heatmapcords);
							console.log(heatmapcords);	
								


					
					var timestamp = ores_for_fra;
					var sintetagmenezz = heatmeroi;
					const poligono = JSON.stringify(sintetagmenezz);

						$.ajax({
							url: './middle_man.php',
							type: 'POST',
							data: {poligono,timestamp},
							cache: false,

							success:function(result) {
								$("#result").html(result);
							}
						});

					heatmeroi = [];
					ores_for_fra=[];
				}
			);
console.log(ores3);
//time picker
var timeArr = [];
		 $('#time_picker').daterangepicker({
					timePicker : true,
					singleDatePicker:false,
					timePicker24Hour : true,
					timePickerIncrement : 1,
					timePickerSeconds : false,
					locale : {
						format : 'HH:mm'
					}
				}).on('show.daterangepicker', function(ev, picker) {
					picker.container.find(".calendar-table").hide();

		   });
		   $('#time_picker').on('apply.daterangepicker', 
				function(ev, picker) {
					
					var epil_ora =  $('#time_picker').val();								// epilegmeno euros oras se morfi 00:00 - 15:51 
					 var start_time = epil_ora.slice(0,5);                 					// spame to epiligmeni ora se 00:00                 
					  var end_time = epil_ora.slice(-5);									// spame to epilegmeni ora se 15:51
					  console.log(start_time);
					  console.log(end_time);
					
					
					function time(start, end, interval) {
						var s = start.split(':').map(e => +e);                               //funtion poy briskei to euros metaksi tou start_time k end_time
						var e = end.split(':').map(e => +e);
						var res = [];
						var t = [];
						while (!(s[0] == e[0] && s[1] > e[1])) {
							t.push(s[0] + ':' + (s[1] < 10 ? '0' +s[1] : s[1]));
							s[1] += interval;
							if (s[1] > 59) {
								s[0] += 1;
								s[1] %= 60;
							}
						}
						for (var i = 0; i < t.length - 1; i++) {
							res.push(t[i] );
						}
						return res;
					}

					timeArr = time(start_time,end_time,1);                      // kaloyme to function time gia to euros

						console.log(timeArr);
			if (start_time !== end_time) {		
				for (var x=0; x< timeArr.length; x++) {	
					for (var v = 0; v < ores3.length; v++) {
						if (ores3[v] == timeArr[x]){
							map.addLayer(heatmapLayer);
							heatmeroi.push(meroi[v]);
							ores_for_fra.push(ores2[v]);
						
						}
					}
				}	
			}else {
				for (var i=0; i<ores3.length; i++){
					if (ores3[i] == start_time){
						map.addLayer(heatmapLayer);
						heatmeroi.push(meroi[i]);
						ores_for_fra.push(ores2[v]);
					}
				
				}
			}
			var heatmapcords = {data:heatmeroi};
			heatmapLayer.setData(heatmapcords);
			if (heatmeroi.length==0){
				alert('no locations found the given time');
			}
			console.log(heatmapcords);	
			
			
					
			var timestamp = ores_for_fra;
			var sintetagmenezz = heatmeroi;
			const poligono = JSON.stringify(sintetagmenezz);

				$.ajax({
					url: './middle_man.php',
					type: 'POST',
					data: {poligono,timestamp},
					cache: false,

					success:function(result) {
						$("#result").html(result);
					}
				});
	
			
			
			ores_for_fra=[];
			heatmeroi = [];
				}
			);



			//anazitisi vasi drastiriotitas


		var test_meroi = [];
								var test_ores = [];
								var test_act = [];
								var test_conf = [];	
								var options = [];
								var solo_act=[];
							const opti =[];
					
							var solo_times =[];
							 
								var first = 0;
								var last = 0;
								var flag = false;									
				$.getJSON("activities_admin.php", function (xz) {
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
						
					
					for (var v = 0; v < test_act.length; v++) { 		
									if ( test_ores[v] == test_ores[v+1]){
										flag = true;
									}else flag = false;
									
									if (flag===true){
										last=last+1;
									}
									
									if (flag===false || v==test_act.length - 1){
										var max = -1;
										var	position = first;                   
									
										for (var j=first; j<=last;j++){
											if (Number(test_conf[j])>=max){
												max = Number(test_conf[j]);
												position = j;
												
												
											}
											
										}
								
										solo_times.push(test_ores[position]);
										solo_act.push(test_act[position]);
										
										first=last+1;
										last++;
									}
							
					}
							//	console.log(solo_times);	
							//	console.log(solo_act);	
					$.each(solo_act, function(i, el){
						if($.inArray(el, options) === -1) options.push(el);
					});
					//console.log(options);
					var select = document.getElementById("myDropdown");
					for(var n = 0; n < options.length; n++) {
						var opt = options[n];
						var el = document.createElement("option");
						el.textContent = opt;
						el.value = opt;
						select.appendChild(el);
					
						opti.push(opt);
					}
					
							
										
					
								
			});


function listboxresult(){
	var first1 = 0;
	var last1 = 0;
	var flag1 = false;
	var temp_arr = [];
	var max_points =[];
	var x= document.getElementById("myDropdown")
	
	
		
			for (var v = 0; v < test_act.length; v++) { 		
			
				if ( test_ores[v] == test_ores[v+1]){
					flag1 = true;
				}else flag1 = false;
				
				if (flag1===true){
					last1=last1+1;
				}
				
				if (flag1===false || v==test_act.length - 1){
					var max1 = -1;
					var	position1 = first1;                   
				
					for (var j=first1; j<=last1;j++){
						if (Number(test_conf[j])>=max1){
							max1 = Number(test_conf[j]);
							position1 = j;
							
						}
						
						
					}
					first1=last1+1;
						last1++;
						for (var i=0; i<x.options.length; i++ ){
							if(x.options[i].selected){
								if (x.options[i].value == test_act[position1]){
									max_points.push(position1);
									temp_arr.push(test_meroi[position1]);
									ores_for_fra.push(test_ores[position1]);
									map.addLayer(heatmapLayer);
									var heatmapcords = {data: temp_arr};
								}
							}
						}		
						
				}
		
			}
			
				heatmapLayer.setData(heatmapcords);
					console.log(heatmapcords);
			var timestamp = ores_for_fra;
			var sintetagmenezz = temp_arr;
			const poligono = JSON.stringify(sintetagmenezz);

				$.ajax({
					url: './middle_man.php',
					type: 'POST',
					data: {poligono,timestamp},
					cache: false,

					success:function(result) {
						$("#result").html(result);
					}
				});
	
			
			
			ores_for_fra=[];
			
		temp_arr = [];	
	


}
		</script>
	</body>
</html>