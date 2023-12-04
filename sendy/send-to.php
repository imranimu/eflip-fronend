<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/create/main.php');?>
<?php include('includes/helpers/short.php');?>
<?php include('includes/create/timezone.php');?>
<?php require_once('includes/helpers/ses.php'); ?>
<?php
	//IDs
	$cid = isset($_GET['c']) && is_numeric($_GET['c']) ? mysqli_real_escape_string($mysqli, (int)$_GET['c']) : exit;
	$aid = isset($_GET['i']) && is_numeric($_GET['i']) ? get_app_info('app') : exit;
			
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('app')!=get_app_info('restricted_to_app'))
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/send-to?i='.get_app_info('restricted_to_app').'&c='.$cid.'"</script>';
			exit;
		}
	}
	
	//loader
	$loader = get_app_info('dark_mode') ? 'loader-dark.gif' : 'loader-light.gif';
	$loader = get_app_info('path').'/img/'.$loader;
?>

<?php include('js/create/main.php');?>
<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/pickaday/pikaday.js"></script>
<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/pickaday/pikaday.jquery.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo get_app_info('path');?>/css<?php echo get_app_info('dark_mode') ? '/dark' : '';?>/pikaday.css" />
<div class="row-fluid">
    <div class="span2">
        <?php include('includes/sidebar.php');?>
    </div> 
    <div class="span3">
    	<div>
	    	<p class="lead">
		    	<?php if(get_app_info('is_sub_user')):?>
			    	<?php echo get_app_data('app_name');?>
		    	<?php else:?>
			    	<a href="<?php echo get_app_info('path'); ?>/edit-brand?i=<?php echo get_app_info('app');?>" data-placement="right" title="<?php echo _('Edit brand settings');?>"><?php echo get_app_data('app_name');?> <span class="icon icon-pencil top-brand-pencil"></span></a>
		    	<?php endif;?>
		    </p>
    	</div>
    	
    	<div class="alert alert-success" id="test-send" style="display:none;">
		  <button class="close" onclick="$('.alert-success').hide();">×</button>
		  <strong><?php echo _('Email has been sent!');?></strong>
		</div>
		
		<div class="alert alert-error" id="test-send-error" style="display:none;">
		  <button class="close" onclick="$('.alert-error').hide();">×</button>
		  <strong><?php echo _('Sorry, unable to send. Please try again later!');?></strong>
		</div>
		
		<div class="alert alert-error" id="test-send-error2" style="display:none;">
		  <button class="close" onclick="$('.alert-error').hide();">×</button>
		  <p id="test-send-error2-msg"></p>
		</div>
		
		<?php		
	    	//check if cron is set up and get main user's email address
	    	$q = 'SELECT username, cron FROM login WHERE id = '.get_app_info('main_userID');
	    	$r = mysqli_query($mysqli, $q);
	    	if ($r)
	    	{
	    	    while($row = mysqli_fetch_array($r))
	    	    {
	    			$cron = $row['cron'];
	    			$main_user_email = $row['username'];
	    	    }  
	    	}
	    	
	    	$timezone = get_app_info('timezone');
	    	
	    	//get scheduled settings
		    $q = 'SELECT send_date, timezone, from_email, web_version_lang, html_text, ignore_checks FROM campaigns WHERE id = '.$cid;
  			$r = mysqli_query($mysqli, $q);
  			if ($r)
  			{
  			    while($row = mysqli_fetch_array($r))
  			    {
  					$send_date = $row['send_date'];
  					if($row['timezone']!='')
						$timezone = $row['timezone'];
					$from_email = $row['from_email'];
					$web_version_lang = $row['web_version_lang'];
					$from_email_domain_array = explode('@', $from_email);
					$from_email_domain = $from_email_domain_array[1];
  					date_default_timezone_set($timezone);
		    		$hour = date("g", $send_date);
		    		$minute = date("i", $send_date);
		    		$ampm = strtolower(date("A", $send_date));
		    		$the_date = date("D M d Y", $send_date);
					$html_text = $row['html_text'];
					$ignore_checks = $row['ignore_checks'];
  					
  					if($send_date=='')
  					{
	  					$send_newsletter_now = '';
	  					$send_newsletter_text = _('Schedule this campaign?');
	  					$schedule_form_style = 'style="display:none; width:260px;"';
  					}
  					else
  					{
	  					$send_newsletter_now = 'style="display:none;"';
	  					$send_newsletter_text = '&larr; '._('Back');
	  					$schedule_form_style = 'style="width:260px;"';
  					}
  			    }  
  			}
  			
  			//Check if 'ONLY_FULL_GROUP_BY' is present in @@sql_mode
			$q = 'select @@sql_mode';
			$r = mysqli_query($mysqli, $q);
			if ($r) while($row = mysqli_fetch_array($r)) $sql_mode = $row['@@sql_mode'];
			$only_full_group_by = strpos($sql_mode, 'ONLY_FULL_GROUP_BY') !== false ? true : false;
			if($only_full_group_by)
			{
				//Remove ONLY_FULL_GROUP_BY from sql_mode
				$q = 'SET SESSION sql_mode = ""';
				$r = mysqli_query($mysqli, $q);
				if (!$r)
				{
					//ONLY_FULL_GROUP_BY is enabled in sql_mode, campaign cannot be send until 'ONLY_FULL_GROUP_BY' is removed from sql_mode
					echo '<div class="alert alert-danger">
							<p><strong>'._('Please disable \'ONLY_FULL_GROUP_BY\' from \'sql_mode\'').'</strong></p>
							<p>'._('We have detected that \'ONLY_FULL_GROUP_BY\' is enabled in \'sql_mode\' in your MySQL server. Your campaign will fail to send unless \'ONLY_FULL_GROUP_BY\' is removed from \'sql_mode\'. Here\'s how to fix this &rarr; ').'<a href="https://sendy.co/troubleshooting#ubuntu-campaign-sent-to-0-recipients-and-or-autoresponders-not-sending" target="_blank">https://sendy.co/troubleshooting#ubuntu-campaign-sent-to-0-recipients-and-or-autoresponders-not-sending</a></p>
							<p>'._('Once done, refresh this page and this error message should disappear.').'</p>
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
								$("#real-btn").addClass("disabled");
								$("#test-send-btn").addClass("disabled");
								$("#schedule-btn").addClass("disabled");
								$("#real-btn").attr("disabled", "disabled");
								$("#test-send-btn").attr("disabled", "disabled");
								$("#schedule-btn").attr("disabled", "disabled");
								$("#email_list").attr("disabled", "disabled");
							});
						</script>';
					
					error_log("[Unable to set sql_mode]".mysqli_error($mysqli).': in '.__FILE__.' on line '.__LINE__);
				}
			}
			
			//Get brand data
			$q = 'SELECT templates_lists_sorting, smtp_host, smtp_port, smtp_ssl, smtp_username, smtp_password FROM apps WHERE id = '.get_app_info('app');
			$r = mysqli_query($mysqli, $q);
			if ($r && mysqli_num_rows($r) > 0) 
			{
				while($row = mysqli_fetch_array($r)) 
				{
					$smtp_host = $row['smtp_host'];
					$smtp_port = $row['smtp_port'];
					$smtp_ssl = $row['smtp_ssl'];
					$smtp_username = $row['smtp_username'];
					$smtp_password = $row['smtp_password'];
					$templates_lists_sorting = $row['templates_lists_sorting'];
				}
			}
			
			//Use appropriate email sender icon
			if($smtp_host == 'smtp.elasticemail.com' && $smtp_port!='' && $smtp_username!='' && $smtp_password!='')
				$smtp_label_img = '<img src="'.get_app_info('path').'/img/elasticemail.png" title="" class="send-newsletter-now-icon"/>';
			else if($smtp_host == 'smtp.sendgrid.net' && $smtp_port!='' && $smtp_username!='' && $smtp_password!='')
				$smtp_label_img = '<img src="'.get_app_info('path').'/img/sendgrid.png" title="" class="send-newsletter-now-icon"/>';
			else if($smtp_host == 'in-v3.mailjet.com' && $smtp_port!='' && $smtp_username!='' && $smtp_password!='')
				$smtp_label_img = '<img src="'.get_app_info('path').'/img/mailjet.png" title="" class="send-newsletter-now-icon"/>';
			else if($smtp_host != '' && $smtp_port!='' && $smtp_username!='' && $smtp_password!='')
				$smtp_label_img = '<span class="icon icon-envelope-alt"></span>';
			else if(get_app_info('s3_key')!='' && get_app_info('s3_secret')!='')
				$smtp_label_img = '<img src="'.get_app_info('path').'/img/aws.png" title="" class="send-newsletter-now-icon"/>';
			else
				$smtp_label_img = '<span class="icon icon-ok"></span>';
			
			//Check if user is using SMTP or Amazon SES to send emails
			if($smtp_host!='' && $smtp_port!='' && $smtp_username!='' && $smtp_password!='')
				$aws_keys_available = 'false';
			else if(get_app_info('s3_key')!='' && get_app_info('s3_secret')!='')
				$aws_keys_available = 'true';
			else
				$aws_keys_available = 'false';
  			
  			//If IAM keys exists
  			if($aws_keys_available=='true')
  			{
				//Check if it's a brand user
				$is_brand_user = !get_app_info('is_sub_user') ? false : true;
				$content_title = _('Please verify your email address');
				$content_body = _('Before you can use the email address as your \'From email\' to send emails, please click the following link to verify that the email address belongs to you');
				
				//Check if from email is verified in SES console
				$ses = new SimpleEmailService(get_app_info('s3_key'), get_app_info('s3_secret'), get_app_info('ses_endpoint'));
				$verify_identity_of_from_email = verify_identity($from_email);
				$verify_identity_of_login_email = verify_identity($main_user_email);
				
				if($verify_identity_of_from_email=='api_error')
				{
					//Unable to commuincate with Amazon SES API
					echo '<div class="alert alert-danger">
							<p><strong>'._('Unable to communicate with Amazon SES API').'</strong></p>
							<p>'._('Visit your "Brands" page by clicking your company\'s name at the top left of the screen. Then check the instructions on the left sidebar on how to resolve this issue').'</p>
						</div>
						<script type="text/javascript">
							$(document).ready(function() {
								$("#real-btn").addClass("disabled");
								$("#test-send-btn").addClass("disabled");
								$("#schedule-btn").addClass("disabled");
								$("#real-btn").attr("disabled", "disabled");
								$("#test-send-btn").attr("disabled", "disabled");
								$("#schedule-btn").attr("disabled", "disabled");
								$("#email_list").attr("disabled", "disabled");
							});
						</script>';
				}
				else
				{				
					if($verify_identity_of_from_email == 'unverified')
					{				
						//If main admin user login email address is not verified in Amazon SES console, send the generic verification email from Amazon if logged in as main user
						if($verify_identity_of_login_email != 'verified')
						{
							//Send the generic verification email from Amazon if logged in as main user
							if(!$is_brand_user)
							{
								//Verify email address
								$ses->verifyEmailAddress($from_email);
	
								show_unverified_email_error();
							}
						}
						//Otherwise, create a custom verification template and send that instead
						else
						{
							//Create custom verification email template			
							$ses->deleteCustomVerificationEmailTemplate('SendyVerificationTemplate');
							$ses->createCustomVerificationEmailTemplate($main_user_email, get_app_info('path'), $content_title, $content_body);
							
							//Send custom verification email
							$ses->sendCustomVerificationEmail($from_email);
							
							show_unverified_email_error();
						}
					}
					else if($verify_identity_of_from_email == 'pending')
					{
						//If logged in as main user, show 'pending' identity error
						if(!$is_brand_user)
						{
							echo '
								<div class="alert alert-danger">
									<p><strong>\''.$from_email.'\' '._('or').' \''.$from_email_domain.'\' '._('is pending verification in your Amazon SES console').'</strong></p>
									<p>'._('Your \'From email\' or its domain is pending verification in your Amazon SES console. Please complete the verification then refresh this page to proceed.').'</p>
								</div>
								<script type="text/javascript">
									$(document).ready(function() {
										$("#real-btn").addClass("disabled");
										$("#test-send-btn").addClass("disabled");
										$("#schedule-btn").addClass("disabled");
										$("#real-btn").attr("disabled", "disabled");
										$("#test-send-btn").attr("disabled", "disabled");
										$("#schedule-btn").attr("disabled", "disabled");
										$("#email_list").attr("disabled", "disabled");
										$("#email_list_exclude").attr("disabled", "disabled");
										$("#pay-btn").addClass("disabled");
									});
								</script>';
						}
						else
						{
							//Create custom verification email template			
							$ses->deleteCustomVerificationEmailTemplate('SendyVerificationTemplate');
							$ses->createCustomVerificationEmailTemplate($main_user_email, get_app_info('path'), $content_title, $content_body);
							
							//Send custom verification email
							$ses->sendCustomVerificationEmail($from_email);
							
							show_unverified_email_error();
						}
					}
					else
					{
						//Set email feedback forwarding to false
						$ses->setIdentityFeedbackForwardingEnabled($from_email, 'false');
						$ses->setIdentityFeedbackForwardingEnabled($from_email_domain, 'false');
					}
				}	
			}
			
			function show_unverified_email_error()
			{
				global $is_brand_user;
				global $from_email;
				
				$unverified_email_error = !$is_brand_user 
				? _('Your \'From email\' or its domain is not verified in your Amazon SES console. A verification email has been sent to your \'From email\' address with a confirmation link to complete the verification. Please click the link to complete the verification, then refresh this page.') 
				: _('A verification email has been sent to your \'From email\' address with a confirmation link. Please click the link to verify you are the owner of the \'From email\', then refresh this page.');
				
				//From email address or domain is not verified in SES console
				echo '<div class="alert alert-danger">
					<p><strong>'._('Unverified \'From email\'').': '.$from_email.'</strong></p>
					<p>'.$unverified_email_error.'</p>
				</div>
				<script type="text/javascript">
					$(document).ready(function() {
						$("#real-btn").addClass("disabled");
						$("#test-send-btn").addClass("disabled");
						$("#schedule-btn").addClass("disabled");
						$("#real-btn").attr("disabled", "disabled");
						$("#test-send-btn").attr("disabled", "disabled");
						$("#schedule-btn").attr("disabled", "disabled");
						$("#email_list").attr("disabled", "disabled");
						$("#email_list_exclude").attr("disabled", "disabled");
						$("#pay-btn").addClass("disabled");
					});
				</script>';
			}
			
			//Get sorting preference
			$sortby = $templates_lists_sorting=='date' ? 'id DESC' : 'name ASC';
			
	    ?>
    	
    	<h2><?php echo _('Test send this campaign');?></h2><br/>
	    <form action="<?php echo get_app_info('path')?>/includes/create/test-send.php" method="POST" accept-charset="utf-8" class="form-vertical" id="test-form">	    
	    	<label class="control-label" for="test_email"><?php echo _('Test email(s)');?></label>
	    	<div class="control-group">
		    	<div class="controls">
	              <input type="text" class="input-xlarge" id="test_email" name="test_email" placeholder="<?php echo _('Email addresses, separated by commas');?>" value="<?php echo get_app_data('test_email');?>" style="width: 85%;">
	            </div>
	        </div>
	        <input type="hidden" name="cid" value="<?php echo $cid;?>">
	        <input type="hidden" name="display_errors" value="<?php echo isset($_GET['display_errors']) ? '1' : '0';?>">
	        <button type="submit" class="btn" id="test-send-btn"><?php echo $smtp_label_img;?> <?php echo _('Test send this newsletter');?></button>
	    </form>
	    
	    <br/>
	    <h2><?php echo _('Define recipients');?></h2><br/>
		    <?php if(get_app_info('is_sub_user')):?>
			    <?php if(paid()):?>
				<form action="<?php echo get_app_info('path')?>/includes/create/send-now.php" method="POST" accept-charset="utf-8" class="form-vertical" id="real-form">
			    <?php else:?>
				<form action="<?php echo get_app_info('path')?>/payment" method="POST" accept-charset="utf-8" class="form-vertical" id="pay-form">
			    <?php endif;?>	    
			<?php else:?>
				<form action="<?php echo get_app_info('path')?>/includes/create/send-now.php" method="POST" accept-charset="utf-8" class="form-vertical" id="real-form">
			<?php endif;?>
	    	<div class="control-group">
	            <label class="control-label" for="multiSelect"><span class="icon icon-plus-sign"></span> <?php echo _('Choose your lists & segments');?></label>
	            <div class="controls">
	              <select multiple="multiple" id="email_list" name="email_list[]" style="height:200px; width: 85%;">
				  		<optgroup label="Lists">
						<?php 
							$q = 'SELECT * FROM lists WHERE app = '.get_app_info('app').' AND userID = '.get_app_info('main_userID').' AND hide = 0 ORDER BY '.$sortby;
							$r = mysqli_query($mysqli, $q);
							if ($r && mysqli_num_rows($r) > 0)
							{
							    while($row = mysqli_fetch_array($r))
							    {
									$list_id = stripslashes($row['id']);
									$list_name = stripslashes($row['name']);
									$list_selected = '';
									
									$q2 = 'SELECT lists FROM campaigns WHERE id = '.$cid;
									$r2 = mysqli_query($mysqli, $q2);
									if ($r2)
									{
									    while($row = mysqli_fetch_array($r2))
									    {
											$lists = $row['lists'];
											if($lists!='')
											{
												$lists_array = explode(',', $lists);
												if(in_array($list_id, $lists_array))
													$list_selected = 'selected';
											}
									    }  
									}
									
									echo '<option value="'.$list_id.'" data-quantity="'.get_list_quantity($list_id).'" id="'.$list_id.'" '.$list_selected.'>'.$list_name.'</option>';
							    }  
							}
							else
							{
						  	echo '<option value="" onclick="window.location=\''.get_app_info('path').'/new-list?i='.$aid.'\'">'._('No list found, click to add one.').'</option>';
							}
						?>
						<option disabled></option>
						<?php if(have_segments()):?>
							<optgroup label="<?php echo _('Segments');?>">
							<?php 
								$q = 'SELECT id, name, list FROM seg WHERE app = '.get_app_info('app');
								$r = mysqli_query($mysqli, $q);
								if ($r && mysqli_num_rows($r) > 0)
								{
								    while($row = mysqli_fetch_array($r))
								    {
								    	$seg_id = $row['id'];
										$seg_name = $row['name'];
										$seg_list_id = $row['list'];
										$list_selected = '';
										
										$q2 = 'SELECT segs FROM campaigns WHERE id = '.$cid;
										$r2 = mysqli_query($mysqli, $q2);
										if ($r2)
										{
										    while($row = mysqli_fetch_array($r2))
										    {
												$segs = $row['segs'];
												
												if($segs!='')
												{
													$segs_array = explode(',', $segs);
													if(in_array($seg_id, $segs_array))
														$list_selected = 'selected';
												}
										    }  
										}
										
										echo '<option value="'.$seg_id.'" data-is-seg="yes" id="seg_'.$seg_id.'" '.$list_selected.'>'.$seg_name.'</option>';
									}
								}
							?>
						<?php else:?>
							<optgroup label="<?php echo _('Segments');?>" style="color:#dddddd;">
							<option disabled><?php echo _('No segments found');?></option>
						<?php endif;?>
	              </select><br/>
	              
	              <p id="excl" style="margin-top:5px;"><a href="javascript:void(0)" class="btn" id="exclude_btn"><span class="icon icon-minus-sign"></span> <?php echo _('Exclude lists from this campaign?');?></a><br/><br/></p>
	              <script type="text/javascript">
		              $(document).ready(function() {
					  	$("#exclude_btn").click(function(){
						  	$("#excl").slideUp();
						  	$("#exclude_list_select").slideDown();
					  	});
					  });
	              </script>
	            </div>
            </div>
            
            <!-- Exclude lists -->
            <div class="control-group" id="exclude_list_select" style="display:none;">
	            <label class="control-label" for="multiSelect" width="200"><span class="icon icon-minus-sign"></span> <?php echo _('Don\'t include emails from these list & segments');?></label>
	            <div class="controls">
					<select multiple="multiple" id="email_list_exclude" name="email_list_exclude[]" style="height:200px; width: 85%;">
						<optgroup label="Lists">
						<?php 
							$q = 'SELECT * FROM lists WHERE app = '.get_app_info('app').' AND userID = '.get_app_info('main_userID').' AND hide = 0 ORDER BY '.$sortby;
							$r = mysqli_query($mysqli, $q);
							if ($r && mysqli_num_rows($r) > 0)
							{
							    while($row = mysqli_fetch_array($r))
							    {
									$list_id = stripslashes($row['id']);
									$list_name = stripslashes($row['name']);
									$list_selected = '';
									
									$q2 = 'SELECT lists_excl, segs_excl FROM campaigns WHERE id = '.$cid;
									$r2 = mysqli_query($mysqli, $q2);
									if ($r2)
									{
									    while($row = mysqli_fetch_array($r2))
									    {
											$lists = $row['lists_excl'];
											$segs = $row['segs_excl'];
											if($lists != '' || $segs !='')
											{
							  					echo '<script charset="utf-8">
									            	$(document).ready(function() {
														$("#excl").hide();
														$("#exclude_list_select").show();
													});
									            </script>';
											}
											
											if($lists!='')
											{
												$lists_array = explode(',', $lists);
												if(in_array($list_id, $lists_array))
													$list_selected = 'selected';
											}
									    }  
									}
									
									echo '<option value="'.$list_id.'" id="excl_'.$list_id.'" '.$list_selected.'>'.$list_name.'</option>';
							    }  
							}
							else
							{
						  	echo '<option value="" onclick="window.location=\''.get_app_info('path').'/new-list?i='.$aid.'\'">'._('No list found, click to add one.').'</option>';
							}
						?>
						<option disabled></option>
						<?php if(have_segments()):?>
							<optgroup label="<?php echo _('Segments');?>">
							<?php 
								$q = 'SELECT id, name, list FROM seg WHERE app = '.get_app_info('app');
								$r = mysqli_query($mysqli, $q);
								if ($r && mysqli_num_rows($r) > 0)
								{
								    while($row = mysqli_fetch_array($r))
								    {
								    	$seg_id = $row['id'];
										$seg_name = $row['name'];
										$seg_list_id = $row['list'];
										$list_selected = '';
										
										$q2 = 'SELECT segs_excl FROM campaigns WHERE id = '.$cid;
										$r2 = mysqli_query($mysqli, $q2);
										if ($r2)
										{
										    while($row = mysqli_fetch_array($r2))
										    {
												$segs_excl = $row['segs_excl'];
												
												if($segs_excl!='')
												{
													$segs_excl_array = explode(',', $segs_excl);
													if(in_array($seg_id, $segs_excl_array))
														$list_selected = 'selected';
												}
										    }  
										}
										
										echo '<option value="'.$seg_id.'" data-is-seg="yes" id="excl_seg_'.$seg_id.'" '.$list_selected.'>'.$seg_name.'</option>';
									}
								}
							?>
						<?php else:?>
							<optgroup label="<?php echo _('Segments');?>" style="color:#dddddd;">
							<option disabled><?php echo _('No segments found');?></option>
						<?php endif;?>
					</select>
				</div>
            </div>
	        <input type="hidden" name="cid" value="<?php echo $cid;?>">
	        <input type="hidden" name="uid" value="<?php echo $aid;?>">
	        <input type="hidden" name="path" value="<?php echo get_app_info('path');?>">
	        <input type="hidden" name="grand_total_val" id="grand_total_val">
	        <input type="hidden" name="cron" value="<?php echo $cron;?>">
	        <input type="hidden" name="total_recipients" id="total_recipients">
	        <input type="hidden" name="in_list" id="in_list">
	        <input type="hidden" name="ex_list" id="ex_list">
	        <input type="hidden" name="in_list_seg" id="in_list_seg">
	        <input type="hidden" name="ex_list_seg" id="ex_list_seg">
	        
	        <?php				
	        	//Get SES quota (array)
	        	if($aws_keys_available=='true')
	        	{
					$ses = new SimpleEmailService(get_app_info('s3_key'), get_app_info('s3_secret'), get_app_info('ses_endpoint'));
					$quotaArray = array();
					foreach($ses->getSendQuota() as $quota){
						array_push($quotaArray, $quota);
					}
					$ses_quota = round($quotaArray[0]);
					$ses_send_rate = round($quotaArray[1]);
				}
				
				//Update send_rate into database if user is using Amazon SES to send emails
				if($aws_keys_available=='true' && (get_app_info('send_rate')=='' || get_app_info('send_rate')==0)) mysqli_query($mysqli, 'UPDATE login SET send_rate = '.$ses_send_rate);
					
	        	//Get limits (SES or brand limits) depending if user is a main or sub user
				if(get_app_info('is_sub_user'))
				{
					$allocated_quota = get_app_data('allocated_quota');
					$day_of_reset = get_app_data('day_of_reset');
					$month_of_next_reset = get_app_data('month_of_next_reset');
					$year_of_next_reset = get_app_data('year_of_next_reset');
					$no_expiry = get_app_data('no_expiry');
					
		        	//Brand limits
					$today_unix_timestamp = time();
					$brand_monthly_quota = $allocated_quota;
					if($brand_monthly_quota!=-1)
					{
						//Check if limit needs to be reset					
						$day_today = date("j", $today_unix_timestamp);
						$month_today = date("M", $today_unix_timestamp);
						$year_today = date("Y", $today_unix_timestamp);
						
						//Find the number of the last day of this month
						$no_of_days_this_month = cal_days_in_month(CAL_GREGORIAN, date("m", $today_unix_timestamp), $year_today);
						
						$brand_limit_resets_on = $day_of_reset>$no_of_days_this_month ? $no_of_days_this_month : $day_of_reset;
						
						//Get UNIX timestamp of 'date today' and 'date of next reset' for comparison
						$date_today_unix = strtotime($day_today.' '.$month_today.' '.$year_today);
						$date_on_reset_unix = strtotime($brand_limit_resets_on.' '.$month_of_next_reset.' '.$year_of_next_reset);
						
						//If date of reset has already passed today's date, reset current limit to 0
						if($date_today_unix>=$date_on_reset_unix)
						{
							//If today's 'day' is passed 'day_of_reset', +1 month for next reset's month
							if($day_today >= $brand_limit_resets_on) $plus_one_month = '+1 month';
							
							//Prepare day, month and year of next reset
							$month_next_unix = strtotime('1 '.$month_today.' '.$year_today.' '.$plus_one_month);
							$month_next = date("M", $month_next_unix);
							$year_next = date("Y", $month_next_unix);
							
							//If brand limits is set to 'No expiry'
							if(!$no_expiry)
							{
								//Reset current limit to 0 and set the month_of_next_reset to the next month
								$q = 'UPDATE apps SET current_quota = 0, month_of_next_reset = "'.$month_next.'", year_of_next_reset = "'.$year_next.'" WHERE id = '.get_app_info('app');
								$r = mysqli_query($mysqli, $q);
								if($r) 
								{
									//Update new $month_of_next_reset
									$month_of_next_reset = $month_next;
								}
							}
						}
						
						//If brand limits is set to 'No expiry'
						if(!$no_expiry)
						{
							//Calculate day of reset for next month
							$month_next = strtotime('1 '.$month_of_next_reset);
							$month_next = date("m", $month_next);
							$no_of_days_next_month = cal_days_in_month(CAL_GREGORIAN, $month_next, $year_today);
							$brand_limit_resets_on = $day_of_reset>$no_of_days_next_month ? $no_of_days_next_month : $day_of_reset;
							$resets_on = ' ('._('resets on').' '.$month_of_next_reset.' '.$brand_limit_resets_on.')';
						}
						else $resets_on = '';
						
						//Get sends left
						$brand_current_quota = get_app_data('current_quota');
						$brand_sends_left = $brand_monthly_quota - $brand_current_quota;
						$ses_sends_left = $brand_sends_left;
					}
					else $ses_sends_left = -1; //unlimited sending
				}
				else
				{
					if($aws_keys_available=='true') $ses_sends_left = round($quotaArray[0]-$quotaArray[2]);
				}
	    	?>
	        
	        <?php if(get_app_info('is_sub_user')):?>
	        
	        	<input type="hidden" id="ses_sends_left" value="<?php echo $ses_sends_left;?>"/>
	        	<input type="hidden" id="aws_keys_available" value="<?php echo $aws_keys_available;?>"/>
	        	<input type="hidden" id="is_sub_user" value="true"/>
	        	
		        <?php if(paid()):?>
		        
			        <?php if($brand_monthly_quota!=-1):?><strong><?php echo _('Monthly limit');?></strong>: <?php echo $brand_monthly_quota.$resets_on;?><br/><?php endif;?>
		        	<strong><?php echo _('Recipients');?></strong>: <span id="recipients">0</span> 
		        	<?php if($brand_monthly_quota!=-1) echo '<span id="remaining">'._('of').' '.$brand_sends_left._(' remaining').'</span>'?><br/><br/>
		        	
		        	<!-- over limit msg -->
			    	<div class="alert alert-error" id="over-limit" style="display:none;">
					  <?php echo _('You can\'t send more than your monthly limit. Request for your limit to be raised by sending an email to').' <a href="mailto:'.$main_user_email.'">'.$main_user_email.'</a>';?> 
					</div>
			    	<!-- /over limit msg -->
			    	
			        <button type="submit" class="btn btn-inverse btn-large" id="real-btn" <?php echo $send_newsletter_now;?>><?php echo $smtp_label_img;?> <?php echo _('Send newsletter now!');?></button>
			        
			        <!-- success msg -->
			        <div id="view-report" class="alert alert-success" style="margin-top: 20px; display:none;">
			    		<p><h3><?php echo _('Your campaign is now sending!');?></h3></p>
			    		<p><?php echo _('You can safely close this window, your campaign will continue to send.');?></p>
			    		<p><?php echo _('You will be notified by email once your campaign has completed sending.');?></p>
			    	</div>
			        <!-- /success msg -->
			    	
			        <p style="margin-top:10px; text-decoration:underline;">
			        	<?php if($cron):?>
			        	<a href="javascript:void(0)" id="send-later-btn"><?php echo $send_newsletter_text;?></a>
			        	<?php endif;?>
			        </p>
			        
		        <?php else:?>
			        <input type="hidden" name="paypal" value="<?php echo get_paypal();?>">
			        <div class="well" style="width:260px;">
			        	<?php if($brand_monthly_quota!=-1):?><strong><?php echo _('Monthly limit');?></strong>: <?php echo $brand_monthly_quota.$resets_on;?><br/><?php endif;?>
				        <strong><?php echo _('Recipients');?></strong>: <span id="recipients">0</span> 
				        <?php if($brand_monthly_quota!=-1) echo '<span id="remaining">'._('of').' '.$brand_sends_left._(' remaining').'</span>'?><br/>
				        <strong><?php echo _('Delivery Fee');?></strong>: <?php echo get_fee('currency');?> <span id="delivery_fee"><?php echo get_fee('delivery_fee');?></span><br/>
				        <strong><?php echo _('Fee per recipient');?></strong>: <?php echo get_fee('currency');?> <span id="recipient_fee"><?php echo get_fee('cost_per_recipient');?></span><br/><br/>
				        <span class="grand_total"><strong><?php echo _('Grand total');?></strong>: <?php echo get_fee('currency');?> <span id="grand_total">0</span></span>
			        </div>
			        
			        <!-- over limit msg -->
			    	<div class="alert alert-error" id="over-limit" style="display:none;">
					  <?php echo _('You can\'t send more than your monthly limit. Request for your limit to be raised by sending an email to').' <a href="mailto:'.$main_user_email.'">'.$main_user_email.'</a>.';?> 
					</div>
			    	<!-- /over limit msg -->
			        
			        <button type="submit" class="btn btn-inverse btn-large" id="pay-btn" <?php echo $send_newsletter_now;?>><i class="icon-arrow-right icon-white"></i> <?php echo _('Proceed to pay for campaign');?></button>
			        <p style="margin-top:10px; text-decoration:underline;">
			        	<?php if($cron):?>
			        	<a href="javascript:void(0)" id="send-later-btn"><?php echo $send_newsletter_text;?></a>
			        	<?php endif;?>
			        </p>
			        
		        <?php endif;?>
		        
		    <?php else:?>
		    
		    	<strong><?php echo _('Recipients');?></strong>: <span id="recipients">0</span> <span id="remaining"><?php echo $aws_keys_available=='true' ? _('of') : '';?> <?php echo $aws_keys_available=='true' ? $ses_sends_left : ''; echo $aws_keys_available=='true' ? _(' remaining') : '';?></span><br/>
		    	
		    	<?php if($aws_keys_available=='true'):?>
		    	<strong><?php echo _('SES sends left');?></strong>: <span id="sends_left"><?php echo $ses_sends_left.' of '.$ses_quota;?></span><br/>
		    	
			    	<?php if($ses_sends_left==0 && $ses_quota==0):?>
			    	<br/><p class="alert alert-danger"><?php echo _('Unable to get your SES quota from Amazon. Visit your "Brands" page by clicking your company\'s name at the top left of the screen. Then check the instructions on the left sidebar on how to resolve this issue');?></p>
			    	<?php endif;?>
		    	
		    	<?php endif;?>
		    	<br/>
		    	
		    	<?php 	
			    	if($aws_keys_available=='true')
			    	{				    						
						//Check bounces & complaints handling setup
						require_once('includes/helpers/sns.php');
						$aws_endpoint_array = explode('.', get_app_info('ses_endpoint'));
						$aws_endpoint = $aws_endpoint_array[1];
						$sns = new AmazonSNS(get_app_info('s3_key'), get_app_info('s3_secret'), $aws_endpoint);
						$bounces_topic_arn = '';
						$bounces_subscription_arn = '';
						$complaints_topic_arn = '';
						$complaints_subscription_arn = '';
						//Get protocol of endpoint
					    $protocol_array = explode(':', get_app_info('path'));
					    $protocol = $protocol_array[0];
						try 
						{
							//Get list of SNS topics and subscriptions
							$v_subscriptions = $sns->ListSubscriptions();
							foreach ($v_subscriptions as $subscription)
							{
								$TopicArn = $subscription['TopicArn'];
								$Endpoint = $subscription['Endpoint'];
								if($Endpoint==get_app_info('path').'/includes/campaigns/bounces.php' || $Endpoint==get_app_info('path').'includes/campaigns/bounces.php')
								{
									$bounces_topic_arn = $TopicArn;
									$bounces_subscription_arn = $Endpoint;
								}
								if($Endpoint==get_app_info('path').'/includes/campaigns/complaints.php' || $Endpoint==get_app_info('path').'includes/campaigns/complaints.php')
								{
									$complaints_topic_arn = $TopicArn;
									$complaints_subscription_arn = $Endpoint;
								}
							}
							
							//Create 'bounces' SNS topic
						    try {$bounces_topic_arn = $sns->CreateTopic('bounces');}
							catch (SNSException $e) {echo '<p class="error">'._('Error').' ($sns->CreateTopic(\'bounces\')): '.$e->getMessage().'. '._('Please try again by refreshing this page. If this error persist, visit your Amazon SNS console and delete all \'Topics\' and \'Subscriptions\' and try again.')."<br/><br/></p>";}
							
							//Create 'complaints' SNS topic
							try {$complaints_topic_arn = $sns->CreateTopic('complaints');}
							catch (SNSException $e) {echo '<p class="error">'._('Error').' ($sns->CreateTopic(\'complaints\')): '.$e->getMessage().'. '._('Please try again by refreshing this page. If this error persist, visit your Amazon SNS console and delete all \'Topics\' and \'Subscriptions\' and try again.')."<br/><br/></p>";}
						    
						    //If 'bounces' and 'complaints' SNS topics exists, create SNS subscriptions for them
						    if($bounces_topic_arn!='' && $complaints_topic_arn!='')
						    {
							    //Create 'bounces' SNS subscription
								try {$bounces_subscribe_endpoint = $sns->Subscribe($bounces_topic_arn, $protocol, get_app_info('path').'/includes/campaigns/bounces.php');}
								catch (SNSException $e) {echo '<p class="error">'._('Error').' ($sns->Subscribe(\'bounces\')): '.$e->getMessage().'. '._('Please try again by refreshing this page. If this error persist, visit your Amazon SNS console and delete all \'Topics\' and \'Subscriptions\' and try again.')."<br/><br/></p>";}
								
								//Create 'complaints' SNS subscription
								try {$complaints_subscribe_endpoint = $sns->Subscribe($complaints_topic_arn, $protocol, get_app_info('path').'/includes/campaigns/complaints.php');}
								catch (SNSException $e) {echo '<p class="error">'._('Error').' ($sns->Subscribe(\'complaints\')): '.$e->getMessage().'. '._('Please try again by refreshing this page. If this error persist, visit your Amazon SNS console and delete all \'Topics\' and \'Subscriptions\' and try again.')."<br/><br/></p>";}
						    }
						    else echo '<p class="error">'._('Error: Unable to create bounces and complaints SNS topics, please try again by refreshing this page.')."<br/><br/></p>";
						    
						    //Set SNS 'Notifications' for 'From email'
							$ses = new SimpleEmailService(get_app_info('s3_key'), get_app_info('s3_secret'), get_app_info('ses_endpoint'));
							
							//Set 'bounces' Notification
							$ses->SetIdentityNotificationTopic($from_email,$bounces_topic_arn,'Bounce');
							$ses->SetIdentityNotificationTopic($from_email_domain,$bounces_topic_arn,'Bounce');
							
							//Set 'complaints' Notification
							$ses->SetIdentityNotificationTopic($from_email,$complaints_topic_arn,'Complaint');
							$ses->SetIdentityNotificationTopic($from_email_domain,$complaints_topic_arn,'Complaint');
							
							//Disable email feedback forwarding
							$ses->setIdentityFeedbackForwardingEnabled($from_email, 'false');
							$ses->setIdentityFeedbackForwardingEnabled($from_email_domain, 'false');
	
						} 
						catch (Exception $e) 
						{
							echo '
							<script type="text/javascript">
								$(document).ready(function() {
									$("#real-btn").addClass("disabled");
									$("#test-send-btn").addClass("disabled");
									$("#schedule-btn").addClass("disabled");
									$("#real-btn").attr("disabled", "disabled");
									$("#test-send-btn").attr("disabled", "disabled");
									$("#schedule-btn").attr("disabled", "disabled");
									$("#email_list").attr("disabled", "disabled");
								});
							</script>
							';
							
							if($e->getMessage()=='AuthorizationError'):
					
			    ?>
			    
							    <div class="alert alert-danger" id="amazon-sns-access">
								  <p><?php echo _('Sendy is unable to verify and setup bounces & complaints handling for your \'From email\' address. Here\'s what you need to do: ');?> </p>
								  <p>
									  <ol>
										  <li style="margin-bottom: 10px;">
										  		<?php echo _('Visit your <a href="https://console.aws.amazon.com/iam/home#/users" target="_blank">IAM console</a> then follow the instructions in <a href="#amazonsnsfullaccess" data-toggle="modal">this video</a>.');?>
										  </li>
										  <li><?php echo _('Then refresh this page.');?></li>
									  </ol>
								  </p>
								  <p><?php echo _('Once this is done, Sendy will be able to setup bounces & complaints handling automatically.');?></p>
								</div>
								
								<!-- Video instructions -->
								<div id="amazonsnsfullaccess" class="modal hide fade">
								<div class="modal-header">
								  <button type="button" class="close" data-dismiss="modal">&times;</button>
								  <h3><i class="icon icon-time" style="margin-top: 5px;"></i> <?php echo _('How to add AmazonSNSFullAccess policy to IAM user ');?></h3>
								</div>
								<div class="modal-body">
								<p><iframe src="https://player.vimeo.com/video/198768239" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen width="522" height="337"></iframe></p>
								<p><a href="https://vimeo.com/198768239" target="_blank" style="text-decoration: underline;">Watch this video in a new tab &rarr;</a></p>
								</div>
								<div class="modal-footer">
								  <a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon icon-ok-sign"></i> <?php echo _('Okay');?></a>
								</div>
								</div>
								<!-- Video instructions -->
			    
			    <?php 		
				    		else:
				    		
					    		echo '<p class="error">'._('Error communicating with Amazon SNS API').': '.$e->getMessage().'</p>';
				    		
				    		endif; 
				    	}
					}
				?>
		    	
		    	<?php if($aws_keys_available=='true' && $ses_quota==200):?>
		    	<div class="alert" id="no-production-access">
				  <?php echo _('It looks like you are still in Amazon SES "Sandbox mode". You can only send to email addresses that you\'ve verified in your');?> <a href="https://us-east-1.console.aws.amazon.com/ses/home#/verified-identities" target="_blank" style="text-decoration:underline"><?php echo _('Amazon SES console.');?></a> <?php echo _('If you try to send to email addresses NOT verified in your SES console, your recipient will not receive the email.');?><br/><br/><a href="https://sendy.co/troubleshooting#moving-out-of-amazon-ses-sandbox-mode" target="_blank" style="text-decoration:underline"><?php echo _('Request Amazon to move your Amazon SES account out of "Sandbox mode"');?></a> <?php echo _('to lift this restriction.');?><br/>
				</div>
				<?php endif;?>
		    	
		    	<!-- over limit msg -->
		    	<div class="alert alert-error" id="over-limit" style="display:none;">
				  <?php echo _('You can\'t send more than your SES daily limit. Either wait till Amazon replenishes your daily limit in the next 24 hours, or request Amazon to');?> <a href="https://sendy.co/troubleshooting#raising-your-daily-sending-limit" target="_blank" style="text-decoration:underline">raise your daily sending limits</a> if you have already moved out of Amazon SES's sandbox mode. 
				</div>
		    	<!-- /over limit msg -->
		    	
		    	<input type="hidden" id="ses_sends_left" value="<?php echo $aws_keys_available=='true' ? $ses_sends_left : 0;?>"/>
		    	<input type="hidden" id="aws_keys_available" value="<?php echo $aws_keys_available;?>"/>
		    	<button type="submit" class="btn btn-inverse btn-large" id="real-btn" <?php echo $send_newsletter_now;?>><?php echo $smtp_label_img;?> <?php echo _('Send newsletter now!');?></button>
		    	
		    	<div id="view-report" class="alert alert-success" style="margin-top: 20px; display:none;">
		    		<p><h3><?php echo _('Your campaign is now sending!');?></h3></p>
		    		<p><?php echo _('You can safely close this window, your campaign will continue to send.');?></p>
		    		<p><?php echo _('You will be notified by email once your campaign has completed sending.');?></p>
		    	</div>
		    	
		    	<?php if(!$cron):?>
		    	<br/><br/>
		    	<div class="alert alert-info">
			    	<p><i class="icon icon-info-sign"></i> <?php echo _('We recommend');?> <a href="#cron-instructions" data-toggle="modal" style="text-decoration:underline"><?php echo _('setting up a cron job');?></a> <?php echo _('to send your newsletters');?>. <?php echo _('Newsletters sent via a cron job have the added ability to automatically resume sending when your server times out. You\'ll also be able to schedule emails.');?></p>
			    	<p><?php echo _('You haven\'t set up a cron job yet, but that\'s okay. You can still send newsletters right now. But keep in mind that you won\'t be able to navigate around Sendy until sending is complete. Also, you\'ll need to manually resume sending (with a click of a button) if your server times out.');?></p>
			    	<p><a href="#cron-instructions" data-toggle="modal" style="text-decoration:underline"><?php echo _('Setup a cron job now');?> &rarr;</a></p>
		    	</div>
		    	<?php endif;?>
		    	
		    	<p style="margin-top:10px; text-decoration:underline;">
		    		<?php if($cron):?>
			    		<a href="javascript:void(0)" id="send-later-btn"><?php echo $send_newsletter_text;?></a>
		    		<?php else:?>
			        	<a href="#cron-instructions" data-toggle="modal"><?php echo $send_newsletter_text;?></a>
		        	<?php endif;?>
		    	</p>
		    	
		    <?php endif;?>
	        
	    </form>
	    
	    <?php if(!$cron):
		    $server_path_array = explode('send-to.php', $_SERVER['SCRIPT_FILENAME']);
		    $server_path = $server_path_array[0];
	    ?>
	    <div id="cron-instructions" class="modal hide fade">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h3><i class="icon icon-time" style="margin-top: 5px;"></i> <?php echo _('Add a cron job');?></h3>
            </div>
            <div class="modal-body">
            <p><?php echo _('To schedule campaigns or to make sending more reliable, add a');?> <a href="http://en.wikipedia.org/wiki/Cron" target="_blank" style="text-decoration:underline"><?php echo _('cron job');?></a> <?php echo _('with the following command.');?></p>
            <h3><?php echo _('Time Interval');?></h3>
<pre id="command">*/5 * * * * </pre>
            <h3><?php echo _('Command');?></h3>
            <pre id="command">php <?php echo $server_path;?>scheduled.php > /dev/null 2>&amp;1</pre>
            <p><?php echo _('This command needs to be run every 5 minutes in order to check the database for any scheduled campaigns to send.');?><br/><em><?php echo _('(Note that adding cron jobs vary from hosts to hosts, most offer a UI to add a cron job easily. Check your hosting control panel or consult your host if unsure.)');?></em>.</p>
            <p><?php echo _('Once added, wait around 5 minutes. If your cron job is functioning correctly, you\'ll see the scheduling options instead of this modal window when you click on "Schedule this campaign?".');?></p>
            </div>
            <div class="modal-footer">
              <a href="#" class="btn btn-inverse" data-dismiss="modal"><i class="icon icon-ok-sign"></i> <?php echo _('Okay');?></a>
            </div>
          </div>
          <script type="text/javascript">
          	$(document).ready(function() {
          		$("#command, #cronjob").click(function(){
					$(this).selectText();
				});
          	});
          </script>
        <?php endif;?>
	    
	    <div class="well" id="schedule-form-wrapper" <?php echo $schedule_form_style;?>>
	    	<?php if(get_app_info('is_sub_user')):?>
			    <?php if(paid()):?>
			    <form action="<?php echo get_app_info('path');?>/includes/create/send-later.php" method="POST" accept-charset="utf-8" id="schedule-form">
			    <input type="hidden" name="total_recipients2" id="total_recipients2">
		    	<?php else:?>
			    <form action="<?php echo get_app_info('path');?>/payment" method="POST" accept-charset="utf-8" id="schedule-form">
			    <input type="hidden" name="pay-and-schedule" value="true"/>
			    <input type="hidden" name="paypal2" value="<?php echo get_paypal();?>">
			    <input type="hidden" name="grand_total_val2" id="grand_total_val2">
			    <input type="hidden" name="total_recipients2" id="total_recipients2">
			    <?php endif;?>
			<?php else:?>
				<form action="<?php echo get_app_info('path');?>/includes/create/send-later.php" method="POST" accept-charset="utf-8" id="schedule-form">
				<input type="hidden" name="total_recipients2" id="total_recipients2">
		    <?php endif;?>
		    	<h3><i class="icon-ok icon-time" style="margin-top:5px;"></i> <?php echo _('Schedule this campaign');?></h3><br/>
	    		<input type="hidden" name="campaign_id" value="<?php echo $cid;?>"/>
	    		<input type="hidden" name="email_lists" id="email_lists"/>
	    		<input type="hidden" name="email_lists_excl" id="email_lists_excl"/>
	    		<input type="hidden" name="email_lists_segs" id="email_lists_segs"/>
	    		<input type="hidden" name="email_lists_segs_excl" id="email_lists_segs_excl"/>
	    		<input type="hidden" name="app" value="<?php echo $aid;?>"/>
	    		
	    		<label for="send_date"><?php echo _('Pick a date');?></label>
	    		<?php 
	    			if($send_date=='')
	    			{
		    			$tomorrow = time()+86400;
			    		$the_date = date("D M d Y", $tomorrow);
			    	}
	    		?>
	    		<div class="input-prepend date">
	             <span class="add-on"><i class="icon-calendar" id="date-icon"></i></span><input type="text" name="send_date" value="<?php echo $the_date;?>" id="datepicker" readonly>
	            </div>
	            <br/>
	            <label><?php echo _('Set a time');?></label>
	    		<select id="hour" name="hour" class="schedule-date">
	    		  <?php if($send_date!=''):?>
	    		  <option value="<?php echo $hour;?>"><?php echo $hour;?></option>
	    		  <?php endif;?>
				  <option>1</option> 
				  <option>2</option> 
				  <option>3</option> 
				  <option>4</option> 
				  <option>5</option> 
				  <option>6</option> 
				  <option>7</option> 
				  <option>8</option> 
				  <option>9</option> 
				  <option>10</option> 
				  <option>11</option> 
				  <option>12</option> 
				</select>
				<select id="min" name="min" class="schedule-date">
				  <?php if($send_date!=''):?>
				  <option value="<?php echo $minute;?>"><?php echo $minute;?></option>
				  <?php endif;?>
				  <option>00</option> 
				  <option>05</option> 
				  <option>10</option> 
				  <option>15</option> 
				  <option>20</option> 
				  <option>25</option> 
				  <option>30</option> 
				  <option>35</option> 
				  <option>40</option> 
				  <option>45</option> 
				  <option>50</option> 
				  <option>55</option> 
				</select>
				<select id="ampm" name="ampm" class="schedule-date">
				  <?php if($send_date!=''):?>
				  <option value="<?php echo $ampm;?>"><?php echo $ampm;?></option>
				  <?php endif;?>
				  <option>am</option> 
				  <option>pm</option> 
				</select>
				<br style="clear:both;"/>
				<br/>
	    		<label for="timezone"><?php echo _('Select a timezone');?></label>
	    		<select id="timezone" name="timezone">
				  <option value="<?php echo $timezone;?>"><?php echo $timezone;?></option> 
				  <?php get_timezone_list();?>
				</select>
				<br/><br/>
				<?php if(get_app_info('is_sub_user')):?>
			        <?php if(paid()):?>
					<button type="submit" class="btn btn-inverse btn-large" id="schedule-btn"><i class="icon-ok icon-time icon-white"></i> <?php echo _('Schedule campaign now');?></button>
					<?php else:?>
					<button type="submit" class="btn btn-inverse btn-large" id="schedule-btn"><i class="icon-arrow-right icon-white"></i> <?php echo _('Schedule and pay for campaign');?></button>
					<?php endif;?>
				<?php else:?>
			    	<button type="submit" class="btn btn-inverse btn-large" id="schedule-btn"><i class="icon-ok icon-time icon-white"></i> <?php echo _('Schedule campaign now');?></button>
				<?php endif;?>
	    	</form>
    	</div>
	    <div id="edit-newsletter"><a href="<?php echo get_app_info('path')?>/edit?i=<?php echo get_app_info('app')?>&c=<?php echo $cid;?>" title=""><i class="icon-pencil"></i> <?php echo _('Edit newsletter');?></a></div>
    </div>   
    
    <div class="span7">
    	<div>
	    	<h2><?php echo _('Newsletter preview');?></h2><br/>
	    	
	    	<blockquote>
	    	<p><strong><?php echo _('From');?></strong> <span class="label"><?php echo get_saved_data('from_name');?> &lt;<?php echo get_saved_data('from_email');?>&gt;</span></p>
	    	<?php if(get_saved_data('label')!=''):?>
		    	<p><strong><?php echo _('Campaign title');?></strong> <span class="label"><?php echo get_saved_data('label');?></span></p>
	    	<?php endif;?>
	    	<p><strong><?php echo _('Subject');?></strong> <span class="label"><?php echo get_saved_data('title');?></span></p>
			
			<?php 
				//Open tracking settings
				if(get_saved_data('opens_tracking')==1) $opens_tracking = '<span class="label label-success">'._('Enabled').'</span>';
				else if(get_saved_data('opens_tracking')==0) $opens_tracking = '<span class="label">'._('Disabled').'</span>';
				else if(get_saved_data('opens_tracking')==2) $opens_tracking = '<span class="label label-success">'._('Anonymously').'</span>';
				
				//Click tracking settings
				if(get_saved_data('links_tracking')==1) $links_tracking = '<span class="label label-success">'._('Enabled').'</span>';
				else if(get_saved_data('links_tracking')==0) $links_tracking = '<span class="label">'._('Disabled').'</span>';
				else if(get_saved_data('links_tracking')==2) $links_tracking = '<span class="label label-success">'._('Anonymously').'</span>';
				
				//get custom domain for web version if available
				$q = 'SELECT custom_domain, custom_domain_protocol, custom_domain_enabled FROM apps WHERE id = '.get_app_info('app').' AND userID = '.get_app_info('main_userID');
				$r = mysqli_query($mysqli, $q);
				if ($r && mysqli_num_rows($r) > 0)
				{
					while($row = mysqli_fetch_array($r))
					{
						$custom_domain = $row['custom_domain'];
						$custom_domain_protocol = $row['custom_domain_protocol'];
						$custom_domain_enabled = $row['custom_domain_enabled'];
						if($custom_domain!='' && $custom_domain_enabled)
						{
							$parse = parse_url(get_app_info('path'));
							$domain = $parse['host'];
							$protocol = $parse['scheme'];
							$app_path = str_replace($domain, $custom_domain, get_app_info('path'));
							$app_path = str_replace($protocol, $custom_domain_protocol, $app_path);
						}
						else $app_path = get_app_info('path');
					}  
				}
				
				//Web version link
				$web_version = get_google_translate_url($app_path.'/w/'.encrypt_val($cid), $web_version_lang);
				
				//Show which language web version will appear in
				if($web_version_lang != '')
				{
					$web_version_lang_array = explode('/', $web_version_lang);
					$web_version_lang_name = $web_version_lang_array[0];
					$web_version_lang_name = ' ('.$web_version_lang_name.')';
				}
				else $web_version_lang_name = '';
			?>
			
	    	<p><strong><?php echo _('Opens tracking');?></strong> <?php echo $opens_tracking;?></p>
	    	<p><strong><?php echo _('Clicks tracking');?></strong> <?php echo $links_tracking;?></p>
	    	<p><strong><?php echo _('Web version').$web_version_lang_name;?></strong> <a href="<?php echo $web_version;?>" target="_blank" title="<?php echo _('View web version link');?>"><span class="label" id="webversion_url"><?php echo $web_version;?></span></a></p>
	    	<?php 
		        if (file_exists('uploads/attachments/'.$cid))
				{
					echo '<p><strong>'._('Attachments').'</strong>';
					if($handle = opendir('uploads/attachments/'.$cid))
					{
						$i = -1;
					    while (false !== ($file = readdir($handle))) 
					    {
					    	if($file!='.' && $file!='..'):
			    ?>
								<ul id="attachments" style="margin-top: 10px;">
									<li id="attachment<?php echo $i;?>" style="background: #fffdef; <?php echo get_app_info('dark_mode') ? 'color:black;' : '';?> padding: 5px 8px;">
										<?php 
											$filen = $file;
											if(strlen($filen)>30) $filen = substr($file, 0, 30).'...';
											echo $filen;
										?> 
										(<?php echo round((filesize('uploads/attachments/'.$cid.'/'.$file)/1000000), 2);?>MB) 
										<a href="<?php echo get_app_info('path');?>/includes/create/delete-attachment.php" data-filename="<?php echo $file;?>" title="<?php echo _('Delete');?>" id="delete<?php echo $i;?>" <?php echo get_app_info('dark_mode') ? 'style="color:black;"' : '';?>><i class="icon icon-trash"></i></a>
										<script type="text/javascript">
											$("#delete<?php echo $i?>").click(function(e){
												e.preventDefault();
												filename = $(this).data("filename");
												campaign_id = "<?php echo $cid?>";
												url = $(this).attr("href");
												c = confirm('<?php echo _('Confirm delete');?> \"'+filename+'\"?');
												
												if(c)
												{
													$.post(url, { filename: filename, campaign_id: campaign_id },
													  function(data) {
													      if(data)
													      {
													      	$("#attachment<?php echo $i?>").fadeOut();
													      }
													      else
													      {
													      	alert("<?php echo _('Sorry, unable to delete. Please try again later!');?>");
													      }
													  }
													);
												}
											});
										</script>
									</li>
								</ul>
				<?php
							endif;
							
							$i++;
					    }
					
					    closedir($handle);
					    
					    echo '</p>';
					}
				}
	        ?>
	    	</div>
			
			<div class="btn-group email-views" data-toggle="buttons-radio">
			  <a href="javascript:void(0)" title="" class="btn" id="desktop-view"><?php echo _('Desktop');?></a>
			  <a href="javascript:void(0)" title="" class="btn" id="mobile-view"><?php echo _('Mobile');?></a>
			</div>
			
			<input type="text" class="input-xlarge" id="email_width" name="email_width" placeholder="" value="">
			
			<script type="text/javascript">
				$(document).ready(function() {	
					$("#desktop-view").button('toggle');
					$("#email_width").val("100%");

					$("#desktop-view").click(function(){
						$("#preview-iframe").css("width", "100%");
						$("#email_width").val("100%");
					});
					$("#mobile-view").click(function(){
						$("#preview-iframe").css("width", "430px");
						$("#email_width").val("430px");
					});
					$("#email_width").on('keypress',function(e) {
						if(e.which == 13) {
							$("#preview-iframe").css("width", $(this).val());
						}
					});
				});
			</script>
			
			<div class="btn-group real-data-preview">
			  <a href="javascript:void(0)" title="<?php echo _('Preview personalization tags and custom fields using real data of up to 100 subscribers from list(s) you select on the left');?>" id="real-data-preview-info"><span class="icon icon-info-sign"></span> </a>
			  <button class="btn" onclick="void(0);" id="rdp-btn-left" disabled><span class="icon icon icon-arrow-left"></span></button>
			  <span id="rdp-email" data-sal="<?php echo _('Select a list');?>"></span>
			  <button class="btn" onclick="void(0);" id="rdp-btn-right" disabled><span class="icon icon icon-arrow-right"></span></button>
			</div>
			
			<br/><br/><br/>
			
			<?php 
				//Check if unsubscribe tag is in the email
				if (strpos($html_text, "[unsubscribe]") !== false || strpos($html_text, "<unsubscribe") !== false){}
				else
				{
					echo "<div class=\"alert alert-warning\"><i class=\"icon icon-exclamation-sign\"></i> "._('Your email does not contain an unsubscribe tag. It is recommended to include an unsubscribe link in your email to minimize complaint rates.')." <a href=\"".get_app_info('path')."/edit?i=".get_app_info('app')."&c=$cid\" style=\"text-decoration: underline;\">"._('Edit your email')."</a>.</div>";
				}
				
				//Check for broken links in the email
				if(!$ignore_checks) //if user didn't dismissed the broken link checks, check for broken links
				{
					$links = array();
					preg_match_all('/href=["\']([^"\']+)["\']/i', $html_text, $matches, PREG_PATTERN_ORDER);
					$matches = array_unique($matches[1]);
					foreach($matches as $var)
					{
						
						if(substr($var, 0, 1)!="#" && substr($var, 0, 6)!="mailto" && substr($var, 0, 3)!="ftp" && substr($var, 0, 3)!="tel" && substr($var, 0, 3)!="sms" && substr($var, 0, 13)!="[unsubscribe]" && substr($var, 0, 12)!="[webversion]" && substr($var, 0, 11)!="[reconsent]" && !strpos($var, 'fonts.googleapis.com') && !strpos($var, 'use.typekit.net') && !strpos($var, 'use.fontawesome.com'))
						{
							array_push($links, $var);
						}
					}
					$brokenLinks = '<ul>';
					$no_of_broken_links = 0;
					foreach ($links as $url) {
						$ch = curl_init($url);
						curl_setopt($ch, CURLOPT_TIMEOUT, 5);
						curl_setopt($ch, CURLOPT_NOBODY, true);
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
						curl_exec($ch);
						$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
						curl_close($ch);
						if ($statusCode != 200) {
							$no_of_broken_links++;
							$brokenLinks .= "<li><a href=\"$url\">$url</a></li>";
						}
					}
					$brokenLinks .= '</ul>';
					
					//If there are one or more broken links, show warning below.
					if($no_of_broken_links>0)
					{
						echo "<div class=\"alert alert-warning\" id=\"broken-link-warning\"><i class=\"icon icon-exclamation-sign\"></i> "._('Some links in your email may be broken, which are listed below. Please check your HTML code and make the necessary corrections.')." <br/><br/> $brokenLinks <br/> <a href=\"".get_app_info('path')."/edit?i=".get_app_info('app')."&c=$cid\" style=\"text-decoration: underline;\">"._('Edit your email')."</a> | <a href=\"javascript:void(0)\" id=\"dimiss-warning\" style=\"text-decoration: underline;\">Dismiss</a></div>";
					}
				}
			?>
			
	    	<iframe src="<?php echo get_app_info('path');?>/w/<?php echo encrypt_val($cid);?>?<?php echo time();?>" id="preview-iframe"></iframe>
    	</blockquote>
    </div>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		//Dismiss broken link checker
		$("#dimiss-warning").click(function(){
			$.post("includes/create/dismiss-broken-link-checks.php", { cid:"<?php echo $cid;?>" },
			function(data) 
			{
				if(data) 
					$("#broken-link-warning").slideUp();
				else 
					alert("Unable to dismiss broken link checks. Please try again.");
			}
			);
		});
		
		var send_or_schedule = '';
		$("#rdp-email").text($("#rdp-email").data("sal"));
		
		//schedule btn
		$("#schedule-btn").click(function(e){
			e.preventDefault(); 
			
			if(email_list == null || $("#recipients").text()=="0")
			{
				$("#schedule-btn").effect("shake", { times:3 }, 60);
				$("#email_list").effect("shake", { times:3 }, 60);
			}
			else
			{					
				//Save & schedule the email to be sent later
				$("#total_recipients2").val($("#recipients").text());
				$("#schedule-form").submit();
			}
		});
		
		//send email for real
		$("#real-form").submit(function(e){
			e.preventDefault(); 
			
			if($("#email_list").val() == null || $("#recipients").text()=="0")
			{
				$("#real-btn").effect("shake", { times:3 }, 60);
				$("#email_list").effect("shake", { times:3 }, 60);
			}
			else
			{
				<?php if($_SESSION[$_SESSION['license']] != hash('sha512', $_SESSION['license'].'ttcwjc8Q4N4J7MS7/hTCrRSm9Uv7h3GS') && !get_app_info('is_sub_user')) :?>
				if(confirm("Hi! This is Ben, the indie developer of Sendy. Please consider supporting my tireless efforts in developing this software you are using by purchasing a copy of Sendy at sendy.co. I really appreciate your support. Thank you and God bless!")) window.location = "https://sendy.co"; else window.location = "https://sendy.co";
				<?php else:?>
				c = confirm("<?php echo addslashes(_('Have you double checked your selected lists? If so, let\'s go ahead and send this!'));?>");
				if(c) send_it();
				<?php endif;?>
			}
		});
		
		//send to PayPal
		$("#pay-form").submit(function(e){
			$("#total_recipients").val($("#recipients").text());
			if($('select#email_list').val() == null || $("#recipients").text()=="0")
			{
				e.preventDefault(); 
				$("#pay-btn").effect("shake", { times:3 }, 60);
			}
			else
			{
				c = confirm('<?php echo addslashes(_('Have you double checked your selected lists? If so, proceed to pay for this campaign.'));?>');
					
				if(!c)
					e.preventDefault(); 
			}
		});
		
		function send_it()
		{			
			$("#total_recipients").val($("#recipients").text());
			
			var $form = $("#real-form"),
			campaign_id = $form.find('input[name="cid"]').val(),
			uid = $form.find('input[name="uid"]').val(),
			path = $form.find('input[name="path"]').val(),
			cron = $form.find('input[name="cron"]').val(),
			total_recipients = $form.find('input[name="total_recipients"]').val(),
			inlists = $("#in_list").val();
			exlists = $("#ex_list").val();
			inlists_seg = $("#in_list_seg").val();
			exlists_seg = $("#ex_list_seg").val();			
			url = $form.attr('action');
			
			$("#real-btn").addClass("disabled");
			$("#real-btn").text("Your email is on the way!");
			$("#view-report").show();
			$("#edit-newsletter").hide();
				
			$.post(url, { campaign_id: campaign_id, email_list: inlists, email_list_exclude: exlists, email_lists_segs: inlists_seg, email_lists_segs_excl: exlists_seg, app: uid, cron: cron, total_recipients: total_recipients },
			  function(data) {
			  	  
			  	  $("#test-send").css("display", "none");
			  	  $("#test-send-error").css("display", "none");
			  	  
			      if(data)
			      {
			      	if(data=='cron_send')
			      		window.location = path+"/app?i="+uid;
			      	else
			      		window.location = path+"/report?i="+uid+"&c="+campaign_id;
			      }
			  }
			);
		}
	});
</script>

<div id="sns-warning" class="modal hide fade">
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal">&times;</button>
  <h3><?php echo _('Important: Bounces or complaints were not set up');?></h3>
</div>
<div class="modal-body">
    <p class="alert alert-danger"><i class="icon icon-warning-sign"></i> <strong><?php echo _('We\'ve detected that bounces or complaints have not been setup.');?></strong></p> 
    <p><?php echo _('Not having bounces or complaints registered means future campaigns will continue to be sent to emails that bounced and recipients who have marked your emails as spam. This may lead to Amazon suspending your AWS account.');?></p>
    <div class="well">
    <p><strong><?php echo _('We highly recommend setting up bounces & complaints');?>:</strong></p>
    <p><?php echo _('Visit our Get Started Guide and complete steps 7 & 8');?> &rarr; <a href="https://sendy.co/get-started" target="_blank"><u>https://sendy.co/get-started</u></a>.</p>
    <p><?php echo _('Or troubleshoot with this FAQ');?> &rarr; <a href="https://sendy.co/troubleshooting#bounces-complaints-warning" target="_blank"><u>https://sendy.co/troubleshooting#bounces-complaints-warning</u></a>.</p></div>
</div>
<div class="modal-footer">
  <a href="#" class="btn btn-inverse" data-dismiss="modal"><?php echo _('Don\'t send');?></a>
  <a href="#" class="btn" data-dismiss="modal" id="send-anyway"><?php echo _('Send anyway');?></a>
</div>
</div>
<?php include('includes/footer.php');?>
