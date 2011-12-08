<?php

include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_http.php");
include("LIB-http/LIB_mysql.php"); // Include mysql library
include("hextorgb.php");

$paths = exe_sql(DATABASE, "SELECT path FROM paths");
print_r($paths);


for($i=0; $i<count($paths); $i++){
// foreach($paths as $value) {
	$target = $paths[$i];
	echo $target . "\n";

	# Download the css file
	$web_page = http_get($target, $referer = "");
	// print_r($web_page);

	$properties_array = parse_array($web_page['FILE'], "{", "}");

	foreach ($properties_array as $value) {
		preg_match_all('/#([0-9abcdef]+?){3,6}/i', $value, $match);
		// print_r($match);
		// echo $match[0][0];
		//echo $match[0][1];
		// echo $match[0][2];

		foreach($match[0] as $k => $v) {
			$rgb_array = (hex2RGB($v));
			// print_r($rgb_array);
			$data_array['red'] = $rgb_array[red];
			$data_array['green'] = $rgb_array[green];
			$data_array['blue'] = $rgb_array[blue];

			// $data_array['color'] = $v;
			insert(DATABASE, $table="colors", $data_array);
		}
	}
}
// for some reason #E4EAE4 is not being inserted properly in the db
$test = '#CC0000';
print_r(hex2RGB($test));
?>
