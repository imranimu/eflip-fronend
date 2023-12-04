<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php 
	$script_filename = basename(__FILE__);
	$server_path_array = explode($script_filename, $_SERVER['SCRIPT_FILENAME']);
	$server_path = substr($server_path_array[0], 0, -1);
?>

<div class="row-fluid">
    <div class="span12">
    	<h2><?php echo _('Reset cron job setup status');?></h2><br/>
    	<?php 
    		$confirm = $_POST['c'];
    		
    		if(count($_POST)!=0 && $confirm==1)
    		{
		    	$q = 'UPDATE login SET cron = 0, cron_ares = 0, cron_csv = 0, cron_seg = 0';
		    	$r = mysqli_query($mysqli, $q);
		    	if ($r)
				{
					echo '<script type="text/javascript">window.location = "'.addslashes(get_app_info('path')).'/reset-cron-success";</script>';
				}
		    	else
		    		echo _('Failed to reset cron.');
		    }
		    else
		    {
			    echo '<form action="" method="post">';
				echo _('Do you want to clear the positive status that cron jobs has been setup so that you can view cron job setup instructions again?');
				echo '<input type="hidden" name="c" value="1"/>
						<br/><br/><input type="submit" name="submit" class="btn" value="'._('Yes, reset cron jobs statuses').'"/>
					</form>';
		    }
    	?>
    </div> 
</div>
<script type="text/javascript">
	$(document).ready(function() {
		$("td").mouseover(function(){
			$(this).selectText();
		});
	});
</script>
<?php include('includes/footer.php');?>
