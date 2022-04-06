<?php

ini_set('memory_limit', '-1');
include('functions_upload.php');

$hostName = "localhost";
$user = "root";
$password = "";
$databaseName = "web_database";
$mysqli = mysqli_connect($hostName,$user, $password, $databaseName);

$curr_user = $_SESSION['user']['id'];     //  krataw to 'id' tou xristi gia to session
$last_upload = time();




$activity_tbl = [];
$timestamp_tbl = [];
$confidence_tbl = [];
$tbls_count=0;


//require('composer.json');
//require_once 'vendor/autoload.php'


//----------------------------- sinartisi gia ta poligona/paralilograma


//$polygon_flag == false;


if(isset($_POST)) {
  $target_dir = "uploads/";
	$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
	$uploadOk = 1;
	$jsonFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if(empty($_SESSION['topoth']) == false){  //An yparxei polygono fiakse...
    $result = $_SESSION['topoth'];         //...to array me tis gonies ton poligonon poy pernoyme apo to cords.php
  }






  $last_upload = time();



   //$cpolygon = count($result);
   /*
	// Elegxoume ama iparxi file me idio onoma
	if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}
   */

	// Elegxoume megethos tou file
   if ($_FILES["fileToUpload"]["size"] > 5000000000000) {
		echo "Sorry, your file is too large.";
		$uploadOk = 0;
	}

	// Epitrepoyme na anevasei mono json files
	if($jsonFileType != "json") {
		echo "Sorry, only JSON files are allowed,";
		$uploadOk = 0;
	}

	// error chekcer. $uploadOk is set to 0 if error happens
	if ($uploadOk == 0) {
		echo "your file was not uploaded.";

	//--------------if everything is ok, try to upload file and parse into Database-------------------------
   } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
         $hostName = "localhost";
         $user = "root";
         $password = "";
         $databaseName = "web_database";
         $mysqli = mysqli_connect($hostName,$user, $password, $databaseName);
         $filename_path = "$target_file";

         $data = file_get_contents($filename_path, true);         //read json file --> into $data
         $array = json_decode($data, true);                     //Convert JSON string into PHP array format

         $JSON = file_get_contents($filename_path);
/*         $array = new RecursiveIteratorIterator(
                  new RecursiveArrayIterator(json_decode($data, TRUE)),
                  RecursiveIteratorIterator::SELF_FIRST);
*/



         //$array = \JsonMachine\JsonMachine::fromFile($data);      //Convert JSON string into PHP array format


        // $last_upload = time();
        // $sql = "INSERT INTO users(last_upload) VALUES ($last_upload) WHERE username = '$curr_user'";




         foreach($array as $row){            //Extract the Array Values by using Foreach Loop
            foreach ($row as $item){         //Deytero loop gia kathe 'Item'

                $Timestamp = $item["timestampMs"];
              	$Latitude = $item["latitudeE7"];
              	$Longitude = $item["longitudeE7"];
              	$Acuracy = $item["accuracy"];

               $center_lat = 38.230462;
               $center_lng = 21.753150;
               $lat = $item["latitudeE7"]/10000000;
               $lng = $item["longitudeE7"]/10000000;
               $distance = haversineGreatCircleDistance($center_lat, $center_lng, $lat, $lng, 6371);


               $check = 0; // enas arithmos p elegxi ama h sinartisi poligonon einai TRUE h FALSE ( 1 i 0)
            //   if($cpolygon != 0){
               if(empty($_SESSION['topoth']) == false){     // An yparxei polygono kane elegxo
                  foreach ($result as $result1){
                        $lat_arr=[];
                        $long_arr=[];

                        foreach($result1 as $result2){
                           $x1 = $result2['lat'];
                           $x2 = $result2['lng'];
                           array_push($lat_arr,$x1);
                           array_push($long_arr,$x2);
                        }
                           //	var_dump($result1);
                           $vertices_x = $lat_arr;    // latitudes ton gonion tou paralilogramoy
                           $vertices_y = $long_arr; // longtitudes ton gonion tou paralilogramou
                           $points_polygon = count($vertices_x) - 1;  // arithmos ton gonion (array)
                           $longitude_x = $item["latitudeE7"]/10000000; // x-coordinate of the point to test
                           $latitude_y = $item["longitudeE7"]/10000000;  // y-coordinate of the point to test
                           $test = is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y) ;
                        if ($test) {
                        // AMA I SINARTISI POLIGONON EINAI TRUE DILADI TO SIMIO EINAI MESA STO POLINO AFKSANOYME TON ARITHMO CHECK

                        $check++;
                        }

                  }
               }else{      //An den yparxei kane to $check 0
                  $check = 0;
               }


               //----------Syntheto Antikeimeno----------------------------
               $Heading = 'Null';
               if(array_key_exists('heading',$item)){
                  $Heading = $item["heading"];
               }

               $Velocity = 'Null';
               if(array_key_exists('velocity',$item)){
                  $Velocity = $item["velocity"];
               }

               $Altitude = 'Null';
               if(array_key_exists('altitude',$item)){
                  $Altitude = $item["altitude"];
               }

               $Vertical_Accuracy = 'Null';
               if(array_key_exists('verticalAccuracy',$item)){
                  $Vertical_Accuracy = $item["verticalAccuracy"];
               }


           		//______________________Apothikeyo ta stoixeia tou [activity]____________________________________________
               if($distance <= 10 && $check == 0 ){
                  if(array_key_exists('activity',$item)){
                 		foreach ($item["activity"] as $item_activity){
                 			$Activity_Timestamp = $item_activity["timestampMs"];
                 			foreach ($item_activity["activity"] as $activity){     // Mpainw mesa sto kathe [activity]

                 				$Activity = $activity["type"];
                 				$Activity_Type = ((string) $Activity);
                 				$Activity_Confidence = $activity["confidence"];

                        $tbls_count++;
                        $activity_tbl[$tbls_count] = $Activity;
                        $timestamp_tbl[$tbls_count] = $Timestamp;
                        $confidence_tbl[$tbls_count] = $Activity_Confidence;

                 				$sql = "INSERT INTO json_extra_data (timestamp, Activity_Timestamp, Activity_Type, Activity_Confidence, user_id)
                 				VALUES ($Timestamp, $Activity_Timestamp, '$Activity_Type', $Activity_Confidence, '$curr_user')";
                           if ($mysqli->query($sql) === TRUE){
                       	      true;
                       		} else {
                 					echo "Error: " . $sql . "<br>" . $mysqli->error;
                 				}

                 			}
                 		}

                     $sql = "INSERT INTO json_data (timestamp, latitude, longtitude, accuracy, heading, velocity, altitude, vertical_accuracy, Activity_timestamp, user_id)
                     VALUES ($Timestamp, $Latitude, $Longitude, $Acuracy, $Heading, $Velocity, $Altitude, $Vertical_Accuracy, $Activity_Timestamp, '$curr_user')";


                     if ($mysqli->query($sql) === TRUE){
                        true;
                     } else {
                        echo "<br/>";
                        echo "Error: " . $sql . "<br>" . $mysqli->error;
                        echo "<br/>";
                     }

                  }else{



                     $sql = "INSERT INTO json_data (timestamp, latitude, longtitude, accuracy, heading, velocity, altitude, vertical_accuracy, user_id)
                 		 VALUES ($Timestamp, $Latitude, $Longitude, $Acuracy, $Heading, $Velocity, $Altitude, $Vertical_Accuracy, '$curr_user')";

                  //   $last_upload = time();
                  //   $sql = "UPDATE users SET last_upload = $last_upload WHERE username = '$curr_user'";

//------- ypologizoume score Oikologikis metakinisis-------------------


                    if ($mysqli->query($sql) === TRUE){
                 	      true;
                 		} else {
                        echo "<br/>";
                 			echo "Error: " . $sql . "<br>" . $mysqli->error;
                        echo "<br/>";
                 		}
                  }
               } //if($distance <= 10)
               $check=0;	// ksanamidenizoyme to check gia to epomeno lat kai long tou json
            }  //foreach
            $check=0;	// ksanamidenizoyme to check gia to epomeno lat kai long tou json
         }  //foreach

         $timestamp_activities = [];

         $numOfItems= count($activity_tbl);
         $flag=false;
         $last=0;
         $first=0;
         $max=0;
         $position=0;

         for($i=0; $i<$numOfItems;){
            if($timestamp_tbl[$i] = $timestamp_tbl[$i+1]){
              $flag = true;
            }

            if($flag){
              $last++;
            }elseif ($i=$numOfItems-1) {
              $max = -1;
              $position = $first;

              for($j=$first; $j<=$last; $j++){
                if($confidence_tbl[$j]>=$max){
                  $max = $confidence_tbl[$j];
                  $position = $j;
                }

              }

              array_push($timestamp_activities, $activity_tbl[$position]);

              $first=$last+1;
              $last++;
            }

            $i++;
         }
         $sum=0;
         foreach($timestamp_activities as $act){
           if($act="ON_FOOT"||$act="WALKING"||$act="ON_BICYCLE"){
             $sum++;
           }
         }
// 				 $score=$sum/count($timestamp_activities);

//var_dump($timestamp_activities);


        $sql = "UPDATE users SET last_upload='$last_upload' WHERE id='$curr_user'";
        if ($mysqli->query($sql) === TRUE)
        {
          true;
        }
        else {
          echo "fail";
        }
         echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
         alert("The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.");
      } else {
      	echo "Sorry, there was an error uploading your file.";
      }
   }  //else
}
?>
