<?php include('../functions.php');?>
<?php include('../login/auth.php');?>
<?php
	
//variables
$campaign_id = (int)mysqli_real_escape_string($mysqli, $_POST['cid']);

//Dismiss broken link checks
$q = 'UPDATE campaigns SET ignore_checks = 1 WHERE id = '.$campaign_id;
$r = mysqli_query($mysqli, $q);
if ($r)
{
	echo true;
}
else
{
	echo false;
	error_log("[Unable to set ignore_checks to 1]".mysqli_error($mysqli).': in '.__FILE__.' on line '.__LINE__);
}
?>