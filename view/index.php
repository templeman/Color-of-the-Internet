<?php
 
 // Php spider goes here.

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
    			<input type="text" placeholder="Enter a URL to get it's average color."/>
    			<input type="submit" value="Submit" />
    		</form>
    		<ul>
    			<li><a class="git" target="_blank" href="https://github.com/templeman/Color-of-the-Internet">Git</a></li>
    			<li><a class="button" href="#" rel="?">?</a></li>
    		</ul>
    	</footer>
   	</footer>
</body>
</html>


