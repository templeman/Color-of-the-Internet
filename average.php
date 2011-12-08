<?php

include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_http.php");
include("LIB-http/LIB_mysql.php"); // Include mysql library
include("hexrgb.php");


// RED
$red = exe_sql(DATABASE, "SELECT red FROM colors");
print_r($red);

$new_red = array();

for($i=0; $i<count($red); $i++){
	$temp = $red[$i][0];
	array_push($new_red, $temp); 
	// echo $new_array . "\n";
}
if(array_sum($new_red) != 0){
$av_red = (array_sum($new_red) / count($new_red));
} else {
$av_red = 0;
}
// echo $av_red;

// GREEN
$green= exe_sql(DATABASE, "SELECT green FROM colors");
// print_r($green);

$new_green = array();

for($i=0; $i<count($green); $i++){
	$temp = $green[$i][0];
	array_push($new_green, $temp); 
	// echo $new_array . "\n";
}
if(array_sum($new_green) != 0){
$av_green = (array_sum($new_green) / count($new_green));
} else {
$av_green = 0;
}
// echo $av_green;

// BLUE
$blue = exe_sql(DATABASE, "SELECT blue FROM colors");
// print_r($blue);

$new_blue = array();

for($i=0; $i<count($blue); $i++){
	$temp = $blue[$i][0];
	array_push($new_blue, $temp); 
	// echo $new_array . "\n";
}
if(array_sum($new_blue) != 0){
$av_blue = (array_sum($new_blue) / count($new_blue));
} else {
$ave_blue = 0;
}
//echo $av_blue;


$new_RGB = RGBToHex($av_red, $av_green, $av_blue);
echo "This website's average color is: " . $new_RGB . "\n";
/*
for($i=0; $i<count($colors); $i++){
	$target = $paths[$i][0];

	# Download the css file
	$web_page = http_get($target, $referer = "");

	$properties_array = parse_array($web_page['FILE'], "{", "}");

	foreach ($properties_array as $value) {
		preg_match_all('/#([0-9abcdef]+?){3,6}/i', $value, $out);
		// print_r($out);
		// echo $out[0][0];
		//echo $out[0][1];
		// echo $out[0][2];

		foreach($out[0] as $k => $v) {
			$data_array['color'] = $v;
			insert(DATABASE, $table="colors", $data_array);
		}
	}
}
 */

?>
