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
	
	//$value3 = hexdec($value3);
	
	$x = array(); 
	  
	array_push($x, $value3);
	
	$sum = array_sum($x);
	
	$av
	

	
	
	
?>