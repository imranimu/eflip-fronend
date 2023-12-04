<?php 
	include('../functions.php');
	include('../login/auth.php');
	
	$id = is_numeric($_POST['id']) ? $_POST['id'] : exit;
	$enable = is_numeric($_POST['enable']) ? $_POST['enable'] : exit;
	
	$q = 'UPDATE rules SET enabled = '.$enable.' WHERE id = '.$id;
	$r = mysqli_query($mysqli, $q);
	if ($r) echo 'success';
	else echo 'failed';
?>