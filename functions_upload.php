<?php

session_start();

function alert($msg) {
    echo "<script type='text/javascript'>alert('$msg');</script>";
}

function haversineGreatCircleDistance($latitudeFrom, $longitudeFrom, $latitudeTo, $longitudeTo, $earthRadius = 6371000){
   // convert from degrees to radians
   $latFrom = deg2rad($latitudeFrom);
   $lonFrom = deg2rad($longitudeFrom);
   $latTo = deg2rad($latitudeTo);
   $lonTo = deg2rad($longitudeTo);

   $latDelta = $latTo - $latFrom;
   $lonDelta = $lonTo - $lonFrom;

   $angle = 2 * asin(sqrt(pow(sin($latDelta / 2), 2) +
   cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2)));
   return $angle * $earthRadius;
}

function is_in_polygon($points_polygon, $vertices_x, $vertices_y, $longitude_x, $latitude_y){
   $i = $j = $c = 0;
   for ($i = 0, $j = $points_polygon ; $i < $points_polygon; $j = $i++) {
      if ( (($vertices_y[$i]  >  $latitude_y != ($vertices_y[$j] > $latitude_y)) &&
       ($longitude_x < ($vertices_x[$j] - $vertices_x[$i]) * ($latitude_y - $vertices_y[$i]) / ($vertices_y[$j] - $vertices_y[$i]) + $vertices_x[$i]) ) )
         $c = !$c;
      }
   return $c;
}
?>
