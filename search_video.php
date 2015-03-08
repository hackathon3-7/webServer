<?php
/****************************
  Declaration 
****************************/
$EDR_FILE = "EDR.txt";
$DELIMITER = ":";
$SERVER_IP = "192.168.1.83";
$VIDEO_NAME= "hpc_portal_demo1.1.mp4";



/****************************
  Functions
****************************/
function get_EDR_data() {
  global $EDR_FILE, $DELIMITER;
  $file = fopen($EDR_FILE, "r") or die("Unable to open file!");
  if ($file) {
    $returnData = array();
    while (($line = fgets($file)) !== false) {
      if (substr($line, 0, 1) != "#") {
        $lineArray = explode($DELIMITER, $line);
        $cameraData = array("name"        =>$lineArray[0],
                            "ip"          =>$lineArray[1],
							"port"        =>$lineArray[2],
                            "description" =>$lineArray[3]);
        array_push($returnData, $cameraData);
      }
    }
    return $returnData;
  }
}

function convert_timestamp($date, $time) {
  $time_array  = explode(":", $time);
  $hr          = $time_array[0];
  $time_array2 = explode(" ", $time_array[1]);
  $min         = $time_array2[0];
  $pmam        = $time_array2[1];
  if (!strcasecmp($pmam, "PM")) {
	$date+=12*3600;
	$date+=3600*$hr;
  }else{
	$date+=3600*$hr;
  }
  return $date;
}

/****************************
  Main
****************************/
$cameraData = get_EDR_data();

foreach ($cameraData as $cam) {
  $ip          = $cam['ip'];
  $port        = $cam['port'];
  $name        = $cam['name'];
  $description = $cam['description'];

  $date      = $_POST["epochDate"]+8*3600;
  $time      = $_POST["time"];
  $timestamp = convert_timestamp($date, $time);
  
 // echo "Remote Camera IP: ".$ip.":".$port."<br />";
 // echo "Timestamp: ".$timestamp."<br />";
 // echo "X: ".$_POST['inLat']."<br />";
 // echo "Y: ".$_POST['inLng']."<br />";

  $data = array ("Type" => "QUERY",
                 "Time" => $timestamp,
                 "Lat"  => $_POST['inLat'],
                 "Lng"  => $_POST['inLng']);
  
  $reqData = json_encode($data);

  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  
  if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
  }

  //echo "Attempting to connect to '$ip' on port '$port'...";
  $result = socket_connect($socket, $ip, $port);

  if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
  }
  
  //echo "Sending HTTP HEAD request...";
  socket_write($socket, $reqData, strlen($reqData));
  //echo "OK.\n";
  /*
  echo "Reading response:\n\n";
  while ($out = socket_read($socket, 2048)) {
    echo $out;
  }
  */
  //echo "Closing socket...";
  socket_close($socket);
  //echo "OK.\n\n";
  
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Search</title>

    <!-- Bootstrap core CSS -->
    <link href="package/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="stylesheet/boot.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="site-wrapper">
      <div class="site-wrapper-inner">
        <div class="cover-container">
          <div class="masthead clearfix">

            <div class="inner">
              <h3 class="masthead-brand">Cover</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li class="active"><a href="#">Home</a></li>
                  <li><a href="#">Features</a></li>
                  <li><a href="#">Contact</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <video width="480" height="320" controls="controls">
            <source src="http://<?php echo $SERVER_IP; ?>/webServer/<?php echo $VIDEO_NAME; ?>" type="video/mp4">
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
            <script src="/package/bootstrap/dist/js/bootstrap.min.js"></script>
            <script src="../../assets/js/docs.min.js"></script>
            <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
            <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
              </div> 
        
        </div>
      </div>
    </div>
  </body>

</video>
</html>