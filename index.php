     <?php 
	include('functions.php');

	if (!isLoggedIn()) {
		$_SESSION['msg'] = "You must log in first";
		header('location: login.php');
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Homepage</title>
	<link rel="stylesheet" type="text/css" href="user_style.css">
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">  
	<link rel="stylesheet" href="https://unpkg.com/leaflet@1.3.4/dist/leaflet.css"/>	
    <style>
</style>
</head>
<body style="background-color: lightyellow;">
<!-- logged in user information -->
		<div class="profile_info">
				<?php  if (isset($_SESSION['user'])) : ?>
					<strong><?php echo $_SESSION['user']['username']; ?></strong>
            
					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
                        
                        <div class="logout">
						<a href="index.php?logout='1'">Logout</a></div>
					</small>

				<?php endif ?>
				
        </div>
			
        <div class="stats"> 
				<button id="drawPolygon">Draw</button>
				<button id="stopDraw">Finish Drawing</button>
				<button id="getData">Don't upload these locations</button>            
            <input type='button' value='View stats & history' class="stats2", onclick="document.location.href='main.php'" />
        </div>
    <div class="contents">
    <div id="map1"></div>
					
	<form method="post" id="myForm" autocomplete="off" enctype="multipart/form-data">
            <div class="row">
                <div class="x">
                    <label for="file">Choose a location history to upload (only .json files allowed) </label>    
                        <div class="form-group">
                            <input type="file" name="fileToUpload" id="fileToUpload" class="form-control">
                        </div>

                        <div class="form-group">
                            <button  type="button" id="upload" class="btn btn-success" > Upload </button>
                        </div>   
                        <div class="form-group">
                            <div id="result"></div> 
                   		</div>
                </div>
            </div>
						
          </form>
		  </div>	
		  <script src="https://unpkg.com/leaflet@1.3.4/dist/leaflet.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"> </script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"> </script>
	
					<script>
				
					//upload file script
									
				$(document).ready(function(){
					$("#upload").click(function(){
						var formData = new FormData(this.form);
						console.log(formData);
					   
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
							 var map = L.map('map1', {drawControl: true})
						//	map._initPathRoot();
						//	map._updatePathViewport();
							var osmUrl = "https://tile.openstreetmap.org/{z}/{x}/{y}.png";
							var osmAttrib =
							  'Map data © <a href="https://openstreetmap.org">OpenStreetMap</a> contributors';
							var osm = new L.TileLayer(osmUrl, { attribution: osmAttrib });
							map.addLayer(osm);
							map.setView([38.246242, 21.7350847],16);
							var polygonLayer = new L.LayerGroup();										
							map.addLayer(polygonLayer); // polygon layer					
											
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
</body>
</html>