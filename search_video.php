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
                            "description" =>$lineArray[2]);
        array_push($returnData, $cameraData);
      }
    }
    return $returnData;
  }
}


/****************************
  Main
****************************/
$cameraData = get_EDR_data();

foreach ($cameraData as $cam) {
  $ip          = $cam['ip'];
  $name        = $cam['name'];
  $description = $cam['description'];
  echo $ip."<br />";
  $data = array("aaaaabbbb");
  /*
  $data = array ("timestamp" => $_POST['timestamp'],
                 "lat"       => $_POST['lat'],
                 "lnt"       => $_POST['lnt']);
  */                
  $ch = curl_init($ip.":5566");
  curl_setopt_array($ch, array(
    CURLOPT_POST => TRUE,
    CURLOPT_RETURNTRANSFER => TRUE,
    CURLOPT_POSTFIELDS => json_encode($data)));

  $response = curl_exec($ch);
  if($response === FALSE){
    die(curl_error($ch));
  }
  
  $responseData = json_decode($response, TRUE);
  echo $responseData['published'];

}
?>