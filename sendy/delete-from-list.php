<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php include('includes/subscribers/main.php');?>

<?php 
	//IDs
	$lid = isset($_GET['l']) && is_numeric($_GET['l']) ? mysqli_real_escape_string($mysqli, (int)$_GET['l']) : exit;
	$app = isset($_GET['i']) && is_numeric($_GET['i']) ? get_app_info('app') : exit;
	
	$err = isset($_GET['e']) ? $_GET['e']: '';
	
	if(get_app_info('is_sub_user')) 
	{
		if(get_app_info('app')!=get_app_info('restricted_to_app'))
		{
			echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/delete-from-list?i='.get_app_info('restricted_to_app').'&l='.$lid.'"</script>';
			exit;
		}
		$q = 'SELECT app FROM lists WHERE id = '.$lid;
		$r = mysqli_query($mysqli, $q);
		if ($r)
		{
		    while($row = mysqli_fetch_array($r))
		    {
				$a = $row['app'];
		    }  
		    if($a!=get_app_info('restricted_to_app'))
		    {
			    echo '<script type="text/javascript">window.location="'.addslashes(get_app_info('path')).'/list?i='.get_app_info('restricted_to_app').'"</script>';
				exit;
		    }
		}
	}
?>

<!-- Validation -->
<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/validate.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
		$("#import-delete-form").validate({
			rules: {
				csv_file: {
					required: true
				}
			},
			messages: {
				csv_file: "<?php echo addslashes(_('Please upload a CSV file'));?>"
			}
		});
		$("#line-import-form").validate({
			rules: {
				line: {
					required: true
				}
			},
			messages: {
				line: "<?php echo addslashes(_('Please enter at least one combination of name & email'));?>"
			}
		});
	});
</script>

<div class="row-fluid">
    <div class="span2">
        <?php include('includes/sidebar.php');?>
    </div> 
    <div class="span10">
    	<div>
	    	<p class="lead">
		    	<?php if(get_app_info('is_sub_user')):?>
			    	<?php echo get_app_data('app_name');?>
		    	<?php else:?>
			    	<a href="<?php echo get_app_info('path'); ?>/edit-brand?i=<?php echo get_app_info('app');?>" data-placement="right" title="<?php echo _('Edit brand settings');?>"><?php echo get_app_data('app_name');?> <span class="icon icon-pencil top-brand-pencil"></span></a>
		    	<?php endif;?>
		    </p>
	    	<p><?php echo _('List');?>: <a href="<?php echo get_app_info('path');?>/subscribers?i=<?php echo get_app_info('app');?>&l=<?php echo $_GET['l'];?>"><span class="label label-info"><?php echo get_list_data('name');?></span></a></p>
	    	<br/>
    	</div>
    	<h2><?php echo _('Mass delete via CSV file');?></h2><br/>
	    <form action="<?php echo get_app_info('path')?>/includes/subscribers/import-delete.php" method="POST" accept-charset="utf-8" class="form-vertical" enctype="multipart/form-data" id="import-delete-form">
	        
	        <?php if($err==1):?>
			<div class="alert alert-error">
			  <button type="button" class="close" data-dismiss="alert">×</button>
			  <strong><?php echo _('There should only be 1 column in your CSV containing emails.');?></strong>
			</div>
			<?php elseif($err==3):?>
			<div class="alert alert-error">
			  <button type="button" class="close" data-dismiss="alert">×</button>
			  <strong><?php echo _('Please upload a CSV file.');?></strong>
			</div>
			<?php endif;?>
	        
	        <label class="control-label" for="csv_file"><em><?php echo _('CSV format example');?>:</em></label>
	        <table class="table table-bordered table-striped table-condensed" style="width: 300px;">
			  <tbody>
			    <tr>
			      <td>pmorris@gmail.com</td>
			    </tr>
			    <tr>
			      <td>jwebster@gmail.com</td>
			    </tr>
			  </tbody>
			</table>
	        <div class="control-group">
		    	<div class="controls">
	              <input type="file" class="input-xlarge" id="csv_file" name="csv_file">
	            </div>
	        </div>
	        
	        <input type="hidden" name="list_id" value="<?php echo $lid;?>">
	        <input type="hidden" name="app" value="<?php echo $app;?>">
	        
	        <br/>
	        <button type="submit" class="btn btn-inverse"><?php echo _('Import');?></button>
	    </form>
	    
	    <br/>
	    
	    <h2><?php echo _('Delete email per line');?></h2><br/>
	    <form action="<?php echo get_app_info('path')?>/includes/subscribers/line-delete.php" method="POST" accept-charset="utf-8" class="form-vertical" enctype="multipart/form-data" id="line-import-form">
	        
	        <?php if($err==2):?>
			<div class="alert alert-error">
			  <button type="button" class="close" data-dismiss="alert">×</button>
			  <strong><?php echo _('Sorry, we didn\'t receive any input.');?></strong>
			</div>
			<?php endif;?>
			
	        <label class="control-label" for="line"><?php echo _('Email to delete');?></label>
            <div class="control-group">
		    	<div class="controls">
	              <textarea class="input-xlarge" id="line" name="line" rows="10" placeholder="Eg. hermanmiller@gmail.com"></textarea>
	            </div>
	        </div>
	        
	        <input type="hidden" name="list_id" value="<?php echo $lid;?>">
	        <input type="hidden" name="app" value="<?php echo $app;?>">
	        
	        <br/>
	        <button type="submit" class="btn btn-inverse"><?php echo _('Delete');?></button>
	    </form>
    </div>   
</div>
<?php include('includes/footer.php');?>
