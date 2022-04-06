<?php

				// pernoyme lats, longs kai timestamps apo vasi dedomenon ta metatrepoyme se readable morfi (mera,minas,xronos,ora) k to stelnoyme sto script gia na ta valoyme se heatmap
	include('functions.php');
	 $curr_user = $_SESSION['user']['id'];


			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$query = "SELECT json_data.timestamp, json_data.latitude, json_data.longtitude, users.username FROM json_data, users WHERE user_id='$curr_user' AND  users.id=json_data.user_id";
			$result = mysqli_query($conn, $query);

			$data = array();

					ini_set('memory_limit', '-1');
				while ($row = mysqli_fetch_object($result))
					{

						array_push($data, $row);

					}

$times = json_decode(json_encode($data), true);

foreach($times as $key => $value)
{

	date_default_timezone_set("Europe/Athens");
  $times[$key]['timestamp'] = strftime("%d/%m/%Y %H:%M",$value['timestamp']/1000);
}


//var_dump($times);
$times1=json_decode(json_encode($times), false);

echo json_encode($times1);

exit();





	?>
