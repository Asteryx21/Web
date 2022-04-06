


<html>
   <head>
      <link rel="stylesheet" type="text/css" href="dash_style.css">
      <br />
      <div class="container" style="width:900px;">
      <h2 class="Titlos">Database Dashboard</h2>
      <br />
     
   </head>

   <body>
      
      <div>
         <form class="button" onclick="post" action="home.php">
           <input type="submit" name="Home" value="Back to Home Page" />
         </form>
          <button onclick="ClearDB()" class="clear_db_btn">Clear Db</button>
      </div>

	  <div id="chartdiv" >
	<canvas id="myChart1" width="100" height="50"></canvas>
	<canvas id="myChart2" width="100" height="50"></canvas>
	<canvas id="myChart3" width="100" height="50"></canvas>
	<canvas id="myChart4" width="100" height="50"></canvas>
	<canvas id="myChart5" width="100" height="50"></canvas>
	<canvas id="myChart6" width="100" height="50"></canvas>
		  </div>
				<script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
      <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	  <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
	


	<script>
			function ClearDB() {
					var r = confirm("Do you want to clear the database?");
					if (r == true) {
						$.ajax({
							type: "POST",
							url: 'clearDatabase.php',
							success:function(result) {
								alert("Database cleared");
								$("#result").html(result);
							}
							});
						}
				}	
								
						var pin1 = [];	
					var pin2=[];
						var options = [];
					var data1x=[];						
						
	$.getJSON("get_data_dash.php", function (data) {
		var counter=0;
	  for (var i = 0; i < data.length; i++) {
		  counter++
		var times = data[i].Activity_Type;
		pin1.push(times);

		}
			$.each(pin1, function(i, el){
				if($.inArray(el, options) === -1) options.push(el);  //KSEXORIZI TA MONADIKA ACTIVITY KAI TA VAZI STO ARRAY OPTIONS
			});
			for (var x=0; x<options.length; x++){	
				var add = 0;
				for (var i=0; i<pin1.length; i++){
					if (pin1[i].includes(options[x])){     // ETIMAZI TON DATA PINAKA GIA TO GRAFIMA
						add++;
					}	
				}
					add=100*add/counter;
					add = add.toFixed(2);
					data1x.push(add);
				
					
			}	
				myChart4.data.datasets[0].data = data1x;
				myChart4.update();
	});
	
			var array_date_data = [0,0,0,0,0,0,0];
var array_times_data = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];	
var array_month_data = [0,0,0,0,0,0,0,0,0,0,0,0]	
	var xronia = [];
	var xroniaoptions =[];
	var array_year_data = [];
	var users_names = [];
	var pin3 = [];
	var array_users_data = [];

	$.getJSON("get_data_dash1.php", function (data) {
		var counter=0;
		
	  for (var i = 0; i < data.length; i++) {
		counter++;
		var times = data[i].timestamp;
		var names = data[i].username
		pin2.push(times);
		pin3.push(names);

		}
		for (var j = 0; j < data.length; j++){
		var imera = moment.unix(pin2[j]/1000).format("dddd");
		var timee = moment.unix(pin2[j]/1000).format("HH");
		var minas = moment.unix(pin2[j]/1000).format("MMMM");
		var xronos_timestamp = new Date(Number(pin2[j]));	
		var xronos = xronos_timestamp.getFullYear()	;	
		xronia.push(xronos);
		
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
					 if (minas =="January"){
					 array_month_data[0]++
						
					}else if (minas =="February"){
						array_month_data[1]++
					}else if (minas =="March"){
						array_month_data[2]++
					}else if (minas =="April"){
						array_month_data[3]++
					}else if (minas =="May"){
						array_month_data[4]++
					}else if (minas =="June"){
						array_month_data[5]++
					}else if (minas =="July"){
						array_month_data[6]++
					}else if (minas =="August"){
						array_month_data[7]++
					}else if (minas =="September"){
						array_month_data[8]++
					}else if (minas =="October"){
						array_month_data[9]++
					}else if (minas =="November"){
						array_month_data[10]++
					}else if (minas =="December"){
						array_month_data[11]++
					}
					
					

			

	}
				var arr_c=0
				var array_a=0;
				//var days=array_date_data.length;
				for(var a=0; a<7; a++){
					
					
					array_a = 100*(array_date_data[a])/counter;
					array_date_data[a] = array_a.toFixed(2);

				}
				
			//	var array_a=0;
				for(var a=0; a<12; a++){
					
					
					array_a = 100*(array_month_data[a])/counter;
					array_month_data[a] = array_a.toFixed(2);

				}
				
			//	var array_a=0;
				for(var a=0; a<24; a++){
					
					
					array_a = 100*(array_times_data[a])/counter;
					array_times_data[a] = array_a.toFixed(2);

				}
				
		
				
				
					
				$.each(xronia, function(i, el){
					if($.inArray(el, xroniaoptions) === -1) xroniaoptions.push(el);  //KSEXORIZI TA MONADIKA ACTIVITY KAI TA VAZI STO ARRAY xroniaoptions
				});
				
				
				for (var x=0; x<xroniaoptions.length; x++){	
				var addy = 0;
				for (var i=0; i<xronia.length; i++){
					if (xronia[i] == xroniaoptions[x]){     // ETIMAZI TON DATA PINAKA GIA TO GRAFIMA
						addy++;
					}	
				}
					addy=100*addy/counter;
					addy = addy.toFixed(2);
					array_year_data.push(addy);
			}
			
				$.each(pin3, function(i, el){
						if($.inArray(el, users_names) === -1) users_names.push(el);  //KSEXORIZI TA MONADIKA username KAI TA VAZI STO ARRAY users_names
					});
				
				for ( var n = 0; n<users_names.length; n++ ){
					var sin = 0;
					for ( var m=0; m<pin3.length; m++) {
						if (users_names[n] == pin3[m]) {
							sin ++ ;
						}
					}	
				array_users_data.push(sin);

				}

				myChart3.data.datasets[0].data = array_date_data;
				myChart3.update();
				
				myChart2.data.datasets[0].data = array_times_data;
				myChart2.update();
				
				myChart1.data.datasets[0].data = array_month_data;
				myChart1.update();
				
				myChart5.data.datasets[0].data = array_year_data;
				myChart5.update();

				myChart6.data.datasets[0].data = array_users_data;
				myChart6.update();

	});
		
	//GRAFIMA ANA USER				
var ctx6 = document.getElementById('myChart6').getContext('2d');
var myChart6 = new Chart(ctx6, {
	type: 'bar',
	data: {
		labels: users_names,
		datasets: [{
			label: 'Number of records / user',
			data:array_users_data,
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
							
	//GRAFIMA ANA MINA				
var ctx1 = document.getElementById('myChart1').getContext('2d');
var myChart1 = new Chart(ctx1, {
	type: 'bar',
	data: {
		labels: ["January","February","March","April","May","June","July","August","September","October","November","December"],
		datasets: [{
			label: 'Number of records / month',
			data:array_month_data,
			fill:false,
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

	//GRAFIMA ANA ORA
	var ctx2 = document.getElementById('myChart2').getContext('2d');
	var myChart2 = new Chart(ctx2, {
		type: 'line',
		data: {
			labels: ["00:00","01:00","02:00","03:00","04:00","05:00","06:00","07:00","08:00","09:00","10:00","11:00","12:00","13:00","14:00","15:00","16:00","17:00","18:00","19:00","20:00","21:00","22:00","23:00"],
			datasets: [{
				label: 'Number of records / hour',
				data: [1],
				fill:true,
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

//GRAFIMA ANA MERA
var ctx3 = document.getElementById('myChart3').getContext('2d');
	var myChart3 = new Chart(ctx3, {
		type: 'bar',
		data: {
			labels: ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],
			datasets: [{
				label: 'Number of records / days',
				data: array_date_data,
				backgroundColor: [
					
				],
				borderColor: [
				
				],
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
					// GRAFIMA ANA ACTIVITY
					var ctx4 = document.getElementById('myChart4').getContext('2d');
					var myChart4 = new Chart(ctx4, {
						type: 'bar',
						data: {
							labels: options,
							datasets: [{
								label: 'Number of records / activity',
								data: data1x,
								backgroundColor: [],
								borderColor: [],
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


// GRAFIMA ANA ETOS

var ctx5 = document.getElementById('myChart5').getContext('2d');
	var myChart5 = new Chart(ctx5, {
		type: 'bar',
		data: {
			labels: xroniaoptions,
			datasets: [{
				label: 'Number of records / year',
				data:array_year_data,
				fill:false,
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
									
							
		</script>



































   </body>
</html>


