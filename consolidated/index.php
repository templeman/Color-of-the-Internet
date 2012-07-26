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

	$page_title = "Get CSS Paths";

	if(isset($_POST['submit'])) { // Get the url
		if (isset($_POST['url'])) {
			$SEED_URL = ($_POST['url']);

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
			echo "This website's average color is: " . $finalHex . "\n";

			# == PARTY TIME == #
		}
	}

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>The Average Color of the Internet, Sort Of.</title>
  <link rel="stylesheet" type="text/css" href="style.css" />
  	<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script type="text/javascript" src="jquery.xcolor.min.js"></script>
	<script type="text/javascript">
		$(document).ready(function(){
			
			// Using "x.color.average" in a recursive function to get an average of an array of hex values
			
			/*
			var colors = new Array('#00FF66','#443456','#000000','#ffffff', '#E28118', '#fffccc', '#006600', '#efefef');

			function averageColors(colors, length) {
				console.log(colors.length);
				if(colors.length == 1) {
					return aveColor = new String(colors);
				} else {
					colors.unshift($.xcolor.average(colors.shift(), colors.shift())); 
					return averageColors(colors, length);
				}
			}  
			console.log(averageColors(colors, colors.length));
			var hexVal = aveColor.concat();

			$("#color").css("background", hexVal);
			*/
			
			$("div#popup").hide();
			$("a.button").click(function(){
				$("div#popup").show();
			});
			$("#close").click(function(){
				$("div#popup").hide("fast");
			});
		});
	</script>
</head>
<body>
	<div id="wrap">
		<h1>Average Color of a Site</h1>
		<?php echo "<div id='color' style='background: $color'></div>"; ?>
		<div class="push"></div>
	</div><!-- end wrap -->
    <footer>
    	<footer id="inner">
    		<div id="popup">
    			<div id="close"></div>
    			<h2>Color of the Internet</h2>
    			<p>This project was conceived in the context of the Rapid Web course
		          at The Art Institute of Portland in Portland, Oregon.
		          The objective is to derive a single hexadecimal color value that is as close
		          an average as possible of all of the color values on the entire Internet.
		          This value will be obtained with a specialized web spider script designed to 
		          scrape websites for color values, store them, and ultimately average them
		          together to produce a final representative "Color of the Internet".
		         </p>
		         <span>Contributers:</span>
		         <h3>Sam Templeman</h3>
		         <h3>Sean Harvey</h3>
    			 <div id="arrow"></div>
    		</div>
    		<form>
				<label for="url">Enter a URL:</label>
				<input type="url" name="url" id="url" placeholder="http://www.someurl.com" value="<?php if (isset($_POST['url'])) echo $_POST['url']; ?>" />
				<input type="submit" name="submit_button" value="Submit" />
				<input type="hidden" name="submit" value="TRUE" />
    		</form>
    		<ul>
    			<li><a class="git" target="_blank" href="https://github.com/templeman/Color-of-the-Internet">Git</a></li>
    			<li><a class="button" href="#" rel="?">?</a></li>
    		</ul>
    	</footer>
   	</footer>
</body>
</html>

<?php // Flush the buffered output.
ob_flush();
?>



