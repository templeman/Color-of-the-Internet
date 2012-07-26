<?php

	include("LIB-http/LIB_http.php");
	include("LIB-http/LIB_parse.php");
	include("LIB-http/LIB_resolve_addresses.php");
	include("LIB-http/LIB_exclusion_list.php");
	include("LIB-http/LIB_simple_spider.php");
	include("LIB-http/LIB_download_images.php");
	include("LIB-http/LIB_mysql.php");
	include("flattenarray.php");
	include("hextorgb.php");
	include("hexrgb.php"); // Hex/RGB conversions

	// Start output buffering.
	ob_start();
	// Initialize a session.
	session_start();


	// Check for a $page_title value.
	if (!isset($page_title)) {
		$page_title = 'Color of a Web Site';
	}

	//if (!isset($finalHex)) {
	//	$finalHex = '#ffffff';
	// }

	// $page_title = "Get CSS Paths";

/*	if(isset($_POST['submit'])) {	// Get the url
	$finished_flag = false;
 */
		if (!isset($_POST['url'])) {
			 print_r("Error");
		} else {
			print_r("Hooray!");
			$SEED_URL = $_POST['url']; 

	// $is_ajax = $_REQUEST['is_ajax'];
	//	if(isset($is_ajax) && $is_ajax)
	//	{
	//		$SEED_URL = $_REQUEST['url'];

			set_time_limit(3600); // script will time out after one hour

			// $SEED_URL = "http://www.samueltempleman.com";
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
			// echo "Total Harvest:\n";
			// print_r($total_harvest);

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
					// echo "No <link> tags at $value\n";
				} else { // If page has links, parse for .css extension

				//	print_r($link_tag_array);

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
			
			// print_r($path_array);

			$reds = array();
			$greens = array();
			$blues = array();

			for($i=0; $i<count($path_array); $i++){
			// foreach($paths as $value) {
				if(is_array($path_array[0])) { // multiple rows returned from bd as multidimensional array
					$target = $path_array[$i][0];
				} else { // single row from db as single-dimensional array
					$target = $path_array[$i];
				}
				// echo "Target path: " . $target . "\n";

				# Download the css file
				$web_page = http_get($target, $referer = "");

				$properties_array = parse_array($web_page['FILE'], "{", "}");

				// print_r($properties_array);

				foreach ($properties_array as $value) {
					$match_num = preg_match_all('/#([0-9a-f]+?){3,6}/i', $value, $match);
					
					foreach($match[0] as $k => $v) {
						$rgb_array = (hex2RGB($v));
						array_push($reds, $rgb_array[red]); // = $rgb_array[red];
						array_push($greens, $rgb_array[green]); // = $rgb_array[green];
						array_push($blues, $rgb_array[blue]); // = $rgb_array[blue];

					}
					// print_r($data_array);
				}
			}
					 print_r($reds);

			function av_color($array) {
				if(array_sum($array) == 0) { // avoid divide by zero
					$average = 0;
				} else {
					$average = (array_sum($array) / count($array));
				}
				return $average;
			}

			# Run av_color function on each of the three color types in db
			$av_red = av_color($reds);
			$av_green = av_color($greens);
			$av_blue = av_color($blues);


			# Convert fully averaged RGB to Hexadecimal
			$finalHex = RGBToHex($av_red, $av_green, $av_blue);
			// echo "This website's average color is: " . $finalHex . "\n";
			$special_array['finalHex'] = $finalHex;
			$JSONobject = json_encode($specialArray);
			// echo $JSONobject;
			exit();

		}
?>
