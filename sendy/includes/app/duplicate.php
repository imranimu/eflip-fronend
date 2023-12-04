<?php include('../functions.php');?>
<?php include('../login/auth.php');?>
<?php 
	//------------------------------------------------------//
	//                      VARIABLES                       //
	//------------------------------------------------------//
	
	$campaign_id = isset($_POST['campaign_id']) && is_numeric($_POST['campaign_id']) ? mysqli_real_escape_string($mysqli, (int)$_POST['campaign_id']) : exit;
	$app_id = isset($_POST['on-brand']) && is_numeric($_POST['on-brand']) ? mysqli_real_escape_string($mysqli, (int)$_POST['on-brand']) : exit;
	
	//------------------------------------------------------//
	//                      FUNCTIONS                       //
	//------------------------------------------------------//
	
	//get campaign's data
	$q = 'SELECT from_name, from_email, reply_to, title, label, plain_text, html_text, query_string, bounce_setup, complaint_setup, opens_tracking, links_tracking, web_version_lang FROM campaigns WHERE id = '.$campaign_id;
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0)
	{
	    while($row = mysqli_fetch_array($r))
	    {
			$from_name = $row['from_name'];
			$from_email = $row['from_email'];
			$reply_to = $row['reply_to'];
			$title = stripslashes($row['title']);
			$label = stripslashes($row['label']);
			$plain_text = stripslashes($row['plain_text']);
			$html_text = stripslashes($row['html_text']);
			$query_string = stripslashes($row['query_string']);
			$bounce_setup = $row['bounce_setup'];
			$complaint_setup = $row['complaint_setup'];
			$opens_tracking = $row['opens_tracking'];
			$links_tracking = $row['links_tracking'];
			$web_version_lang = $row['web_version_lang'];
	    }  
	}
	
	//Insert into database
	$q3 = 'INSERT INTO campaigns (userID, app, from_name, from_email, reply_to, title, label, plain_text, html_text, query_string, bounce_setup, complaint_setup, wysiwyg, opens_tracking, links_tracking, web_version_lang) VALUES ('.get_app_info('main_userID').', '.$app_id.', "'.$from_name.'", "'.$from_email.'", "'.$reply_to.'", "'.addslashes($title).'", "'.addslashes($label).'", "'.addslashes($plain_text).'", "'.addslashes($html_text).'", "'.addslashes($query_string).'", '.$bounce_setup.', '.$complaint_setup.', 1, '.$opens_tracking.', '.$links_tracking.', "'.$web_version_lang.'")';
	$r3 = mysqli_query($mysqli, $q3);
	if ($r3)
	     header("Location: ".get_app_info('path')."/app?i=".$app_id);
	else
		echo 'Error duplicating.';
?>