    <?php 
	include('functions.php');

	if (!isLoggedIn()) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}
?>
<!DOCTYPE HTML>
<html>
<head>
	
	<title>Home</title>
		<link rel="stylesheet" type="text/css" href="user_style.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">  
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
 <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">

</head>
<body style="background-color: lightyellow;">       
    
    <div id="stick-here"></div>
	      <div id="stickstuff"> 
				<input id="MapToggle" type="button" value="Toggle Map" onClick="MapToggle()">
		        <input id="search" type="button" value="Clear map" onClick="clear_func()">
              <input id="getCoordinates" type="button" value="Show all my history" onClick="getCordinates()">
              
               <input type="text" name="datess" id="datess" value="" placeholder="Choose Date"/>
			     <input type="text" name="time_picker" id="time_picker" value="" placeholder="Choose Time" />
              <select id="myDropdown" >
                  <option>Choose an activity</option>        
              </select>
              <select id="monthdropdown" >
                <option> Ecological </option>
                    <option> This month </option>
                    <option> Last 12 months </option>
                  </select>


              <!-- logged in user information -->
                    <div class="profile_info">    
                            <?php  if (isset($_SESSION['user'])) : ?>
					   <strong><?php echo $_SESSION['user']['username']; ?></strong>
            
					   <small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
                        <div class="logout">
						<a href="index.php?logout='1'">Logout</a></div>
					</small>

				    <?php endif ?>
                    </div> 	
            <div id="mapid"></div>
    </div>
                <p id="range_record"></p> 
	<div id="chartdiv">
	<canvas id="myChart7" style="z-index:0" width="100" height="100"></canvas>
	<p id="your_score"></p>
	<canvas id="myChart" style="z-index:0" width="200" height="100"></canvas>
	<canvas id="myChart2" style="z-index:0" width="100" height="100"></canvas>
	<canvas id="myChart3" style="z-index:0" width="200" height="100"></canvas>
	<canvas id="myChart4" style="z-index:0" width="100" height="100"></canvas>
	<canvas id="myChart6" style="z-index:0" width="100" height="100"></canvas>
		  </div>	
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
							//	valueField: 'count'
							 
							};
							
							var heatmapLayer =  new HeatmapOverlay(cfg); // heatmap layer
							
							//ANIGOKLINI MAP
							function MapToggle() {
							  var x = document.getElementById("mapid");
							  if (x.style.display === "none") {
								x.style.display = "block";
							  } else {
								x.style.display = "none";
							  }
							  var x = document.getElementById("overlay");
							  if (x.style.display === "none") {
								x.style.display = "block";
							  } else {
								x.style.display = "none";
							  }	
							}
			
		</script>
		<script>
		


		
			
						
					//pigenei sto get_data.php(epilegei ta latitudes kai ta longtitudes apo tin B.D.) ta emfanizi sto heatmap
								
							function getCordinates() {
								var topothesies = [];
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
		
				
						
				// clear map apo poligona kai heatmap points
			function clear_func(){
					map.eachLayer(function (layer) {
					//	markersLayer.clearLayers();
						map.removeLayer(heatmapLayer);
					
						
					});
			}
	

//anazitisi sintetagmenon analoga me Έτος,μήνας,ημέρα,ώρα
				
					
						var ores = [];
						var meroi = [];
						var heatmeroi = [];
						var curr_user=[];
						  $.getJSON("search.php", function (data) {
								 for (var x = 0; x < data.length; x++) {                                                            
										var locations = new L.LatLng(data[x].latitude/10000000, data[x].longtitude/10000000);          
										var times = data[x].timestamp;																 
										var userr = data[x].username;
										meroi.push(locations);
										ores.push(times);		
										curr_user.push(userr);								
									}				
								});
								//console.log(meroi);
							//	console.log(ores);

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
														var heatmapcords = {data:heatmeroi};
													}
												}
											}
											if (heatmeroi.length==0){
									alert('no locations found the given time');
								}
												heatmapLayer.setData(heatmapcords);
												console.log(heatmapcords);	
												 heatmeroi = [];
							
								});
							
							
				//		TIME PICKER
		   $('#time_picker').daterangepicker({
					timePicker : true,
					singleDatePicker:true,
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
					var epil_ora =  $('#time_picker').val();
					console.log(epil_ora);
					for (var v = 0; v < ores.length; v++) {

						if (ores[v].includes(epil_ora)){
							map.addLayer(heatmapLayer);
							heatmeroi.push(meroi[v]);
							var heatmapcords = {data:heatmeroi};
						}
					}
					if (heatmeroi.length==0){
									alert('no locations found the given time');
								}
					heatmapLayer.setData(heatmapcords);
					console.log(heatmapcords);
					
					
						
					heatmeroi = [];
				}
			);
				
</script>
  <script>
				
									

							
									
							
							
				//anazitisi vasi drastiriotitas
								var test_meroi = [];
								var test_ores = [];
								var test_act = [];
								var test_conf = [];	
								var options = [];
								var solo_act=[];
							const opti =[];
							const data1x=[];
							var solo_times =[];
							 
								var first = 0;
								var last = 0;
								var flag = false;									
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
										
												
															
										for (var x=0; x<opti.length; x++){	
												var add = 0;
											for (var i=0; i<solo_act.length; i++){
												if (solo_act[i].includes(opti[x])){
													add++;
												}	
											}
												add=100*add/solo_act.length;
												add = add.toFixed(2);
												data1x.push(add);
										}	
													myChart.data.datasets[0].data= data1x;
													myChart.update();	
								});
								
							
							//GRAFIMA ME POSOSTO TON ACTIVITIES TU USER
		
				
					var ctx = document.getElementById('myChart').getContext('2d');
					var myChart = new Chart(ctx, {
						type: 'bar',
						data: {
							labels: opti,
							datasets: [{
								label: 'Number of records per activity',
								data: data1x,
								backgroundColor: ['rgba(255, 99, 132, 0.2)'],
								borderColor: ['rgba(255, 159, 64, 1)'],
								borderWidth: 1
							}]
						},
						 options: {
							scales: {
								yAxes: [{
									ticks: {
										// Include a dollar sign in the ticks
										callback: function(value, index, values) {
											return value +'%';
										}
									}
								}]
							}
						}
					});
			
				
					
								
					var array_date_data = [0,0,0,0,0,0,0];
var array_times_data = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];			
	

	
	document.querySelector("#myDropdown").addEventListener("change",function(e){
		var first1 = 0;
	var last1 = 0;
	var flag1 = false;
	var temp_arr = [];
	var max_points =[];
	
	
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
						if (this.value == test_act[position1]){
						//console.log(max1);
						max_points.push(position1);
						temp_arr.push(test_meroi[position1]);
						map.addLayer(heatmapLayer);
						var heatmapcords = {data: temp_arr};
						}
				}
		
		}
		console.log(test_meroi);
		heatmapLayer.setData(heatmapcords);
		temp_arr = [];					

		for (var a=0; a < max_points.length; a++){
			var point = max_points[a];
			for (var b=0; b< test_ores.length; b++){
				if (max_points[a] == b){
					var timest = test_ores[b];
					var imera = moment.unix(timest).format("dddd");
					var timee = moment.unix(timest).format("HH");
					
					if (imera=="Monday"){
						array_date_data[0]++
					}
					else if (imera=="Tuesday"){
						array_date_data[1]++
					}
					else if (imera=="Wednesday"){
						array_date_data[2]++
					}
					else if (imera=="Thursday"){
						array_date_data[3]++
					}
					else if (imera=="Friday"){
						array_date_data[4]++
					}
					else if (imera=="Saturday"){
						array_date_data[5]++
					}
					else if (imera=="Sunday"){
						array_date_data[6]++
					}
					if (timee=="00"){
						array_times_data[0]++
					}
					else if (timee=="01"){
						array_times_data[1]++
					}
					else if (timee=="02"){
						array_times_data[2]++
					}
					else if (timee=="03"){
						array_times_data[3]++
					}
					else if (timee=="04"){
						array_times_data[4]++
					}
					else if (timee=="05"){
						array_times_data[5]++
					}
					else if (timee=="06"){
						array_times_data[6]++
					}
					else if (timee=="07"){
						array_times_data[7]++
					}else if (timee=="08"){
						array_times_data[8]++
					}else if (timee=="09"){
						array_times_data[9]++
					}else if (timee=="10"){
						array_times_data[10]++
					}else if (timee=="11"){
						array_times_data[11]++
					}else if (timee=="12"){
						array_times_data[12]++
					}else if (timee=="13"){
						array_times_data[13]++
					}else if (timee=="14"){
						array_times_data[14]++
					}else if (timee=="15"){
						array_times_data[15]++
					}else if (timee=="16"){
						array_times_data[16]++
					}else if (timee=="17"){
						array_times_data[17]++
					}else if (timee=="18"){
						array_times_data[18]++
					}else if (timee=="19"){
						array_times_data[19]++
					}else if (timee=="20"){
						array_times_data[20]++
					}else if (timee=="21"){
						array_times_data[21]++
					}else if (timee=="22"){
						array_times_data[22]++
					}else if (timee=="23"){
						array_times_data[23]++
					}
					
				}
			}
		}
		
		
				myChart2.data.datasets[0].data = array_date_data;
				myChart2.update();
				console.log(array_date_data); 
				
					myChart3.data.datasets[0].data= array_times_data;
				 myChart3.update();
				array_date_data = [0,0,0,0,0,0,0];	

				array_times_data = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];
	});		
		
		

		
				
	
		//GRAFIMA ME  HMERA ANA ARITHMO EIDON DRASTIRIOTIAS


	
		
	var ctx = document.getElementById('myChart2').getContext('2d');
	var myChart2 = new Chart(ctx, {
		type: 'bar',
		data: {
			labels: ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
			datasets: [{
				label: 'Dates / activity selected',
				data: array_date_data,
				backgroundColor: [
					'rgba(152, 159, 64, 0.2)'
				],
				borderColor: [
					'rgba(255, 159, 64, 1)'
				],
				borderWidth: 1
			}]
		},
		options: {
			
		}
	});
	 
	

	
	
	//GRAFIMA ME  ores ANA ARITHMO EIDON DRASTIRIOTIAS

	var ctx1 = document.getElementById('myChart3').getContext('2d');
	var myChart3 = new Chart(ctx1, {
		type: 'line',
		data: {
			labels: ["00:00","01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"],
			datasets: [{
				label: 'Hours / activity selected',
				data: array_times_data,
				fill:false,
				backgroundColor: [
					
				],
				borderColor: [
				
				],
				
			}]
		},
		options: {
			
		}
	});

var array_month_data = [];
var this_month = [0];
var today = moment.unix(Date.now()/1000).format("MMMM, YYYY");
var month_option = [];

document.querySelector("#monthdropdown").addEventListener("change",function(e){
	
	var proto = 0;
	var tel = 0;
	var flaga = false;		
	var solo_times =[];
	var solo_act = [];
	var abc = 0;
	for (var v = 0; v < test_act.length; v++) { 		
		if ( test_ores[v] == test_ores[v+1]){
			flaga = true;
		}else flaga = false;
		
		if (flaga===true){
			tel=tel+1;
		}
		
		if (flaga===false || v==test_act.length - 1){
			var max = -1;
			var	position = proto;                   
		
			for (var j=proto; j<=tel;j++){
				if (Number(test_conf[j])>=max){
					max = Number(test_conf[j]);
					position = j;
					
					
				}
				
			}
	
			solo_times.push(moment.unix(test_ores[position]/1000).format("MMMM, YYYY"));
			solo_act.push(test_act[position]);
			
			proto=tel+1;
			tel++;
		}	
	}
	console.log(solo_times);

	if (this.value == 'This month'){
		var today = moment.unix(Date.now()/1000).format("MMMM, YYYY");                         //EMFANISI OIKOLOGIKO SCORE GIA TON TORINO MINA
		for (var l = 0; l<solo_act.length; l++){
			if ( (solo_act[l] == "WALKING" || solo_act[l] == "RUNNING" || solo_act[l] == "ON_FOOT" || solo_act[l] == "ON_BICYCLE" ) ){
				
					if (solo_times[l]==today){
						console.log('test');
						this_month[0]++
					}
			
			}
		}
	
				this_month[0]=100*this_month[0]/solo_act.length;
				this_month[0] = this_month[0].toFixed(2);

		myChart4.data.datasets[0].data= this_month;
		myChart4.update();
	}
if (this.value == 'Last 12 months'){               																		   //EMFANISI OIKOLOGIKO SCORE GIA TOUS TELEFTEOUS 12 MINES ( APO TON TORINO)
	var today = new Date(moment.unix(Date.now()/1000).format("YYYY-MM-DD"));         												//PERNOYME TO TORINO TIMESTAMP K TO METRATEPOYME SE MORFI YYYY-MM-DD GIA NA IPOLOGISO TO MONTH RANGE
	var twelveMonthsAgo = new Date(moment().subtract(12, 'months').format('YYYY-MM-DD'));             							//PERNOYME TON MINA POY EINAI 12 MINES PRIN APO TON TORINO
	var month_range =[];                                          												 // to array auto tha periexi ola ta timestamps ton meron apo ton torino eos kai 12 mines prin 
	var getMonthArray = function(start, end) {                                             					 //
		var arr = new Array();                                          						   //
		var dt = new Date(start);                                          					   //
		while (dt <= end) {                                           						  //    TO FUNCTION POY IPOLOGIZI TO EUROS METAKSI TON MERON/TIMESTAMPS POY THELOYME
			arr.push(new Date(dt));                                            			 //
			dt.setDate(dt.getDate() + 1);                                             //
		}																			//
		return arr;                                           						  //
	}                                           									 //
																					//
	var monthArr = getMonthArray(twelveMonthsAgo, today);                           //        DILONOYME ANAMESA SE PIES TIMES THELEI NA IPOLOGISI TO EUROS

	
	

	for (var i = 0; i < monthArr.length; i++) {                     // Output toy array me olo to euros ton minon se morfi poy theloyme (MMMM, YYYY)
		
		 month_range.push(moment(monthArr[i]).format("MMMM, YYYY"));
	}
		
		$.each(month_range, function(i, el){
				if($.inArray(el, month_option) === -1) month_option.push(el);  //KSEXORIZI TOUS MONADIKOUS MINES KAI TA VAZI STO ARRAY MONTH_OPTIONS
			});
					console.log(month_range);		
			for (var i=0; i<month_option.length; i++){          //PRWTH FOR GIA TOUS MONADIKOUS MINES
				var add =0;            //ENAS ATHRISTIS
				for (var j=0; j<solo_times.length; j++){           //DEFTERI FOR GIA OLA TA "SOSTA" TIMESTAMPS TOU XRISTI
					if (  ( solo_times[j] == month_option[i] ) && ( solo_act[j] == "WALKING" || solo_act[j] == "RUNNING" || solo_act[j] == "ON_FOOT" || solo_act[j] == "ON_BICYCLE" )   ){
					  add++;
					
					}
				}
				  
				add=100*add/solo_act.length;
					add = add.toFixed(2);
				array_month_data.push(add);
			}
	
			myChart6.data.datasets[0].data = array_month_data;
				myChart6.update();
}
});


var ctx2 = document.getElementById('myChart4').getContext('2d');
	var myChart4 = new Chart(ctx2, {
		type: 'bar',
		data: {
			labels: [today],
			datasets: [{
				label: 'Ecological score this month',
				data:this_month,
				backgroundColor: [
					
				],
				borderColor: [
				
				],
				
			}]
		},
		 options: {
        scales: {
            yAxes: [{
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return value +'%';
                    }
                }
            }]
        }
    }
	});
	
	
	var ctx6 = document.getElementById('myChart6').getContext('2d');
	var myChart6 = new Chart(ctx6, {
		type: 'bar',
		data: {
			labels: month_option,
			datasets: [{
				label: 'Ecological score 12 past months',
				data:array_month_data,
				backgroundColor: [
					
				],
				borderColor: [
				
				],
				
			}]
		},
		 options: {
        scales: {
            yAxes: [{
                ticks: {
                    // Include a dollar sign in the ticks
                    callback: function(value, index, values) {
                        return value +'%';
                    }
                }
            }]
        }
    }
	});
	  //function poy emfanizi to epitheto ton user sintetmimena
 	function getInitials (name) {
			var parts = name.split(" ")                            
			var initials = ""
			for (var i = 1; i < parts.length; i++) {
			if (parts[i].length > 0 && parts[i] !== "") {
			  initials += parts[i][0]
			}
			}
		var res = name.replace(parts[1], initials);
  return res;
}
var users_to_chart = [];
		var score_to_chart = [];
	var sintetmimena_names = [];
	var users_eco = [];
$.getJSON("score.php", function (score) {

	var ids = [];
	var timestamps = [];
	var confidences = [];
	var type = [];
	var first = 0;
	var last = 0;
	var flag = false;
	var sosta_act = [];
	var sosta_times = [];
	var sosta_user = [];
	var uniq_user = [];

  
	for (var z = 0; z < score.length; z++) {                                                            
		var act_times = score[z].Activity_Timestamp;
		var act = score[z].Activity_Type;
		var conf = score[z].Activity_Confidence;
		var idz = score[z].username;
								
		ids.push(idz);
		timestamps.push(act_times);
		confidences.push(conf);
		type.push(act);		
											
	}
		for (var v = 0; v < type.length; v++) { 		
			if ( timestamps[v] == timestamps[v+1]){
				flag = true;
			}else flag = false;
			
			if (flag===true){
				last=last+1;
			}
			
			if (flag===false || v==type.length - 1){
				var max = -1;
				var	position = first;                   
			
				for (var j=first; j<=last;j++){
					if (Number(conf[j])>=max){
						max = Number(conf[j]);
						position = j;
						
						
					}
					
				}
		
				sosta_times.push(moment.unix(timestamps[position]/1000).format("MMMM YYYY"));
				sosta_act.push(type[position]);
				sosta_user.push(ids[position]);
				first=last+1;
				last++;
			}
												
		}
	//console.log(sosta_times);
	var lastmonth = moment().subtract(1, 'months').format('MMMM YYYY');
	//console.log(lastmonth);
	$.each(sosta_user, function(i, el){
				if($.inArray(el, uniq_user) === -1) uniq_user.push(el);  //KSEXORIZI TOUS MONADIKOUS users_ids KAI TA VAZI STO ARRAY uniq_user
			});
	for (var a=0; a<uniq_user.length; a++){
		
		var add = 0;
		var counter = 0;
		for (var j=0; j<sosta_user.length; j++){
			if (  (sosta_act[j] == "WALKING" || sosta_act[j] == "RUNNING" || sosta_act[j] == "ON_FOOT" || sosta_act[j] == "ON_BICYCLE") && (sosta_times[j] == lastmonth) && (sosta_user[j] == uniq_user[a]) ) {
				add++
				
			}
			if (sosta_user[j] == uniq_user[a]){
				counter++;
			}
			
			
		}
		
					add=100*add/counter;                    //POSOSTO KATHENOS XRISTO KSEXORISTA APOTHIKEVETE STO ARRAY USERS_ECO
					add = add.toFixed(2);                    // O CURRENT LOGED IN USER EINAI STO ARRAY curr_user
					users_eco.push(add);
	}
	var	logged_in_user = getInitials(curr_user[0]);
		
	 

		
		
			for (var k=0; k<uniq_user.length; k++){
				var name = uniq_user[k];
				var sosto_name = getInitials(name);                          //gemizi ton pinaka sintetmimena names me ta sintetmimena names ton user (xrisimopiontas tis function getInitials k replaceAt
				 var newArray = replaceAt(uniq_user, k, sosto_name);
				 	sintetmimena_names.push(newArray[k]);
			}
	
		
	
var armixed = sintetmimena_names.map(function (x, i) { 
                          return {name1:x, score : users_eco[i]}
                      });

				// sort by score
				armixed.sort(function (a, b) {
				  return b.score - a.score;
				});
	console.log(armixed);
		
	for (var m = 0; m < armixed.length; m++) {                                                            
		var name1 = armixed[m].name1;
		var score_obj = armixed[m].score;
		
								
		users_to_chart.push(name1);
		score_to_chart.push(score_obj);
			
											
	}
		for(var n =0; n<score_to_chart.length; n++){
			if (users_to_chart[n] == logged_in_user){
				var your_score_e = score_to_chart[n];
				var your_rank = score_to_chart.indexOf(your_score_e)+1;
			}
		}
	
	var index = 2;																				 // APO AUTO TO INDEX KAI META THA AFERESOUME  (TOP 3 [0] [1] [2] + USER [3])
	score_to_chart.splice(index + 1, score_to_chart.length - (index + 1) );             					// KOVOUME TA ELEMENTS META TO SCORE [3]
	users_to_chart.splice(index + 1, users_to_chart.length - (index + 1) );       				 // KOVOUME TA ELEMENTS META TO ONOMA [3]



		myChart7.data.datasets[0].data = score_to_chart;
		myChart7.update();
		
		
		document.getElementById("your_score").innerHTML = "   Your rank: " + your_rank + ", Your score: " + your_score_e;
		
		
});



				function array_move(arr, old_index, new_index) {
					if (new_index >= arr.length) {
						var k = new_index - arr.length + 1;
						while (k--) {
							arr.push(undefined);
						}
					}
					arr.splice(new_index, 0, arr.splice(old_index, 1)[0]);
					return arr; // for testing
				};

		function replaceAt(arrayz, index, valuez) {                                   // function poy kanei replace to fullname ton user se sintetmimeni morfi 
			var ret = arrayz.slice(0);
			ret[index] = valuez;
			return ret;
		}
	
	var ctx7 = document.getElementById('myChart7').getContext('2d');
	var myChart7 = new Chart(ctx7, {
		type: 'bar',
		data: {
			labels:users_to_chart,
			datasets: [{
				label: 'Top 3 eco-friendly users',
				data: score_to_chart,
				backgroundColor: [
					
				],
				borderColor: [
				
				],
				
			}]
		},
		 options: {
			scales: {
				yAxes: [{
					ticks: {
						// Include a dollar sign in the ticks
						callback: function(value, index, values) {
							return value +'%';
						}
					}
				}]
			}
		}
	});
	
	
	// IMEROMINIA TELEFTEOU UPLOAD + PERIODOS EGGRAFON
	
	
	$.getJSON("get_record_range.php", function (record_range) {
		var range_array = [];
	
		for (var x = 0; x < record_range.length; x++) {   
			var records = record_range[x].timestamp;
			var last_upload = record_range[0].last_upload;	
		
			range_array.push(records);
		}	
		
	document.getElementById("range_record").innerHTML = "   Last upload at: " + last_upload + " | Your record range is: " + range_array[0] + "-" +range_array[range_array.length-1] ;				
	});
	
	
	
				
</script>
   <script>
            function sticktothetop() {
        var window_top = $(window).scrollTop();
        var top = $('#stick-here').offset().top;
        if (window_top > top) {
            $('#stickstuff').addClass('stick');
            $('#stick-here').height($('#stickstuff').outerHeight());
        } else {
            $('#stickstuff').removeClass('stick');
            $('#stick-here').height(0);
        }
            }
            $(function() {
                $(window).scroll(sticktothetop);
                sticktothetop();
            });
    </script>		

</body>

</html>