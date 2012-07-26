<?php

# Initialization
include("LIB-http/LIB_http.php");
include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_resolve_addresses.php");
include("LIB-http/LIB_exclusion_list.php");
include("LIB-http/LIB_simple_spider.php");
include("LIB-http/LIB_download_images.php");

set_time_limit(3600);

$SEED_URL = "http://www.samueltempleman.com";
$MAX_PENETRATION = 3;
$FETCH_DELAY = 1;
$ALLOW_OFFSITE = true;
$spider_array = array();

# Get links from $SEED_URL
echo "Harvesting Seed URL\n";
$temp_link_array = harvest_links($SEED_URL);
$spider_array = archive_links($spider_array, 0, $temp_link_array);

# Spider links from remaining penetration levels
for($penetration_level=1; $penetration_level<=$MAX_PENETRATION; $penetration_level++)
{
	$previous_level = $penetration_level - 1;
	for($xx=0; $xx<count($spider_array[$previous_level]); $xx++)
	{
		unset($temp_link_array);
		$temp_link_array = harvest_links($spider_array[$previous_level][$xx]);
		echo "Level=$penetration_level, xx=$xx of
			".count($spider_array[$previous_level])." \n";
		$spider_array = archive_links($spider_array, $penetration_level, $temp_link_array);
	}
}

# Add the payload to the simple spider
// Include download and directory creation lib

// Download images from pages referenced in $spider_array
for($penetration_level=1; $penetration_level<=$MAX_PENETRATION; $penetration_level++)
{
	for($xx=0; $xx<count($spider_array[$previous_level]); $xx++)
	{
		download_images_for_page($spider_array[$previous_level][$xx]);
	}
}


?>
