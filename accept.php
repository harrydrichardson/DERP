<?PHP

require('config.php');

// slack post icon
define("slackemoji",":derp:");

$channels = array("testing-webhooks","support","support-ss");
$admins = array("harry","tyler");

		// Ensure that incoming post has proper auth.
		// Ensure that it is an authorized channel
        if($_POST['token'] != slackout
                || !in_array($_POST['channel_name'],$channels))
        {
                die('Not Authorized');
        }

// split posted timestamp = 123123423.00020340
$timestamp = explode(".",$_POST['timestamp']);
//convert to GMT
$_POST['timestamp'] = gmdate('r',$timestamp[0]);

// format channel name for the expectation of the incoming hook
$_POST['channel_name'] = "#".$_POST['channel_name'];


$keys = array_keys($_POST);
	if($_POST['user_name'] == 'andrewherrington'){
		messageToSlack("You are why we can't have nice things",$_POST['channel_name']);
}
        if( $_POST['user_name'] == 'harry' || $_POST['user_name'] ==  'tyler'){
                	
                messageToSlack($_POST['user_name'].": ".$_POST['timestamp']." -- '".$_POST['text']."'",$_POST['channel_name']);
		die();
                }
        
$payload = explode("[derp]",strtolower($_POST['text']));




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
                //      debug($data);
                //      debug($broadcastURL);
                $ch = curl_init($broadcastURL);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                return curl_exec($ch);
        }


?>

