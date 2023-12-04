<?php include('../functions.php');?>
<?php include('../login/auth.php');?>
<?php require_once('../helpers/EmailAddressValidator.php');?>
<?php 
	$subscriber_id = isset($_POST['sid']) && is_numeric($_POST['sid']) ? mysqli_real_escape_string($mysqli, (int)$_POST['sid']) : exit;
	$name = isset($_POST['name']) ? mysqli_real_escape_string($mysqli, $_POST['name']) : '';
	$name = str_replace(array('"', "'"), '', $name);
	$email = isset($_POST['email']) ? mysqli_real_escape_string($mysqli, $_POST['email']) : '';
	$note = isset($_POST['note']) ? mysqli_real_escape_string($mysqli, strip_tags($_POST['note'])) : '';
	$time = time();
	
	$i = 0;
	foreach ($_POST as $key => $value) 
	{
		if($i==1)
		{
			$post_custom_key = $key;
			if(substr($post_custom_key, 0, 4)=='ncf_')
			{
				$post_custom_key_array = explode('ncf_', $post_custom_key);
				$post_custom_key = $post_custom_key_array[1];
			}
			$post_custom_field = $_POST[$key];
			$post_custom_key = str_replace('_dash_', '-', $post_custom_key);
			$post_custom_key = str_replace('_question_', '?', $post_custom_key);
		}
		$i++;
	}
	
	//update name
	if($name!='')
	{
		$q = 'UPDATE subscribers SET name = "'.$name.'" WHERE id = '.$subscriber_id;
		$r = mysqli_query($mysqli, $q);
		if ($r) echo true;
		else echo _('Oops! Unable to save, please try again later.');
	}
	
	//update email
	else if($email!='')
	{
		//Get app ID
		$app = isset($_POST['app']) && is_numeric($_POST['app']) ? mysqli_real_escape_string($mysqli, (int)$_POST['app']) : '';
		
		$q2 = 'SELECT list FROM subscribers WHERE id = '.$subscriber_id;
		$r2 = mysqli_query($mysqli, $q2);
		if ($r2)
		{
		    while($row = mysqli_fetch_array($r2)) 
		    	$list_id = $row['list'];
		}
		
		$q = 'SELECT email FROM subscribers WHERE email = "'.$email.'" AND list = '.$list_id;
		$r = mysqli_query($mysqli, $q);
		if (mysqli_num_rows($r) > 0)
		{
		    echo _('Email already exist in this list.');
		}
		else
		{
			//get email's domain
			$email_explode = explode('@', trim($email));
			$email_domain = $email_explode[1];
			
			//check if email is valid
			$validator = new EmailAddressValidator;
			if ($validator->check_email_address($email))
			{
				$q2 = '(SELECT id FROM suppression_list WHERE email = "'.trim($email).'" AND app = '.$app.') UNION (SELECT id FROM blocked_domains WHERE domain = "'.$email_domain.'" AND app = '.$app.')';
				$r2 = mysqli_query($mysqli, $q2);
				if (mysqli_num_rows($r2) == 0)
				{
					$q = 'UPDATE subscribers SET email = "'.$email.'" WHERE id = '.$subscriber_id;
					$r = mysqli_query($mysqli, $q);
					if ($r) echo true;
					else echo _('Oops! Unable to save, please try again later.');
				}
				else
				{
					//Update block_attempts count				
					$q3 = 'UPDATE suppression_list SET block_attempts = block_attempts+1, timestamp = "'.$time.'" WHERE email = "'.trim($email).'" AND app = '.$app;
					$q4 = 'UPDATE blocked_domains SET block_attempts = block_attempts+1, timestamp = "'.$time.'" WHERE domain = "'.$email_domain.'" AND app = '.$app;
					mysqli_query($mysqli, $q3);
					mysqli_query($mysqli, $q4);
					
					echo _('Email is suppressed.');
				}
			}
			else
				echo _('Email address is invalid.');
		}
	}
	
	else if(isset($_POST['note']))
	{
		$q = 'UPDATE subscribers SET notes = "'.$note.'" WHERE id = '.$subscriber_id;
		$r = mysqli_query($mysqli, $q);
		if ($r) echo true;
		else echo _('Oops! Unable to save, please try again later.');
	}
	
	//if it is a custom field
	else
	{		
		$q = 'SELECT lists.custom_fields as list_custom_fields, subscribers.custom_fields as subscriber_custom_fields FROM lists, subscribers WHERE subscribers.id = '.$subscriber_id.' AND subscribers.list = lists.id';
		$r = mysqli_query($mysqli, $q);
		if ($r && mysqli_num_rows($r) > 0)
		{
		    while($row = mysqli_fetch_array($r))
		    {
				$list_custom_fields = $row['list_custom_fields'];
				$subscriber_custom_fields = $row['subscriber_custom_fields'];
		    }
		    
		    $list_custom_fields_array = explode('%s%', $list_custom_fields);
		    $subscriber_custom_fields_array = explode('%s%', $subscriber_custom_fields);
		    $cf_vals = '';
		    
		    $i = 0;
		    foreach($list_custom_fields_array as $lcf)
		    {
			    $lcf_array = explode(':', $lcf);
			    if(str_replace(" ", "", $lcf_array[0]) == $post_custom_key) 
			    {
			    	if($lcf_array[1]=='Date')
			    	{
						if($post_custom_field != '')
						{
				    		$date_value1 = strtotime($post_custom_field);
							$date_value2 = date("M d, Y 12\a\m", $date_value1);
							$val = strtotime($date_value2);
							$cf_vals .= $val;
						}
						else $cf_vals .= '';
			    	}
			    	else
				    	$cf_vals .= $post_custom_field;
			    }
			    else $cf_vals .= $subscriber_custom_fields_array[$i];
			    
			    $cf_vals .= '%s%';
			    $i++;
		    }
		    
		    $cf_vals = substr($cf_vals, 0, -3);
		    
		    //update subscribers table
		    $q2 = 'UPDATE subscribers SET custom_fields = "'.$cf_vals.'" WHERE id = '.$subscriber_id;
		    $r2 = mysqli_query($mysqli, $q2);
		    if ($r2) echo true; 
		    else echo _('Oops! Unable to save, please try again later.');
		}
	}
?>