<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/templates/main.php');?>
<?php 
	//IDs
	$tid = isset($_GET['t']) && is_numeric($_GET['t']) ? mysqli_real_escape_string($mysqli, $_GET['t']) : exit;
	$aid = isset($_GET['i']) && is_numeric($_GET['i']) ? get_app_info('app') : exit;
	
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('app')!=get_app_info('restricted_to_app'))
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/templates?i='.get_app_info('restricted_to_app').'"</script>';
			exit;
		}
		$q = 'SELECT app FROM template WHERE id = '.$tid;
		$r = mysqli_query($mysqli, $q);
		if ($r)
		{
		    while($row = mysqli_fetch_array($r))
		    {
				$a = $row['app'];
		    }  
		    if($a!=get_app_info('restricted_to_app'))
		    {
			    echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/templates?i='.get_app_info('restricted_to_app').'"</script>';
				exit;
		    }
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
				template_name: {
					required: true	
				},
				html: {
					required: true
				}
			},
			messages: {
				template_name: "<?php echo addslashes(_('The name of this template is required'));?>",
				html: "<?php echo addslashes(_('Your HTML code is required'));?>"
			}
		});
	});
</script>

<div class="row-fluid">
    <div class="span2">
        <?php include('includes/sidebar.php');?>
    </div> 
    
    <div class="span10">
	    <div class="row-fluid">
		    <div class="span10">
			    <div>
			    	<p class="lead">
		    	<?php if(get_app_info('is_sub_user')):?>
			    	<?php echo get_app_data('app_name');?>
		    	<?php else:?>
			    	<a href="<?php echo get_app_info('path'); ?>/edit-brand?i=<?php echo get_app_info('app');?>" data-placement="right" title="<?php echo _('Edit brand settings');?>"><?php echo get_app_data('app_name');?> <span class="icon icon-pencil top-brand-pencil"></span></a>
		    	<?php endif;?>
		    </p>
		    	</div>
		    	<h2><?php echo _('Edit template');?></h2><br/>
		    </div>
	    </div>
	    
	    <div class="row-fluid">
		    		    
	    	<form action="<?php echo get_app_info('path')?>/includes/templates/save-template.php?i=<?php echo get_app_info('app')?>&t=<?php echo $tid;?>&edit=true" method="POST" accept-charset="utf-8" class="form-vertical" id="edit-form">
		    	
		    	<div class="span3">
			        <label class="control-label" for="template_name"><?php echo _('Template name');?></label>
			    	<div class="control-group">
				    	<div class="controls">
			              <input type="text" class="input-xlarge" id="template_name" name="template_name" placeholder="<?php echo _('Name of this template');?>" value="<?php echo htmlspecialchars(get_saved_data('template_name'));?>">
			            </div>
			        </div>
					
					<label class="control-label" for="from_name" style="clear:both;"><?php echo _('From name');?></label>
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
					</div>
					
					<label class="control-label" for="reply_to"><?php echo _('Reply to email');?></label>
					<div class="control-group">
						<div class="controls">
						  <input type="text" class="input-xlarge" id="reply_to" name="reply_to" placeholder="<?php echo _('name@domain.com');?>" value="<?php echo get_saved_data('reply_to');?>">
						</div>
					</div>
			        
			        <label class="control-label" for="plain"><?php echo _('Plain text version');?></label>
		            <div class="control-group">
				    	<div class="controls">
			              <textarea class="input-xlarge" id="plain" name="plain" rows="10" placeholder="<?php echo _('Plain text version of this template');?>"><?php echo get_saved_data('plain_text');?></textarea>
			            </div>
			        </div>
			        
			        <button type="submit" class="btn btn-inverse" id="save-button"><i class="icon-ok icon-white"></i> <?php echo _('Save template');?></button>
			    </div>
		    				    
			    <div class="span9">
			    	<p>
				    	<label class="control-label" for="html"><?php echo _('HTML code');?></label>
			            <div class="control-group">
					    	<div class="controls">
				              <textarea class="input-xlarge" id="html" name="html" rows="10" placeholder="<?php echo _('Email content');?>"><?php echo get_saved_data('html_text');?></textarea>
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
