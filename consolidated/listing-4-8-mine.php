<?php 

# Include libraries
include("LIB-http/LIB_parse.php"); // Include parse library
include("LIB-http/LIB_http.php");  // Include cURL library

# Download a web page
$web_page = http_get($target = "http://www.samueltempleman.com/css/main.css", $referer = "");

# Parse the image tags
// $link_tag_array = parse_array($web_page['FILE'], "<link", ">");

# Echo the image source attribute from each image tag
// for($xx = 0; $xx < count($link_tag_array); $xx++)
//	{
//		$name = get_attribute($link_tag_array[$xx], $attribute = "href");
//		echo $name ."\n";
//	}

if(stristr($web_page, "color"))
	echo "This css file has a color attribute.";
else
	echo "This css file doesn't mention color.";
?>
