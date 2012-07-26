<?php

# Function to push all values within a
# multidimensional array into single dimensional array

	function flatten_array($array) {
			$output = array(); 

			// Push all $val onto $output. 
			array_walk_recursive($array, create_function('$val, $key, $obj', 'array_push($obj, $val);'), &$output); 

			return $output;
	}

?>
