<?php 

/*	This code takes a target domain
		and returns all css file paths
*/

# Include libraries
include("LIB-http/LIB_parse.php"); // Include parse library
include("LIB-http/LIB_http.php");  // Include cURL library
include("LIB-http/LIB_mysql.php"); // Include mysql library

$paths = exe_sql(DATABASE, "SELECT path FROM paths");
// print_r($paths);


for($i=0; $i<count($paths); $i++){
	$target = $paths[$i][0];

	# Download a web page
	 
	$web_page = http_get($target = "http://www.oregonlive.com", $referer = "");


	# Parse the image tags
	$link_tag_array = parse_array($web_page['FILE'], "<link", ">");

	# Echo the image source attribute from each image tag
	//for($xx = 0; $xx < count($link_tag_array); $xx++)
	foreach ($link_tag_array as $key => $value) {
			//echo $link_tag_array[$xx]."\n";
			$name = get_attribute($link_tag_array[$key], $attribute = "href");
			$ext = substr($name, -3, 3);
			//echo $name . "<br />";
			//echo $ext . "<br />";
			if($ext == 'css') {
			// preg_match_all('/^.*\.(css)$/i', $value, $out);
			echo $name . "<br />";
			//echo $out[0][0];
			}
		}
?>
