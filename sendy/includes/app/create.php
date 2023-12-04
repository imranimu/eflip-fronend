<?php include('../functions.php');?>
<?php include('../login/auth.php');?>
<?php 
	//------------------------------------------------------//
	//                      VARIABLES                       //
	//------------------------------------------------------//
	
	//Only allow script to continue if it's the main admin user generating a password for a brand
	if(get_app_info('is_sub_user')) exit;
	
	//Init
	$app_name = mysqli_real_escape_string($mysqli, $_POST['app_name']);
	$from_name = mysqli_real_escape_string($mysqli, $_POST['from_name']);
	$from_email = mysqli_real_escape_string($mysqli, $_POST['from_email']);
	$login_email = mysqli_real_escape_string($mysqli, $_POST['login_email']);
	$reply_to = mysqli_real_escape_string($mysqli, $_POST['reply_to']);
	$allowed_attachments = mysqli_real_escape_string($mysqli, $_POST['allowed_attachments']);
	$currency = mysqli_real_escape_string($mysqli, $_POST['currency']);
	$delivery_fee = mysqli_real_escape_string($mysqli, $_POST['delivery_fee']);
	$cost_per_recipient = mysqli_real_escape_string($mysqli, $_POST['cost_per_recipient']);
	$password = mysqli_real_escape_string($mysqli, $_POST['pass']);
	$pass_encrypted = hash('sha512', $password.'PectGtma');
	$smtp_host = mysqli_real_escape_string($mysqli, $_POST['smtp_host']);
	$smtp_port = mysqli_real_escape_string($mysqli, $_POST['smtp_port']);
	$smtp_ssl = mysqli_real_escape_string($mysqli, $_POST['smtp_ssl']);
	$smtp_username = mysqli_real_escape_string($mysqli, $_POST['smtp_username']);
	$smtp_password = mysqli_real_escape_string($mysqli, $_POST['smtp_password']);
	$language = mysqli_real_escape_string($mysqli, $_POST['language']);
	$choose_limit = mysqli_real_escape_string($mysqli, $_POST['choose-limit']);
	$campaigns = isset($_POST['campaigns']) ? 0 : 1;
	$templates = isset($_POST['templates']) ? 0 : 1;
	$lists = isset($_POST['lists-subscribers']) ? 0 : 1;
	$reports = isset($_POST['reports']) ? 0 : 1;
	$notify_campaign_sent = isset($_POST['notify_campaign_sent']) ? 1 : 0;
	$campaign_report_rows = is_numeric($_POST['campaign_report_rows']) ? mysqli_real_escape_string($mysqli, (int)$_POST['campaign_report_rows']) : 10;
	$query_string = mysqli_real_escape_string($mysqli, $_POST['query_string']);
	$gdpr_only = isset($_POST['gdpr_only']) ? 1 : 0;
	$gdpr_only_ar = isset($_POST['gdpr_only_ar']) ? 1 : 0;
	$gdpr_options = isset($_POST['gdpr_options']) ? 1 : 0;
	$recaptcha_sitekey = mysqli_real_escape_string($mysqli, $_POST['recaptcha_sitekey']);
	$recaptcha_secretkey = mysqli_real_escape_string($mysqli, $_POST['recaptcha_secretkey']);
	$test_email_prefix = mysqli_real_escape_string($mysqli, $_POST['test_email_prefix']);
	$custom_domain_protocol = !isset($_POST['protocol']) ? '' : mysqli_real_escape_string($mysqli, $_POST['protocol']);
	$custom_domain = !isset($_POST['custom_domain']) ? '' : mysqli_real_escape_string($mysqli, $_POST['custom_domain']);
	$custom_domain_enabled = is_numeric($_POST['custom_domain_status']) ? mysqli_real_escape_string($mysqli, (int)$_POST['custom_domain_status']) : 0;
	$templates_lists_sorting = mysqli_real_escape_string($mysqli, $_POST['sort-by']);
	$opens_tracking = is_numeric($_POST['opens']) ? mysqli_real_escape_string($mysqli, (int)$_POST['opens']) : 1;
	$links_tracking = is_numeric($_POST['clicks']) ? mysqli_real_escape_string($mysqli, (int)$_POST['clicks']) : 1;
	$hide_lists = is_numeric($_POST['hide_lists']) ? mysqli_real_escape_string($mysqli, (int)$_POST['hide_lists']) : 1;
	$opt_in = is_numeric($_POST['opt_in']) ? mysqli_real_escape_string($mysqli, (int)$_POST['opt_in']) : 1;
	
	if($choose_limit=='custom' || $choose_limit=='no_expiry')
	{
		$monthly_limit = mysqli_real_escape_string($mysqli, $_POST['monthly-limit']);
		
		if($choose_limit=='custom')
		{
			$reset_on_day = mysqli_real_escape_string($mysqli, $_POST['reset-on-day']);
			
			//Calculate month of next reset
			$today_unix_timestamp = time();
			$day_today = date("j", $today_unix_timestamp);
			$month_today = date("M", $today_unix_timestamp);
			$month_next = strtotime('1 '.$month_today.' +1 month');
			$month_next = date("M", $month_next);
			if($day_today<$reset_on_day) $month_to_reset = $month_today;
			else $month_to_reset = $month_next;
			$no_expiry = 0;
		}
		else if($choose_limit=='no_expiry')
		{
			$reset_on_day = 1;
			$month_to_reset = '';
			$no_expiry = 1;
		}
	}
	else if($choose_limit=='unlimited')
	{
		$monthly_limit = -1;
		$reset_on_day = 1;
		$month_to_reset = '';
		$no_expiry = 0;
	}
	
	//------------------------------------------------------//
	//                      FUNCTIONS                       //
	//------------------------------------------------------//
	
	$q = 'INSERT INTO apps (userID, app_name, from_name, from_email, reply_to, allowed_attachments, currency, delivery_fee, cost_per_recipient, smtp_host, smtp_port, smtp_ssl, smtp_username, smtp_password, app_key, allocated_quota, day_of_reset, month_of_next_reset, no_expiry, reports_only, campaigns_only, templates_only, lists_only, notify_campaign_sent, campaign_report_rows, query_string, gdpr_only, gdpr_only_ar, gdpr_options, recaptcha_sitekey, recaptcha_secretkey, test_email_prefix, custom_domain_protocol, custom_domain, custom_domain_enabled, templates_lists_sorting, opens_tracking, links_tracking, hide_lists, opt_in) VALUES ('.get_app_info('userID').', "'.$app_name.'", "'.$from_name.'", "'.$from_email.'", "'.$reply_to.'", "'.$allowed_attachments.'", "'.$currency.'", "'.$delivery_fee.'", "'.$cost_per_recipient.'", "'.$smtp_host.'", "'.$smtp_port.'", "'.$smtp_ssl.'", "'.$smtp_username.'", "'.$smtp_password.'", "'.ran_string(30, 30, true, false, true).'", '.$monthly_limit.', '.$reset_on_day.', "'.$month_to_reset.'", '.$no_expiry.', '.$reports.', '.$campaigns.', '.$templates.', '.$lists.', '.$notify_campaign_sent.', '.$campaign_report_rows.', "'.$query_string.'", '.$gdpr_only.', '.$gdpr_only_ar.', '.$gdpr_options.', "'.$recaptcha_sitekey.'", "'.$recaptcha_secretkey.'", "'.$test_email_prefix.'", "'.$custom_domain_protocol.'", "'.$custom_domain.'", '.$custom_domain_enabled.', "'.$templates_lists_sorting.'", '.$opens_tracking.', '.$links_tracking.', '.$hide_lists.', '.$opt_in.')';
	$r = mysqli_query($mysqli, $q);
	if ($r)
	{
		//app id
		$id = mysqli_insert_id($mysqli);
		
		//insert new record
		$q = 'INSERT INTO login (name, company, username, password, tied_to, app, timezone, language) VALUES ("'.$from_name.'", "'.$app_name.'", "'.$login_email.'", "'.$pass_encrypted.'", '.get_app_info('userID').', '.$id.', "'.get_app_info('timezone').'", "'.$language.'")';
		$r = mysqli_query($mysqli, $q);
		if ($r)
		{
			//Create Rule for email notifications on campaign_sent
			$q = 'INSERT INTO rules (`brand`, `trigger`,  `action`,  `notification_email`,  `app`) VALUES ('.$id.', "campaign_sent", "notify", "'.$from_email.'", '.$id.')';
			$r = mysqli_query($mysqli, $q);
			if (!$r)
			{
			    show_error(_('Can\'t insert rule'), '<p>'.mysqli_error($mysqli).'</p>', true);
				error_log("[Can't insert rule]".mysqli_error($mysqli).': in '.__FILE__.' on line '.__LINE__); 
				exit; 
			}
			
			//Upload brand logo
			//Create /logos/ directory in /uploads/ if it doesn't exist
			if(!file_exists("../../uploads/logos")) 
			{
				//Create /csvs/ directory
				if(!mkdir("../../uploads/logos", 0777))
				{
					//Could not create directory '/logos/'. 
					//Please make sure permissions in /uploads/ folder is set to 777. 
					header("Location: ".get_app_info('path').'/edit-brand?i='.$id.'&e=1');
					exit;
				}
				else
				{
					//chmod uploaded file
					chmod("../../uploads/logos",0777);
				}
			}
			
			//Upload logo
			$file = $_FILES['logo']['tmp_name'];
			$file_name = $_FILES['logo']['name'];
			if($file_name!='') //if an image file was uploaded, upload the image
			{
				$extension_explode = explode('.', $file_name);
				$extension = $extension_explode[count($extension_explode)-1];
				$time = time();
				chmod("../../uploads",0777);
				
				//Check filetype
				$allowed = array("jpeg", "jpg", "gif", "png");
				if(in_array($extension, $allowed)) //if file is an image, allow upload
				{
					//Upload file
					if(!move_uploaded_file($file, '../../uploads/logos/'.$id.'.'.$extension))
					{
						//Could not upload brand logo image to '/logos/' folder. 
						//Please make sure permissions in /uploads/ folder is set to 777. 
						//Then remove the /logos/ folder in the /uploads/ folder and try again.
						header("Location: ".get_app_info('path').'/edit-brand?i='.$id.'&e=3');
					}
					else
					{
						//Update brand_logo_filename in database
						mysqli_query($mysqli, 'UPDATE apps SET brand_logo_filename = "'.$id.'.'.$extension.'" WHERE id = '.$id);
					}
				}
				else 
				{
					//Please upload only these image formats: jpeg, jpg, gif and png.
					header("Location: ".get_app_info('path').'/edit-brand?i='.$id.'&e=2');
					exit;
				}
			}
			
			header("Location: ".get_app_info('path'));
		}
		else
		{
			show_error(_('Can\'t insert rule'), '<p>'.mysqli_error($mysqli).'</p>', true);
			error_log("[Can't insert rule]".mysqli_error($mysqli).': in '.__FILE__.' on line '.__LINE__); 
			exit;
		}
	}
?>