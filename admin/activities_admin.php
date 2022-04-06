<?php

include('functions.php');
 $curr_user = $_SESSION['user']['username'];

	
			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$result = mysqli_query($conn, "SELECT json_data.latitude , json_data.longtitude , json_extra_data.Activity_Timestamp , json_extra_data.Activity_Type , json_extra_data.Activity_Confidence FROM json_data, json_extra_data WHERE json_data.timestamp=json_extra_data.timestamp");
			
			$xz = array();
					
					ini_set('memory_limit', '-1');
				while ($row = mysqli_fetch_object($result))
					{
						
						
						array_push($xz, $row);
						
					}

			 echo json_encode($xz);
	
		
exit();



?>