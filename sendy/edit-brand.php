<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/app/main.php');?>
<?php
	check_simplexml();
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('reports_only'))
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/reports?i='.get_app_info('restricted_to_app').'"</script>';
			exit;
		}
		else
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/app?i='.get_app_info('restricted_to_app').'"</script>';
			exit;
		}
	}
?>
<!-- Validation -->
<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/validate.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#settings-form").validate({
			rules: {
				app_name: {
					required: true	
				},
				from_name: {
					required: true	
				},
				from_email: {
					required: true,
					email: true
				},
				reply_to: {
					required: true,
					email: true
				},
				campaign_report_rows: {
					required: true,
					digits: true,
					min: 1	
				}
			},
			messages: {
				app_name: "<?php echo addslashes(_('Please specify your brand\'s name'));?>",
				from_name: "<?php echo addslashes(_('\'From name\' is required'));?>",
				from_email: "<?php echo addslashes(_('A valid \'From email\' is required'));?>",
				reply_to: "<?php echo addslashes(_('A valid \'Reply to\' email is required'));?>"
			}
		});
		
		//Check if login email clashes with the main login email address
		$("#settings-form").submit(function(e){			
			login_email = $('#login_email').val();
			if(login_email == "<?php echo get_app_info('email');?>")
			{
				e.preventDefault(); 
				$("#duplicate-login-email").show();
			}
			
		});
		
		//accordion1		
		$("#collapse3").on('shown', function(){$('html, body').animate({scrollTop:$(this).parent().parent().position().top-60}, 'slow', 'easeOutExpo'); $("#smtp_host").focus();});
		$("#collapse0").on('shown', function(){$('html, body').animate({scrollTop:$(this).parent().parent().position().top-10}, 'slow', 'easeOutExpo'); $("#custom_domain").focus();});
		$("#collapse2").on('shown', function(){$('html, body').animate({scrollTop:$(this).parent().parent().position().top+45}, 'slow', 'easeOutExpo'); $("#recaptcha_sitekey").focus();});
		$("#collapse1").on('shown', function(){$('html, body').animate({scrollTop:$(this).parent().parent().position().top+97}, 'slow', 'easeOutExpo');});
		$("#collapse5").on('shown', function(){$('html, body').animate({scrollTop:$(this).parent().parent().position().top+152}, 'slow', 'easeOutExpo'); });
		$("#collapse4").on('shown', function(){$('html, body').animate({scrollTop:$(this).parent().parent().position().top+205}, 'slow', 'easeOutExpo'); $("#query_string").focus();});
		
		//accordion2
		$("#collapse2_1").on('shown', function(){$("#login_email").focus();});
		$("#collapse2_4").on('shown', function(){$("#delivery_fee").focus();});
		$("#collapse2_5").on('shown', function(){$("#monthly-limit").focus();});
	});
</script>

<form action="<?php echo get_app_info('path')?>/includes/app/edit.php" method="POST" accept-charset="utf-8" class="form-vertical" enctype="multipart/form-data" id="settings-form">

<div class="row-fluid">
	<div class="span2">
		<div class="sidebar-nav sidebar-box" style="padding: 19px;">
			<?php include('includes/helpers/ses-quota.php');?>
		</div>
	</div>
    <div class="span5">
	    	    
    	<h2><?php echo _('Edit brand');?></h2><br/>
	    	
    	<label class="control-label" for="app_name"><?php echo _('Brand name');?></label>
    	<div class="control-group">
	    	<div class="controls">
              <input type="text" class="input-xlarge" id="app_name" name="app_name" placeholder="<?php echo _('Name of the brand');?>" value="<?php echo get_saved_data('app_name');?>">
            </div>
        </div>
        
        <label class="control-label" for="from_name"><?php echo _('From name');?></label>
    	<div class="control-group">
	    	<div class="controls">
              <input type="text" class="input-xlarge" id="from_name" name="from_name" placeholder="<?php echo _('From name');?>" value="<?php echo get_saved_data('from_name');?>">
            </div>
        </div>
        
        <label class="control-label" for="from_email"><?php echo _('From email');?></label>
    	<div class="control-group">
	    	<div class="controls">
              <input type="text" class="input-xlarge" id="from_email" name="from_email" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_saved_data('from_email');?>">
            </div>
            <p id="verification-check-loader" style="display:none;"><img src="<?php echo get_app_info('path')?>/img/<?php echo get_app_info('dark_mode') ? 'loader-dark.gif' : 'loader-light.gif';?>" style="width:16px;"/> <?php echo _('Checking if your \'From email\' is verified in your SES console..');?><br/><br/></p>
            <div class="alert alert-danger" id="unverified-email" style="display:none;"><strong><i class="icon icon-warning-sign"></i> <?php echo _('Unverified \'From email\'');?></strong>: <?php echo _('Your \'From email\' or its domain is not verified in your Amazon SES console. Do either of the following:');?>
            <br/><br/>
            	<ul>
		            <li id="click-to-verify-copy"><a href="javascript:void(0);" id="click-to-verify-btn"><?php echo _('Click here to verify this \'From email\', Amazon will send a verification email to this \'From email\'');?> →</a></li>
		            <li><a href="https://console.aws.amazon.com/ses/home?#verified-senders-domain:" target="_blank"><?php echo _('Alternatively, verify this \'From email\'s \'domain\' in your Amazon SES console');?> →</a></li>
		        </ul>
		    <p><?php echo _('When you verify a domain, you can use any \'From email\' address belonging to that domain without having to verify them individually.');?></p>
		    <p><?php echo _('Lastly, the \'From email\' is case sensitive. Please ensure you are using the same case as the email address verified in your Amazon SES console.');?></p>
            </div>
            <div class="alert alert-danger" id="unverified-email-pending" style="display:none;"><strong><i class="icon icon-warning-sign"></i> <?php echo _('\'From email\' pending verification');?></strong>: <?php echo _('Your \'From email\' or its domain is pending verification in your Amazon SES console. Please complete the verification.');?></div>
            <div class="alert alert-danger" id="api-error" style="display:none;"><strong><i class="icon icon-warning-sign"></i> <?php echo _('Unable to communicate with Amazon SES API');?></strong>: <?php echo _('Please check the error message on the left for instructions.');?></div>
            <div class="alert alert-success" id="verified-email" style="display:none;"><strong><i class="icon icon-ok"></i> <?php echo _('Congrats! This \'From email\' is verified.');?></strong></div>
            
			<?php 
				$q = 'SELECT smtp_host, smtp_port, smtp_username, smtp_password FROM apps WHERE id = '.get_app_info('app');
				$r = mysqli_query($mysqli, $q);
				if ($r && mysqli_num_rows($r) > 0)
				{
					while($row = mysqli_fetch_array($r))
					{
						$smtp_host = $row['smtp_host'];
						$smtp_port = $row['smtp_port'];
						$smtp_username = $row['smtp_username'];
						$smtp_password = $row['smtp_password'];
					}  
				}
			?>
			
			<?php if($smtp_host=='' || $smtp_port=='' || $smtp_username=='' || $smtp_password==''):?>
            	<?php if(get_app_info('s3_key')!='' && get_app_info('s3_key')!=''):?>
            	<script type="text/javascript">
            		$(document).ready(function() {
            			$("#from_email").focusout(function(){
            				$("#verification-check-loader").show();
            				$("#unverified-email").hide();
            				$("#unverified-email-pending").hide();
            				$("#api-error").hide();
            				$("#verified-email").hide();
            				
	            			$.post("<?php echo get_app_info('path')?>/includes/app/check-email-verification.php", { from_email: $("#from_email").val(), auto_verify: 'no' },
            			  	function(data) {
            			      	if(data=='unverified')
            			      	{
            			      		$("#verification-check-loader").hide();
            			      		$("#unverified-email").show();
            			      		$("#unverified-email-pending").hide();
            			      		$("#api-error").hide();
            			      		$("#verified-email").hide();
            			      	}
            			      	else if(data=='pending verification')
            			      	{
	            			    	$("#verification-check-loader").hide();
	            			    	$("#unverified-email").hide();
            			      		$("#unverified-email-pending").show();
            			      		$("#api-error").hide();
            			      		$("#verified-email").hide();
            			      	}
            			      	else if(data=='verified')
            			      	{
            			      		$("#verification-check-loader").hide();
            			      		$("#unverified-email").hide();
            			      		$("#unverified-email-pending").hide();
            			      		$("#api-error").hide();
            			      		$("#verified-email").show();
            			      	}
            			      	else if(data=="api error")
            			      	{
	            			    	$("#verification-check-loader").hide();
            			      		$("#unverified-email").hide();
            			      		$("#unverified-email-pending").hide();
            			      		$("#api-error").show();
            			      		$("#verified-email").hide();
            			      	}
            			      	else
            			      	{
	            			  		$("#verification-check-loader").hide();
            			      	}
            			  	}
            				);
            			});
            			<?php 
				        	//Check if from email is verified in SES console
			  				if(!get_app_info('is_sub_user') && get_app_info('s3_key')!='' && get_app_info('s3_secret')!='')
			  				{
				  				$from_email = get_saved_data('from_email');
				  				
				  				if(verify_identity($from_email)=='api_error')
				  				{
					  				echo '$("#api-error").show();';
				  				}
				  				else if(verify_identity($from_email)=='unverified')
								{						
									//From email address or domain is not verified in SES console
									echo '$("#unverified-email").show();';
								}
								else if(verify_identity($from_email)=='pending')
								{
									//From email address or domain 'pending verification' in SES console
									echo '$("#unverified-email-pending").show();';
								}
							}
			        	?>
			        	
			        	$("#click-to-verify-btn").click(function(e){
            				e.preventDefault();
            				$("#click-to-verify-copy").html("<?php echo _('Please wait..');?>");
            				$.post("<?php echo get_app_info('path')?>/includes/app/verify-email.php", { from_email: $("#from_email").val() },
        				  	function(data) {
        				      	if(data)
        				      	{
        				      		if(data=="success")
        				      			$("#unverified-email").html("<?php echo _('A verification email has been sent to your \'From email\' address with a confirmation link to complete the verification. Please click the link to complete the verification, then refresh this page.');?>");
        				      		else if(data=="success2")
        				      			$("#unverified-email").html("<?php echo _('Two verification emails has been sent to both your login email address and the above \'From email\' address. Please click the verification link in both emails to complete the verification, then refresh this page.');?>");
        				      		else if(data=="failed")
        				      			$("#unverified-email").html("<?php echo _('Unable to send your \'From email\' address to Amazon SES for verification! Please try again later.');?>");
        				      	}
        				      	else
        				      	{
        				      		alert("<?php echo _('Sorry, unable to verify email address. Please try again later!');?>");
        				      	}
        				  	}
        					);
        				});
            		});
            	</script>
            	<?php endif;?>
            <?php endif;?>
        </div>
        
        <label class="control-label" for="reply_to"><?php echo _('Reply to email');?></label>
    	<div class="control-group">
	    	<div class="controls">
              <input type="text" class="input-xlarge" id="reply_to" name="reply_to" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_saved_data('reply_to');?>">
            </div>
        </div>
        
        <br/>
        
        <label class="control-label" for="logo"><?php echo _('Brand logo');?> <em class="thirtytwo"><?php echo _('(32 x 32 pixel, jpeg, jpg, gif or png format)');?></em></label>
        <div class="control-group">
	    	<div class="controls">
	    		<input type="file" id="logo" name="logo" />
            </div>
            <?php if(get_saved_data('brand_logo_filename')!=''):?>
            <div id="brand-logo-management">
	            <img src="<?php echo get_app_info('path').'/uploads/logos/'.get_saved_data('brand_logo_filename');?>" id="brand-logo"/>
	            <a href="javascript:void(0);" title="" class="icon icon-trash" id="delete-brand-logo"> <?php echo _('Delete');?></a>
	            <script type="text/javascript">
		            $(document).ready(function() {
		            	$("#delete-brand-logo").click(function(e){
		            		e.preventDefault();
		            		c = confirm("<?php echo _('Confirm delete this logo?');?>");
		            		if(c)
		            		{
			            		$.post("<?php echo get_app_info('path').'/includes/app/delete-logo.php'?>", { id: "<?php echo get_app_info('app');?>", filename: "<?php echo get_saved_data('brand_logo_filename');?>" },
			            		  function(data) {
			            		      if(data)
			            		      {
			            		      	$("#brand-logo-management").fadeOut();
			            		      }
			            		      else
			            		      {
			            		      	alert("<?php echo _('Sorry, unable to delete. Please try again later!');?>");
			            		      }
			            		  }
			            		);
			            	}
		            	});
		            });
	            </script>
	            <br/>
            </div>
            <?php endif;?>
        </div>
        
        <br/>
		
		<?php $err = isset($_GET['e']) ? $_GET['e'] : '';?> 
        
        <?php if($err=='1'):?>
	        <div class="alert alert-error">
			  <button class="close" onclick="$('.alert-error').hide();">×</button>
			  <strong><?php echo _('Could not create \'/logos/\' directory. Please make sure permissions in /uploads/ folder is set to 777. Your logo has not been uploaded.');?></strong>
			</div>
		<?php elseif($err=='2'):?>
			<div class="alert alert-error">
			  <button class="close" onclick="$('.alert-error').hide();">×</button>
			  <strong><?php echo _('Please upload only these file formats: jpeg, jpg, gif or png.');?></strong>
			</div>
		<?php elseif($err=='3'):?>
			<div class="alert alert-error">
			  <button class="close" onclick="$('.alert-error').hide();">×</button>
			  <strong><?php echo _('Could not upload image to \'/logos/\' folder. <br/>Please make sure permissions in /uploads/ folder is set to 777. <br/>Then remove the /logos/ folder in the /uploads/ folder and try again.');?></strong>
			</div>
		<?php endif;?>
        
        <hr/>
        
        <div class="accordion" id="accordion">
			
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapse3" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse"><h3><span class="icon icon-envelope-alt"></span> <?php echo _('SMTP settings');?></h3></a>
				</div>
				
				<div id="collapse3" class="accordion-body collapse">
					
					<?php 
						$smtp_provider = array(
												'choose'=>'<span class="icon  icon-envelope-alt"></span> '._('Choose an SMTP provider')
												,'elasticemail'=>'<img src="'.get_app_info('path').'/img/elasticemail.png" class="smtp-provider-img"/> Elastic Email'
												 ,'sendgrid'=>'<img src="'.get_app_info('path').'/img/sendgrid.png" class="smtp-provider-img"/> Sendgrid'
												 ,'mailjet'=>'<img src="'.get_app_info('path').'/img/mailjet.png" class="smtp-provider-img"/> Mailjet'
												 ,'other'=>'<span class="icon  icon-envelope-alt"></span> Other'
											);
						
						$smtp_instructions = array(
												'elasticemail'=>'<img src="'.get_app_info('path').'/img/elasticemail.png" class="smtp-provider-img"/> '._('See how to setup ElasticEmail with Sendy').' → <a href="https://sendy.co/elasticemail?'.get_app_info('path').'" target="_blank">https://sendy.co/elasticemail?'.get_app_info('path').'</a>'
												,'sendgrid'=>'<img src="'.get_app_info('path').'/img/sendgrid.png" class="smtp-provider-img"/> '._('See how to setup Sendgrid with Sendy').' → <a href="https://sendy.co/sendgrid?'.get_app_info('path').'" target="_blank">https://sendy.co/sendgrid?'.get_app_info('path').'</a>'
												,'mailjet'=>'<img src="'.get_app_info('path').'/img/mailjet.png" class="smtp-provider-img"/> '._('See how to setup Mailjet with Sendy').' → <a href="https://sendy.co/mailjet?'.get_app_info('path').'" target="_blank">https://sendy.co/mailjet?'.get_app_info('path').'</a>'
											);
					?>
					
					<div class="alert" id="smtp_alert" <?php if((get_app_info('s3_key')=='' && get_app_info('s3_secret')=='') && get_saved_data('smtp_host')=='') echo 'style="display:none;"'?>>
						<?php if(get_app_info('s3_key')!='' && get_app_info('s3_secret')!=''):?>
						<p>
							<span class="icon icon-info-sign"></span> <?php echo _('Emails are sent via Amazon SES by default. However, if you want to send emails via another SMTP provider for this brand, you can set the SMTP credentials here.');?>
						</p>
						<?php endif;?>
						
						<p id="smtp-setup-instructions">
							<?php 
								if(get_app_info('s3_key')!='' && get_app_info('s3_secret')!='')
									echo '<br/>';
							
								if(get_saved_data('smtp_host')=='smtp.elasticemail.com')
									echo $smtp_instructions['elasticemail'];
								else if(get_saved_data('smtp_host')=='smtp.sendgrid.net')
									echo $smtp_instructions['sendgrid'];
								else if(get_saved_data('smtp_host')=='in-v3.mailjet.com')
									echo $smtp_instructions['mailjet'];
							?>
						</p>
					</div>
					
					<div class="btn-group" id="smtp-selector">
					  <button class="btn btn-white dropdown-toggle" data-toggle="dropdown">
						<?php 
							if(get_saved_data('smtp_host')=='') echo $smtp_provider['choose'];
							else if(get_saved_data('smtp_host')=='smtp.elasticemail.com') echo $smtp_provider['elasticemail'];
							else if(get_saved_data('smtp_host')=='smtp.sendgrid.net') echo $smtp_provider['sendgrid'];
							else if(get_saved_data('smtp_host')=='in-v3.mailjet.com') echo $smtp_provider['mailjet'];
							else echo $smtp_provider['other'];
						?>
						<span class="caret"></span>
					  </button>
					  <ul class="dropdown-menu">
						  <li>
							  <a href="javascript:void(0);" id="elasticemail-btn"><?php echo $smtp_provider['elasticemail'];?></a>
							  <a href="javascript:void(0);" id="sendgrid-btn"><?php echo $smtp_provider['sendgrid'];?></a>
							  <a href="javascript:void(0);" id="mailjet-btn"><?php echo $smtp_provider['mailjet'];?></a>
							  <a href="javascript:void(0);" id="other-btn"><?php echo $smtp_provider['other'];?></a>
						  </li>
					  </ul>
					</div>
					<script type="text/javascript">
						$(document).ready(function() {
							
							prev_smtp_username = "<?php echo get_saved_data('smtp_username');?>";
							caret = ' <span class="caret"></span>';
							
							<?php if(get_saved_data('smtp_host')==''):?>
							$("#smtp-setup-instructions").hide();
							<?php else:?>
							$("#smtp-setup-instructions").show();
							<?php endif;?>
							
							$("#elasticemail-btn").click(function(){
								//Set SMTP fields
								$("#smtp-selector button").html('<?php echo $smtp_provider['elasticemail'];?>'+caret);
								$("#smtp_host").val("smtp.elasticemail.com");
								$("#smtp_port").val("465");
								$("#ssl").attr("selected", "selected");
								$("#tls").removeAttr("selected");
								$("#smtp_username").val(prev_smtp_username);
								
								//Set instructions
								$("#smtp_alert").show();
								$("#smtp-setup-instructions").show();
								$("#smtp-setup-instructions").empty();
								$("#smtp-setup-instructions").html('<?php echo $smtp_instructions['elasticemail']?>');
							});
							$("#sendgrid-btn").click(function(){
								//Set SMTP fields
								$("#smtp-selector button").html('<?php echo $smtp_provider['sendgrid'];?>'+caret);
								$("#smtp_host").val("smtp.sendgrid.net");
								$("#smtp_port").val("465");
								$("#ssl").attr("selected", "selected");
								$("#tls").removeAttr("selected");
								$("#smtp_username").val("apikey");
								
								//Set instructions
								$("#smtp_alert").show();
								$("#smtp-setup-instructions").show();
								$("#smtp-setup-instructions").empty();
								$("#smtp-setup-instructions").html('<?php echo $smtp_instructions['sendgrid']?>');
							});
							$("#mailjet-btn").click(function(){
								//Set SMTP fields
								$("#smtp-selector button").html('<?php echo $smtp_provider['mailjet'];?>'+caret);
								$("#smtp_host").val("in-v3.mailjet.com");
								$("#smtp_port").val("587");
								$("#ssl").removeAttr("selected");
								$("#tls").attr("selected", "selected");
								$("#smtp_username").val(prev_smtp_username);
								
								//Set instructions
								$("#smtp_alert").show();
								$("#smtp-setup-instructions").show();
								$("#smtp-setup-instructions").empty();
								$("#smtp-setup-instructions").html('<?php echo $smtp_instructions['mailjet']?>');
							});
							$("#other-btn").click(function(){
								//Set SMTP fields
								$("#smtp-selector button").html('<?php echo $smtp_provider['other'];?>'+caret);
								$("#smtp_host").val("");
								$("#smtp_port").val("");
								$("#ssl").removeAttr("selected");
								$("#tls").removeAttr("selected");
								$("#smtp_username").val(prev_smtp_username);
								
								//Set instructions
								<?php if(get_app_info('s3_key')=='' && get_app_info('s3_secret')==''):?>
								$("#smtp_alert").hide();
								<?php endif;?>
								$("#smtp-setup-instructions").hide();
							});
						});
					</script>
					
					<label class="control-label" for="smtp_host"><?php echo _('Host');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="text" class="input-xlarge" id="smtp_host" name="smtp_host" placeholder="eg. smtp.gmail.com" value="<?php echo get_saved_data('smtp_host');?>" autocomplete="off">
						</div>
					</div>
					
					<label class="control-label" for="smtp_port"><?php echo _('Port');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="text" class="input-xlarge" id="smtp_port" name="smtp_port" placeholder="eg. 465" value="<?php echo get_saved_data('smtp_port');?>" autocomplete="off">
						</div>
					</div>
					
					<label class="control-label" for="smtp_ssl">SSL / TLS</label>
					<div class="control-group">
						<div class="controls">
							<select name="smtp_ssl">
							  <option value="ssl" id="ssl">SSL</option>
							  <option value="tls" id="tls">TLS</option>
							 </select>
							 <script type="text/javascript">
								 $("#<?php echo get_saved_data('smtp_ssl');?>").attr("selected", "selected");
							 </script>
						</div>
					</div>
					
					<label class="control-label" for="smtp_username"><?php echo _('Username');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="text" class="input-xlarge" id="smtp_username" name="smtp_username" placeholder="<?php echo _('Username (usually your email)');?>" value="<?php echo get_saved_data('smtp_username');?>" autocomplete="off">
						</div>
					</div>
					
					<label class="control-label" for="smtp_password"><?php echo _('Password');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="password" class="input-xlarge" id="smtp_password" name="smtp_password" placeholder="<?php echo _('Leave blank to not change it');?>" value="" autocomplete="new-password">
						</div>
					</div>
					
					<br/>
				</div>
			</div>
	        
	        <div class="accordion-group">
		        <div class="accordion-heading">
			        <a href="#collapse0" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse"><h3><span class="icon icon-globe"></span> <?php echo _('Custom domain');?></h3></a>
		        </div>
		        
		        <div id="collapse0" class="accordion-body collapse">
			        
			        <?php 
					    $licensed_custom_domain_maxed = licensed_custom_domain_maxed();
					    $licensed_custom_domain_used = licensed_custom_domain_used();
					    $licensed_custom_domain_count = licensed_custom_domain_count();
				    ?>
			        
			        <span>
				        <?php if($licensed_custom_domain_count==0):?>
				        <span class="label">You have 0 custom domain licenses</span>
				        <?php else:?>
			        	<span class="label label-<?php echo $licensed_custom_domain_maxed ? 'important' : 'success';?>"><?php echo $licensed_custom_domain_used;?> of <?php echo $licensed_custom_domain_count?> custom domain licenses used</span>
				        <?php endif;?>
				    </span>
			        
			        <?php if($licensed_custom_domain_maxed && get_saved_data('custom_domain')==''):?>
			        <p class="alert" style="margin-top: 20px;">
				        <span class="icon icon-info-sign"></span> <?php echo _('Just like Sendy domain licenses, custom domains requires licensing.');?> <?php echo _('You can purchase custom domain licenses here →');?> <a href="https://sendy.co/custom-domain-licenses?l=<?php echo get_app_info('license');?>" target="_blank" style="text-decoration: underline;">https://sendy.co/custom-domain-licenses</a>. <?php echo _('Refresh this page once done and you will be able to set your custom domain.');?>
				        
				        <?php if($licensed_custom_domain_count==0):?>
					        <br/><br/>
					        <?php echo _('When you set a custom domain, unsubscribe, web version and all trackable links in emails you send will use this custom domain instead of the domain you installed Sendy on. Brand users can also login to their accounts via the custom domain.');?>
					    <?php endif;?>
			        </p>
			        
			        <?php else:?>
			        <p class="alert" style="margin-top: 20px;"><span class="icon icon-info-sign"></span> <?php echo _('Unsubscribe, web version and all trackable links in emails you send will use this custom domain instead of the domain you installed Sendy on. Brand users can also login to their accounts via the custom domain.');?> <?php echo _('You can purchase more custom domain licenses here →');?> <a href="https://sendy.co/custom-domain-licenses?l=<?php echo get_app_info('license');?>" target="_blank" style="text-decoration: underline;">https://sendy.co/custom-domain-licenses</a>.</p>
			        
			        <?php endif;?>
			        
			        <label class="control-label" for="custom_domain"><?php echo _('Custom domain');?></label>
			    	<div class="control-group">
				    	<div class="controls">
					    	
					    	<!-- Protocol -->
					    	<select name="protocol" id="protocol" style="width:80px;" <?php echo $licensed_custom_domain_maxed && get_saved_data('custom_domain')=='' ? 'disabled' : ''; ?>>
								<option value="http" id="http" selected>http://</option>
								<option value="https" id="https">https://</option>
							</select>
							<script type="text/javascript">
								 $("#<?php echo get_saved_data('custom_domain_protocol');?>").attr("selected", "selected");
							 </script>
							
							<!-- Custom domain -->
			                <input type="text" class="input-xlarge" id="custom_domain" name="custom_domain" placeholder="<?php echo 'Eg. domain.com or sub.domain.com'?>" value="<?php echo get_saved_data('custom_domain');?>" <?php echo $licensed_custom_domain_maxed && get_saved_data('custom_domain')=='' ? 'disabled' : ''; ?> autocomplete="off">
							<div class="alert" id="cd_instructions" style="<?php echo get_saved_data('custom_domain')=='' ? 'display:none' : '';?>">
								<span class="icon icon-info-sign"></span> <?php echo _('Create a CNAME record in your custom domain\'s DNS and point it to your Sendy installation\'s domain:');?>
								
								<table class="table table-bordered" style="width: 300px;" id="cd_instructions">
								  <tbody>
								  	<tr>
									  	<th>Hostname</th>
									  	<th>Type</th>
									  	<th>Record</th>
				  				  	</tr>
								    <tr>
								      <td><span class="customdomain" id="cd_example"><?php echo get_saved_data('custom_domain')=='' ? 'customdomain.com' : get_saved_data('custom_domain');?></span></td>
								      <td>CNAME</td>
								      <td>
									      <span id="sd">
									      <?php 
										    //Get hostname
											$parse = parse_url(get_app_info('path'));
											$domain = $parse['host'];
											
											if($domain=='')
											{
												$domain_exp = explode('/', get_app_info('path'));
												$domain = $domain_exp[2];
											}
											
											echo $domain;
											
											//Get installation URI
											$uri_array = explode($domain, get_app_info('path'));
											$uri = $uri_array[1];
									      ?>
									      </span>
								      </td>
				      			    </tr>
								  </tbody>
								</table>
								
								<p>
									<?php echo _('Before enabling your custom domain, test your custom domain setup by visiting the following URL and it should load your Sendy login page');?> &rarr; 
									<code><span id="protocol-test"><?php echo get_saved_data('custom_domain_protocol')=='' ? 'http' : get_saved_data('custom_domain_protocol');?></span>://<span id="hostname-test"><?php echo get_saved_data('custom_domain');?></span><?php echo $uri;?></code>
								</p>
								
								<p><?php echo _('Otherwise, <a href="https://sendy.co/troubleshooting#custom-domain-not-working" target="_blank" style="text-decoration: underline;">please see this troubleshooting tip</a>');?></p>
							</div>
							
			            </div>
			            <br/>
			            
			            <!-- Enabled/Disabled -->
			            <label class="control-label" for="custom_domain"><?php echo _('Enable custom domain?');?></label>
		                <div class="btn-group" data-toggle="buttons-radio">
							<a href="javascript:void(0)" title="" class="btn" id="cd-enabled" style="text-decoration: none;" <?php echo $licensed_custom_domain_maxed && get_saved_data('custom_domain')=='' ? 'disabled' : ''; ?>><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
							<a href="javascript:void(0)" title="" class="btn active" id="cd-disabled" style="text-decoration: none;" <?php echo $licensed_custom_domain_maxed && get_saved_data('custom_domain')=='' ? 'disabled' : ''; ?>><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						</div>
						<input type="hidden" name="custom_domain_status" id="custom_domain_status" value="0">
						<script type="text/javascript">
							$(document).ready(function() {		
								<?php 
									$custom_domain_enabled = get_saved_data('custom_domain_enabled');
									if($custom_domain_enabled==1):
								?>
								$("#cd-enabled").button('toggle');
								$("#custom_domain_status").val("1");
								<?php else:?>
								$("#cd-disabled").button('toggle');
								$("#custom_domain_status").val("0");
								<?php endif;?>
														
								$("#cd-enabled").click(function(){
									$("#custom_domain_status").val("1");
								});
								$("#cd-disabled").click(function(){
									$("#custom_domain_status").val("0");
								});
							});
						</script>
						
			        </div>
			        			        
			        <br/><br/>
			        
		        </div>
	        </div>
			
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapse2" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse"><h3><span class="icon icon-ok"></span> <?php echo _('Google reCAPTCHA v2');?></h3></a>
				</div>
				
				<div id="collapse2" class="accordion-body collapse">
					<div class="alert">
						<span class="icon icon-info-sign"></span> <?php echo _('You can use Google\'s reCAPTCHA v2 to protect your subscription forms from spam bots. To enable, register your site on <a href="https://www.google.com/recaptcha/admin/create" target="_blank" style="text-decoration: underline;">Google\'s reCAPTCHA website</a> and select \'reCAPTCHA v2 > "I\'m not a robot" tickbox\' to get your \'Site key\' and \'Secret key\', then paste them below. reCAPTCHA will be disabled if the following fields are empty.');?>
					</div>
					
					<label class="control-label" for="recaptcha_sitekey"><?php echo _('Site key');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="text" class="input-xlarge" id="recaptcha_sitekey" name="recaptcha_sitekey" placeholder="Site key" value="<?php echo get_saved_data('recaptcha_sitekey');?>" autocomplete="off">
						</div>
					</div>
					
					<label class="control-label" for="recaptcha_secretkey"><?php echo _('Secret key');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="password" class="input-xlarge" id="recaptcha_secretkey" name="recaptcha_secretkey" placeholder="Secret key" value="<?php echo get_saved_data('recaptcha_secretkey');?>" autocomplete="new-password">
						</div>
					</div>
					
					<br/>
				</div>
			</div>
        
	        <div class="accordion-group">
		        <div class="accordion-heading">
			        <a href="#collapse1" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse"><h3><span class="icon icon-certificate"></span> <?php echo _('GDPR features');?></h3></a>
		        </div>
		        
		        <div id="collapse1" class="accordion-body collapse">
			        <p class="alert"><span class="icon icon-info-sign"></span> <?php echo _('The <a href="https://www.eugdpr.org/the-regulation.html" target="_blank" style="text-decoration: underline;">General Data Protection Regulation (GDPR)</a> is a regulation in EU law on data protection and privacy for all individuals within the European Union. The GDPR regulation affects anyone in the world who collect and process the personal data of EU users. If you collect and process data of EU users, Sendy\'s GDPR features will be useful to you.')?></p>
			        
			        <label class="control-label"><?php echo _('GDPR options');?></label>
			    	<div class="control-group">
				    	<div class="dashed-box">
					    	<div class="checkbox">
							  <label><input type="checkbox" name="gdpr_options" <?php echo get_app_data('gdpr_options')==1 ? 'checked' : '';?>><?php echo _('Show me GDPR options where applicable');?> <br/><br/><i class="thirtytwo"><?php echo _('By enabling this option, GDPR features will show up in the app where appropriate. For example, subscribe form dialogues will present you with an option to enable \'GDPR fields\' amongst many others.');?></i></label>
							</div>
				    	</div>
			        </div>
			        
			        <br/>
			        
			        <label class="control-label"><?php echo _('GDPR safe switch');?></label>
			    	<div class="control-group">
				    	<div class="dashed-box">
					    	<div class="checkbox">
							  <label><input type="checkbox" name="gdpr_only" <?php echo get_app_data('gdpr_only')==1 ? 'checked' : '';?>><?php echo _('Only send Campaigns to subscribers with <span class="label label-warning">GDPR</span> tag');?> <br/><br/><i class="thirtytwo"><?php echo _('Subscribers who signup through Sendy\'s \'Ready-to-use subscribe form\', the embeddable \'Subscribe form HTML code\' or the \'subscribe\' API with \'gdpr\' parameter set to \'true\' will be tagged with \'GDPR\'. By enabling this option, your future Campaigns will only send to subscribers tagged with \'GDPR\'.');?></i></label>
							</div>
				    	</div>
				    	<br/>
				    	<div class="dashed-box">
					    	<div class="checkbox">
							  <label><input type="checkbox" name="gdpr_only_ar" <?php echo get_app_data('gdpr_only_ar')==1 ? 'checked' : '';?>><?php echo _('Only send Autoresponders to subscribers with <span class="label label-warning">GDPR</span> tag');?> <br/><br/><i class="thirtytwo"><?php echo _('Subscribers who signup through Sendy\'s \'Ready-to-use subscribe form\', the embeddable \'Subscribe form HTML code\' or the \'subscribe\' API with \'gdpr\' parameter set to \'true\' will be tagged with \'GDPR\'. By enabling this option, your future Autoresponders will only send to subscribers tagged with \'GDPR\'.');?></i></label>
							</div>
				    	</div>
			        </div>
			        
			        <br/><br/>
		        </div>
	        </div>
			
			<div class="accordion-group">
				<div class="accordion-heading">
					<a href="#collapse5" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse"><h3><span class="icon icon-eye-open"></span> <?php echo _('Privacy');?></h3></a>
				</div>
				
				<div id="collapse5" class="accordion-body collapse">
					<p class="alert"><span class="icon icon-info-sign"></span> <?php echo _('Set your default email tracking preference when creating new campaigns or autoresponders. This can still be changed on the fly when you create new campaigns or autoresponders.')?></p>
					
					<p>
						<?php echo _('Track opens');?>: 
						<div class="btn-group tracking" data-toggle="buttons-radio">
						  <a href="javascript:void(0)" title="<?php echo _('Enable opens tracking');?>" class="btn" id="opens_tracking_on" data-placement="right"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Disable opens tracking');?>" class="btn" id="opens_tracking_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Track opens without identifying users to respect their privacy');?>" class="btn" id="opens_tracking_anon"><i class="icon icon-ok"></i> <?php echo _('Anonymously');?></a>
						</div>
						
						<script type="text/javascript">
							$(document).ready(function() {
								<?php 
									$opens_tracking = get_saved_data('opens_tracking');
									if($opens_tracking==1):
								?>
								$("#opens_tracking_on").button('toggle');
								$("#opens").val("1");
								<?php elseif($opens_tracking==0):?>
								$("#opens_tracking_off").button('toggle');
								$("#opens").val("0");
								<?php elseif($opens_tracking==2):?>
								$("#opens_tracking_anon").button('toggle');
								$("#opens").val("2");
								<?php endif;?>
								
								$("#opens_tracking_on").click(function(){
									$("#opens").val("1");
								});
								$("#opens_tracking_off").click(function(){
									$("#opens").val("0");
								});
								$("#opens_tracking_anon").click(function(){
									$("#opens").val("2");
								});
							});
						</script>
					</p>
					<br/>
					<p>
						<?php echo _('Track clicks');?>: 
						<div class="btn-group tracking" data-toggle="buttons-radio">
						  <a href="javascript:void(0)" title="<?php echo _('Enable clicks tracking');?>" class="btn" id="clicks_tracking_on" data-placement="right"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Disable clicks tracking');?>" class="btn" id="clicks_tracking_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Track clicks without identifying users to respect their privacy');?>" class="btn" id="clicks_tracking_anon"><i class="icon icon-ok"></i> <?php echo _('Anonymously');?></a>
						</div>
						
						<script type="text/javascript">
							$(document).ready(function() {
								<?php 
									$links_tracking = get_saved_data('links_tracking');
									if($links_tracking==1):
								?>
								$("#clicks_tracking_on").button('toggle');
								$("#clicks").val("1");
								<?php elseif($links_tracking==0):?>
								$("#clicks_tracking_off").button('toggle');
								$("#clicks").val("0");
								<?php elseif($links_tracking==2):?>
								$("#clicks_tracking_anon").button('toggle');
								$("#clicks").val("2");
								<?php endif;?>
								
								$("#clicks_tracking_on").click(function(){
									$("#clicks").val("1");
								});
								$("#clicks_tracking_off").click(function(){
									$("#clicks").val("0");
								});
								$("#clicks_tracking_anon").click(function(){
									$("#clicks").val("2");
								});
							});
						</script>
					</p>
					<input type="hidden" name="opens" id="opens" value="">
					<input type="hidden" name="clicks" id="clicks" value="">
					
					<br/><br/>
				</div>
			</div>
	        
	        <div class="accordion-group">
		        <div class="accordion-heading">
			        <a href="#collapse4" class="accordion-toggle" data-parent="#accordion" data-toggle="collapse"><h3><span class="icon icon-reorder"></span> <?php echo _('Miscellaneous');?></h3></a>
		        </div>
		        
		        <div id="collapse4" class="accordion-body collapse">
			        <br/>
			        <label class="control-label" for="query_string" style="width:70%;"><?php echo _('Default URL query string');?></label>
				    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="query_string" name="query_string" placeholder="utm_source=newsletter&utm_medium=sendy&utm_campaign=email_marketing" value="<?php echo get_saved_data('query_string');?>" style="width: 70%;" autocomplete="off">
			              <br/>
			              <span><i class="thirtytwo"><?php echo _('(Default query string to append to all links in your campaigns and autoresponders. A good use case is Google Analytics tracking. Don\'t include \'?\' in your query string.)')?></i></span>
			            </div>
			        </div>
			        
			        <br/>
			        
			        <label class="control-label" for="test_email_prefix"><?php echo _('Prefix for subject line of test emails');?></label>
				    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="test_email_prefix" name="test_email_prefix" placeholder="[Test]" value="<?php echo get_saved_data('test_email_prefix');?>" autocomplete="off">
			              <br/>
			              <span><i class="thirtytwo"><?php echo _('(Optionally add a prefix to the subject line of test emails)')?></i></span>
			            </div>
			        </div>
			        
			        <br/>
			        
			        <label class="control-label" for="allowed_attachments"><?php echo _('Allowed attachments file types');?></label>
				    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="allowed_attachments" name="allowed_attachments" placeholder="jpeg,jpg,gif,png,pdf,zip" value="<?php echo get_saved_data('allowed_attachments');?>" autocomplete="off">
			              <br/>
			              <span><i class="thirtytwo"><?php echo _('(Empty field to disable attachments in campaigns)')?></i></span>
			            </div>
			        </div>
			        
			        <br/>
			        
			        <label class="control-label" for="sort-by"><?php echo _('Sort lists and templates by');?></label>
			        	<div class="control-group">
					    	<div class="controls">					    	
					    		<!-- Choice -->
							<select name="sort-by" id="sort-by">
							  <option value="date" id="sort-by-date" <?php if(get_saved_data('templates_lists_sorting')=='date'):?>selected="selected"<?php endif;?>><?php echo _('Date added');?></option>
							  <option value="name" id="sort-by-name" <?php if(get_saved_data('templates_lists_sorting')=='name'):?>selected="selected"<?php endif;?>><?php echo _('Name');?></option>
							 </select>
					    	</div>
			        	</div>
			        	
			        	<br/>
			        
			        <label class="control-label" for="campaign_report_rows"><?php echo _('No. of rows to show in campaigns, reports, lists and rules');?></label>
				    	<div class="control-group">
				    	<div class="input-prepend input-append">
			              <input type="text" class="input-xlarge" id="campaign_report_rows" name="campaign_report_rows" placeholder="10" value="<?php echo get_saved_data('campaign_report_rows');?>" style="width: 80px;" autocomplete="off"><span class="add-on"><?php echo _('rows per page');?></span>
			            </div>
			        </div>
					
					<br/>
					
					<label class="control-label"><?php echo _('Default opt-in method');?>:</label>
					<div class="btn-group" data-toggle="buttons-radio" style="margin-top: 5px;">
					  <a href="javascript:void(0)" title="" class="btn" id="single"><i class="icon icon-angle-right"></i> <?php echo _('Single Opt-In');?></a>
					  <a href="javascript:void(0)" title="" class="btn" id="double"><i class="icon icon-double-angle-right"></i> <?php echo _('Double Opt-In');?></a>
					</div>
					
					<br/>
										
					<label class="control-label" style="margin-top: 17px;"><?php echo _('Hide hidden lists');?>:</label>
					<div class="btn-group tracking" data-toggle="buttons-radio">
					  <a href="javascript:void(0)" title="" class="btn" id="hide_hidden_lists_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
					  <a href="javascript:void(0)" title="" class="btn" id="hide_hidden_lists_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
					</div>
			        
					<script type="text/javascript">
						$(document).ready(function() {
							//Show / hide hidden lists
							<?php 
								$hide_hidden_lists = get_saved_data('hide_lists');
								if($hide_hidden_lists==1):
							?>
							$("#hide_hidden_lists_on").button('toggle');
							$("#hide_lists").val("1");
							<?php elseif($hide_hidden_lists==0):?>
							$("#hide_hidden_lists_off").button('toggle');
							$("#hide_lists").val("0");
							<?php endif;?>
							
							$("#hide_hidden_lists_on").click(function(){
								$("#hide_lists").val("1");
							});
							$("#hide_hidden_lists_off").click(function(){
								$("#hide_lists").val("0");
							});
							
							//Default opt-in method
							<?php 
								$opt_in = get_saved_data('opt_in');
								if($opt_in==0):
							?>
							$("#single").button('toggle');
							$("#opt_in").val("0");
							<?php else:?>
							$("#double").button('toggle');
							$("#opt_in").val("1");
							<?php endif;?>
												
							$("#single").click(function(){
								$("#opt_in").val("0");
							});
							$("#double").click(function(){
								$("#opt_in").val("1");
							});
						});
					</script>
					
					<input type="hidden" name="opt_in" id="opt_in" value="<?php echo $opt_in;?>">
					
					<input type="hidden" name="hide_lists" id="hide_lists" value="">
					
			        <input type="hidden" name="id" value="<?php echo get_app_info('app');?>">
			        
			        <br/>
		        </div>
	        </div>
	        
	        <br/>
	        
	        <button type="submit" class="btn btn-inverse"><i class="icon-ok icon-white"></i> <?php echo _('Save');?></button>
	        
        </div>
	    
    </div> 
    
    <div class="span5">
	    <h2><?php echo _('Brand settings');?></h2><br/>
	    
	    <div class="alert alert-info"><i class="icon icon-info-sign"></i> <?php echo _('If you\'re creating this brand for your client, you can allow them to send newsletters on their own at a fee you preset below.');?> <?php echo _('Send the');?> <strong><?php echo _('Client login details');?></strong> <?php echo _('to your client so that they can login to manage lists, subscribers and send newsletters.');?><br/><br/><?php echo _('Also, don\'t forget to set your PayPal account email address in');?> <a href="<?php echo get_app_info('path');?>/settings" style="text-decoration: underline;"><?php echo _('Settings');?></a>.</div>
	    
	    <div class="well">
		    
		    <div class="accordion" id="accordion2">
	    		<div class="accordion-group">
		    		<div class="accordion-heading">
				    	<a href="#collapse2_1" class="accordion-toggle" data-parent="#accordion2" data-toggle="collapse"><h3><span class="icon icon-lock"></span> <?php echo _('Client login details');?></h3></a>
		    		</div>
		    		<div id="collapse2_1" class="accordion-body collapse in">
				    	<p><strong><?php echo _('Login URL');?></strong>: 
				    		<span id="loginurl">
					    	<?php
						    	$parse = parse_url(get_app_info('path'));
								$protocol = $parse['scheme'];
								$domain = $parse['host'];
								$path = $parse['path']; 
						    	if(get_saved_data('custom_domain')=='' || get_saved_data('custom_domain_enabled')==0)
									echo '<span id="sendy_protocol">'.$protocol.'</span>'.'://'.'<span class="customdomain" id="sendy_domain">'.$domain.'</span>'.$path;
								else
									echo '<span id="sendy_protocol">'.get_saved_data('custom_domain_protocol').'</span>'.'://'.'<span class="customdomain" id="sendy_domain">'.get_saved_data('custom_domain').'</span>'.$path;
					    	?>
				    		</span>
				    	</p>
					    <p><strong><?php echo _('Login email');?></strong>: <input type="text" name="login_email" id="login_email" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_login_data('username');?>" style="margin-top: 5px;" autocomplete="off"/></p>
					    <div class="alert alert-danger" id="duplicate-login-email" style="display:none;"><i class="icon icon-warning-sign"></i> <?php echo _('This login email is already in use by your main login email address set in your main Settings. Please use another email address or remove the email address from the field.');?> </div>
				    	<p><strong><?php echo _('Password');?></strong>: <span id="generate-password-wrapper"><a href="javascript:void(0)" style="text-decoration:underline;" id="generate-password"><?php echo _('Generate new password');?></a></span></p>
				    	<?php if(get_login_data('auth_enabled')):?>
				    		<p id="2fa-text"><?php echo _('Two-factor authentication is currently <strong class="label label-success">enabled</strong>');?></p>
				    		<p id="disable-2fa-btn">
					    		<button id="disable-two-factor-btn" class="btn">
						    		<span class="icon icon-key"></span> <?php echo _('Disable two-factor authentication');?>
						    	</button>
				    		</p>
				    		<script type="text/javascript">
					    		$("#disable-two-factor-btn").click(function(e){
									e.preventDefault(); 
									if(confirm("Are you sure you want to disable two-factor authentication?"))
									{
										$.post("<?php echo get_app_info("path");?>/includes/settings/two-factor.php", { enable: 0, otp: 0, uid: <?php echo get_login_data('id');?> },
										  function(data) {
										      if(data)
										      {
											      $("#disable-2fa-btn").slideUp();
											      $("#2fa-text").html("<?php echo _('Two-factor authentication is currently <strong class=\"label\">disabled</strong>').'<br/><i>'._('(two-factor authentication can only be enabled by the brand user while logged in to their account)').'</i>';?>");
											      $("#2fa-text i").addClass("thirtytwo");
										      } else alert("<?php echo _('Unable to save. Please try again.');?>");
										  }
										);
									}
								});
				    		</script>
				    	<?php else:?>
				    		<p><?php echo _('Two-factor authentication is currently <strong class="label">disabled</strong>');?><br/><i class="thirtytwo"><?php echo _('(two-factor authentication can only be enabled by the brand user while logged in to their account)');?></i></p>
				    	<?php endif;?>
				    	<script type="text/javascript">
					    	$("#generate-password").click(function(){
					    		$("#form").submit();
					    		
					    		$.post('<?php echo get_app_info('path');?>/includes/app/generate-password.php', {app: <?php echo get_app_info('app');?>, brand_name: $("#app_name").val(), from_name: $("#from_name").val(), from_email: $("#from_email").val()},
					    		  function(data) {
					    		      if(data)
					    		      {
					    		      	$("#generate-password-wrapper").html(data);
					    		      }
					    		  }
					    		);
					    	});
				    	</script>
						<p><strong><strong><?php echo _('Language');?></strong>: </strong>
							<select id="language" name="language" style="margin-top:5px;">
							  <option value="<?php echo get_login_data('language');?>"><?php echo get_login_data('language');?></option>
							  <?php 
									if($handle = opendir('locale')) 
									{
										$i = -1;						
									    while (false !== ($file = readdir($handle))) 
									    {
									    	if($file!='.' && $file!='..' && substr($file, 0, 1)!='.')	
									    	{
									    		if(get_login_data('language')!=$file)
											    	echo '<option value="'.$file.'">'.$file.'</option>';
										    }
											
											$i++;
									    }
									    closedir($handle);
									}
							  ?>
							</select>
						</p>
						<br/>
		    		</div>
	    		</div>
	    		
	    		<div class="accordion-group">
		    		<div class="accordion-heading">
						<a href="#collapse2_2" class="accordion-toggle" data-parent="#accordion2" data-toggle="collapse"><h3><span class="icon icon-check"></span> <?php echo _('Client privileges');?></h3></a>
		    		</div>
		    		
		    		<div id="collapse2_2" class="accordion-body collapse">
						<p><?php echo _('By default, your client will have full access to their own brand so they can manage their own lists, subscribers, campaigns, templates and see reports. You can however adjust these privileges below.');?></p>
						<p>
							<div class="dashed-box">
								<div class="checkbox">
								  <label><input type="checkbox" name="campaigns" <?php echo get_app_data('campaigns_only')==0 ? 'checked' : '';?>><?php echo _('Client can access campaigns');?></label>
								</div>
								<div class="checkbox">
								  <label><input type="checkbox" name="templates" <?php echo get_app_data('templates_only')==0 ? 'checked' : '';?>><?php echo _('Client can access templates');?></label>
								</div>
								<div class="checkbox">
								  <label><input type="checkbox" name="lists-subscribers" <?php echo get_app_data('lists_only')==0 ? 'checked' : '';?>><?php echo _('Client can access lists and subscribers');?></label>
								</div>
								<div class="checkbox">
								  <label><input type="checkbox" name="reports" <?php echo get_app_data('reports_only')==0 ? 'checked' : '';?>><?php echo _('Client can access campaign reports');?></label>
								</div>
							</div>
						</p>
						<br/>
		    		</div>
	    		</div>
				
				<div class="accordion-group">
					<div class="accordion-heading">
						<a href="#collapse2_3" class="accordion-toggle" data-parent="#accordion2" data-toggle="collapse"><h3><span class="icon icon-envelope"></span> <?php echo _('Campaign sent notifications');?></h3></a>
					</div>
					
					<div id="collapse2_3" class="accordion-body collapse">
						<p><?php echo _('When a campaign is sent successfully, a \'Campaign sent\' email notification is sent to the \'From email\' address that was used to send out the campaign. If you want the same email notification to be sent to your main login email address as well so that you\'re notified each time your client sends out a campaign, check the box below.');?></p>
						<p>
							<div class="dashed-box">
								<div class="checkbox">
								  <label><input type="checkbox" name="notify_campaign_sent" <?php echo get_app_data('notify_campaign_sent')==1 ? 'checked' : '';?>><?php echo _('Send email notifications to my main login email ').'('.get_app_info('email').')';?></label>
								</div>
							</div>
						</p>
						<br/>
					</div>
				</div>
				
				<div class="accordion-group">
					<div class="accordion-heading">
						<a href="#collapse2_4" class="accordion-toggle" data-parent="#accordion2" data-toggle="collapse"><h3><span class="icon icon-money"></span> <?php echo _('Campaign fee settings');?></h3></a>
					</div>
			    	
			    	<div id="collapse2_4" class="accordion-body collapse">
				    	<label class="control-label" for="currency"><?php echo _('Currency');?></label>
				    	<div class="control-group">
					    	<div class="controls">
								<select name="currency">
									<option value="USD" id="USD">U.S. Dollars</option>
									<option value="CAD" id="CAD">Canadian Dollars</option>
									<option value="EUR" id="EUR">Euros</option>
									<option value="GBP" id="GBP">Pounds Sterling</option>
									<option value="AUD" id="AUD">Australian Dollars</option>
									<option value="JPY" id="JPY">Yen</option>
									<option value="INR" id="INR">Indian rupee</option>
									<option value="NZD" id="NZD">New Zealand Dollar</option>
									<option value="CHF" id="CHF">Swiss Franc</option>
									<option value="HKD" id="HKD">Hong Kong Dollar</option>
									<option value="SGD" id="SGD">Singapore Dollar</option>
									<option value="SEK" id="SEK">Swedish Krona</option>
									<option value="DKK" id="DKK">Danish Krone</option>
									<option value="PLN" id="PLN">Polish Zloty</option>
									<option value="NOK" id="NOK">Norwegian Krone</option>
									<option value="HUF" id="HUF">Hungarian Forint</option>
									<option value="RUB" id="RUB">Russian ruble</option>
									<option value="CZK" id="CZK">Czech Koruna</option>
									<option value="ILS" id="ILS">Israeli Shekel</option>
									<option value="MXN" id="MXN">Mexican Peso</option>
									<option value="BRL" id="BRL">Brazilian Real</option>
									<option value="MYR" id="MYR">Malaysian Ringgits</option>
									<option value="PHP" id="PHP">Philippine Pesos</option>
									<option value="TWD" id="TWD">Taiwan New Dollars</option>
									<option value="THB" id="THB">Thai Baht</option>
								 </select>
								 <script type="text/javascript">
									 $("#<?php echo get_saved_data('currency');?>").attr("selected", "selected");
								 </script>
				            </div>
				        </div>
				        
				        <?php 
					        $currency_symbol = get_saved_data('currency');
					        if($currency_symbol=='USD' || $currency_symbol=='SGD' || $currency_symbol=='')
					        	$currency_symbol = '$';
				        ?>
				        
				        <label class="control-label" for="delivery_fee"><?php echo _('Delivery Fee');?></label>
				    	<div class="control-group">
					    	<div class="controls">
					    		<div class="input-prepend input-append" style="margin-left:1px;">
					              <span class="add-on"><?php echo $currency_symbol;?></span><input type="text" class="input-xlarge" id="delivery_fee" name="delivery_fee" placeholder="Eg. 5" value="<?php echo get_saved_data('delivery_fee');?>" style="width: 80px;" autocomplete="off">
					            </div>
				            </div>
				        </div>
				        
				        <label class="control-label" for="cost_per_recipient"><?php echo _('Cost per recipient');?></label>
				    	<div class="control-group">
					    	<div class="controls">
					    		<div class="input-prepend input-append" style="margin-left:1px;">
					              <span class="add-on"><?php echo $currency_symbol;?></span><input type="text" class="input-xlarge" id="cost_per_recipient" name="cost_per_recipient" placeholder="Eg. .01" value="<?php echo get_saved_data('cost_per_recipient');?>" style="width: 80px;" autocomplete="off">
					            </div>
				            </div>
				        </div>
				        <br/>
			    	</div>
				</div>
		        
		        <div class="accordion-group">
			        <div class="accordion-heading">
				    	<a href="#collapse2_5" class="accordion-toggle" data-parent="#accordion2" data-toggle="collapse"><h3><span class="icon icon-pause"></span> <?php echo _('Sending limits');?></h3></a>
			        </div>
			    	
			    	<div id="collapse2_5" class="accordion-body collapse">
				    	<div class="control-group">
					    	<div class="controls">
					    	
					    		<!-- Choice -->
								<select name="choose-limit" id="choose-limit">
								  <option value="unlimited" id="unlimited"><?php echo _('No limits');?></option>
								  <option value="custom" id="custom" <?php if(get_saved_data('allocated_quota')!='-1' && !get_saved_data('no_expiry')):?>selected="selected"<?php endif;?>><?php echo _('Monthly limit');?></option>
								  <option value="no_expiry" id="no_expiry" <?php if(get_saved_data('no_expiry')):?>selected="selected"<?php endif;?>><?php echo _('Non expiring limit');?></option>
								 </select>
								 
								 <!-- Set number -->
								 <label class="control-label" for="choose-limit" id="no_of_emails_label" style="margin-top: 15px; <?php if(get_saved_data('allocated_quota')=='-1'):?>display:none;<?php endif;?>"><?php echo get_saved_data('no_expiry') ? _('Number of emails') : _('Number of emails per month');?></label>
								 <input type="text" class="input-xlarge" id="monthly-limit" name="monthly-limit" placeholder="eg. 100000" value="<?php $aquota = get_saved_data('allocated_quota'); if($aquota!='-1') echo $aquota;?>" <?php if(get_saved_data('allocated_quota')=='-1'):?>style="display:none;"<?php endif;?>>
								 
								 <label class="control-label" for="current-limit" style="margin-top: 7px; <?php if(get_saved_data('allocated_quota')=='-1'):?>display:none;<?php endif;?>" id="current-limit-label"><?php echo _('Currently used');?></label>
								 <input type="text" class="input-xlarge" id="current-limit" name="current-limit" value="<?php echo get_saved_data('current_quota');?>" <?php if(get_saved_data('allocated_quota')=='-1'):?>style="display:none;"<?php endif;?>>
								 
								 <script type="text/javascript">
									 $(document).ready(function() {
									 	//Choose or set limit
									 	$("#choose-limit").change(function(){			
											if($(this).find(":selected").text()=='<?php echo _('Monthly limit');?>')
											{
												$("#no_of_emails_label").text("<?php echo _('Number of emails per month');?>");
												$("#no_of_emails_label").show();
												$("#monthly-limit").show();
												$("#limit-reset-label").show();
												$("#reset-on-day").show();
												$("#current-limit-label").show();
												$("#current-limit").show();
												$("#monthly-limit").focus();
											}
											else if($(this).find(":selected").text()=='<?php echo _('Non expiring limit');?>')
											{
												$("#no_of_emails_label").text("<?php echo _('Number of emails');?>");
												$("#no_of_emails_label").show();
												$("#monthly-limit").show();
												$("#limit-reset-label").hide();
												$("#reset-on-day").hide();
												$("#current-limit-label").show();
												$("#current-limit").show();
												$("#monthly-limit").focus();
											}
											else
											{
												$("#no_of_emails_label").text("");
												$("#no_of_emails_label").hide();
												$("#monthly-limit").hide();
												$("#limit-reset-label").hide();
												$("#reset-on-day").hide();
												$("#current-limit-label").hide();
												$("#current-limit").hide();
											}							
										});
										
										//preselect 'reset on day'
										$("#reset-on-day").val('<?php echo get_saved_data('day_of_reset');?>');
									 });
								 </script>
				            </div>
				        </div>
				        
				        <!-- Reset on day -->
				        <label class="control-label" for="reset-on-day" id="limit-reset-label" <?php if(get_saved_data('allocated_quota')=='-1' || get_saved_data('no_expiry')):?>style="display:none;"<?php endif;?>><?php echo _('Reset limit on which day of the month?');?></label>
				    	<div class="control-group">
					    	<div class="controls">
								<select name="reset-on-day" id="reset-on-day" style="width:80px; <?php if(get_saved_data('allocated_quota')=='-1' || get_saved_data('no_expiry')):?>display:none;<?php endif;?>">
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5</option>
									<option value="6">6</option>
									<option value="7">7</option>
									<option value="8">8</option>
									<option value="9">9</option>
									<option value="10">10</option>
									<option value="11">11</option>
									<option value="12">12</option>
									<option value="13">13</option>
									<option value="14">14</option>
									<option value="15">15</option>
									<option value="16">16</option>
									<option value="17">17</option>
									<option value="18">18</option>
									<option value="19">19</option>
									<option value="20">20</option>
									<option value="21">21</option>
									<option value="22">22</option>
									<option value="23">23</option>
									<option value="24">24</option>
									<option value="25">25</option>
									<option value="26">26</option>
									<option value="27">27</option>
									<option value="28">28</option>
									<option value="29">29</option>
									<option value="30">30</option>
									<option value="31">31</option>
								</select>
					        </div>
				        </div>
			    	</div>
		        </div>
		    
		    </div>
		    
	    </div>
        
        <input type="hidden" name="lists" id="lists" value="">
        
        <button type="submit" class="btn btn-inverse"><i class="icon-ok icon-white"></i> <?php echo _('Save');?></button>
        
    </div>  
</div>

</form>

<script type="text/javascript">
$(document).ready(function() {	
	if($("#custom_domain").val()=="") 
		$("#cd_example").text("customdomain.com");
	
	//Custom domain
	var default_customdomain_text = "customdomain.com";
	var default_domain = "<?php echo $domain;?>";
	var default_protocol = "<?php echo $protocol;?>";
	
	//When typing in custom domain field
	$("#custom_domain").keyup(function() {
		$("#cd_instructions").slideDown();
		$(".customdomain").text($("#custom_domain").val());
		$("#sendy_protocol").text($("#protocol").val());
		$("#hostname-test").text($("#custom_domain").val());
		
		if($("#custom_domain").val()=="")
		{
			$("#cd_instructions").slideUp();
			$("#cd_example").text(default_customdomain_text);
			$("#sendy_domain").text(default_domain);
			$("#sendy_protocol").text(default_protocol);
		}
	});
	
	//When changing protocol drop down menu
	$("#protocol").change(function(){
		if($("#custom_domain").val()!="")
			$("#sendy_protocol").text($(this).val());
		
		$("#protocol-test").text($(this).val());
	});
	
	$("#cd_example, #sd, #loginurl").mouseover(function(){$(this).selectText();});
});
</script>

<?php include('includes/footer.php');?>