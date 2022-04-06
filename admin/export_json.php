<?php

   $hostName = "localhost";
   $user = "root";
   $password = "";
   $databaseName = "web_database";
   $connect = mysqli_connect($hostName,$user, $password, $databaseName);
   session_start();

//-------------------------------------------------------- Erotima - 4 (.json) --------------------------------------------------------/

   $counter = 0;
   $export_array = [];
   $flag=0;

   if(array_key_exists('exp',$_SESSION) && !empty($_SESSION['exp'])){              //An yparxoun apotelesmata apo to Heatmap...
      $data_to_export = $_SESSION['exp'];          //...to array me tis syntetagmenes pou epistrefei to Heatmap
      $timestamps_to_export = $_SESSION['time'];   //...to array me ta timestamps ton apotelesmaton tou Heatmap

      //  var_dump($data_to_export);
      //  echo "Mesa sto 1o export\n";
      foreach($data_to_export as $item1){
         $lat=($item1['lat']*10000000);
         $lng=($item1['lng']*10000000);
         $timestamp = $timestamps_to_export[$counter];
         $counter++;

         $item = "SELECT json_data.timestamp, json_data.latitude, json_data.longtitude, json_data.accuracy, json_data.heading, json_data.velocity, json_data.altitude,
                  json_data.vertical_accuracy, json_data.Activity_timestamp, json_extra_data.Activity_Type, json_extra_data.Activity_Confidence,  json_extra_data.user_id
                  FROM json_data INNER JOIN json_extra_data ON json_data.timestamp = json_extra_data.timestamp
                  WHERE json_data.latitude=$lat AND json_data.longtitude=$lng AND (json_data.timestamp=$timestamp OR json_data.Activity_timestamp=$timestamp)";
         $query_item = mysqli_query($connect, $item);
         while ($row = mysqli_fetch_assoc($query_item)){
            $export_array[] = $row;
         }
      }
      header('Content-disposition: attachment; filename=Location data.json');      // Mesa stin if katevazei mono ta
      // apotelesmata tou Heatmap   (filename='Location data.json')
   }else{
      //
      //An DEN yparxoun apotelesmata apo to Heatmap...
      //

      $item = "SELECT json_data.timestamp, json_data.latitude, json_data.longtitude, json_data.accuracy, json_data.heading, json_data.velocity, json_data.altitude,
               json_data.vertical_accuracy, json_data.Activity_timestamp, json_extra_data.Activity_Type, json_extra_data.Activity_Confidence,  json_extra_data.user_id
               FROM json_data INNER JOIN json_extra_data ON json_data.timestamp = json_extra_data.timestamp";
      $query_item = mysqli_query($connect, $item);
      while ($row = mysqli_fetch_assoc($query_item)){
         $export_array[] = $row;
      }
      header('Content-disposition: attachment; filename=Database.json');        // Mesa stin else katevazei oli tin vasi (filename='Database.json')
   }
   //    var_dump($export_array);
   //  $export_array = json_encode( array($export_array));

   header('Content-type: application/json');
   print_r($export_array);

   $_SESSION['exp'] = [];     // Adeiazo to array toy session gia tin epomeni fora

?>
