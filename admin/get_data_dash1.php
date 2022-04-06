<?php	
	
	$conn = mysqli_connect("localhost", "root", "", "web_database");
			$result = mysqli_query($conn, "SELECT json_data.timestamp , users.username FROM json_data, users WHERE users.id=json_data.user_id");
			
			$xz = [];
					
					ini_set('memory_limit', '-1');
				while ($row = mysqli_fetch_object($result))
					{
						
						
						array_push($xz, $row);
						
					}

			 echo json_encode($xz);
	
		
exit();
		
		
?>