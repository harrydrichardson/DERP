<?PHP

require('config.php');

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
