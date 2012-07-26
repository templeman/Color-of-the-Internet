<?php 

# Include libraries
include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_http.php");

# Download a web page
$web_page = http_get($target = "http://www.nostarch.com", $referer = "");

# Parse the title of the web page, inclusive of the title tags
$title_incl = return_between($web_page['FILE'], "<title>", "</title>", INCL);

# Parse the title of the web page, exclusive of the title tags
$title_excl = return_between($web_page['FILE'], "<title>", "</title>", EXCL);

# Display the parsed text
echo "title_incl = ".$title_incl;
echo "\n";
echo "title_excl = ".$title_excl;

?>
