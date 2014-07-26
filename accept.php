<?PHP

require('config.php');

//remove trigger word from string
$message = $_POST['text'];
//split at the sentences spaces.
$message = explode(" ",$message);
//remove the slack triggerword
unset($message[0]);
//setup the message to send back without the trigger word
$readBackMessage = "";
//loop through the array assembling the sentence
//foreach($message as $word){
//	$readBackMessage .= "$word ";
//}
	
$user_name = $_POST['user_name'];

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
$_POST['timestamp'] = gmdate("Y-m-d H:i:s",$timestamp[0]);

// format channel name for the expectation of the incoming hook
$_POST['channel_name'] = "#".$_POST['channel_name'];

	// If not me then deny
        if($_POST['user_name'] != 'harry'){
                messageToSlack("I am in maintenance mode, you are not an authorized tech",$_POST['channel_name']);
}
	//if me allow access
        if( $_POST['user_name'] == 'harry'){
		
		if ($message[1] == 'shiftnote'){
		
		try{
		//set hostname
		$hostname = mariahost;
		//trigger word has been unset. ensure that command word is removed from the readback message. 
		unset($message[1]);
		foreach($message as $word){
        		$readBackMessage .= "$word ";
		}

		
		//detup connection through PDO
	        $DBH = new PDO("mysql:host=$hostname;dbname=derp", mariauser, mariapass);
		
		//Prepare statement
		// insert into shiftnotes (user_name,created_at,note) values ('username',NOW()
		// ,message);
		$insertSlackNote = $DBH->prepare("insert into shiftnotes (user_name,created_at,
		note) values ('$user_name',NOW(),'$readBackMessage')");
		
		//execute the command
		$insertSlackNote->execute();
		
		//compose and send the message back to slack.
                messageToSlack($_POST['user_name'].": ".$_POST['timestamp'].
		" -- Message Received contains ".count($message)." elements. Message reads '"
		.$readBackMessage."'",$_POST['channel_name']);                
		

		//exit we are done here
		die();

		}
		
		catch(PDOException $e)
		{
		messageToSlack("Failure!! check logs.",$_POST['channel_name']);

		$e->getMessage();
		}
                }
		else{
			messageToSlack($_POST['user_name'].": Uknown command. Try [derp] help for more options.",$_POST['channel_name']);
		}

		}
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
                //configure the broadcast URL
		$broadcastURL = slackurl.slackin;
                
		//configure payload for delivery back to slack
		$data= 'payload={"username": "DERP", "channel": "'.$channel.'", "text": "';
                $data.=$message.'", "icon_emoji": "'.slackemoji.'"}';
                

		//      debug($data);
                //      debug($broadcastURL);
                
		// setup curl to 
		$ch = curl_init($broadcastURL);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                return curl_exec($ch);
        }

	function process($command,$data){
		$returns = "";
		

		
	}
?>

