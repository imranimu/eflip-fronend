<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/create/main.php');?>
<?php include('includes/create/google-translate-languages.php');?>
<?php
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('app')!=get_app_info('restricted_to_app'))
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/create?i='.get_app_info('restricted_to_app').'"</script>';
			exit;
		}
	}
?>

<script src="<?php echo get_app_info('path');?>/js/ckeditor/ckeditor.js?11"></script>
<?php $dark_mode = get_app_info('dark_mode');?>
<?php include('js/create/editor.php');?>

<!-- Validation -->
<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/validate.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#edit-form").validate({
			rules: {
				subject: {
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
				html: {
					required: true
				}
			},
			messages: {
				subject: "<?php echo addslashes(_('The subject of your email is required'));?>",
				from_name: "<?php echo addslashes(_('\'From name\' is required'));?>",
				from_email: "<?php echo addslashes(_('A valid \'From email\' is required'));?>",
				reply_to: "<?php echo addslashes(_('A valid \'Reply to\' email is required'));?>",
				html: "<?php echo addslashes(_('Your HTML code is required'));?>"
			}
		});
		
		//Check if Grammarly extension is installed in the browser, if so, inform the user
		setTimeout(	function()	{if($("grammarly-btn").length) $("#grammarly-error").slideDown();}, 5000);
		
		$("#subject").focus();
	});
</script>

<div class="row-fluid">
    <div class="span2">
        <?php include('includes/sidebar.php');?>
    </div> 
    <div class="span10">
    	<div class="row-fluid">
	    	<div>
		    	<p class="lead">
		    	<?php if(get_app_info('is_sub_user')):?>
			    	<?php echo get_app_data('app_name');?>
		    	<?php else:?>
			    	<a href="<?php echo get_app_info('path'); ?>/edit-brand?i=<?php echo get_app_info('app');?>" data-placement="right" title="<?php echo _('Edit brand settings');?>"><?php echo get_app_data('app_name');?> <span class="icon icon-pencil top-brand-pencil"></span></a>
		    	<?php endif;?>
		    </p>
	    	</div>
	    	<h2><?php echo _('New campaign');?></h2><br/>
    	</div>
    	<div class="row-fluid">
    		<form action="<?php echo get_app_info('path')?>/includes/create/save-campaign.php?i=<?php echo get_app_info('app')?>" method="POST" accept-charset="utf-8" class="form-vertical" id="edit-form" enctype="multipart/form-data">
			    <div class="span3">
				    
				    <?php if(have_templates()):?>
				    <div class="dropdown">
					  <button class="btn dropdown-toggle" type="button" data-toggle="dropdown"><i class="icon-plus-sign"></i> <?php echo _('Select a template');?>
					  <span class="caret"></span></button>
					  <ul class="dropdown-menu">
						  <?php 
							  $q = 'SELECT id, template_name FROM template WHERE app = '.get_app_info('app').' ORDER BY id DESC';
							  $r = mysqli_query($mysqli, $q);
							  if ($r && mysqli_num_rows($r) > 0)
							  {
							      while($row = mysqli_fetch_array($r))
							      {
							  		$template_id = $row['id'];
							  		$template_name = stripslashes($row['template_name']);
							  		
							  		echo '<li><a href="'.get_app_info('path').'/includes/templates/use-template.php?i='.get_app_info('app').'&t='.$template_id.'">'.$template_name.'</a></li>';
							      }  
							  }
						  ?>
					  </ul>
					</div>
					<br/>
					<?php endif;?>
				    
				    <div id="campaign-title-field" style="display:none;">
					    <label class="control-label" for="campaign_title"><?php echo _('Campaign title');?></label>
				    	<div class="control-group">
					    	<div class="controls">
				              <input type="text" class="input-xlarge" id="campaign_title" name="campaign_title" placeholder="<?php echo _('Title of this campaign');?>">
				            </div>
				        </div>
			        </div>
				    
			    	<label class="control-label" for="subject"><?php echo _('Subject');?></label>
			    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="subject" name="subject" placeholder="<?php echo _('Subject of this email');?>">
			            </div>
			        </div>
			        
			        <a href="javascript:void(0);" id="set-campaign-title-btn"><?php echo _('Set a title for this campaign?');?></a> 
			        <a href="javascript:void(0)" title="<?php echo _('This title (instead of the subject line) will be displayed in your campaigns list and reports. You can also set the title later in the campaign report after sending this campaign.');?>" class="icon icon-info-sign" id="set-campaign-title-btn-info"></a>
					<script type="text/javascript">
					  $(document).ready(function() {
					  	$("#set-campaign-title-btn, #set-campaign-title-btn-info").click(function(){
					      	$(this).fadeOut();
					      	$("#set-campaign-title-btn-info").fadeOut();
					      	$("#campaign-title-field").slideDown("fast");
					      	$("#campaign_title").focus();
					  	});
					  	$("#campaign_title").blur(function(){
						  	if($(this).val()=='')
						  	{
							  	$("#set-campaign-title-btn").fadeIn();
							  	$("#set-campaign-title-btn-info").fadeIn();
						      	$("#campaign-title-field").slideUp("fast");
					        }
					  	});
					  });
					</script>
			        
			        <label class="control-label" for="from_name" style="clear:both;"><?php echo _('From name');?></label>
			    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="from_name" name="from_name" placeholder="<?php echo _('From name');?>" value="<?php echo get_app_data('from_name');?>">
			            </div>
			        </div>
			        
			        <label class="control-label" for="from_email"><?php echo _('From email');?></label>
			    	<div class="control-group">
				    	<div class="controls">
					      <?php 
						      //Get main user's login email address
						      $q = 'SELECT username FROM login WHERE id = '.get_app_info('main_userID');
						      $r = mysqli_query($mysqli, $q);
						      if ($r) while($row = mysqli_fetch_array($r)) $main_email = $row['username'];
					      ?>
			              <input type="text" class="input-xlarge" <?php if(get_app_info('is_sub_user') && verify_identity($main_email)!='verified' && get_app_info('s3_key')!='' && get_app_info('s3_secret')!='') echo 'readonly="readonly"';?> id="from_email" name="from_email" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_app_data('from_email');?>">
			            </div>
			        </div>
			        
			        <label class="control-label" for="reply_to"><?php echo _('Reply to email');?></label>
			    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="reply_to" name="reply_to" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_app_data('reply_to');?>">
			            </div>
			        </div>
			        
			        <label class="control-label" for="plain"><?php echo _('Plain text version');?></label>
		            <div class="control-group">
				    	<div class="controls">
			              <textarea class="input-xlarge" id="plain" name="plain" rows="10" placeholder="<?php echo _('Plain text version of this email');?>"></textarea>
			            </div>
			        </div>
			        
			        <label class="control-label" for="query_string"><?php echo _('Query string');?> <a href="javascript:void(0)" title="<?php echo _("Optionally append a query string to all links in your email newsletter. A good use case is Google Analytics tracking. Don't include '?' in your query string.");?>"><span class="icon icon-question-sign"></span></a></label>
			    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="query_string" name="query_string" placeholder="eg. utm_source=newsletter&utm_medium=sendy&utm_campaign=email_marketing" value="<?php echo get_app_data('query_string');?>" style="width: 100%;">
			            </div>
			        </div><br/>
					
					<div>
						<label for="web_version_language"><?php echo _('Web version language');?> <a href="javascript:void(0)" title="<?php echo _("The web version of this email will be translated by Google Translate to the language you select.");?>"><span class="icon icon-question-sign"></span></a></label>
						<select id="web_version_language" name="web_version_language"> 
							<option value="dont-translate"><?php echo _('Don\'t translate');?></option>
							  <?php get_google_translate_languages();?>
						</select>
					</div><br/>
			        
			        <?php 
				        $allowed_attachments = get_app_data('allowed_attachments');
				        $allowed_attachments_array = array_map('trim', explode(',', $allowed_attachments));
				        $allowed_attachments_exts = implode(', ', $allowed_attachments_array);
				        if($allowed_attachments!=''):
				    ?>
			        <label class="control-label" for="attachments"><?php echo _('Attachments');?></label>
		            <div class="control-group">
				    	<div class="controls">
				    		<input type="file" id="attachments" name="attachments[]" multiple />
			            </div>
			            <p class="thirtytwo"><i><?php echo _('Allowed file types');?>: <?php echo $allowed_attachments_exts;?></i></p>
			        </div><br/>
			        <?php endif;?>
			        
			        <p>
				        <?php echo _('Track opens');?>: 
				        <div class="btn-group tracking" data-toggle="buttons-radio">
						  <a href="javascript:void(0)" title="<?php echo _('Enable opens tracking');?>" class="btn" id="opens_tracking_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Disable opens tracking');?>" class="btn" id="opens_tracking_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Track opens without identifying users to respect their privacy');?>" class="btn" id="opens_tracking_anon"><i class="icon icon-ok"></i> <?php echo _('Anonymously');?></a>
						</div>
						
						<script type="text/javascript">
							$(document).ready(function() {
								<?php 
									$opens_tracking = get_app_data('opens_tracking');
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
						  <a href="javascript:void(0)" title="<?php echo _('Enable clicks tracking');?>" class="btn" id="clicks_tracking_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Disable clicks tracking');?>" class="btn" id="clicks_tracking_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						  <a href="javascript:void(0)" title="<?php echo _('Track clicks without identifying users to respect their privacy');?>" class="btn" id="clicks_tracking_anon"><i class="icon icon-ok"></i> <?php echo _('Anonymously');?></a>
						</div>
						
						<script type="text/javascript">
							$(document).ready(function() {
								<?php 
									$links_tracking = get_app_data('links_tracking');
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
					<br/>
					<p>
						<?php echo _('Save this email as a template');?>: 
						<div class="btn-group tracking" data-toggle="buttons-radio">
							<a href="javascript:void(0)" title="<?php echo _('Save this email as a template');?>" class="btn" id="save_as_template_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
							<a href="javascript:void(0)" title="<?php echo _('Don\'t save this email as a template');?>" class="btn" id="save_as_template_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						</div>
						
						<script type="text/javascript">
							$(document).ready(function() {
								$("#save_as_template_off").button('toggle');
								$("#save_as_template").val("off");
								
								$("#save_as_template_on").click(function(){
									$("#save_as_template").val("on");
								});
								$("#save_as_template_off").click(function(){
									$("#save_as_template").val("off");
								});
							});
						</script>
					</p>
					<br/>
					<p>
						<?php echo _('Check for broken links');?>: 
						<div class="btn-group tracking" data-toggle="buttons-radio">
							<a href="javascript:void(0)" title="<?php echo _('Check for broken links');?>" class="btn" id="check_for_broken_links_on"><i class="icon icon-ok"></i> <?php echo _('Yes');?></a>
							<a href="javascript:void(0)" title="<?php echo _('Don\'t check for broken links');?>" class="btn" id="check_for_broken_links_off"><i class="icon icon-remove-sign"></i> <?php echo _('No');?></a>
						</div>
						
						<script type="text/javascript">
							$(document).ready(function() {
								$("#check_for_broken_links_off").button('toggle');
								$("#check_for_broken_links").val("off");
								
								$("#check_for_broken_links_off").click(function(){
									$("#check_for_broken_links").val("off");
								});
								$("#check_for_broken_links_on").click(function(){
									$("#check_for_broken_links").val("on");
								});
							});
						</script>
					</p>
					
			        <input type="hidden" name="opens" id="opens" value="">
			        <input type="hidden" name="clicks" id="clicks" value="">
			        <input type="hidden" name="save_as_template" id="save_as_template" value="">
					<input type="hidden" name="check_for_broken_links" id="check_for_broken_links" value="">
					
			        <br/><br/>
			        
			        <a href="javascript:void(0)" id="campaign-save-only-btn" class="btn"><i class="icon-ok icon-white"></i> <?php echo _('Save');?></a>
			        <button type="submit" class="btn btn-inverse"><i class="icon-arrow-right icon-white"></i> <?php echo _('Save & next');?></button>		        
			    </div>   
			    <div class="span9">
				    <!-- Grammarly error -->
				    <?php 
					    $app_path_no_http_array = explode('/', get_app_info('path'));
					    $app_path_no_http = $app_path_no_http_array[2];
				    ?>
				    <div class="alert alert-error" id="grammarly-error" style="display:none;">
					  <button class="close" onclick="$('.alert-error').hide();">×</button>
					  <h3><span class="icon icon-warning-sign"></span> <?php echo _('Disable Grammarly extension in your browser');?></h3><br/>
					  <p><?php echo _('You have \'Grammarly\' extension installed in your browser. Grammarly injects thousands of lines of code into the email editor. As a result your email will have an extremely huge file size, look weird and you may not be able to save or send your campaign. To avoid all these, please do the following:');?></p>
					  <ol>
						  <li><?php echo _('Disable Grammarly extension in your browser (please see image below)');?></li>
						  <li><?php echo _('Then refresh this page');?></li>
					  </ol>
					  <p><img src="<?php echo get_app_info('path');?>/img/turn-off-grammarly.gif" title=""/></p>
					</div>
					<!-- Grammarly error -->
					
			    	<p>
				    	<label class="control-label" for="html"><?php echo _('HTML code');?></label>
				    	<div class="btn-group">
				    	<button class="btn" id="toggle-wysiwyg"><?php echo _('Save and switch to HTML editor');?></button> 
				    	<span class="wysiwyg-note"><?php echo _('Switch to HTML editor if the WYSIWYG editor is causing your newsletter to look weird.');?></span>
						<script type="text/javascript">
							$("#toggle-wysiwyg").click(function(e){
								e.preventDefault();
								
								$('<input>').attr({
								    type: 'hidden',
								    id: 'wysiwyg',
								    name: 'wysiwyg',
								    value: '0',
								}).appendTo("#edit-form");
								
								$('<input>').attr({
								    type: 'hidden',
								    id: 'w_clicked',
								    name: 'w_clicked',
								    value: '1',
								}).appendTo("#edit-form");
								
								$("#subject").rules("remove");
								$("#html").rules("remove");
								if($("#subject").val()=="") $("#subject").val("Untitled");
								
								$("#edit-form").submit();
							});
						</script>
						</div>
						<br/>
			            <div class="control-group">
					    	<div class="controls">
				              <textarea class="input-xlarge" id="html" name="html" rows="10" placeholder="<?php echo _('Email content');?>"></textarea>
				            </div>
				        </div>
				    	<p><?php echo _('Use the following tags in your subject, plain text or HTML code and they\'ll automatically be formatted when your campaign is sent. For web version and unsubscribe tags, you can style them with inline CSS.');?></p><br/>
				    	<div class="row-fluid">
					    	<?php include('includes/helpers/personalization.tags.php');?>
				    	</div>
			    	</p>
			    </div> 
			 </form>
		</div>
	</div>
</div>
<?php include('includes/footer.php');?>
