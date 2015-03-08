<?php

	//One day = 86400 seconds, server can + - this area to decide

	$inLat=$_POST["inLat"];
	$inLng=$_POST["inLng"];
	$date=$_POST["epochDate"]+8*3600;
	$time=$_POST["time"];


	echo "<br>";
	//String process of 

	$time_array=explode(":", $time);
	$hr=$time_array[0];
	$time_array2=explode(" ", $time_array[1]);
	$min=$time_array2[0];
	$pmam=$time_array2[1];
	if (!strcasecmp($pmam, "PM")) {

		$date+=12*3600;
		$date+=3600*$hr;
	}else{
		$date+=3600*$hr;
	}
	//End process 



	echo "Lat:".$inLat;
	echo "<br>";
	echo "Lng".$inLng;	
	echo "<br>";
	echo "Time:".$time;
	echo "final Epoch,Date:".$date."<br>";

?>