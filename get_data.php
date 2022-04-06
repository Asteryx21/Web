<?php
	include('functions.php');
	 $curr_user = $_SESSION['user']['id'];
//pernei tis sintetagmenes apo ti vasi dedomenon k tis vazei sto xarti

			$conn = mysqli_connect("localhost", "root", "", "web_database");
			$query = "SELECT latitude, longtitude FROM json_data WHERE user_id='$curr_user'";
			$result = mysqli_query($conn, $query);

			$data = array();

				while ($row = mysqli_fetch_object($result))
					{
						array_push($data, $row);

					}
				echo json_encode($data);

		?>
