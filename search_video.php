<?php
/****************************
  Declaration 
****************************/
$EDR_FILE = "EDR.txt";
$DELIMITER = ":";



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
  
  echo "Remote Camera IP: ".$ip.":".$port."<br />";
  echo "Timestamp: ".$timestamp."<br />";
  echo $_POST['inLat']."<br />";
  echo $_POST['inLng']."<br />";

  $data = array ("timestamp"  => $timestamp,
                 "inLat" => $_POST['inLat'],
                 "inLng" => $_POST['inLng']);
  
  $reqData = json_encode($data);

  $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
  
  if ($socket === false) {
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
  } else {
    echo "OK.\n";
  }

  echo "Attempting to connect to '$ip' on port '$port'...";
  $result = socket_connect($socket, $ip, $port);

  if ($result === false) {
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
  } else {
    echo "OK.\n";
  }
  
  echo "Sending HTTP HEAD request...";
  socket_write($socket, $reqData, strlen($reqData));
  echo "OK.\n";
  /*
  echo "Reading response:\n\n";
  while ($out = socket_read($socket, 2048)) {
    echo $out;
  }
  */
  echo "Closing socket...";
  socket_close($socket);
  echo "OK.\n\n";
  
}
?>
<video width="480" height="320" controls="controls">
<source src="http://localhost/webServer/hpc_portal_demo1.1.mp4" type="video/mp4">
</video>