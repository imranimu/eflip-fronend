<?php 
	if(isset($_COOKIE['logged_in'])) $cookie = $_COOKIE['logged_in'];
	else $cookie = '';
	
	if(
		!is_null(get_app_info('userID')) && 
		!is_null(get_app_info('email')) && 
		!is_null(get_app_info('password')) && 
		$cookie===hash('sha512', get_app_info('userID').get_app_info('email').get_app_info('password').get_app_info('auth_salt'))
	   )
	   		start_app();
	else
	{
		$request_uri = $_SERVER['REQUEST_URI'];
		$request_uri_array = explode('/', $request_uri);
		$redirect_to = $request_uri_array[count($request_uri_array)-1];
		
		if($redirect_to=='')
			echo '<script type="text/javascript">window.location = "'.addslashes(get_app_info('path')).'/login";</script>';
		else
			echo '<script type="text/javascript">window.location = "'.addslashes(get_app_info('path')).'/login?redirect='.addslashes($redirect_to).'";</script>';
		exit;
	}
?>