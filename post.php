<?php

	//One day = 86400 seconds, server can + - this area to decide
	
	$inLat=$_POST["inLat"];
	$inLng=$_POST["inLng"];
	$date=$_POST["epochDate"];
	$time=$_POST["epoch"];
	echo "Lat:".$inLat;
	echo "<br>";
	echo "Lng".$inLng;
	echo "<br>";
	echo "Epoch,Date:".$date;	
	echo "<br>";
	echo "Time:".$time;
?>