<?php

include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_http.php");
include("LIB-http/LIB_mysql.php"); // Include mysql library
include("hextorgb.php");
include("flattenarray.php");

$paths = exe_sql(DATABASE, "SELECT path FROM paths");
print_r($paths);
$match_test = array();

for($i=0; $i<count($paths); $i++){
// foreach($paths as $value) {
	if(is_array($paths[0])) { // multiple rows returned from bd as multidimensional array
		$target = $paths[$i][0];
	} else { // single row from db as single-dimensional array
		$target = $paths[$i];
	}
	echo "Target path: " . $target . "\n";

	# Download the css file
	$web_page = http_get($target, $referer = "");

	$properties_array = parse_array($web_page['FILE'], "{", "}");

	print_r($properties_array);

	foreach ($properties_array as $value) {
		$match_num = preg_match_all('/#([0-9a-f]+?){3,6}/i', $value, $match);
			if($match_num > 0) {
				array_push($match_test, $match[0]);
			}
		
		foreach($match[0] as $k => $v) {
			$rgb_array = (hex2RGB($v));
			$data_array['red'] = $rgb_array[red];
			$data_array['green'] = $rgb_array[green];
			$data_array['blue'] = $rgb_array[blue];

			insert(DATABASE, $table="colors", $data_array);
		}
	}
	echo "Path #" . $i;
}

// Testing purposes only (output all color values)
$total_matches = flatten_array($match_test);
echo "Total colors matched: ";
print_r($total_matches);
?>
