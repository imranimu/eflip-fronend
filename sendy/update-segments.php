<?php 
	mysqli_report(MYSQLI_REPORT_OFF);
	include('includes/config.php');
	
	//--------------------------------------------------------------//
	function dbConnect() { //Connect to database
	//--------------------------------------------------------------//
	    // Access global variables
	    global $mysqli;
	    global $dbHost;
	    global $dbUser;
	    global $dbPass;
	    global $dbName;
	    global $dbPort;
	    
	    // Attempt to connect to database server
	    if(isset($dbPort)) $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName, $dbPort);
	    else $mysqli = new mysqli($dbHost, $dbUser, $dbPass, $dbName);
	
	    // If connection failed...
	    if ($mysqli->connect_error) {
	        fail();
	    }
	    
	    global $charset; mysqli_set_charset($mysqli, isset($charset) ? $charset : "utf8");
	    
	    return $mysqli;
	}
	//--------------------------------------------------------------//
	function fail() { //Database connection fails
	//--------------------------------------------------------------//
	    print 'Database error';
	    exit;
	}
	// connect to database
	dbConnect();
?>
<?php

//If list_id is passed, this script is called after user is subscribed to the list to update segments of this particular list only
$list_id = isset($_GET['list_id']) && is_numeric($_GET['list_id']) ? mysqli_real_escape_string($mysqli, (int)$_GET['list_id']) : 0;

//setup cron
$q = 'SELECT id, cron_seg, timezone FROM login LIMIT 1';
$r = mysqli_query($mysqli, $q);
if ($r)
{
    while($row = mysqli_fetch_array($r))
    {
		$cron = $row['cron_seg'];
		$userid = $row['id'];
		$timezone = $row['timezone'];
		
		if($cron==0)
		{
			$q2 = 'UPDATE login SET cron_seg=1 WHERE id = '.$userid;
			$r2 = mysqli_query($mysqli, $q2);
			if ($r2) exit;
		}
    }  
}

//Update segmentation results wherever segments are found
$q = !$list_id ? 'SELECT * FROM seg' : 'SELECT * FROM seg WHERE list = '.$list_id;
$r = mysqli_query($mysqli, $q);
if ($r && mysqli_num_rows($r) > 0)
{
    while($row = mysqli_fetch_array($r))
    {
		$id = $row['id'];
		$app = $row['app'];
		$list = $row['list'];
		
		//Check if any campaign is sending to this list, if so, don't update segment while the campaign is sending
		$q2 = 'SELECT id FROM campaigns WHERE app = '.$app.' AND sent != "" AND recipients < to_send';
		$r2 = mysqli_query($mysqli, $q2);
		if (mysqli_num_rows($r2) == 0) // No campaigns are currently sending in the brand
		{
		    //Then update segment
			file_get_contents_curl(APP_PATH.'/includes/segments/segmentate.php?i='.$app.'&l='.$list.'&s='.$id.'&t='.$timezone.'&ts='.time());
		}
    }  
}
else exit;

//--------------------------------------------------------------//
function file_get_contents_curl($url) 
//--------------------------------------------------------------//
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	$data = curl_exec($ch);
	$response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);
	
	if($response_code!=200) return 'blocked';
	else return $data;
}

?>
