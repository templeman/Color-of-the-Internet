<?php

include("LIB-http/LIB_parse.php");
include("LIB-http/LIB_http.php");

$web_page = http_get($target="http://www.samueltempleman.com/css/cssParserTest.css", $referer="");

$pattern = "/^#[[:xdigit:]]*/";

// print_r($web_page);
$properties_array = parse_array($web_page['FILE'], "{", "}");

// print_r($properties_array);
/* for($xx=0; $xx<count($properties_array); $xx++){

	$name = $properties_array[$xx];
	$parsed
	echo $name ."\n";
 */
foreach ($properties_array as $value) {
	// echo $value;
	// echo "Hi my name is bob";
	preg_match_all('/#([0-9abcdef]+?){3,6}/i', $value, $out);
	echo $out[0][0];
	echo $out[0][1];
	echo $out[0][2];
	// echo $out[1][1];
	// $res = preg_replace('/[[:xdigit:]]/', '', $value);
	// echo $res;
	// if(preg_match('/#([0-9abcdef]+?){3,6}/i', $res) {
	// if(preg_match('/\bcolor\b/i', $res)) {
	// if(preg_match('/^#([0-9a-f]{1,2}){3}$/i', $value)) {
	// preg_match_all($pattern, $value, $out, PREG_PATTERN_ORDER);
		// echo $out[0][0] . ", " . $out[0][1] . "\n";
		// echo $res . "<br />";
	// }
}

?>
