<?PHP

// Require the configuration file
require('config.php');

// Setup the channels that we are allowed to talk in
$channels = array("testing-webhooks","support","support-ss");

// Setup the users who can utilize the tool
$admins = array("harry","tyler","andrewherrington","gary");

// Apply the posted username to a variable
$user_name = $_POST['user_name'];

// slack post icon
define("slackemoji",":derp:");

//remove trigger word from string
$message = $_POST['text'];
//split at the sentences spaces.
$message = explode(" ",$message);
//remove the slack triggerword
unset($message[0]);
//setup the message to send back without the trigger word
$readBackMessage = "";


// Ensure that incoming post has proper auth.
// Ensure that it is an authorized channel
if($_POST['token'] != slackout || !in_array($_POST['channel_name'],$channels)) {
                die('Not Authorized');
        }

// split posted timestamp = 123123423.00020340
$timestamp = explode(".",$_POST['timestamp']);
//convert to GMT
$_POST['timestamp'] = gmdate("Y-m-d H:i:s",$timestamp[0]);
// format channel name for the expectation of the incoming hook
$_POST['channel_name'] = "#".$_POST['channel_name'];

      //if me allow access
if(in_array($_POST['user_name'],$admins)){
		if ($message[1] == 'shiftnote'){
        	try{
            	//set hostname
            	$hostname = mariahost;
            	//trigger word has been unset. ensure that command word is removed from the readback message. 
            	unset($message[1]);
            	// reassemble the message without the trigger word or command.
            	foreach($message as $word){
					$readBackMessage .= "$word ";
            	}
            	//detup connection through PDO
            	$DBH = new PDO("mysql:host=$hostname;dbname=derp", mariauser, mariapass);
            	
            	//Prepare statement
            	// insert into shiftnotes (user_name,created_at,note) values ('username',NOW()
            	// ,message);
            	$insertSlackNote = $DBH->prepare("insert into shiftnotes (user_name,created_at,note) values ('$user_name',NOW(),'$readBackMessage')");
            	
            	//execute the command
            	$insertSlackNote->execute();
                messageToSlack($_POST['user_name'].": ".$_POST['timestamp']." : The following message has been generated and sent to the database. '".$readBackMessage."'",$_POST['channel_name']);
            	
            	//exit we are done here
            	die();
            
			}catch(PDOException $e){
				//Alert slack to a failure
            	messageToSlack("Failure!! check logs.",$_POST['channel_name']);
            	$e->getMessage();
            }
			
		}elseif($message['1'] == 'help'){
				// Compose help message when requested
                messageToSlack("DERP Dependable electronics records program: visit the github wiki for more information.",$_POST['channel_name']);
				
        }elseif($message['1'] == 'getnotes'){
        	try{
				//Set the hostname for the PDO mysql connection
				$hostname = mariahost;
				
				//set date from the provided date
				$date = $message['2'];
				
				//setup new connection
				$DBH = new PDO("mysql:host=$hostname;dbname=derp", mariauser, mariapass);
				
				//Prepare the new Database handler 
				$getShiftNotes = $DBH->prepare("SELECT * FROM shiftnotes where user_name='$user_name' and created_at >= '$date' limit 20");
				
				// define PDO setFetchMode
				$getShiftNotes->setFetchMode(PDO::FETCH_ASSOC);
				
				// Execute the prepared query
				$getShiftNotes->execute();
				
				// Retrieve the results
				$result = $getShiftNotes->fetchAll();
				
				// Loop through the results sending each note back to slack.
				foreach ($result as $row){
					messageToSlack("Username: ".$row['user_name']." @".$row['created_at']." -- note: '".$row['note']."'",$_POST['channel_name']);
				}

			}catch(PDOException $e){
				// Alert slack to check the logs.
		        messageToSlack("Failure!! check logs.",$_POST['channel_name']);
				$e->getMessage();
			}
			
	    }elseif($message[1] == 'return'){
	    	// remove the 'return' from the command string
		  	unset($message[1]);
		  	
            // reassemble the message without the trigger word or command.
            foreach($message as $word){
                  $readBackMessage .= "$word ";
            }
            
			//compose and send the message back to slack.
            messageToSlack($_POST['user_name'].": '".$readBackMessage."'",$_POST['channel_name']);  
		}else{
			//choosing not to reply to unknown commands, No need for extraneous information in the channel
		  	die();
                        }
}else{
	//choosing not to rely to non authorized users, no need for extraneous information in the channel
	die();
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
