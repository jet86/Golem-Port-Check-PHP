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
	
	if(mb_strlen($_GET['override']) == 2) // for testing purposes only
	{
	    $fullNodes = intval(substr($_GET['override'], 0, 1));
	    $partNodes = intval(substr($_GET['override'], 1, 1));
	    
	    echo "\nOverriding with the following values:\n";
	    echo "<font color='green'>Found $fullNodes fully open nodes.</font>\n";
	    echo "<font color='orange'>Found $partNodes partially open nodes.</font>\n";
	}
	else
	{
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
	}
	
	echo "\n</pre>\n";
	
	echo "<hr>\n";
	echo "<b>Results & Next Steps:</b><br><br>\n";
	
	if($fullNodes < 1)
	{
	    if($partNodes < 1) // 0 fully open, 0 partly open
	    {
	        echo "The port check was not able to connect to any ports.<br>\n";
	        echo "<ul><li>If you did not have Golem open when you ran this test, please open the Golem application, wait a few minutes, then <a href=''>run this test again</a>.</li>\n";
	        echo "<li>If you did have Golem open when you ran this test, please make sure you have forwarded ports 3282, 40102, and 40103 in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	    elseif($partNodes == 1) // 0 fully open, 1 partly open
	    {
	        echo "The port check was able to connect to some, but not all, required ports.<br>\n";
	        echo "<ul><li>Please make sure you have correctly forwarded all 3 ports 3282, 40102, and 40103 in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>, and double-check the ports that did not indicate a successful connection in the above test.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	    else // 0 fully open, multiple partly open
	    {
	        echo "It appears you are trying to run multiple Golem nodes on your network, however none of these nodes have their ports correctly forwarded. The port check was able to connect to some, but not all, required ports.<br>\n";
	        echo "<ul><li>Please make sure you have correctly forwarded all 3 ports for each node in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>, and double-check the ports that did not indicate a successful connection in the above test.</li>\n";
	        echo "<li>For an explanation of how to forward ports for multiple nodes running behind the one router/NAT setup, please see <a href='https://www.reddit.com/r/GolemProject/comments/8la5rk/golem_client_updates/dzfoprw/'>this example</a>.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	}
	elseif($fullNodes == 1)
	{
	    if($partNodes < 1) // 1 fully open, 0 partly open
	    {
	        echo "The port check found 1 fully open node. If you are trying to run Golem on a single computer on your network, then the ports are forwarded correctly for you. If you need any further assistance using Golem, please head to the <a href='https://chat.golem.network/channel/testers'>Golem chat</a><br><br>\n";
	        echo "If you are attempting to run Golem on more than 1 computer, you will need to forward additional ports for the additional nodes.<br>\n";
	        echo "<ul><li>If you did not have Golem open on all computers when you ran this test, please open the Golem application, wait a few minutes, then <a href=''>run this test again</a>.</li>\n";
	        echo "<li>If you did have Golem open when you ran this test, please make sure you have correctly forwarded all 3 ports for each node in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>, and double-check the ports that did not indicate a successful connection in the above test.</li>\n";
	        echo "<li>For an explanation of how to forward ports for multiple nodes running behind the one router/NAT setup, please see <a href='https://www.reddit.com/r/GolemProject/comments/8la5rk/golem_client_updates/dzfoprw/'>this example</a>.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	    else // 1 fully open, 1 or more partly open
	    {
	        echo "It appears you are trying to run multiple Golem nodes on your network, however only the first node has its ports correctly forwarded. The port check was able to connect to some, but not all, required ports.<br>\n";
	        echo "<ul><li>Please make sure you have correctly forwarded all 3 ports for each node in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>, and double-check the ports that did not indicate a successful connection in the above test.</li>\n";
	        echo "<li>For an explanation of how to forward ports for multiple nodes running behind the one router/NAT setup, please see <a href='https://www.reddit.com/r/GolemProject/comments/8la5rk/golem_client_updates/dzfoprw/'>this example</a>.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	}
	else
	{
	    if($partNodes < 1) // multiple fully open, 0 partly open
	    {
	        echo "The port check found $fullNodes fully open nodes. If you are trying to run Golem on $fullNodes computers on your network, then the ports are forwarded correctly for you. If you need any further assistance using Golem, please head to the <a href='https://chat.golem.network/channel/testers'>Golem chat</a><br><br>\n";
	        echo "If you are attempting to run Golem on more than $fullNodes computer, you will need to forward additional ports for the additional nodes.<br>\n";
	        echo "<ul><li>If you did not have Golem open on all computers when you ran this test, please open the Golem application, wait a few minutes, then <a href=''>run this test again</a>.</li>\n";
	        echo "<li>If you did have Golem open when you ran this test, please make sure you have correctly forwarded all 3 ports for each node in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>, and double-check the ports that did not indicate a successful connection in the above test.</li>\n";
	        echo "<li>For an explanation of how to forward ports for multiple nodes running behind the one router/NAT setup, please see <a href='https://www.reddit.com/r/GolemProject/comments/8la5rk/golem_client_updates/dzfoprw/'>this example</a>.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	    else // multiple fully open, 1 or more partly open
	    {
	        echo "It appears you are trying to run multiple Golem nodes on your network, however only the first $fullNodes nodes have their ports correctly forwarded. The port check was able to connect to some, but not all, required ports.<br>\n";
	        echo "<ul><li>Please make sure you have correctly forwarded all 3 ports for each node in your router, as per the <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Golem Docs</a>, and double-check the ports that did not indicate a successful connection in the above test.</li>\n";
	        echo "<li>For an explanation of how to forward ports for multiple nodes running behind the one router/NAT setup, please see <a href='https://www.reddit.com/r/GolemProject/comments/8la5rk/golem_client_updates/dzfoprw/'>this example</a>.</li>\n";
	        echo "<li>If you are certain that you have correctly forwarded these ports in your router, then something else is blocking these ports - for example Windows Firewall or other firewall software - or your ISP may not allow forwarding of specific custom ports.</li></ul>\n";
	    }
	}
?>
        <br>
        <hr>
        <br>
        <a href='https://github.com/jet86/Golem-Port-Check-PHP'>View the source code</a><br>
        <br>
        <a href='../'>Back to golem.timjones.id.au</a><br>
        <br>
        <a href='https://golem.network/documentation/09-common-issues-troubleshooting/port-forwarding-connection-errors/'>Official Port Forwarding Documentation</a>
        <br>
        <br>
        <p>If you wish to donate to me, you can send ETH or GNT to the following address:</p>
        <p>0xA51699378fc02c81302aC514D82e9935B716FC7E</p>
    </body>
</html>
