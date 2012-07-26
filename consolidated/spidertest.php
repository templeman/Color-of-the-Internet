<?php

# Initialization
include("LIB-http/LIB_http.php");
include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_resolve_addresses.php");
include("LIB-http/LIB_exclusion_list.php");
include("LIB-http/LIB_simple_spider.php");
include("LIB-http/LIB_download_images.php");
include("LIB-http/LIB_mysql.php");

set_time_limit(3600);

$SEED_URL = "http://www.sandwichofportland.com";
$MAX_PENETRATION = 1;
$FETCH_DELAY = 1;
$ALLOW_OFFSITE = false;
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
$path_array = array();

// Download images from pages referenced in $spider_array
for($penetration_level=1; $penetration_level<=$MAX_PENETRATION; $penetration_level++)
{
	for($xx=0; $xx<count($spider_array[$previous_level]); $xx++)
	{
		foreach($spider_array as $key => $value){

			foreach($value as $key2 => $value2){

				$target = $value2;
				//echo $target . "\n";

				# Download a web page
				$web_page = http_get($target, $referer = "");

				# Parse the link tags
				$link_tag_array = parse_array($web_page['FILE'], "<link", ">");

				# Echo the image source attribute from each image tag
				//for($xx = 0; $xx < count($link_tag_array); $xx++)
				foreach ($link_tag_array as $key => $value) {
						//echo $link_tag_array[$xx]."\n";
						$name = get_attribute($link_tag_array[$key], $attribute = "href");
						$resolved = resolve_address($name, $SEED_URL); 
						$ext = substr($resolved, -3, 3);
						//echo $name . "<br />";
						//echo $ext . "<br />";
						if(($ext == 'css') && (!in_array($resolved, $path_array))) {
							// preg_match_all('/^.*\.(css)$/i', $value, $out);
							array_push($path_array, $resolved);
							// echo $name . "\n";
							//echo $out[0][0];
						}
					}
			}
		}
	}
}
print_r($path_array);
foreach($path_array as $key => $value) {
$data_array['path'] = $value;
insert(DATABASE, $table="paths", $data_array);
//	echo $value . "\n";
// exe_sql(DATABASE, "INSERT INTO paths (path) VALUES ('$value')");
}

?>
