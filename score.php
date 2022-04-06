<?php

	include('functions.php');
	 $curr_user = $_SESSION['user']['id'];


	$conn = mysqli_connect("localhost", "root", "", "web_database");
	$query = "SELECT json_extra_data.* , users.username FROM json_extra_data, users WHERE users.id=json_extra_data.user_id";
	$result = mysqli_query($conn, $query);

	$data = [];

	ini_set('memory_limit', '-1');
	while ($row = mysqli_fetch_object($result))
	{

		array_push($data, $row);

	}

	echo json_encode($data);

	exit();

?>
