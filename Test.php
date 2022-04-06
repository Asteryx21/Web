<!DOCTYPE html>
<html>
	<head>
		<h1>My First PHP Page</h1>
	</head>
	<body>
<?php

	$hostName = "localhost";
	$user = "root";
	$password = "";
	$databaseName = "web_database";
	$mysqli = mysqli_connect($hostName,$user, $password, $databaseName);


//	$filename_path = "arxeia json/setA.json";
//	$filename_path = "arxeia json/setA - Copy.json";
//	$filename_path = "arxeia json/setA - Copy2.json";
	$filename_path = "arxeia json/setA - Copy3.json";
	$data = file_get_contents($filename_path, true); 	//read json file --> into $data
	$array = json_decode($data, true); 		//Convert JSON string into PHP array format

//	echo "<pre>";					$filename, true
//	print_r($array);

	$counter = 0;
	foreach($array as $row){ //Extract the Array Values by using Foreach Loop
		foreach ($row as $item){ //Deytero loop gia ta stoixeia tou $row


//----------Aplo Antikeimeno----------------------------------------------

			if (count($item) == 4){

				echo "\nSimple Item ------->Akyro, ola good ", "<br/>";

				$Simple_Timestamp = $item["timestampMs"];
				$Simple_Latitude = $item["latitudeE7"];
				$Simple_Longitude = $item["longitudeE7"];
				$Simple_Acuracy = $item["accuracy"];

				echo "Timestamp:\t", $Simple_Timestamp, "<br/>";
				echo "Latitude:\t", $Simple_Latitude, "<br/>";
				echo "Longitude:\t", $Simple_Longitude, "<br/>";
				echo "Acuracy:\t", $Simple_Acuracy, "<br/>";

				$sql = "INSERT INTO json_data (timestamp, latitude, longtitude, accuracy, Item_Type)
				VALUES ($Simple_Timestamp, $Simple_Latitude, $Simple_Longitude, $Simple_Acuracy, 'Simple Item')";
				if ($mysqli->query($sql) === TRUE){
					echo "New record created successfully";
					echo "<br/>";
					echo "<br/>";
				} else {
					echo "Error: " . $sql . "<br>" . $mysqli->error;
				}
			}



//----------Syntheto Antikeimeno----------------------------

			if (count($item) == 5){
//				echo "<pre>";
//				print_r($item);

				echo "<br/>";
				echo "\n\nComplex Item ------->Pousthdes mas exete gamhsei<br/>";

				$Complex_Timestamp = $item["timestampMs"];
				$Complex_Latitude = $item["latitudeE7"];
				$Complex_Longitude = $item["longitudeE7"];
				$Complex_Acuracy = $item["accuracy"];
				$Activity = $item["activity"];

				echo "Complex_Timestamp:\t", $Complex_Timestamp, "<br/>";
				echo "Complex_Latitude:\t", $Complex_Latitude, "<br/>";
				echo "Complex_Longitude:\t", $Complex_Longitude, "<br/>";
				echo "Complex_Acuracy:\t", $Complex_Acuracy, "<br/>";

				$sql = "INSERT INTO json_data (timestamp, latitude, longtitude, accuracy, Item_Type)
				VALUES ($Complex_Timestamp, $Complex_Latitude, $Complex_Longitude, $Complex_Acuracy, 'Complex Item')";

				if ($mysqli->query($sql) === TRUE){
					echo "New record created successfully";
					echo "<br/>";
					echo "<br/>";
				} else {
					echo "Error: " . $sql . "<br>" . $mysqli->error;
				}



				//______________________Αποθηκεύω στοιχεία του [activity]____________________________________________

				foreach ($item["activity"] as $item_activity){

					$Activity_Timestamp = $item_activity["timestampMs"];
					echo "Acticity_Timestamp:\t",$Activity_Timestamp, "<br/>";

					$num_of_activities = count($item["activity"]);
					foreach ($item_activity["activity"] as $activity){  // Mpainw mesa sto kathe [activity]

						echo "<br/>";
						echo $num_of_activities;
						echo "<br/>";

						$Activity = $activity["type"];
						$Activity_Type = ((string) $Activity);
						$Activity_Confidence = $activity["confidence"];
						echo "Activity Type:", $Activity_Type, "<br/>";
						echo "Activity Confidence:", $Activity_Confidence, "<br/>";

						$sql = "INSERT INTO json_extra_data (Activity_Timestamp, Activity_Type, Activity_Confidence)
						VALUES ($Activity_Timestamp, '$Activity_Type', $Activity_Confidence)";

						if ($mysqli->query($sql) === TRUE){
							echo "New record created successfully";
							echo "<br/>";
							echo "<br/>";
						} else {
							echo "Error: " . $sql . "<br>" . $mysqli->error;
						}

					}

				}

			}
		}
	}

?>

	</body>
</html>
