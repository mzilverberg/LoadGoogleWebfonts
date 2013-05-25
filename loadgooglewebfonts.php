<?php
/*
LOAD GOOGLE WEBFONTS
 *
 * Description:	A PHP script for loading Google Webfonts' css files in an orderly manner
 * Version:			0.8
 * Author:			Maarten Zilverberg (www.mzilverberg.com)
 * Examples:		https://github.com/mzilverberg/LoadGoogleWebfonts
 
 * Licensed under the GPL license:
 * http://www.gnu.org/licenses/gpl.html
 * 
 * Last but not least:
 * if you like this script, I would appreciate it if you took the time to share it
*/
function loadGoogleWebfonts($fonts, $use_fallback = true, $debug = false) {
	// if debugging, use &lt; and $gt; notation for output as plain text
	// otherwise, use < and > for output as html
	$debug ? $x = array('&lt;', '&gt;') : $x = array('<', '>');
	// create a new font array
	$array = array();
	// create a new fallback array for storing possible fallback urls
	$fallback_urls = array();
	// determine how many fonts are requested by checking if the array key ['name'] exists
	// if it exists, push that single font into the $array variable
	// otherwise, just copy the $fonts variable to $array
	isset($fonts['name']) ? array_push($array, $fonts) : $array = $fonts;
	// request the link for each font
	foreach ($array as $font) {
		// set the basic url
		$base_url = 'http://fonts.googleapis.com/css?family=' . str_replace(' ', '+', $font['name']) . ':';
		$url = $base_url;
		// create a new array for storing the font weights
		$weights = array();
		// if the font weights are passed as a string (from which all spaces will be removed), insert each value into the $weights array
		// otherwise, just copy the weights passed
		if(isset($font['weight'])) {
			gettype($font['weight']) == 'string' ? $weights = explode(',', str_replace(' ', '', $font['weight'])) : $weights = $font['weight'];
		// if font weights aren't defined, default to 400 (normal weight)
		} else {
			$weights = array('400');
		}
		// add each weight to $url and remove the last comma from the url string
		foreach($weights as $weight) { 
			$url .= $weight . ',';
			// if the fallback notation is necessary, add a single weight url to the fallback array
			if($use_fallback && count($weights) != 1) array_push($fallback_urls, "$base_url$weight");
		}
		$url = substr_replace($url, '', -1);
		// normal html output
		echo $x[0] . 'link href="' . $url . '" rel="stylesheet" type="text/css" /' . $x[1] . "\n";
	}
	// add combined conditional comment for each font weight if necessary 
	if($use_fallback && !empty($fallback_urls)) {
		// begin conditional comment
		echo $x[0] . '!--[if lte IE 8]' . $x[1] . "\n";
		// add each fallback url within the conditional comment
		foreach($fallback_urls as $fallback_url) {
			echo '  ' . $x[0] . 'link href="' . $fallback_url . '" rel="stylesheet" type="text/css" /' . $x[1] . "\n";
		}
		// end conditional comment
		echo $x[0] . '![endif]--' . $x[1] . "\n";
	}
}
?>