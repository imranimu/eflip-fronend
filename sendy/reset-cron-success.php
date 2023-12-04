<?php include('includes/header.php');?>
<?php include('includes/login/auth.php');?>
<?php 
	$script_filename = basename(__FILE__);
	$server_path_array = explode($script_filename, $_SERVER['SCRIPT_FILENAME']);
	$server_path = substr($server_path_array[0], 0, -1);
?>

<div class="row-fluid">
    <div class="span12">
    	<h2><?php echo _('Cron job statuses has been reset');?></h2><br/>
    	<?php 		
			$q = 'SELECT cron, cron_ares, cron_csv, cron_seg FROM login WHERE id = 1';
			$r = mysqli_query($mysqli, $q);
			if ($r && mysqli_num_rows($r) > 0)
			{
				while($row = mysqli_fetch_array($r))
				{
					$cron = $row['cron'];
					$cron_ares = $row['cron_ares'];
					$cron_csv = $row['cron_csv'];
					$cron_seg = $row['cron_seg'];
				}  
			}
				
			echo _('Cron job instructions will now appear again where it is relevant.'); 
			
			echo "
			<p>
				<ol>
					<li>When you are at the ‘Define recipients’ page, you will see the cron job setup instructions in a yellow box at the bottom (<a href=\"http://go.sendy.co/sfO6HC\" target=\"_blank\" style=\"text-decoration: underline;\">see screenshot</a>)</li>
					
					<li>Inside any lists, click on the 'Autoresponders' button at the top right and you'll be shown the cron job setup instructions (<a href=\"http://go.sendy.co/ASYhGx\" target=\"_blank\" style=\"text-decoration: underline;\">see screenshot</a>)</li>
					
					<li>When you're at the import CSV page, you'll see the cron job setup instructions in a yellow box (<a href=\"http://go.sendy.co/F7iGZq\" target=\"_blank\" style=\"text-decoration: underline;\">see screenshot</a>)</li>
					
					<li>Inside any segments, you'll see the cron job setup instructions in a yellow box at the bottom (<a href=\"http://go.sendy.co/2mAq9D\" target=\"_blank\" style=\"text-decoration: underline;\">see screenshot</a>)</li>
				</ol>
			</p>
			<br/>
			<p>Alternatively for your convenience, the following are the full list of cron jobs that you can setup.</p>		
			";
			
			echo "
				<div class=\"row-fluid\">
					<div class=\"span12\">
						<div class=\"well\">
							<p><strong>Cron jobs</strong>: </p>
							<table class=\"table table-striped responsive\">
							  <thead>
								<tr>
								  <th>Timing</th>
								  <th>Command</th>
								  <th>Purpose</th>
								  <th>Status</th>
								</tr>
							  </thead>
							  <tbody>
								  <tr>
									  <td>*/5 * * * * </td>  
									  <td>php $server_path/scheduled.php > /dev/null 2>&1</td>  
									  <td>For scheduling emails and sending campaigns in the background with auto-resume when server times out</td>
									  <td>$cron</td>
								  </tr>
								  <tr>
									  <td>*/1 * * * * </td>  
									  <td>php $server_path/autoresponders.php > /dev/null 2>&1</td>  
									  <td>For Autoresponders to work</td>
									  <td>$cron_ares</td>
								  </tr>
								  <tr>
									  <td>*/1 * * * * </td>  
									  <td>php $server_path/import-csv.php > /dev/null 2>&1</td> 
									  <td>For importing large CSVs in the background</td> 
									  <td>$cron_csv</td>
								  </tr>
								  <tr>
									  <td>*/15 * * * * </td>  
									  <td>php $server_path/update-segments.php > /dev/null 2>&1</td>  
									  <td>For automatic updating of segments periodically</td>
									  <td>$cron_seg</td>
								  </tr>
							  </tbody>
							</table>
						</div>
					</div>
				</div>
			";
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
