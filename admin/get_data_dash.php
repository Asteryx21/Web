<?php
			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$result = mysqli_query($conn, "SELECT Activity_Type  FROM json_extra_data");
			
			$xz = array();
					
					ini_set('memory_limit', '-1');
				while ($row = mysqli_fetch_object($result))
					{
						
						
						array_push($xz, $row);
						
					}

			 echo json_encode($xz);
	
		
exit();
		
		
?>
