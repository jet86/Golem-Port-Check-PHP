<?PHP
	error_reporting(E_ERROR);
	
	$wanIP = $_SERVER['REMOTE_ADDR'];
	$lanIP = "127.0.0.1";
	$localhost = "localhost";
	$timeout = 3;
	$checkIP = ($wanIP) ? $wanIP : $lanIP;
	
	$port = array(3282, 40102, 40103);
	$jump = array(1, 2, 2);
	
	$fullNodes = 0;
	$partNodes = 0;
	
?>

<html>
    <head>
        <title>Golem Port Check</title>
    </head>
    <body style="font-family: Sans-Serif; padding: 40px;">
        <h1>Golem Port Check</h1>
        This page will check your network to see if ports used by <a href='https://golem.network/' target='_blank'>Golem</a> are open and responding.<br><br>
        It will continue to check for successive open ports until it times out. If you are only running Golem on 1 computer on your network, node number 1 should have all 3 ports open, and node number 2 should have all 3 ports time out (1 node equals 1 computer running Golem).<br><br>
        <b>Note:</b> This will only succeed if you have Golem running while you run this test.<br>
        <br>
        <hr>

<?PHP
	echo "<pre>\n";
	
	echo "Running Golem port check\n";
	echo "WAN IP detected as '$wanIP'\n";
	echo "checking ports for IP '$checkIP'\n";
	
	$nodeID = 0;	
	do{
		$nodeID++;
		$foundPort = false;
		$failedPort = false;
		$status = array(false, false, false);
		$checkingPort = 0;
		
		echo "\nChecking ports for node number " . $nodeID . ":\n";
		
		while($checkingPort < sizeof($port))
		{
			$currentPort = $port[$checkingPort] + ($jump[$checkingPort] * ($nodeID - 1));
			echo "Checking port " . $currentPort . ": ";
			$stream = fsockopen($checkIP, $currentPort, $errno, $errstr, $timeout);
			if (!$stream) {
				echo "<font color='red'>{$errstr} (error {$errno})</font>\n";
				$failedPort = true;
			}
			else
			{
				echo "<font color='green'>Success!</font>\n";
				$foundPort = true;
				$status[$checkingPort] = true;
				fclose($stream);
			}
			$checkingPort++;
		}
		
		if(!$failedPort)
		{
			$fullNodes++;
		}
		elseif($foundPort)
		{
			$partNodes++;
		}
		
	} while ($foundPort);
	
	echo "\nChecked $nodeID nodes.\n";
	echo "<font color='green'>Found $fullNodes fully open nodes.</font>\n";
	echo "<font color='orange'>Found $partNodes partially open nodes.</font>\n";
	
	echo "\n</pre>\n";
?>
        <hr>
        <br>
        <a href='https://github.com/jet86/Golem-Port-Check-PHP'>View the source code</a><br>
        <br>
        <a href='../'>Back to golem.timjones.id.au</a><br>
        <br>
        <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Official Port Forwarding Documentation</a>
    </body>
</html>
