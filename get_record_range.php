<?php


include('functions.php');
	 $curr_user = $_SESSION['user']['id'];

	
			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$result = mysqli_query($conn, "SELECT json_data.timestamp, users.last_upload FROM json_data, users WHERE json_data.user_id='$curr_user' AND users.id = '$curr_user'");
			
			$xz = array();
					
					ini_set('memory_limit', '-1');
				while ($row = mysqli_fetch_object($result))
					{
						
						
						array_push($xz, $row);
						
					}

			//	echo json_encode($xz);
	
		
$times = json_decode(json_encode($xz), true);

foreach($times as $key => $value)
{
	
	//date_default_timezone_set("Europe/Athens");
  $times[$key]['timestamp'] = strftime("%d-%m-%Y",$value['timestamp']/1000);
  $times[$key]['last_upload'] = strftime("%d-%m-%Y",$value['last_upload']);
}

//$times = json_encode($times);
echo json_encode($times);
//var_dump ($times[0]);
//var_dump($times[count($times)-1]);

 

exit();
	
	



?>