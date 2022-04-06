<?php
	include('functions.php');
	 $curr_user = $_SESSION['user']['username'];
//pernei tis sintetagmenes apo ti vasi dedomenon k tis vazei sto xarti
	
			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$result = mysqli_query($conn, "SELECT latitude, longtitude FROM json_data");
			
			$data = array();
					
					
				while ($row = mysqli_fetch_object($result))
					{
						array_push($data, $row);
						
					}

				echo json_encode($data);
	
		?>	