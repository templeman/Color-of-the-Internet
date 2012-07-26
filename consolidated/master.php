<?php

include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_http.php");
include("LIB-http/LIB_mysql.php"); // Include mysql library

$paths = exe_sql(DATABASE, "SELECT path FROM paths");
// print_r($paths);


for($i=0; $i<count($paths); $i++){
	$target = $paths[$i][0];

	# Download a web page
	 
	$web_page = http_get($target, $referer = "");

	$properties_array = parse_array($web_page['FILE'], "{", "}");

	foreach ($properties_array as $value) {
		preg_match_all('/#([0-9abcdef]+?){3,6}/i', $value, $out);
		echo $out[0][0];
		echo $out[0][1];
		echo $out[0][2];
}

?>
