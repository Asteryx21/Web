<?php

   $hostName = "localhost";
   $user = "root";
   $password = "";
   $databaseName = "web_database";
   $connect = mysqli_connect($hostName,$user, $password, $databaseName);


//------------------------- Erotima - 4 (.csv) -----------------------------------/
   header('Content-Type: text/csv;');
   header('Content-Disposition: attacehment; filename=data.csv');
   $output = fopen("php://output", "w");
   $header_array = array('username', 'timestamp', 'latitude', 'longtitude', 'accuracy', 'heading', 'velocity', 'altitude', 'vertical_accuracy', 'Item_type', 'Activity_timestamp');
   fputcsv($output, array('username', 'timestamp', 'latitude', 'longtitude', 'accuracy', 'heading', 'velocity', 'altitude', 'vertical_accuracy', 'Item_type', 'Activity_timestamp'));

   $query = "SELECT * FROM json_data";
   $result = mysqli_query($connect, $query);
   while($row = mysqli_fetch_assoc($result)){
    //$records = array_keys($row);
    fputcsv($output, $row);
   }
   fclose($output);
?>
