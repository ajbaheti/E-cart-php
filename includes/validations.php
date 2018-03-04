<?php
	
	/* Function to sanitize all inputs */
	function sanitizeString($var){
		$var = trim($var);
		$var = stripslashes($var);
		$var = htmlspecialchars($var);
		$var = htmlentities($var);
		$var = strip_tags($var);
		return $var;
	}//sanitizeString

	/* Regex to check alpha numeric characters along with space for product name */
	function alphanumeric($value) {
		$reg = "/^[A-Z a-z0-9]+$/";
		return preg_match($reg,$value);
	}//alphanumeric

	/* Regex to check numeric values and dot for product price and sale price */
	function dotnumeric($value) {
		$reg = "/^[0-9\.]+$/";
		return preg_match($reg,$value);
	}//dotnumeric

	/* Regex to check numeric values for product quantity */
	function numeric($value) {
		$reg = "/^[0-9]+$/";
		return preg_match($reg,$value);
	}//numeric

	function sqlMetaChars($value) {
		$reg = "/((\%3D)|(=))[^\n]*((\%27)|(\')|(\-\-)|(\%3B)|(;))/i";
		return preg_match($reg,$value);
	}//sqlMetaChars

	function sqlInjection($value) {
		$reg = "/\w*((\%27)|(\'))((\%6F)|o|(\%4F))((\%72)|r|(\%52))/i";
		return preg_match($reg,$value);
	}//sqlInjection

	function sqlInjectionUnion($value) {
		$reg = "/((\%27)|(\'))union/i";
		return preg_match($reg,$value);
	}//sqlInjectionUnion

	function sqlInjectionSelect($value) {
		$reg = "/((\%27)|(\'));\s*select/i";
		return preg_match($reg,$value);
	}//sqlInjectionSelect

	function sqlInjectionInsert($value) {
		$reg = "/((\%27)|(\'));\s*insert/i";
		return preg_match($reg,$value);
	}//sqlInjectionInsert

	function sqlInjectionDelete($value) {
		$reg = "/((\%27)|(\'));\s*delete/i";
		return preg_match($reg,$value);
	}//sqlInjectionDelete

	function sqlInjectionDrop($value) {
		$reg = "/((\%27)|(\'));\s*drop/i";
		return preg_match($reg,$value);
	}//sqlInjectionDrop

	function sqlInjectionUpdate($value) {
		$reg = "/((\%27)|(\'));\s*update/i";
		return preg_match($reg,$value);
	}//sqlInjectionUpdate

	function crossSiteScripting($value) {
		$reg = "/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/i";
		return preg_match($reg,$value);
	}//crossSiteScripting

	function crossSiteScriptingImg($value) {
		$reg = "/((\%3C)|<)((\%69)|i|(\%49))((\%6D)|m|(\%4D))((\%67)|g|(\%47))[^\n]+((\%3E)|>)/i";
		return preg_match($reg,$value);
	}//crossSiteScriptingImg
?>