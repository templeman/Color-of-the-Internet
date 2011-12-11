<?php

	include("LIB-http/LIB_parse.php");
	include("LIB-http/LIB_http.php");
	include("LIB-http/LIB_mysql.php"); // Include mysql library
	include("hexrgb.php");


// RED
	$red = exe_sql(DATABASE, "SELECT red FROM colors");
	$new_red = array();

	for($i=0; $i<count($red); $i++) {
		$temp = $red[$i][0];
		array_push($new_red, $temp); 
		// echo $new_array . "\n";
	}

	if(array_sum($new_red) == 0) { // avoid divide by zero
		$av_red = 0;
	} else {
		$av_red = (array_sum($new_red) / count($new_red));
	}
	// echo $av_red;


// GREEN
	$green= exe_sql(DATABASE, "SELECT green FROM colors");
	$new_green = array();

	for($i=0; $i<count($green); $i++) {
		$temp = $green[$i][0];
		array_push($new_green, $temp); 
		// echo $new_array . "\n";
	}

	if(array_sum($new_green) == 0) { // avoid divide by zero
		$av_green = 0;
	} else {
		$av_green = (array_sum($new_green) / count($new_green));
	}
	// echo $av_green;


// BLUE
	$blue = exe_sql(DATABASE, "SELECT blue FROM colors");
	$new_blue = array();

	for($i=0; $i<count($blue); $i++) {
		$temp = $blue[$i][0];
		array_push($new_blue, $temp); 
		// echo $new_array . "\n";
	}

	if(array_sum($new_blue) == 0) { // avoid divide by zero
		$ave_blue = 0;
	} else {
		$av_blue = (array_sum($new_blue) / count($new_blue));
	}
	//echo $av_blue;


	$new_RGB = RGBToHex($av_red, $av_green, $av_blue);
	echo "This website's average color is: " . $new_RGB . "\n";

?>
