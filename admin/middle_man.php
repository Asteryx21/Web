<?php
  session_start();

  $sint = json_decode(($_POST['poligono']), true);
  $time = $_POST['timestamp'];
  $_SESSION['exp'] = $sint;
  $_SESSION['time'] = $time;

?>
