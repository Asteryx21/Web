

<?php 
		


//                   apothikevoyme tis sintetagmenes ton gonion tou poligonoy se php friendly morfi
			
				    session_start();
										
						$sint = json_decode(($_POST['sint']), true);
						$apotel = call_user_func_array("array_merge",$sint);
					$_SESSION['topoth'] = $apotel; //stelnoyme tis gonies sto upload gia na gini eleggxos

?>
	