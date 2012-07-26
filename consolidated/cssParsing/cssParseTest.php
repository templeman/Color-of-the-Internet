<?php
    include('cssparser.php');
	
	$cssFile = "holiday.css";
	
	$cssProp = new cssparser();
	
	$cssProp->Parse($cssFile);
	
	$cssProp = get_object_vars($cssProp);

function flatten_array($array) {
// function to push all values within $spider_array into single dimensional array
		$output = array(); 

		// Push all $val onto $output. 
		array_walk_recursive($array, create_function('$val, $key, $obj', 'array_push($obj, $val);'), &$output); 

		// Printing 
		// print_r($output); 
		return $output;
}

$cssValues = flatten_array($cssProp);
print_r($cssValues);
	

//	$pattern = "/^#[[:xdigit:]]*/";
	
/*
	foreach($cssProp as $key => $value)
	{
		// print_r($value);
		foreach($value as $key2 => $value2)
		{
		//	print_r($value2);
			foreach($value2 as $key3 => $value3)
			{
				if(!is_array($value3) && preg_match($pattern, $value3))
				{
					echo '<pre>';
					// print_r($value3);
					echo '</pre>';
					//$value3 = (int)$value3;

				}
			}	
		}
	}
 */
	
	//$value3 = hexdec($value3);

	/*
	$x = array(); 
	  
	array_push($x, $value3);
	
	$sum = array_sum($x);
	
	$av
	 */
	
	
?>
