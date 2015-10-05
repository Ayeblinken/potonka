<html>
	<head>
		<title>Server Variables</title>
	</head>
	<body>
            <?php
                echo "Server details :<br/>";
                echo "SERVER_NAME: ".$_SERVER['SERVER_NAME']."<br/>";
                echo "SERVER_ADDR: ".$_SERVER['SERVER_ADDR']."<br/>";
                echo "SERVER_PORT ".$_SERVER['SERVER_PORT']."<br/>";
                echo "SERVER_ADDR ".$_SERVER['LOCAL_ADDR']."<br/>";
                
                echo "<hr/>";
                echo "page details:<br/> ";
                echo "DOCUMENT_ROOT:". $_SERVER['DOCUMENT_ROOT']."<br/>";
                echo "PHP_SELF:". $_SERVER['PHP_SELF']."<br/>";
                echo "SCRIPT_FILENAME:". $_SERVER['SCRIPT_FILENAME']."<br/>";
                
                echo "<hr/>";
                echo "Request details:<br/> ";
                echo "REMOTE_ADDR: ". $_SERVER['REMOTE_ADDR']."<br/>";
                echo "REQUEST_URI: ". $_SERVER['REQUEST_URI']."<br/>";
                echo "QUERY_STRING: ". $_SERVER['QUERY_STRING']."<br/>";
                echo "REQUEST_METHOD: ". $_SERVER['REQUEST_METHOD']."<br/>";
                echo "REQUEST_TIME: ". $_SERVER['REQUEST_TIME']."<br/>";
                echo "HTTP_REFERRER: ". $_SERVER['HTTP_REFERRER']."<br/>";
                echo "HTTP_USER_AGENT: ". $_SERVER['HTTP_USER_AGENT']."<br/>";
                
                echo "<hr/>";
                $curtime=time();
                echo strftime("%Y-%m-%d %H:%M:%S",$curtime);
                echo "<hr/>";
                echo "<hr/>";
                $timetotake=$_SERVER['REQUEST_TIME']."<br/>";
                echo strftime("%m/%d/%y",$_SERVER['REQUEST_TIME'])."<br/>";
                echo strftime("%Y-%m-%d %H:%M:%S",$_SERVER['REQUEST_TIME'])."<br/>";
                echo "<hr/>";
//                echo $_SERVER['HTTP_USER_AGENT'] . "\n\n";
//                $browser = get_browser(null, true);
//                print_r($browser);
//                echo "<hr/>";
                print_r($_SERVER);
            ?>
	
	</body>
</html>