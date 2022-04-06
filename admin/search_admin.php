<?php

				// pernoyme lats, longs kai timestamps apo vasi dedomenon ta metatrepoyme se readable morfi (mera,minas,xronos,ora) k to stelnoyme sto script gia na ta valoyme se heatmap
	include('functions.php');
	 $curr_user = $_SESSION['user']['username'];


			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$result = mysqli_query($conn, "SELECT timestamp, latitude, longtitude FROM json_data ");

			$data = array();

					ini_set('memory_limit', '-1');
				while ($row = mysqli_fetch_object($result))
					{
						array_push($data, $row);
					}

echo json_encode($data);




exit();





	?>
