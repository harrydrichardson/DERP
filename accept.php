<?PHP

require('config.php');

// slack post icon
define("slackemoji",":derp:");

$channels = array("testing-webhooks","support","support-ss");
$admins = array("harry","tyler");
$hello = "herp";
//echo "Token: " . $_POST['token'] . "</br>";
//echo "Channel: " . $_POST['channel_name'] . "</br>";
	if($_POST['token'] != slackout 
		|| !in_array($_POST['channel_name'],$channels))
	{
		die('Not Authorized');
	}

$_POST['channel_name'] = "#".$_POST['channel_name']; 
	
	if( $_POST['user_name'] == 'harry' || $_POST['user_name'] == 'tyler'){
		messageToSlack("herp",$_POST['channel_name']);
		die();
	}
$payload = explode("[derp]",strtolower($_POST['text']));
$token = strtok($payload[1]," ");




	//debugs if DEBUG_MODE is true
	function debug($debugMsg)
	{
		if(DEBUG_MODE)
		{
			$file = "/var/www/html/public/debug.log";
			file_put_contents($file,$debugMsg."\r\n",FILE_APPEND);
		}
	}

	function messageToSlack($message,$channel)
	{
		$broadcastURL = slackurl.slackin;
        	$data= 'payload={"username": "DERP", "channel": "'.$channel.'", "text": "';
		$data.=$message.'", "icon_emoji": "'.slackemoji.'"}';
		//	debug($data);
		//	debug($broadcastURL);
        	$ch = curl_init($broadcastURL);
	        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      		return curl_exec($ch);
	}	


?>
