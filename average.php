<?php

	include("LIB-http/LIB_mysql.php"); // Include mysql library
	include("hexrgb.php"); // Hex/RGB conversions

	function av_color($color) {
		echo $color;
		$color_results = exe_sql(DATABASE, "SELECT $color FROM colors");
		$color_array = array();

		for($i=0; $i<count($color_results); $i++) {
			$temp = $color_results[$i][0];
			array_push($color_array, $temp);
		}

		if(array_sum($color_array) == 0) { // avoid divide by zero
			$average = 0;
		} else {
			$average = (array_sum($color_array) / count($color_array));
		}
		return $average;
	}

	# Run av_color function on each of the three color types in db
	$av_red = av_color(red);
	$av_green = av_color(green);
	$av_blue = av_color(blue);


	# Convert fully averaged RGB to Hexadecimal
	$finalHex = RGBToHex($av_red, $av_green, $av_blue);
	echo "This website's average color is: " . $finalHex . "\n";

	# == PARTY TIME == #
?>
