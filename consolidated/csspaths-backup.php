<?php

	include("LIB-http/LIB_http.php");
	include("LIB-http/LIB_parse.php");
	include("LIB-http/LIB_resolve_addresses.php");
	include("LIB-http/LIB_exclusion_list.php");
	include("LIB-http/LIB_simple_spider.php");
	include("LIB-http/LIB_download_images.php");
	include("LIB-http/LIB_mysql.php");
	include("flattenarray.php");

	set_time_limit(3600); // script will time out after one hour

	$SEED_URL = "http://www.blackmatrixpub.com";
	$MAX_PENETRATION = 2;
	$FETCH_DELAY = 1;
	$ALLOW_OFFSITE = false;
	$spider_array = array();
	$path_array = array();

	# Get links from $SEED_URL
	echo "Harvesting Seed URL\n";
	$temp_link_array = harvest_links($SEED_URL);
	$spider_array = archive_links($spider_array, 0, $temp_link_array);
	// returns all the links found on SEED_URL home

	# Spider links from remaining penetration levels
	// At each penetration level, loop over all items at that level, then move down to
	// next level and repeat until all levels have been parsed
	for($penetration_level=1; $penetration_level<=$MAX_PENETRATION; $penetration_level++) {
		$previous_level = $penetration_level - 1;
		for($i=0; $i<count($spider_array[$previous_level]); $i++) {
			unset($temp_link_array);
			$temp_link_array = harvest_links($spider_array[$previous_level][$i]);
			// echo "Level=$penetration_level, i=$i of
			// ".count($spider_array[$previous_level])." \n";
			$spider_array = archive_links($spider_array, $penetration_level, $temp_link_array);
		}
	}

	$total_harvest = flatten_array($spider_array);
	echo "Total Harvest:\n";
	print_r($total_harvest);

	#
	# Begin Payload
	#
	# For optimization, send as few links to payload as possible

	// Filter all returned links for .css paths only
	// This process times out on larger sites. 
	// Find a way to optimize.
	foreach($total_harvest as $key => $value) {
		# Download a web page
		$web_page = http_get($value, $referer = "");

		# Update the target in case there was a redirection
		$value = $web_page['STATUS']['url'];

		# Strip file name off target for use as page base
		$page_base = get_base_page_address($value);

		# Parse the link tags
		$link_tag_array = parse_array($web_page['FILE'], "<link", ">");

		if(count($link_tag_array)==0) {
			echo "No <link> tags at $value\n";
		} else { // If page has links, parse for .css extension

			print_r($link_tag_array);

			for($i = 0; $i < count($link_tag_array); $i++) {
				// foreach ($link_tag_array as $key1 => $value1) {
				//echo $link_tag_array[$xx]."\n";
				$name = get_attribute($link_tag_array[$i], $attribute = "href");
				// echo " file: ".$name;
				$resolved = resolve_address($name, $page_base); 
				$ext = substr($resolved, -3, 3);
				//echo $name . "<br />";
				// echo $ext;
				// if path is not already in paths array, add it
				if(($ext == 'css') && (!in_array($resolved, $path_array))) {
					array_push($path_array, $resolved);
				}
			}
		}
	}
	
	print_r($path_array);

	// Save css paths to db
	foreach($path_array as $key => $value) {
		$data_array['path'] = $value;
		insert(DATABASE, $table="paths", $data_array);
	}

/*	TESTING CODE: IGNORE
	for($penetration_level=1; $penetration_level<=$MAX_PENETRATION; $penetration_level++) {
		for($i=0; $i<count($spider_array[$previous_level]); $i++) {
			// print_r($spider_array[$previous_level]);
		
			 foreach($spider_array as $key => $value){
				 foreach($value as $key2 => $value2){
					$target = $value2;
					// echo $target . "\n";

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
} */
?>
