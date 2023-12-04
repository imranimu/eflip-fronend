<?php include('../functions.php');?>
<?php include('../login/auth.php');?>
<?php include('google-translate-languages.php');?>
<?php 
	//------------------------------------------------------//
	//                      	INIT                       //
	//------------------------------------------------------//
	
	$edit = isset($_GET['edit']) ? $_GET['edit'] : '';
	$campaign_id = isset($_GET['c']) && is_numeric($_GET['c']) ? mysqli_real_escape_string($mysqli, (int)$_GET['c']) : '';
	$campaign_title = addslashes(mysqli_real_escape_string($mysqli, $_POST['campaign_title']));
	$subject = addslashes(mysqli_real_escape_string($mysqli, $_POST['subject']));
	$from_name = mysqli_real_escape_string($mysqli, $_POST['from_name']);
	$from_email = mysqli_real_escape_string($mysqli, $_POST['from_email']);
	$reply_to = mysqli_real_escape_string($mysqli, $_POST['reply_to']);
	$plain = addslashes($_POST['plain']);
	$query_string = addslashes($_POST['query_string']);
	$html = stripslashes($_POST['html']);
	if(trim($html)=='<html><head></head><body></body></html>') $html = '';
	$filename = $_FILES['attachments']['name'];	
	$file = $_FILES['attachments']['tmp_name'];	
	$wysiwyg = isset($_POST['wysiwyg']) && is_numeric($_POST['wysiwyg']) ? mysqli_real_escape_string($mysqli, (int)$_POST['wysiwyg']) : 1;
	$w_clicked = isset($_POST['w_clicked']) ? $_POST['w_clicked'] : null;
	$track_opens = isset($_POST['opens']) ? $_POST['opens'] : 1;
	$track_clicks = isset($_POST['clicks']) ? $_POST['clicks'] : 1;
	$wysiwyg = $wysiwyg=='1' ? 1 : 0;
	$bounce_setup = 0;
	$complaint_setup = 0;
	$allow_attachments = 1;
	$save_only = 0;
	if(isset($_POST['save-only'])) $save_only = is_numeric($_POST['save-only']) ? $_POST['save-only'] : 0;
	$save_as_template = isset($_POST['save_as_template']) && $_POST['save_as_template']=='on' ? true : false;
	$check_for_broken_links = isset($_POST['check_for_broken_links']) && $_POST['check_for_broken_links']=='on' ? 0 : 1;
	$web_version_language = mysqli_real_escape_string($mysqli, $_POST['web_version_language']);
	$web_version_lang = $web_version_language == 'dont-translate' ? '' : $lang_array[$web_version_language].'/'.$web_version_language;
	
	//------------------------------------------------------//
	//                      FUNCTIONS                       //
	//------------------------------------------------------//
	
	//make attachments directory if it don't exist
	if(!file_exists("../../uploads/attachments")) mkdir("../../uploads/attachments", 0777); 
	
	//get allowed attachments
	$q = 'SELECT allowed_attachments FROM apps WHERE id = '.get_app_info('app');
	$r = mysqli_query($mysqli, $q);
	if ($r) while($row = mysqli_fetch_array($r)) $allowed = array_map('trim', explode(',', $row['allowed_attachments']));
	$allow_attachments = $row['allowed_attachments']='' ? 0 : 1;
		
	//Check for dummy unsubscribe URL, if so, replace it with [unsubscribe] tag
	preg_match_all('/href=["\']([^"\']+)["\']/i', $html, $matches, PREG_PATTERN_ORDER);
	$matches = array_unique($matches[1]);
	foreach($matches as $link)
	{
		if (strpos($link, "unsubscribe-success.php") !== false) {
			$html = str_replace($link, '[unsubscribe]', $html);
			break;
		}
	}
	
	if($edit)
	{
		$q = 'UPDATE campaigns SET from_name="'.$from_name.'", from_email="'.$from_email.'", reply_to="'.$reply_to.'", title="'.$subject.'", label="'.$campaign_title.'", plain_text="'.$plain.'", html_text="'.addslashes($html).'", query_string="'.$query_string.'", bounce_setup = '.$bounce_setup.', complaint_setup = '.$complaint_setup.', opens_tracking = '.$track_opens.', links_tracking = '.$track_clicks.', web_version_lang = "'.$web_version_lang.'", ignore_checks = '.$check_for_broken_links.' WHERE id='.$campaign_id;
		$r = mysqli_query($mysqli, $q);
		if ($r)
		{
			//Upload attachment(s)
			if($allow_attachments && $file[0]!='') //check if user uploaded any attachments
			{
				if(!file_exists("../../uploads/attachments/$campaign_id")) mkdir("../../uploads/attachments/$campaign_id", 0777);
				for($i=0;$i<count($file);$i++)
				{
					$extension_explode = explode('.', $filename[$i]);
					$extension = $extension_explode[count($extension_explode)-1];
					if(in_array(strtolower($extension), $allowed))
					{
						if(!move_uploaded_file($file[$i], "../../uploads/attachments/$campaign_id/".$filename[$i]))
						{
							show_error(_('Unable to upload attachment'), '<p>'._('Please ensure the /uploads/ folder permission is set to 777.').'</p>');
							exit;
						}
					}	
				}
			}
		}
		else
		{
			show_error(_('Unable to save campaign'), '<p>'.mysqli_error($mysqli).'</p><p>Or check <a href="https://sendy.co/troubleshooting#403-forbidden-error-when-clicking-save-and-next">https://sendy.co/troubleshooting#403-forbidden-error-when-clicking-save-and-next</a> as you may have "mod_security" enabled on your server.</p>');
			exit;
		}
	}
	else
	{
		//Check if 'From email's bounces/complaints 'Notifications' are set previously
		$q = 'SELECT bounce_setup, complaint_setup FROM campaigns WHERE from_email = "'.$from_email.'" AND bounce_setup=1 AND complaint_setup=1';
		$r = mysqli_query($mysqli, $q);
		if ($r && mysqli_num_rows($r) > 0) 
		{
			$bounce_setup = 1;
			$complaint_setup = 1;
		}
	
		//Insert into campaigns
		$q = 'INSERT INTO campaigns (userID, app, from_name, from_email, reply_to, title, label, plain_text, html_text, query_string, wysiwyg, bounce_setup, complaint_setup, opens_tracking, links_tracking, web_version_lang, ignore_checks) VALUES ('.get_app_info('main_userID').', '.get_app_info('app').', "'.$from_name.'", "'.$from_email.'", "'.$reply_to.'", "'.$subject.'", "'.$campaign_title.'", "'.$plain.'", "'.addslashes($html).'", "'.$query_string.'", '.$wysiwyg.', '.$bounce_setup.', '.$complaint_setup.', '.$track_opens.', '.$track_clicks.', "'.$web_version_lang.'", '.$check_for_broken_links.')';
		$r = mysqli_query($mysqli, $q);
		if ($r)
		{
			//get the campaign id from the new insert
		    $campaign_id = mysqli_insert_id($mysqli);
		    
		    //Upload attachment(s)
			if($allow_attachments && $file[0]!='') //check if user uploaded allowed attachments
			{
				if(!file_exists("../../uploads/attachments/$campaign_id")) mkdir("../../uploads/attachments/$campaign_id", 0777);
				for($i=0;$i<count($file);$i++)
				{
					$extension_explode = explode('.', $filename[$i]);
					$extension = $extension_explode[count($extension_explode)-1];
					if(in_array(strtolower($extension), $allowed))
					{
						if(!move_uploaded_file($file[$i], "../../uploads/attachments/$campaign_id/".$filename[$i]))
						{
							show_error(_('Unable to upload attachment'), '<p>'._('Please ensure the /uploads/ folder permission is set to 777.').'</p>');
							exit;
						}
					}
				}
			}
		}
		else
		{
			show_error(_('Unable to create campaign'), '<p>'.mysqli_error($mysqli).'</p><p>Or check <a href="https://sendy.co/troubleshooting#403-forbidden-error-when-clicking-save-and-next">https://sendy.co/troubleshooting#403-forbidden-error-when-clicking-save-and-next</a> as you may have "mod_security" enabled on your server.</p>');
			exit;
		}
	}
	
	//Check if user wants to save email campaign as a template too
	if($save_as_template)
	{
		$q = 'INSERT INTO template (userID, app, template_name, html_text, plain_text) VALUES ('.get_app_info('main_userID').', '.get_app_info('app').', "'.$subject.'", "'.addslashes($html).'", "'.$plain.'")';
		$r = mysqli_query($mysqli, $q);
		if (!$r)
		{
		    show_error(_('Unable to create template'), '<p>'.mysqli_error($mysqli).'</p>', true);
			exit;
		}
	}
	
	//After saving, redirect to appropriate page
	if($w_clicked || $save_only)
		header('Location: '.get_app_info('path').'/edit?i='.get_app_info('app').'&c='.$campaign_id);
	else
		header('Location: '.get_app_info('path').'/send-to?i='.get_app_info('app').'&c='.$campaign_id);
?>