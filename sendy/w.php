<?php 
	ini_set('display_errors', 0);
	mysqli_report(MYSQLI_REPORT_OFF);
	include('includes/config.php');
	
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
	include('includes/helpers/short.php');
	
	$time = time();
	
	//get variable
	$i = $_GET['i']=='' ? exit : mysqli_real_escape_string($mysqli, $_GET['i']);
	$i_array = array();
	$i_array = explode('/', $i);
	
	//new encrytped string
	if((count($i_array)==1 || count($i_array)==2) && !is_numeric(decrypt_string($i_array[0])))
	{
		$get = decrypt_string($i_array[0]);
		$get_array = explode('/', $get);
		$is_ares = $i_array[1]=='a' ? true : false;
		
		$i_array = array();
		$i_array[0] = encrypt_val($get_array[0]);
		$i_array[1] = encrypt_val($get_array[1]);
		$i_array[2] = encrypt_val($get_array[2]);
		$i_array[3] = $is_ares ? 'a' : '';
	}
	
	//Get all data
	if(count($i_array)==3 || count($i_array)==4)
	{
		$subscriber_id = decrypt_int($i_array[0]);
		$subscriber_list = decrypt_int($i_array[1]);
		$campaign_id = decrypt_int($i_array[2]);
		$just_show_html = false;
		
		if(count($i_array)==4 && $i_array[3]=='a')
			$table = 'ares_emails';
		else
			$table = 'campaigns';
	}
	else if(count($i_array)==1 || count($i_array)==2)
	{
		$campaign_id = decrypt_int($i_array[0]);
		$just_show_html = true;
		
		if(count($i_array)==2 && $i_array[1]=='a')
			$table = 'ares_emails';
		else
			$table = 'campaigns';
	}
	else exit;
	
	//get html text from campaign
	if($table == 'campaigns') 
		$q = 'SELECT campaigns.from_name, campaigns.from_email, campaigns.html_text, campaigns.query_string, campaigns.sent, campaigns.send_date, campaigns.opens_tracking, campaigns.links_tracking, login.timezone, campaigns.app, web_version_lang FROM campaigns, login WHERE campaigns.userID = login.id AND campaigns.id = '.$campaign_id;
	else if($table == 'ares_emails') 
		$q = 'SELECT ares_id, from_name, from_email, html_text, opens_tracking, links_tracking, query_string FROM ares_emails WHERE id = '.$campaign_id;
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0)
	{
	    while($row = mysqli_fetch_array($r))
	    {
			$timezone = isset($row['timezone']) ? $row['timezone'] : '';
			$sent = isset($row['sent']) ? $row['sent'] : '';
			$send_date = isset($row['send_date']) ? $row['send_date'] : '';
			$app = isset($row['app']) ? $row['app'] : '';
			$ares_id = isset($row['ares_id']) ? $row['ares_id'] : '';
			$query_string = isset($row['query_string']) ? stripslashes($row['query_string']) : '';
			$html = $row['html_text'];
			$from_name = $row['from_name'];
			$from_email = $row['from_email'];
			$opens_tracking = $row['opens_tracking'];
			$links_tracking = $row['links_tracking'];
			$web_version_lang = $row['web_version_lang']=='' ? '' : $row['web_version_lang'];
			
			if($table == 'ares_emails') 
			{
				//Get app id
				$q2 = 'SELECT app FROM lists WHERE id = '.$subscriber_list;
				$r2 = mysqli_query($mysqli, $q2);
				if ($r2) while($row = mysqli_fetch_array($r2)) $app = $row['app'];
			}
			
			//Get custom domain
			$q2 = 'SELECT custom_domain, custom_domain_protocol, custom_domain_enabled FROM apps WHERE id = '.$app;
			$r2 = mysqli_query($mysqli, $q2);
			if ($r2 && mysqli_num_rows($r2) > 0)
			{
			    while($row = mysqli_fetch_array($r2))
			    {
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
			
			if($just_show_html)
			{
				//tags
				preg_match_all('/\[([a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+),\s*fallback=/i', $html, $matches_var, PREG_PATTERN_ORDER);
				preg_match_all('/,\s*fallback=([a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*)\]/i', $html, $matches_val, PREG_PATTERN_ORDER);
				preg_match_all('/(\[[a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+,\s*fallback=[a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*\])/i', $html, $matches_all, PREG_PATTERN_ORDER);
				preg_match_all('/\[([^\]]+),\s*fallback=/i', $html, $matches_var, PREG_PATTERN_ORDER);
				preg_match_all('/,\s*fallback=([^\]]*)\]/i', $html, $matches_val, PREG_PATTERN_ORDER);
				preg_match_all('/(\[[^\]]+,\s*fallback=[^\]]*\])/i', $html, $matches_all, PREG_PATTERN_ORDER);
				$matches_var = $matches_var[1];
				$matches_val = $matches_val[1];
				$matches_all = $matches_all[1];
				for($i=0;$i<count($matches_var);$i++)
				{   
					$field = $matches_var[$i];
					$fallback = $matches_val[$i];
					$tag = $matches_all[$i];
					//for each match, replace tag with fallback
					$html = str_replace($tag, $fallback, $html);
				}
				//Name tag
				$html = str_replace('[Name]', $from_name, $html);
				
				//Email tag
				$html = str_replace('[Email]', $from_email, $html);
				
				//set web version links
				if($table == 'campaigns') 
				{
					$html = str_replace('<webversion', '<a href="'.$app_path.'/w/'.encrypt_val($campaign_id).'" target="_blank"', $html);
					$html = str_replace('</webversion>', '</a>', $html);
					$html = str_replace('[webversion]', $app_path.'/w/'.encrypt_val($campaign_id), $html);
				}
				else if($table == 'ares_emails')
				{
					$html = str_replace('<webversion', '<a href="'.$app_path.'/w/'.encrypt_val($campaign_id).'/a" target="_blank"', $html);
					$html = str_replace('</webversion>', '</a>', $html);
					$html = str_replace('[webversion]', $app_path.'/w/'.encrypt_val($campaign_id).'/a', $html);
				}
				
				//set unsubscribe links
				$html = str_replace('<unsubscribe', '<a href="'.$app_path.'/unsubscribe-success.php?c='.$campaign_id.'" target="_blank"', $html);
				$html = str_replace('</unsubscribe>', '</a>', $html);
				$html = str_replace('[unsubscribe]', $app_path.'/unsubscribe-success.php?c='.$campaign_id, $html);
				
				//set reconsent links
				$html = str_replace('[reconsent]', $app_path.'/reconsent-success?c='.$campaign_id, $html);
				
				//convert date tags
				convert_date_tags();
				
				echo $html;
				exit;
			}
	    }  
	}
	else
	{
		echo '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><meta name="viewport" content="width=device-width, initial-scale=1"><link rel="Shortcut Icon" type="image/ico" href="'.APP_PATH.'/img/favicon.png"><title>'._('Web version no longer exists').'</title></head><style type="text/css">body{background: #ffffff;font-family: Helvetica, Arial;}#wrapper{background: #ffffff; border: 1px solid #ededed; width: 360px;height: 70px;margin: -140px 0 0 -180px;position: absolute;top: 50%;left: 50%;-webkit-border-radius: 5px;-moz-border-radius: 5px;border-radius: 5px;}p{text-align: center;}h2{font-weight: normal;text-align: center;}a{color: #000;}a:hover{text-decoration: none;}#top-pattern{margin-top: -8px;height: 8px;background: url("'.APP_PATH.'/img/top-pattern2.gif") repeat-x 0 0;background-size: auto 8px;}</style><body><div id="top-pattern"></div><div id="wrapper"><h2>'._('Web version no longer exists').'</h2></div></body></html>';
		exit;
	}
	
	//convert date tags
	convert_date_tags();
	
	//convert date tags
	function convert_date_tags()
	{
		global $timezone;
		global $html;
		global $sent;
		global $send_date;
		global $unconverted_date;
		global $converted_date;
		
		if($timezone!='') date_default_timezone_set($timezone);
		$today = $sent == '' ? time() : $sent;
		$today = $send_date !='' && $send_date !=0 ? $send_date : $today;
		$currentdaynumber = date('d', $today);
		$currentday = date('l', $today);
		$currentmonthnumber = date('m', $today);
		$currentmonth = date('F', $today);
		$currentyear = date('Y', $today);
		$unconverted_date = array('[currentdaynumber]', '[currentday]', '[currentmonthnumber]', '[currentmonth]', '[currentyear]');
		$converted_date = array($currentdaynumber, $currentday, $currentmonthnumber, $currentmonth, $currentyear);
		$html = str_replace($unconverted_date, $converted_date, $html);
	}
	
	//replace new links on HTML code
	$links = array();
	//extract all links from HTML
	preg_match_all('/href=["\']([^"\']+)["\']/i', $html, $matches, PREG_PATTERN_ORDER);
	$matches = array_unique($matches[1]);
	foreach($matches as $var)
	{    
		if($var!="#" && substr($var, 0, 6)!="mailto" && substr($var, 0, 3)!="ftp" && substr($var, 0, 3)!="tel" && substr($var, 0, 3)!="sms")
		{
			$var = str_replace($unconverted_date, $converted_date, $var);
	    	array_push($links, $var);
	    }
	}
	
	//if this is an autoresponder web version,
	if(count($i_array)==4 && $i_array[3]=='a')
		$q2 = 'SELECT * FROM links WHERE ares_emails_id = '.$campaign_id;
	else
		$q2 = 'SELECT * FROM links WHERE campaign_id = '.$campaign_id;
	$r2 = mysqli_query($mysqli, $q2);
	if ($r2 && mysqli_num_rows($r2) > 0)
	{			
	    while($row2 = mysqli_fetch_array($r2))
	    {
	    	$linkID = $row2['id'];
			$link = $row2['link'];
			
			if($query_string!='')
	    	{
		    	$link = (strpos($row2['link'],'?'.$query_string) !== false) ? str_replace('?'.$query_string, '', $row2['link']) : str_replace('&'.$query_string, '', $row2['link']);
	    	}
	    	else $link = $row2['link'];
			
			//replace new links on HTML code
	    	$html = str_replace('href="'.$link.'"', 'href="'.$app_path.'/l/'.encrypt_val($subscriber_id).'/'.encrypt_val($linkID).'/'.encrypt_val($campaign_id).'" rel="noreferrer"', $html);
	    	$html = str_replace('href=\''.$link.'\'', 'href="'.$app_path.'/l/'.encrypt_val($subscriber_id).'/'.encrypt_val($linkID).'/'.encrypt_val($campaign_id).'" rel="noreferrer"', $html);
	    }  
	}
	
	//get user's email for unsubscription link formatting
	$q = 'SELECT name, email, custom_fields FROM subscribers WHERE id = '.$subscriber_id;
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0)
	{
	    while($row = mysqli_fetch_array($r))
	    {
			$name = trim($row['name']);
			$email = trim($row['email']);
			$custom_values = $row['custom_fields'];
	    }  
	}
	
	//tags
	preg_match_all('/\[([a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+),\s*fallback=/i', $html, $matches_var, PREG_PATTERN_ORDER);
	preg_match_all('/,\s*fallback=([a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*)\]/i', $html, $matches_val, PREG_PATTERN_ORDER);
	preg_match_all('/(\[[a-zA-Z0-9!#%^&*()+=$@._\-\:|\/?<>~`"\'\s]+,\s*fallback=[a-zA-Z0-9!,#%^&*()+=$@._\-\:|\/?<>~`"\'\s]*\])/i', $html, $matches_all, PREG_PATTERN_ORDER);
	preg_match_all('/\[([^\]]+),\s*fallback=/i', $html, $matches_var, PREG_PATTERN_ORDER);
	preg_match_all('/,\s*fallback=([^\]]*)\]/i', $html, $matches_val, PREG_PATTERN_ORDER);
	preg_match_all('/(\[[^\]]+,\s*fallback=[^\]]*\])/i', $html, $matches_all, PREG_PATTERN_ORDER);
	$matches_var = $matches_var[1];
	$matches_val = $matches_val[1];
	$matches_all = $matches_all[1];
	for($i=0;$i<count($matches_var);$i++)
	{   
		$field = $matches_var[$i];
		$fallback = $matches_val[$i];
		$tag = $matches_all[$i];
		
		if($field=='Name')
		{
			if($name=='')
			{
				$html = str_replace($tag, $fallback, $html);
			}
			else
			{
				$html = str_replace($tag, $name, $html);
			}
		}
		//otherwise, replace custom field tag
		else
		{					
			$q5 = 'SELECT custom_fields FROM lists WHERE id = '.$subscriber_list;
			$r5 = mysqli_query($mysqli, $q5);
			if ($r5)
			{
			    while($row2 = mysqli_fetch_array($r5)) $custom_fields = $row2['custom_fields'];
			    $custom_fields_array = explode('%s%', $custom_fields);
			    $custom_values_array = explode('%s%', $custom_values);
			    $cf_count = count($custom_fields_array);
			    $k = 0;
			    
			    for($j=0;$j<$cf_count;$j++)
			    {
				    $cf_array = explode(':', $custom_fields_array[$j]);
				    $key = str_replace(' ', '', $cf_array[0]);
				    
				    //if tag matches a custom field
				    if($field==$key)
				    {
				    	if(array_key_exists($j, $custom_values_array)) $cva = $custom_values_array[$j];
				    	else $cva = '';
				    	
				    	//if custom field is empty, use fallback
				    	if($cva=='')
					    	$html = str_replace($tag, $fallback, $html);
				    	//otherwise, use the custom field value
				    	else
				    	{
				    		//if custom field is of 'Date' type, format the date
				    		if($cf_array[1]=='Date')
					    		$html = str_replace($tag, date("D, M d, Y", $cva), $html);
				    		//otherwise just replace tag with custom field value
				    		else
						    	$html = str_replace($tag, $cva, $html);
				    	}
				    }
				    else
				    	$k++;
			    }
			    if($k==$cf_count)
			    	$html = str_replace($tag, $fallback, $html);
			}
		}
	}
	//Name tag
	$html = str_replace('[Name]', $name, $html);
	
	//Email tag
	$html = str_replace('[Email]', $email, $html);
	
	$ar = $table=='ares_emails' ? '/a' : '';
	
	//set web version links
	$html = str_replace('<webversion', '<a href="'.$app_path.'/w/'.encrypt_val($subscriber_id).'/'.encrypt_val($subscriber_list).'/'.encrypt_val($campaign_id).$ar.'" ', $html);
	$html = str_replace('</webversion>', '</a>', $html);
	$html = str_replace('[webversion]', $app_path.'/w/'.encrypt_val($subscriber_id).'/'.encrypt_val($subscriber_list).'/'.encrypt_val($campaign_id).$ar, $html);
	
	//set unsubscribe links
	$html = str_replace('<unsubscribe', '<a href="'.$app_path.'/unsubscribe/'.encrypt_val($email).'/'.encrypt_val($subscriber_list).'/'.encrypt_val($campaign_id).$ar.'" ', $html);
	$html = str_replace('</unsubscribe>', '</a>', $html);
	$html = str_replace('[unsubscribe]', $app_path.'/unsubscribe/'.encrypt_val($email).'/'.encrypt_val($subscriber_list).'/'.encrypt_val($campaign_id).$ar, $html);
	
	//set reconsent links
	$html = str_replace('[reconsent]', $app_path.'/r?e='.encrypt_val($email).'&a='.encrypt_val($app).'&w='.encrypt_val($subscriber_id).'/'.encrypt_val($subscriber_list).'/'.encrypt_val($campaign_id), $html);
	
	//Google translate function
	function get_google_translate_url($url, $translate_to_lang)
	{
		if($translate_to_lang != '')
		{
			//Get language code
			$lang_code_array = explode('/', $translate_to_lang);
			$lang_code = $lang_code_array[1];
			
			//Return google translate URL		
			return 'https://translate.google.com/translate?js=n&sl=auto&tl='.$lang_code.'&u='.$url;
		}
		else return $url;
	}
	
	if(count($i_array)==4 && $i_array[3]=='a')
		$web_version = get_google_translate_url($app_path.'/w/'.$i_array[2].'/a', $web_version_lang);
	else
		$web_version = get_google_translate_url($app_path.'/w/'.$i_array[2], $web_version_lang);
	
	//Update click count
	//if this is an autoresponder web version,	
	$val = '';
	$q = 'SELECT clicks, link FROM links WHERE link = "'.$web_version.'"';
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0)
	{
	    while($row = mysqli_fetch_array($r))
	    {
			$clicks = $row['clicks'];
			$link = $row['link'];
			
			if($clicks=='')
				$val = $subscriber_id;
			else
			{
				$clicks .= ','.$subscriber_id;
				$val = $clicks;
			}
	    }  
	}	
	//Attribute a click to the link
	if($links_tracking && !empty($val))
	{
		$q = 'UPDATE links SET clicks = "'.$val.'" WHERE link = "'.$web_version.'"';
		mysqli_query($mysqli, $q);
	}
	
	//Set open
	$q = count($i_array)==4 && $i_array[3]=='a' ? 'SELECT opens FROM ares_emails WHERE id = '.$campaign_id : 'SELECT opens FROM campaigns WHERE id = '.$campaign_id;
	$r = mysqli_query($mysqli, $q);
	if ($r && mysqli_num_rows($r) > 0)
	{
		$opened = false;
	    while($row = mysqli_fetch_array($r))
	    {
			$opens = $row['opens'];
			if($opens != '')
			{
				$opens_array = explode(',', $opens);
				foreach($opens_array as $open)
				{
					$open_array = explode(':', $open);
					$sid = $open_array[0];
					if($sid == $subscriber_id) $opened = true;
				}
			}
	    }  
	}
	if(!$opened) 
	{
		if(count($i_array)==4 && $i_array[3]=='a') 
			if($opens_tracking) 
				file_get_contents_curl($app_path.'/t/'.$i_array[2].'/'.$i_array[0].'/a');
		else 
			if($opens_tracking) 
				file_get_contents_curl($app_path.'/t/'.$i_array[2].'/'.$i_array[0]);
	}
	
	//Update subscriber's timestamp
	if($links_tracking!=2)
	{
		$q = 'UPDATE subscribers SET timestamp = "'.$time.'" WHERE id = '.$subscriber_id;
		mysqli_query($mysqli, $q);
	}
	
	function file_get_contents_curl($url) 
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$data = curl_exec($ch);
		$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		
		if($response_code!=200) return 'blocked';
		else return $data;
	}
	
	echo $html;
?>