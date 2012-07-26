<?php
    include('cssparser.php');
	
	$cssFile = "cssParserTest.css";
	
	$cssProp = new cssparser();
	
	$cssProp->Parse($cssFile);
	
	$cssProp = get_object_vars($cssProp);
	
	$pattern = "/^#[[:xdigit:]]*/";
	
	foreach($cssProp as $key => $value)
	{
		foreach($value as $key2 => $value2)
		{
			foreach($value2 as $key3 => $value3)
			{
				if(!is_array($value3) && preg_match($pattern, $value3))
				{
					echo '<pre>';
					print_r($value3);
					echo '</pre>';
					//$value3 = (int)$value3;

				}
			}
		}
	}
	
	$value3 = hexdec($value3);
	
	$x = array(); 
	  
	array_push($x, $value3); // creates an array of hex values
	
	$sum = array_sum($x);
	
	$sum = $sum/4;// get the average of the array
	
	$sum = dechex($sum);
	
	echo "The average of the hex values:";
	echo "<div style='background: #$sum; width: 250px; height: 95px;'></div>";
	

	
	
	
?>