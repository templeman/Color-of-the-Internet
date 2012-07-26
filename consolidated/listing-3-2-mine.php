<?php
include('cssparser.php'); 

# Download the target file

$target = "http://www.samueltempleman.com/css/main.css";
$downloaded_page_array = file($target);
$pattern = "/^#[[:xdigit:]]*/";

# Echo the contents of file
for($xx = 0; $xx < count($downloaded_page_array); $xx++)

		foreach($downloaded_page_array as $key => $value)
			{
				if(preg_match($pattern, $value))
				{
					echo"<pre>";
					print_r($value);
					echo"</pre>";
				}
			}
		//echo"<pre>";
		//print_r($downloaded_page_array);
		//echo"</pre>";
?>
