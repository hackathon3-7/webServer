<?php
/**** Declaration ****/
$EDR_FILE = "EDR.txt";
$DELIMITER = ":";

/**** Functions ****/
function get_EDR_data() {
  global $EDR_FILE, $DELIMITER;
  $file = fopen($EDR_FILE, "r") or die("Unable to open file!");
  if ($file) {
    $returnData = array();
    while (($line = fgets($file)) !== false) {
	  if (substr($line, 0, 1) != "#") {
	    $lineArray = explode($DELIMITER, $line);
		$camData = array("name"        =>$lineArray[0],
		                 "ip"          =>$lineArray[1],
						 "description" =>$lineArray[2]);
		array_push($returnData, $camData);
	  }
	}
	return $returnData;
  }
}

// test
$camData = get_EDR_data();
foreach ($camData as $cam) {
  echo $cam['name']."<br />";
  echo $cam['ip']."<br />";
  echo $cam['description']."<br />";
  echo "<br />";
}

// get location and timestamp data
$location  = $_POST['location'];
$timestamp = $_POST['timestamp'];



?>