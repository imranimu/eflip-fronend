<?php 
	ini_set('display_errors', 0);
	mysqli_report(MYSQLI_REPORT_OFF);
	include('includes/config.php');
	include('includes/helpers/locale.php');
	include('includes/helpers/integrations/zapier/triggers/functions.php');
	include('includes/helpers/integrations/rules.php');
	include('includes/helpers/subscription.php');
	include('includes/helpers/EmailAddressValidator.php');
	
	//--------------------------------------------------------------//
	function dbConnect() { //Connect to database
	//--------------------------------------------------------------//
	    // Access global variables
	    global $mysqli;
	    global $dbHost;
	    global $dbUser;
	    global $dbPass;
	    global $dbName;
	    global $dbPort;
	    
	    // Attempt to connect to database server
	    if(isset($dbPort)) $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
	    else $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	
	    // If connection failed...
	    if ($mysqli->connect_error) {
	        fail("<!DOCTYPE html><html><head><meta http-equiv=\"Content-Type\" content=\"text/html;charset=utf-8\"/><link rel=\"Shortcut Icon\" type=\"image/ico\" href=\"/img/favicon.png\"><title>"._('Can\'t connect to database')."</title></head><style type=\"text/css\">body{background: #ffffff;font-family: Helvetica, Arial;}#wrapper{background: #f2f2f2;width: 300px;height: 110px;margin: -140px 0 0 -150px;position: absolute;top: 50%;left: 50%;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}p{text-align: center;line-height: 18px;font-size: 12px;padding: 0 30px;}h2{font-weight: normal;text-align: center;font-size: 20px;}a{color: #000;}a:hover{text-decoration: none;}</style><body><div id=\"wrapper\"><p><h2>"._('Can\'t connect to database')."</h2></p><p>"._('There is a problem connecting to the database. Please try again later.')."</p></div></body></html>");
	    }
	    
	    global $charset; mysqli_set_charset($mysqli, isset($charset) ? $charset : "utf8");
	    
	    return $mysqli;
	}
	//--------------------------------------------------------------//
	function fail($errorMsg) { //Database connection fails
	//--------------------------------------------------------------//
	    echo $errorMsg;
	    exit;
	}
	// connect to database
	dbConnect();
?>
<?php
	include_once('includes/helpers/short.php');
	include_once('includes/helpers/PHPMailerAutoload.php');
	require 'includes/helpers/geo/geolite2/vendor/autoload.php';
	use GeoIp2\Database\Reader;
	
	//vars
	$time = time();
	$join_date = round(time()/60)*60;
	$already_subscribed = false;
	$feedback = '';
	$subform = isset($_POST['subform']) && mysqli_real_escape_string($mysqli, $_POST['subform'])=='yes' ? true : false;
	$ip1 = $ip2 = $country1 = $country2 = $referrer1 = $referrer2 = $gdpr1 = $gdpr2 = $gdpr3 = $notes1 = $notes2 = '';
	$last_activity = '';
	$opt_in_throttle = 3600;
	
	//get variable
	if(isset($_GET['i']))
	{
		$i = mysqli_real_escape_string($mysqli, $_GET['i']);
		$i_array = array();
		$i_array = explode('/', $i);
		if(count($i_array)==1)
		{
			$i_array = array();
			$i_array = explode('/', decrypt_string($i));
			$email = encrypt_val(trim($i_array[0]));
			$list_id = (int)$i_array[1];
			if(array_key_exists(2, $i_array)) $name = $i_array[2];
			if(array_key_exists(3, $i_array)) $return_boolean = $i_array[3];
			else $return_boolean = '';
		}
		else
		{
			$email = trim($i_array[0]);
			$email = str_replace(" ", "+", $email);
	        $email = str_replace("%20", "+", $email);
			$list_id = decrypt_int($i_array[1]);
			if(array_key_exists(2, $i_array)) $name = $i_array[2];
			if(array_key_exists(3, $i_array)) $return_boolean = $i_array[3];
			else $return_boolean = '';
		}
		
		//Set language
		$q = 'SELECT login.language FROM lists, login WHERE lists.id = '.$list_id.' AND login.app = lists.app';
		$r = mysqli_query($mysqli, $q);
		if ($r && mysqli_num_rows($r) > 0) while($row = mysqli_fetch_array($r)) $language = $row['language'];
		set_locale($language);
		
		//check if email needs to be decrypted
		$validator = new EmailAddressValidator;
		if ($validator->check_email_address($email)) 
		{
			if($return_boolean=='true')
			{
				echo 'Invalid email address.';
				exit;
			}
			else $feedback = _('Email address is invalid.');
		}
		else
		{
			$email = decrypt_string($email);
			
			//check if email is valid
			$validator = new EmailAddressValidator;
			if ($validator->check_email_address($email)) {}
			else
			{
				if($return_boolean=='true')
				{
					echo 'Invalid email address.';
					exit;
				}
				else $feedback = _('Email address is invalid.');
			}
		}
	}
	else if(isset($_POST['email']))//email posted from subscribe form or API
	{		
		//parameters
		$email = mysqli_real_escape_string($mysqli, trim($_POST['email'])); //compulsory
		$name = strip_tags(mysqli_real_escape_string($mysqli, $_POST['name'])); //optional
		$list_id = strip_tags(decrypt_int(mysqli_real_escape_string($mysqli, $_POST['list']))); //compulsory
		$return_boolean = isset($_POST['boolean']) ? strip_tags(mysqli_real_escape_string($mysqli, $_POST['boolean'])) : ''; //compulsory
		$hp = isset($_POST['hp']) ? strip_tags(mysqli_real_escape_string($mysqli, $_POST['hp'])) : ''; //honeypot
		$ipaddress = isset($_POST['ipaddress']) ? strip_tags(mysqli_real_escape_string($mysqli, $_POST['ipaddress'])) : ''; //optional
		$country = isset($_POST['country']) ? strip_tags(mysqli_real_escape_string($mysqli, $_POST['country'])) : ''; //optional
		$referrer = isset($_POST['referrer']) ? strip_tags(mysqli_real_escape_string($mysqli, $_POST['referrer'])) : ''; //optional
		$notes = isset($_POST['notes']) ? strip_tags(mysqli_real_escape_string($mysqli, $_POST['notes'])) : ''; //optional
			
		if($ipaddress != '')
		{
			//Check if it's a valid IP
			if(filter_var($ipaddress, FILTER_VALIDATE_IP) === false)
			{
				if($return_boolean=='true')
				{
					echo 'IP address is invalid.';
					exit;
				}
				else
					$feedback = _('IP address is invalid.');
			}
			$ip1 = ', ip';
			$ip2 = ', "'.$ipaddress.'"';
		}
		if($country != '')
		{
			//Check country code is valid
			if(country_code_exist($country))
			{
				if($return_boolean=='true')
				{
					echo 'Country must be a valid 2 letter country code';
					exit;
				}
				else
					$feedback = _('Country must be a valid 2 letter country code');
			}
			$country1 = ', country';
			$country2 = ', "'.$country.'"';
		}
		if($referrer != '')
		{
			//Check if referrer is a valid URL
			if (filter_var($referrer, FILTER_VALIDATE_URL) === FALSE) 
			{
			    if($return_boolean=='true')
				{
					echo 'Referrer is not a valid URL';
					exit;
				}
				else
					$feedback = _('Referrer is not a valid URL');
			}
			$referrer1 = ', referrer';
			$referrer2 = ', "'.$referrer.'"';
		}		
		if($notes != '')
		{
			$notes1 = ', notes';
			$notes2 = ', "'.$notes.'"';
		}
		$added_via = 2; //1 = Sendy app, 2 = API, 3 = Sendy's subscribe form
		
		$q = 'SELECT login.app, lists.app FROM login, app WHERE lists.app = login.app';
		
		//Set language
		$q = 'SELECT login.language, login.timezone, lists.gdpr_enabled as gdpr_enabled FROM lists, login WHERE lists.id = '.$list_id.' AND login.app = lists.app';
		$r = mysqli_query($mysqli, $q);
		if ($r && mysqli_num_rows($r) > 0) 
		{
			while($row = mysqli_fetch_array($r)) 
			{
				$language = $row['language'];
				$timezone = $row['timezone'];
				$gdpr_enabled = $row['gdpr_enabled'];
			}
		}
		else
		{
			$q2 = 'SELECT login.language, login.timezone, lists.gdpr_enabled as gdpr_enabled FROM lists, login WHERE lists.id =  '.$list_id.' AND login.id = 1';
			$r2 = mysqli_query($mysqli, $q2);
			if ($r2 && mysqli_num_rows($r2) > 0)
			{
				while($row = mysqli_fetch_array($r2)) 
				{
					$language = $row['language'];
					$timezone = $row['timezone'];
					$gdpr_enabled = $row['gdpr_enabled'];
				}
			}
		}
		
		//Set timezone
		if($timezone=='') date_default_timezone_set(date_default_timezone_get());
		else date_default_timezone_set($timezone);
		
		//Set language
		set_locale($language);
		
		if(!$subform)
		{
			if($_POST['gdpr']=='true')
				$gdpr = 1;
			else if($_POST['gdpr']=='false')
				$gdpr = 0;
			else 
				$gdpr = 0;
			
			if($gdpr!='')
			{
				$gdpr1 = ', gdpr = '.$gdpr;
				$gdpr2 = ', gdpr';
				$gdpr3 = ', '.$gdpr;
			}
		}
		
		//check if no data passed
		if($email=='' || $list_id=='')
		{
			if($return_boolean=='true')
			{
				echo 'Some fields are missing.';
				exit;
			}
			else
				$feedback = _('Some fields are missing.');
		}
		else
		{
			//check if email is valid
			$validator = new EmailAddressValidator;
			if (!$validator->check_email_address($email) || $hp!='')
			{
				if($return_boolean=='true')
				{
					echo 'Invalid email address.';
					exit;
				}
				else
				    $feedback = _('Email address is invalid.');
			}
		}
		
		//Check if email is bounced anywhere in the database 
		$q = 'SELECT id FROM subscribers WHERE email = "'.$email.'" AND bounced = 1';
		$r = mysqli_query($mysqli, $q);
		if (mysqli_num_rows($r) > 0)
		{
		    //email is bounced, don't add it to the list
		    if($return_boolean=='true')
			{
				echo 'Bounced email address.';
				exit;
			}
			else
			{
			    $feedback = _('Email address is bounced.');
			}  
		}
		
	}
	else if($_GET['i']=='')
	{
		exit;
	}
	
	//get app id and list name
	$q = 'SELECT userID, app, name, opt_in, subscribed_url, thankyou, thankyou_subject, thankyou_message, confirmation_subject, confirmation_email, custom_fields, no_consent_url, already_subscribed_url FROM lists WHERE id = '.$list_id;
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0) 
	{
		while($row = mysqli_fetch_array($r)) 
		{
			$userID = $row['userID'];
			$app = $row['app'];
			$list_name = $row['name'];
			$opt_in = isset($_POST['silent']) && $_POST['silent']=='true' ? 0 : $row['opt_in'];
			$subscribed_url = $row['subscribed_url'];
			$thankyou = $row['thankyou'];
			$thankyou_subject = stripslashes($row['thankyou_subject']);
			$thankyou_message = stripslashes($row['thankyou_message']);
			$custom_fields = $row['custom_fields'];
			$confirmation_subject = stripslashes($row['confirmation_subject']);
			$confirmation_email = stripslashes($row['confirmation_email']);
			$no_consent_url = $row['no_consent_url'];
			$already_subscribed_url = $row['already_subscribed_url'];
		}
	}
	else
	{
		echo 'Invalid list ID.';
		exit;
	}
	
	//get IAM keys
	$q = 'SELECT s3_key, s3_secret, api_key FROM login WHERE id = '.$userID;
	$r = mysqli_query($mysqli, $q);
	if ($r)
	{
	    while($row = mysqli_fetch_array($r))
	    {
			$s3_key = $row['s3_key'];
			$s3_secret = $row['s3_secret'];
			$user_api_key = $row['api_key'];
	    }
	}
	
	//get data from apps
	$q = 'SELECT from_name, from_email, reply_to, smtp_host, smtp_port, smtp_ssl, smtp_username, smtp_password, allocated_quota, recaptcha_secretkey, custom_domain, custom_domain_protocol, custom_domain_enabled FROM apps WHERE id = '.$app;
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0)
	{
	    while($row = mysqli_fetch_array($r))
	    {
			$from_name = $row['from_name'];
			$from_email = $row['from_email'];
			$reply_to = $row['reply_to'];
			$smtp_host = $row['smtp_host'];
			$smtp_port = $row['smtp_port'];
			$smtp_ssl = $row['smtp_ssl'];
			$smtp_username = $row['smtp_username'];
			$smtp_password = $row['smtp_password'];
			$allocated_quota = $row['allocated_quota'];
			$recaptcha_secretkey = $row['recaptcha_secretkey'];
			$custom_domain = $row['custom_domain'];
			$custom_domain_protocol = $row['custom_domain_protocol'];
			$custom_domain_enabled = $row['custom_domain_enabled'];
			if($custom_domain!='' && $custom_domain_enabled)
			{
				$parse = parse_url(APP_PATH);
				$domain = $parse['host'];
				$protocol = $parse['scheme'];
				$app_path = str_replace($domain, $custom_domain, APP_PATH);
				$app_path = str_replace($protocol, $custom_domain_protocol, $app_path);
			}
			else $app_path = APP_PATH;
	    }  
	}
	
	//get email's domain
	$email_explode = explode('@', $email);
	$email_domain = $email_explode[1];
	
	//If user subscribes using Sendy's subscription form or HTML code, store user's IP address and country code
	if($subform)
	{
		//IP
		$ipaddress = ipaddress();
		$ip1 = ', ip';
		$ip2 = ', "'.$ipaddress.'"';
		
		if($recaptcha_secretkey!='')
		{
			//reCAPTCHA verification
			$captcha=$_POST['g-recaptcha-response'];
			$secretkey = $recaptcha_secretkey;					
			$response=file_get_contents_curl("https://www.google.com/recaptcha/api/siteverify?secret=".$secretkey."&response=".$captcha."&remoteip=".$ipaddress);
			$responseKeys = json_decode($response,true);
			if(intval($responseKeys["success"]) !== 1) 
			{
				if($return_boolean=='true')
				{
					echo 'Failed reCAPTCHA test.';
					exit;
				}
				else
					$feedback = _('Failed reCAPTCHA test.');
			}
		}
		
		//Get country code
		if(version_compare(PHP_VERSION, '5.4')==-1)
		{
			include_once('includes/helpers/geo/geolite/geoip.inc');
			$gi = geoip_open("includes/helpers/geo/geolite/GeoIP.dat",GEOIP_STANDARD);
			$country = geoip_country_code_by_addr($gi, $ipaddress);
			geoip_close($gi);
		}
		else
		{
			$reader = new Reader('includes/helpers/geo/geolite2/vendor/geoip2/geoip2/maxmind-db/GeoLite2-Country.mmdb');
			try 
			{
				$record = $reader->country($ipaddress);
				$country = $record->country->isoCode;
			}
			catch (Exception $e) { $country = ''; }
		}
		
		$country1 = ', country';
		$country2 = ', "'.$country.'"';
		//Referrer
		$referrer = isset($_SERVER['HTTP_REFERER']) ? mysqli_real_escape_string($mysqli, $_SERVER['HTTP_REFERER']) : '';
		$referrer1 = ', referrer';
		$referrer2 = ', "'.$referrer.'"';
		//Added via
		$added_via = 3; //1 = Sendy app, 2 = API, 3 = Sendy's subscribe form
		
		//Check the GDPR checkbox
		if($gdpr_enabled)
		{
			if(isset($_POST['gdpr']))
				$gdpr = 1;
			else
			{
				if($return_boolean=='true')
				{
					echo 'Consent not given.';
					exit;
				}
				else
				{
					if($no_consent_url=='')
						$feedback =  _('Consent not given.');
					else
					{
						header("Location: $no_consent_url");
						exit;
					}
				}
			}
		}
		else $gdpr = 0;
		
		$gdpr1 = ', gdpr = '.$gdpr;
		$gdpr2 = ', gdpr';
		$gdpr3 = ', '.$gdpr;
	}
	else
	{
		//Using API, api_key is required
		$api_key = mysqli_real_escape_string($mysqli, trim($_POST['api_key'])); //compulsory
		
		if(isset($_POST['email']) && !isset($_POST['api_key'])) // No api_key was passed
		{
			if($return_boolean=='true')
			{
				echo 'API key not passed';
				exit;
			}
			else
				$feedback = _('API key not passed');
		}
		else // api_key was passed
		{
			// but incorrect
			if(isset($_POST['email']) && $api_key != $user_api_key)
			{
				if($return_boolean=='true')
				{
					echo 'Invalid API key';
					exit;
				}
				else
					$feedback = _('Invalid API key');
			}
		}
	}
	
	if($feedback!=_('Some fields are missing.') && $feedback!=_('Email address is invalid.') && $feedback!=_('Email address is bounced.') && $feedback!=_('Consent not given.') && $feedback!=_('Failed reCAPTCHA test.') && $feedback!=_('Invalid secret key.') && $feedback != _('API key not passed') && $feedback != _('Invalid API key'))
	{		
		//get custom fields list and format it for db insert
	    $cf_vals = '';
		$custom_fields_array = explode('%s%', $custom_fields);
		foreach($custom_fields_array as $cf)
		{
			$cf_array = explode(':', $cf);
			foreach ($_POST as $key => $value)
			{
				//if custom field matches POST data but IS NOT name, email, list or submit
				if(str_replace(' ', '', $cf_array[0])==$key && ($key!='name' && $key!='email' && $key!='list' && $key!='submit'))
				{
					//if custom field format is Date
					if($cf_array[1]=='Date')
					{
						$date_value1 = strtotime($value);
						$date_value2 = date("M d, Y 12\a\m", $date_value1);
						$value = strtotime($date_value2);
						$cf_vals .= $value;
					}
					//else if custom field format is Text
					else
						$cf_vals .= addslashes($value);
				}
			}
			$cf_vals .= '%s%';
		}
		
		//check if user is in this list
		$q = 'SELECT id, userID, custom_fields, unsubscribed, confirmed, bounced, complaint, timestamp FROM subscribers WHERE email = "'.$email.'" AND list = '.$list_id;
		$r = mysqli_query($mysqli, $q);
		if ($r && mysqli_num_rows($r) > 0) //if so, update subscriber
		{
			while($row = mysqli_fetch_array($r))
		    {
		    	$subscriber_id = $row['id'];
				$userID = $row['userID'];
				$custom_values = $row['custom_fields'];
				$unsubscribed = $row['unsubscribed'];
				$confirmed = $row['confirmed'];
				$bounced = $row['bounced'];
				$complaint = $row['complaint'];
				$timestamp = $row['timestamp'];
				$last_activity = $time - $timestamp;
		    } 
		    
		    //get custom fields values
		    $j = 0;
		    $cf_value = '';
		    $custom_values_array = explode('%s%', $custom_values);
		    foreach($custom_fields_array as $cf_fields)
			{
				$k = 0;
				$cf_fields_array = explode(':', $cf_fields);
				foreach ($_POST as $key => $value)
				{
					//if custom field matches POST data but IS NOT name, email, list or submit
					if(str_replace(' ', '', $cf_fields_array[0])==$key && ($key!='name' && $key!='email' && $key!='list' && $key!='submit'))
					{
						//if user left field empty
						if($value=='')
						{
							$cf_value .= '';
						}
						else
						{
							//if custom field format is Date
							if($cf_fields_array[1]=='Date')
							{
								$date_value1 = strtotime($value);
								$date_value2 = date("M d, Y 12\a\m", $date_value1);
								$value = strtotime($date_value2);
								$cf_value .= $value;
							}
							//else if custom field format is Text
							else
								$cf_value .= strip_tags($value);
						}
					}
					else
					{
						$k++;
					}
				}
				if(count($_POST)==$k) $cf_value .= $custom_values_array[$j];			
				$cf_value .= '%s%';
				$j++;
			}
		    
			if($opt_in) 
			{
				$confirmed = $unsubscribed && $confirmed ? 0 : $confirmed;				
				$name_line = !isset($_POST['name']) ? '' : 'name = "'.$name.'",';				
				$q = 'UPDATE subscribers SET unsubscribed = 0, last_campaign = NULL, timestamp = '.$time.', confirmed = '.$confirmed.', '.$name_line.' custom_fields = "'.substr($cf_value, 0, -3).'" '.$gdpr1.', notes = "'.$notes.'", ip = "'.$ipaddress.'", country = "'.$country.'", referrer = "'.$referrer.'" WHERE email = "'.$email.'" AND list = '.$list_id;
			}
			else
			{
				$name_line = !isset($_POST['name']) ? '' : ', name = "'.$name.'"';				
				$q = 'UPDATE subscribers SET unsubscribed = 0, last_campaign = NULL, timestamp = '.$time.', confirmed = 1 '.$name_line.', custom_fields = "'.substr($cf_value, 0, -3).'" '.$gdpr1.', notes = "'.$notes.'", ip = "'.$ipaddress.'", country = "'.$country.'", referrer = "'.$referrer.'" WHERE email = "'.$email.'" AND list = '.$list_id;
			}
			$r = mysqli_query($mysqli, $q);
			if ($r)
			{
				if(!$unsubscribed && $confirmed) $already_subscribed = true;
				if(!$already_subscribed)
				{
					if($opt_in && $confirmed!=1)
					{
						if($last_activity > $opt_in_throttle || $unsubscribed)
							$feedback = '<span style="font-size: 20px;padding:10px;float:left;margin-top:-18px;">'._('Thank you, a confirmation email has been sent to you.').'</span><br/><img src="'.$app_path.'/img/email-notifications/paperplane.gif" height="150"/>';
						else
							$feedback = '<span style="font-size: 20px;padding:10px;float:left;margin-top:-18px;">'._('A confirmation email had already been sent to you.').'</span><br/><img src="'.$app_path.'/img/email-notifications/paperplane.gif" height="150"/>';
					}
					else
						$feedback = _('You\'re subscribed!');
				}
				else
				{
					if($return_boolean=='true')
					{
						echo 'Already subscribed.';
						exit;
					}
					else
					{
						if($confirmed==0)
							$feedback = '<span style="font-size: 20px;padding:10px;float:left;margin-top:-18px;">'._('A confirmation email had already been sent to you.').'</span>';
						else
						{
							if($already_subscribed_url=='')
							    $feedback = _('You\'re already subscribed!');
							else
							{
								$already_subscribed_url = str_replace('%n', urlencode($name), $already_subscribed_url);
								$already_subscribed_url = str_replace('%e', urlencode($email), $already_subscribed_url);
								$already_subscribed_url = str_replace('%l', encrypt_val($list_id), $already_subscribed_url);
								
							    header("Location: $already_subscribed_url");
							    exit;
							 }
						 }
					}
				}
			}
		}
		//if user does not exist in list, insert subscriber into database
		else
		{
			$q = 'SELECT userID FROM lists WHERE id = '.$list_id;
			$r = mysqli_query($mysqli, $q);
			if ($r && mysqli_num_rows($r) > 0)
			{
			    while($row = mysqli_fetch_array($r)) $userID = $row['userID'];
			    
			    $q2 = '(SELECT id FROM suppression_list WHERE email = "'.$email.'" AND app = '.$app.') UNION (SELECT id FROM blocked_domains WHERE domain = "'.$email_domain.'" AND app = '.$app.')';
				$r2 = mysqli_query($mysqli, $q2);
				if (mysqli_num_rows($r2) == 0)
				{
				    //if not, insert user into list
				    if($opt_in) //if double opt in,
						$q = 'INSERT INTO subscribers (userID, email, name, custom_fields, list, timestamp, confirmed, method, added_via '.$ip1.' '.$country1.' '.$referrer1.' '.$gdpr2.' '.$notes1.') VALUES ('.$userID.', "'.$email.'", "'.$name.'", "'.substr($cf_vals, 0, -3).'", '.$list_id.', '.$time.', 0, 2, '.$added_via.' '.$ip2.' '.$country2.' '.$referrer2.' '.$gdpr3.' '.$notes2.')';
					else
						$q = 'INSERT INTO subscribers (userID, email, name, custom_fields, list, timestamp, join_date, method, added_via '.$ip1.' '.$country1.' '.$referrer1.' '.$gdpr2.' '.$notes1.') VALUES ('.$userID.', "'.$email.'", "'.$name.'", "'.substr($cf_vals, 0, -3).'", '.$list_id.', '.$time.', '.$join_date.', 1, '.$added_via.' '.$ip2.' '.$country2.' '.$referrer2.' '.$gdpr3.' '.$notes2.')';
					$r = mysqli_query($mysqli, $q);
					if ($r){
						
						$subscriber_id = mysqli_insert_id($mysqli);
						
						if($opt_in)
							$feedback = '<span style="font-size: 20px;padding:10px;float:left;margin-top:-18px;">'._('Thank you, a confirmation email has been sent to you.').'</span><br/><img src="'.$app_path.'/img/email-notifications/paperplane.gif" height="150"/>';
						else
						{
							$feedback = _('You\'re subscribed!');
							
							//Zapier Trigger 'new_user_subscribed' event
							zapier_trigger_new_user_subscribed($name, $email, $list_id);
							
							//Run rules
							$rules_data = array(
							    'trigger' => 'subscribe',
							    'name' => $name,
							    'email' => $email,
							    'list_id' => encrypt_val($list_id),
							    'list_name' => $list_name,
							    'list_url' => $app_path.'/subscribers?i='.$app.'&l='.$list_id,
							    'gravatar' => get_gravatar($email, 88)
						    );
						    
						    //Populate custom fields (if available)
							if($custom_fields!='')
							{
								$custom_field_lines = '';
								$custom_fields_values_array = explode('%s%', substr($cf_vals, 0, -3));
								for($c=0;$c<count($custom_fields_array);$c++)
								{
									$fields_array = explode(':', $custom_fields_array[$c]);
									$values_array = $fields_array[1]=='Date' ? date("M d, Y", (int)$custom_fields_values_array[$c]) : $custom_fields_values_array[$c];
									$rules_data[$fields_array[0]] = $values_array;
								}
							}
						    
						    //Run rules
							run_rule($rules_data);
							
							//Update segments
							update_segments($app_path, $list_id);
						}
					}
				}
				else
				{
					//Update block attempts count
					$q = 'UPDATE suppression_list SET block_attempts = block_attempts+1, timestamp = "'.$time.'" WHERE email = "'.$email.'" AND app = '.$app;
					$q2 = 'UPDATE blocked_domains SET block_attempts = block_attempts+1, timestamp = "'.$time.'" WHERE domain = "'.$email_domain.'" AND app = '.$app;
					mysqli_query($mysqli, $q);
					mysqli_query($mysqli, $q2);
					
					if($return_boolean=='true')
					{
						echo 'Email is suppressed.';
						exit;
					}
					else $feedback = _('Email is suppressed.');
				}
			}
			else
			{
				echo 'Invalid list ID.';
				exit;
			}
		}
		
		if(!$already_subscribed)
		{			
			//send confirmation email if list is double opt in
			if($opt_in && $confirmed!=1 && $bounced!=1 && $complaint!=1 && $feedback!=_('Email is suppressed.'))
			{
				if(isset($_GET['i']))
					$confirmation_link = $app_path.'/confirm?e='.encrypt_val($subscriber_id).'&l='.$i_array[1];
				else
					$confirmation_link = $app_path.'/confirm?e='.encrypt_val($subscriber_id).'&l='.encrypt_val($list_id);
				
				if($confirmation_subject=='')
					$confirmation_subject = _('Confirm your subscription to').' '.$from_name;
				
				if(strlen(trim(preg_replace('/\xc2\xa0/',' ', $confirmation_email))) == 0 || trim($confirmation_email)=='<p><br></p>' || $output = trim(str_replace(array("\r\n", "\r", "\n", "	"), '', $confirmation_email))=="<html><head><title></title></head><body></body></html>")
					$confirmation_email = "<!DOCTYPE html><html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"/><title></title></head><body><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" style=\"table-layout:fixed;background-color:#ffffff;\" id=\"bodyTable\"><tbody><tr><td align=\"center\" valign=\"top\" style=\"padding-right:10px;padding-left:10px;\" id=\"bodyCell\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;\" width=\"100%\" class=\"wrapperWebview\"><tbody><tr><td align=\"center\" valign=\"top\"></td></tr></tbody></table><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"max-width:600px;\" width=\"100%\" class=\"wrapperBody\"><tbody><tr><td align=\"center\" valign=\"top\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"background-color:#FFFFFF;border-color:#E5E5E5; border-style:solid; border-width:0 1px 1px 1px;\" width=\"100%\" class=\"tableCard\"><tbody><tr><td height=\"3\" style=\"clear: both; height: 5px; background: url('$app_path/img/top-pattern2.gif') repeat-x 0 0; background-size: 46px;\" class=\"topBorder\">&nbsp;</td></tr><tr><td align=\"center\" valign=\"top\" style=\"padding-bottom: 10px;\" class=\"imgHero\"><a href=\"#\" target=\"_blank\" style=\"text-decoration:none;\"><img src=\"$app_path/img/email-notifications/almost-there.gif\" width=\"150\" alt=\"\" border=\"0\" style=\"width:100%; max-width:150px; height:auto; display:block;\"></a></td></tr><tr><td align=\"center\" valign=\"top\" style=\"padding-bottom: 5px; padding-left: 20px; padding-right: 20px;\" class=\"mainTitle\"><h2 class=\"text\" style=\"color:#000000; font-family: Helvetica, Arial, sans-serif; font-size:28px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:36px; text-transform:none; text-align:center; padding:0; margin:0\">"._('You\'re almost there!')."</h2></td></tr><tr><td align=\"center\" valign=\"top\" style=\"padding-bottom: 30px; padding-left: 20px; padding-right: 20px;\" class=\"subTitle\"><h4 class=\"text\" style=\"color:#848484; font-family: Helvetica, Arial, sans-serif; font-size:16px; font-weight:500; font-style:normal; letter-spacing:normal; line-height:24px; text-transform:none; text-align:center; padding:0; margin:0\">"._('Please confirm your subscription by clicking the link below')."</h4></td></tr><tr><td align=\"center\" valign=\"top\" style=\"padding-left:20px;padding-right:20px;\" class=\"containtTable ui-sortable\"><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" class=\"tableDescription\" style=\"margin-bottom: 20px;\"><tbody><tr><td align=\"left\" valign=\"top\" style=\"padding: 15px; background: #F8F9FC;\" class=\"description\"><p class=\"text\" style=\"color:#666666; font-family:'Open Sans', Helvetica, Arial, sans-serif; font-size:14px; font-weight:400; font-style:normal; letter-spacing:normal; line-height:22px; text-transform:none; text-align:left; padding:0; margin:0\"><strong>"._('Confirm').": </strong>$confirmation_link<br/></p></td></tr></tbody></table><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"tableButton\" style=\"\"><tbody><tr><td align=\"center\" valign=\"top\" style=\"padding-top:20px;padding-bottom:20px;\"><table align=\"center\" border=\"0\" cellpadding=\"0\" cellspacing=\"0\"><tbody><tr><td align=\"center\" class=\"ctaButton\" style=\"background-color:#000000;padding-top:12px;padding-bottom:12px;padding-left:35px;padding-right:35px;border-radius:50px\"><a class=\"text\" href=\"$confirmation_link\" target=\"_blank\" style=\"color:#FFFFFF; font-family: Helvetica, Arial, sans-serif; font-size:13px; font-weight:600; font-style:normal;letter-spacing:1px; line-height:20px; text-transform:uppercase; text-decoration:none; display:block\">"._('Confirm your subscription')."</a></td></tr></tbody></table></td></tr></tbody></table></td></tr><tr><td height=\"20\" style=\"font-size:1px;line-height:1px;\">&nbsp;</td></tr></tbody></table><table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" width=\"100%\" class=\"space\"><tbody><tr><td height=\"30\" style=\"font-size:1px;line-height:1px;\">&nbsp;</td></tr></tbody></table></td></tr></tbody></table></td></tr></tbody></table></body></html>
					";
				else
					$confirmation_email = str_replace('[confirmation_link]', $confirmation_link, $confirmation_email);
	
				//Convert personaliztion tags
				convert_tags($confirmation_subject, $subscriber_id, 'confirm', 'subject');
				convert_tags($confirmation_email, $subscriber_id, 'confirm', 'message');
				
				//Convert name tag
				$confirmation_email = str_replace('[Name]', $name, $confirmation_email);
				$confirmation_subject = str_replace('[Name]', $name, $confirmation_subject);
				
				//Convert email tag
				$confirmation_email = str_replace('[Email]', $email, $confirmation_email);
				$confirmation_subject = str_replace('[Email]', $email, $confirmation_subject);
				
				//Send double opt-in confirmation email
				if($last_activity=='' || $last_activity > $opt_in_throttle || $unsubscribed)
					send_email($confirmation_subject, $confirmation_email, $email, $name, '', '', encrypt_val($list_id));
			}
			else //if single opt in, check if we need to send a thank you email
			{
				if($thankyou && $confirmed!=1 && $feedback!=_('Email is suppressed.'))
				{					
					//Convert personaliztion tags
					convert_tags($thankyou_subject, $subscriber_id, 'thankyou', 'subject');
					convert_tags($thankyou_message, $subscriber_id, 'thankyou', 'message');
					
					//Convert name tag
					$thankyou_message = str_replace('[Name]', $name, $thankyou_message);
					$thankyou_subject = str_replace('[Name]', $name, $thankyou_subject);
					
					//Convert email tag
					$thankyou_message = str_replace('[Email]', $email, $thankyou_message);
					$thankyou_subject = str_replace('[Email]', $email, $thankyou_subject);
					
					//Unsubscribe tag
					if($smtp_host == 'smtp.elasticemail.com' && ($s3_key=='' && $s3_secret==''))
					{
						$thankyou_message = str_replace('<unsubscribe', '<a href="{unsubscribe}" ', $thankyou_message);
						$thankyou_message = str_replace('</unsubscribe>', '</a>', $thankyou_message);
						$thankyou_message = str_replace('[unsubscribe]', '{unsubscribe}', $thankyou_message);
					}
					else
					{
						$thankyou_message = str_replace('<unsubscribe', '<a href="'.$app_path.'/unsubscribe/'.encrypt_val($email).'/'.encrypt_val($list_id).'" ', $thankyou_message);
						$thankyou_message = str_replace('</unsubscribe>', '</a>', $thankyou_message);
						$thankyou_message = str_replace('[unsubscribe]', $app_path.'/unsubscribe/'.encrypt_val($email).'/'.encrypt_val($list_id), $thankyou_message);
					}
					
					//Send thank you email
					send_email($thankyou_subject, $thankyou_message, $email, $name, '', '', encrypt_val($list_id));
				}
			}
		}
	}
	
//--------------------------------------------------------------//
function file_get_contents_curl($url) 
//--------------------------------------------------------------//
{	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}

if($return_boolean=='true'):
	echo true;
	exit;
else:
	//if user sets a redirection URL
	if($subscribed_url != '' && !$already_subscribed && $feedback!=_('Some fields are missing.') && $feedback!=_('Email address is invalid.') && $feedback!=_('Email address is bounced.') && $feedback!=_('Consent not given.') && $feedback!=_('Failed reCAPTCHA test.') && $feedback!=_('Invalid secret key.') && $feedback!=_('API key not passed') && $feedback != _('Invalid API key')):
		$subscribed_url = str_replace('%n', urlencode($name), $subscribed_url);
		$subscribed_url = str_replace('%e', urlencode($email), $subscribed_url);
		$subscribed_url = str_replace('%l', encrypt_val($list_id), $subscribed_url);
		header("Location: ".$subscribed_url);
	else:
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="robots" content="noindex, nofollow">
		<link rel="Shortcut Icon" type="image/ico" href="<?php echo $app_path;?>/img/favicon.png">
		<title><?php echo strip_tags($feedback);?></title>
	</head>
	<style type="text/css">
		body{
			background: #f7f9fc;
			font-family: Helvetica, Arial;
		}
		#wrapper 
		{
			background: #ffffff;
			-webkit-box-shadow: 0px 16px 46px -22px rgba(0,0,0,0.75);
			-moz-box-shadow: 0px 16px 46px -22px rgba(0,0,0,0.75);
			box-shadow: 0px 16px 46px -22px rgba(0,0,0,0.75);
			
			width: 300px;
			padding-bottom: 10px;
			
			margin: -180px 0 0 -150px;
			position: absolute;
			top: 50%;
			left: 50%;
			-webkit-border-radius: 5px;
			-moz-border-radius: 5px;
			border-radius: 5px;
		}
		p{
			text-align: center;
		}
		h2{
			font-weight: normal;
			text-align: center;
		}
		a{
			color: #000;
			text-decoration: none;
		}
		a:hover{
			text-decoration: underline;
		}
		#top-pattern{
			margin-top: -8px;
			height: 8px;
			background: url("<?php echo $app_path; ?>/img/top-pattern2.gif") repeat-x 0 0;
			background-size: auto 8px;
		}
	</style>
	<body>
		<div id="top-pattern"></div>
		<div id="wrapper">
			<h2><?php echo $feedback;?></h2>
			<?php echo $feedback==_('You\'re subscribed!') ? '<p><img src="'.$app_path.'/img/email-notifications/subscribed.gif" height="150" /></p>' : '';?>
			<p style="font-size: 14px;"><a href="javascript:window.history.go(-1)">← Back</a></p>
		</div>
	</body>
</html>
<?php endif;?>
<?php endif;?>