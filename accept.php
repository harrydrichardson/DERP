<?PHP

require('config.php');

// slack post icon
define("slackemoji",":derp:");

$channels = array("support","support-ss");
$admins = array("harry");

	if($_POST['token'] != slackout 
		|| !in_array($_POST['channel_name'],$ALLOWED_CHANNELS))
	{
		die('Not Authorized');
	}
	
$command = explode("[derp]",strtolower($_POST['text']));


	//debugs if DEBUG_MODE is true
	function debug($debugMsg)
	{
		if(DEBUG_MODE)
		{
			$file = "/var/www/html/public/debug.log";
			file_put_contents($file,$debugMsg."\r\n",FILE_APPEND);
		}
	}



?>
