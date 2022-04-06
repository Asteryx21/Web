<?php

   $hostName = "localhost";
   $user = "root";
   $password = "";
   $databaseName = "web_database";
   $connect = mysqli_connect($hostName,$user, $password, $databaseName);
   session_start();

//-------------------------------------------------------- Erotima - 4 (.csv) --------------------------------------------------------/

   $export_array = [];
   $counter = 0;


   if(array_key_exists('exp',$_SESSION) && !empty($_SESSION['exp'])){              //An yparxoun apotelesmata apo to Heatmap...
      header('Content-Type: text/csv;');
      header('Content-Disposition: attacehment; filename=Location data.csv');       // Mesa stin if katevazei mono ta
                                                                                    // apotelesmata tou Heatmap   (filename='Location data.csv')
      $export_array = fopen("php://output", "w");
      $header_array = array('timestamp', 'latitude', 'longtitude', 'accuracy', 'heading', 'velocity', 'altitude', 'vertical_accuracy', 'Activity_timestamp', 'Activity_Type','Activity_Confidence', 'User ID');
      fputcsv($export_array, array('timestamp', 'latitude', 'longtitude', 'accuracy', 'heading', 'velocity', 'altitude', 'vertical_accuracy', 'Activity_timestamp', 'Activity_Type','Activity_Confidence', 'User ID'));

      $data_to_export = $_SESSION['exp'];          //...to array me tis syntetagmenes pou epistrefei to Heatmap
      $timestamps_to_export = $_SESSION['time'];   //...to array me ta timestamps ton apotelesmaton tou Heatmap

      foreach($data_to_export as $item1){
         $lat=($item1['lat']*10000000);
         $lng=($item1['lng']*10000000);
         $timestamp = $timestamps_to_export[$counter];
         $counter++;

         $item = "SELECT json_data.timestamp, json_data.latitude, json_data.longtitude, json_data.accuracy, json_data.heading, json_data.velocity, json_data.altitude, json_data.vertical_accuracy, json_data.Activity_timestamp, json_extra_data.Activity_Type, json_extra_data.Activity_Confidence,  json_extra_data.user_id
                  FROM json_data INNER JOIN json_extra_data ON json_data.timestamp = json_extra_data.timestamp
                  WHERE json_data.latitude=$lat AND json_data.longtitude=$lng AND (json_data.timestamp=$timestamp OR json_data.Activity_timestamp=$timestamp)";
         $query_item = mysqli_query($connect, $item);
         while($row = mysqli_fetch_assoc($query_item)){
            //$records = array_keys($row);
            fputcsv($export_array, $row);
         }
      }
   }else{
      //
      //An DEN yparxoun apotelesmata apo to Heatmap...
      //

      header('Content-Type: text/csv;');
      header('Content-Disposition: attacehment; filename=Database.csv');       // Mesa stin else katevazei oli tin vasi (filename='Database.csv')
      $export_array = fopen("php://output", "w");
      $header_array = array('timestamp', 'latitude', 'longtitude', 'accuracy', 'heading', 'velocity', 'altitude', 'vertical_accuracy', 'Activity_timestamp', 'Activity_Type','Activity_Confidence', 'User ID');
      fputcsv($export_array, array('timestamp', 'latitude', 'longtitude', 'accuracy', 'heading', 'velocity', 'altitude', 'vertical_accuracy', 'Activity_timestamp', 'Activity_Type','Activity_Confidence', 'User ID'));


      $item = "SELECT json_data.timestamp, json_data.latitude, json_data.longtitude, json_data.accuracy, json_data.heading, json_data.velocity, json_data.altitude,
              json_data.vertical_accuracy, json_data.Activity_timestamp, json_extra_data.Activity_Type,
              json_extra_data.Activity_Confidence,  json_extra_data.user_id
               FROM json_data INNER JOIN json_extra_data ON json_data.timestamp = json_extra_data.timestamp";
      $query_item = mysqli_query($connect, $item);
      while ($row = mysqli_fetch_assoc($query_item)){
         fputcsv($export_array, $row);
      }
   }

   fclose($export_array);
   $_SESSION['exp'] = [];     // Adeiazo to array toy session gia tin epomeni fora

?>
