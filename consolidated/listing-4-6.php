<?php 

# Include libraries
include("LIB-http/LIB_parse.php"); // Include parse library
include("LIB-http/LIB_http.php");  // Include cURL library

# Download a web page
$web_page = http_get($target = "http://www.fbi.gov", $referer = "");
$meta_tag_array = parse_array($web_page['FILE'], "<meta", ">");

for($xx = 0; $xx < count($meta_tag_array); $xx++)
	echo $meta_tag_array[$xx]."\n";

?>
