<?php

   $hostName = "localhost";
   $user = "root";
   $password = "";
   $databaseName = "web_database";
   $connect = mysqli_connect($hostName,$user, $password, $databaseName);
   session_start();

//-------------------------------------------------------- Erotima - 4 (.xml) --------------------------------------------------------/

$counter = 0;
$export_array = [];
$flag=0;

   $xml = new DOMDocument();                                          //  Anoigo .xml arxeio kai
   $rootNode = $xml->appendChild($xml->createElement("history"));     //  vazo se ayto ton arxiko komvo

   if(array_key_exists('exp',$_SESSION) && !empty($_SESSION['exp'])){       //An yparxoun apotelesmata apo to Heatmap...

      $data_to_export = $_SESSION['exp'];             //...to array me tis syntetagmenes pou epistrefei to Heatmap
      $timestamps_to_export = $_SESSION['time'];      //...to array me ta timestamps ton apotelesmaton tou Heatmap

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

         foreach ($export_array as $array) {                                      //
            if (! empty($array)) {                                                //
              $itemNode = $rootNode->appendChild($xml->createElement('item'));    //
               foreach ($array as $k => $v) {                                     //
                  $itemNode->appendChild($xml->createElement($k, $v));            // Gemizo to 'Location data.xml'
               }                                                                  //
            }                                                                     //
         }                                                                        //
      }

   $export_file_name = 'Location data.xml';        // Mesa stin if katevazei mono ta
                                                   // apotelesmata tou Heatmap   (filename='Location data.xml')
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

      foreach ($export_array as $array) {                                     //
         if (! empty($array)) {                                               //
         $itemNode = $rootNode->appendChild($xml->createElement('item'));     //
            foreach ($array as $k => $v) {                                    //
               $itemNode->appendChild($xml->createElement($k, $v));           // Gemizo to 'Location data.xml'
            }                                                                 //
         }                                                                    //
      }

      $export_file_name = 'Database.xml';       // Mesa stin else katevazei oli tin vasi (filename='Database.xml')
   }

   // var_dump($export_array);

   $xml->formatOutput = true;
   $xml->save($export_file_name);

   header('Content-Description: File Transfer');
   header('Content-Type: application/xml');
   header('Content-Disposition: attachment; filename=' . basename($export_file_name));
   header('Content-Transfer-Encoding: binary');
   header('Expires: 0');
   header('Cache-Control: must-revalidate');
   header('Pragma: public');
   header('Content-Length: ' . filesize($export_file_name));
   ob_clean();
   flush();
   readfile($export_file_name);
   exec('rm ' . $export_file_name);

   $_SESSION['exp'] = [];     // Adeiazo to array toy session gia tin epomeni fora

?>
