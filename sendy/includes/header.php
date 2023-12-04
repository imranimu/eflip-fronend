<?php include('includes/functions.php');?>
<?php if(isset($_COOKIE['logged_in'])){start_app();}?>
<?php 
	//Check if dark mode appearance should be loaded
	
	$dark_mode = 0;
	
	//If main user
	if(!get_app_info('is_sub_user') && CURRENT_DOMAIN == APP_PATH_DOMAIN)
	{
		//If not logged in yet, get dark mode settings from db
		if(get_app_info('dark_mode')=='')
		{
			$q = 'select dark_mode from login order by id ASC limit 1';
			$r = mysqli_query($mysqli, $q);
			if ($r && mysqli_num_rows($r) > 0) while($row = mysqli_fetch_array($r)) $dark_mode = $row['dark_mode'];
		}
		else $dark_mode = get_app_info('dark_mode');
	}
	else
	{
		$is_login_page = basename($_SERVER['SCRIPT_FILENAME'])=='login.php';
		$is_index_page = basename($_SERVER['SCRIPT_FILENAME'])=='index.php';
		
		if($is_login_page || $is_index_page)
		  $q = 'SELECT brand_logo_filename, id FROM apps WHERE custom_domain = "'.CURRENT_DOMAIN.'"';
		else
		  $q = 'SELECT brand_logo_filename, id FROM apps WHERE id = '.get_app_info('app');
		$r = mysqli_query($mysqli, $q);
		if ($r) 
		{
		  while($row = mysqli_fetch_array($r)) 
		  {
		      $logo_filename = $row['brand_logo_filename'];  
		      $app_id = isset($row['id']) ? $row['id'] : '';
		  }
		}
		if($logo_filename=='') $logo_image = 'https://www.gravatar.com/avatar/'.md5(strtolower(trim(get_app_info('email')))).'?s=36&d='.get_app_info('path').'/img/sendy-avatar.png';
		else $logo_image = get_app_info('path').'/uploads/logos/'.$logo_filename;
		
		if($is_login_page)
		{
		  $q = 'SELECT company, dark_mode FROM login WHERE app = '.$app_id;
		  $r = mysqli_query($mysqli, $q);
		  if ($r && mysqli_num_rows($r) > 0) 
		  {
			  while($row = mysqli_fetch_array($r)) 
			  {
				  $company = $row['company'];
				  $dark_mode = $row['dark_mode'];
			  }
		  }
		}
		else $dark_mode = get_app_info('dark_mode');
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="">
		<meta name="author" content="">
		<meta name="robots" content="noindex, nofollow">
		
		<?php if(!get_app_info('is_sub_user') && CURRENT_DOMAIN == APP_PATH_DOMAIN):?>
		<link rel="Shortcut Icon" type="image/ico" href="<?php echo get_app_info('path');?>/img/favicon.png">
		<?php else: ?>
		<link rel="Shortcut Icon" type="image/ico" href="<?php echo $logo_image;?>">
		<?php endif;?>
		
		<link rel="stylesheet" type="text/css" href="<?php echo get_app_info('path');?>/css<?php echo $dark_mode ? '/dark' : '';?>/bootstrap.css?31" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_app_info('path');?>/css/bootstrap-responsive.css?30" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_app_info('path');?>/css<?php echo $dark_mode ? '/dark' : '';?>/responsive-tables.css?30" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_app_info('path');?>/css/font-awesome.min.css" />
		<link rel="apple-touch-icon-precomposed" href="<?php echo get_app_info('path');?>/img/sendy-icon.png" />
		<link rel="stylesheet" type="text/css" href="<?php echo get_app_info('path');?>/css<?php echo $dark_mode ? '/dark' : '';?>/all.css?41" />
		<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/jquery-3.5.1.min.js"></script>
		<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/jquery-ui-1.8.21.custom.min.js"></script>
		<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/bootstrap.js"></script>
		<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/responsive-tables.js"></script>
		<script type="text/javascript" src="<?php echo get_app_info('path');?>/js/main.js?3"></script>
		<link href='https://fonts.googleapis.com/css?family=Roboto:400,400italic,700,700italic' rel='stylesheet' type='text/css'>
		<link href="https://fonts.googleapis.com/css?family=Questrial" rel="stylesheet">
		<title><?php echo get_app_info('company');?></title>
	</head>
	<body>
		<?php 			
		    function catch_fatal_error()
			{
			  // Getting Last Error
			  $last_error =  error_get_last();
			  
			  // Check if Last error is of type FATAL
			  if(isset($last_error['type']) && $last_error['type']==E_ERROR)
			  {  
			    // Fatal Error Occurs
			    echo '
			    <div class="alert alert-danger" id="wrapper">
				    <p class="session_error"><h2><span class="icon  icon-exclamation-sign"></span> '._('Error').'</h2></p>
				    <p class="session_error">
				    	<b>Message</b>: '.$last_error['message'].'<br/>
				    	<b>File</b>: '.$last_error['file'].'<br/>
						<b>Line</b>: '.$last_error['line'].'
				    </p>
			    </div></body></html>';
			  }
			
			}
			register_shutdown_function('catch_fatal_error');		
			
			$uri = $_SERVER['REQUEST_URI'];
			$uri_array = explode('/', $uri);
			$current_page = $uri_array[count($uri_array)-1];
			
			if($current_page != '_install.php')
				if(_('Login'))
		?>
		<div class="navbar navbar-fixed-top">
		  <div class="separator"></div>
	      <div class="navbar-inner">
	        <div class="container-fluid">
	          <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	            <span class="icon-bar"></span>
	          </a>
	          	          
	          <!-- Check if sub user -->
	          <?php if(!get_app_info('is_sub_user') && CURRENT_DOMAIN == APP_PATH_DOMAIN):?>
	          
		          <a class="brand" href="<?php echo get_app_info('path');?>/"><img src="https://www.gravatar.com/avatar/<?php echo md5(strtolower(trim(get_app_info('email'))));?>?s=36&d=<?php echo get_app_info('path');?>/img/sendy-avatar.png" title="" class="main-gravatar" onerror="this.src='<?php echo get_app_info('path');?>/img/sendy-avatar.png'"/><?php echo get_app_info('company');?></a>
		          
	          <?php else:?>
	          
		          <a class="brand" href="<?php echo get_app_info('path');?>/app?i=<?php echo get_app_info('restricted_to_app');?>"><img src="<?php echo $logo_image;?>" title="" class="main-gravatar"/><?php echo $is_login_page ? $company : get_app_info('company');?></a>
		          
		          <script type="text/javascript">
			          $(document).ready(function() {
			          	document.title = "<?php echo $is_login_page ? $company : get_app_info('company');?>";
			          });
		          </script>
		          
	          <?php endif;?>
	          
	          <?php if(currentPage()!='login.php' && currentPage()!='two-factor.php' && currentPage()!='_install.php'): ?>
	          <div class="btn-group pull-right">
	            <button class="btn btn-inverse dropdown-toggle" data-toggle="dropdown">
	              <i class="icon-user icon-white"></i> <?php echo get_app_info('name');?>
	              <span class="caret"></span>
	            </button>
	            <ul class="dropdown-menu">
	              <li><a href="<?php echo get_app_info('path');?>/settings<?php if(get_app_info('is_sub_user')) echo '?i='.get_app_info('app');?>"><i class="icon icon-cog"></i> <?php echo _('Settings');?></a></li>
	              <li class="divider"></li>
	              <li><a href="<?php echo get_app_info('path');?>/logout"><i class="icon icon-off"></i> <?php echo _('Logout');?></a></li>
	            </ul>
	          </div>
	          
	          
	          <!-- Check if sub user -->
	          <?php if(!get_app_info('is_sub_user')):?>	          
	          <div class="btn-group pull-right">
				  <button class="btn btn-white dropdown-toggle" data-toggle="dropdown">
				    <?php 
				    	$get_i = isset($_GET['i']) ? get_app_info('app') : '';
				    	
					    $q = "SELECT app_name, from_email, brand_logo_filename FROM apps WHERE id = '$get_i'";
					    $r = mysqli_query($mysqli, $q);
					    if ($r && mysqli_num_rows($r) > 0)
					    {
					        while($row = mysqli_fetch_array($r))
					        {
					        	$from_email = explode('@', $row['from_email']);
					  			$get_domain = $from_email[1];
					  			$brand_logo_filename = $row['brand_logo_filename'];
			  			
					  			//Brand logo
					  			if($brand_logo_filename=='') $logo_image = 'https://www.google.com/s2/favicons?domain='.$get_domain;
					  			else $logo_image = get_app_info('path').'/uploads/logos/'.$brand_logo_filename;
					  			
					    		echo '<img src="'.$logo_image.'" style="margin:-4px 5px 0 0; width:16px; height: 16px;"/>'.$row['app_name'];
					        }  
					    }
					    else
					    	echo '<span class="icon icon-th-list"></span> '._('Brands');
				    ?>
				    <span class="caret"></span>
				  </button>
				  <ul class="dropdown-menu">
				  	<?php 
		              $q = 'SELECT id, app_name, from_email, brand_logo_filename FROM apps WHERE userID = '.get_app_info('userID').' ORDER BY app_name ASC';
		              $r = mysqli_query($mysqli, $q);
		              if ($r && mysqli_num_rows($r) > 0)
		              {
		                  while($row = mysqli_fetch_array($r))
		                  {
		                  	$app_id = $row['id'];
		              		$app_name = $row['app_name'];
		              		$from_email = explode('@', $row['from_email']);
				  			$get_domain = $from_email[1];
				  			$brand_logo_filename = $row['brand_logo_filename'];
				  						  			
				  			//Brand logo
				  			if($brand_logo_filename=='') $logo_image = 'https://www.google.com/s2/favicons?domain='.$get_domain;
				  			else $logo_image = get_app_info('path').'/uploads/logos/'.$brand_logo_filename;
				  			
		              		echo '<li';
		              		if($get_i==$app_id)
		              			echo ' class="active"';
		              		echo'><a href="'.get_app_info('path').'/app?i='.$app_id.'"><img src="'.$logo_image.'" style="margin:-4px 5px 0 0; width:16px; height: 16px;"/>'.$app_name.'</a></li>';
		                  }  
		              }
		              else
		              {
			              echo '<li><a href="'.get_app_info('path').'/new-brand" title="">'._('Add a new brand').'</a></li>';
		              }
		            ?>
				  </ul>
				</div>
				<?php endif;?>
				
				
	          <div class="nav-collapse">
	            <ul class="nav">
	              
	            </ul>
	          </div><!--/.nav-collapse -->
	          
	          
	          
	          <?php endif;?>
	          
	        </div>
	      </div>
	    </div>
	    <div class="container-fluid">
	    <?php ini_set('display_errors', isset($_GET['display_errors']) ? 1 : 0);?>