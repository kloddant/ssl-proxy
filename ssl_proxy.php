<?php

	$url = $_GET['url'];
	$url = strip_tags($url);
	
	$url_pieces = parse_url($url);
	if (!isset($url_pieces['path'])) {
		exit;
	}
	$scheme = (isset($url_pieces['scheme']) ? $url_pieces['scheme']."://" : '');
	$host = (isset($url_pieces['host']) ? $url_pieces['host'] : '');
	$path = $url_pieces['path'];
	$file = basename($path);

	$path = urldecode($path);
	$path = implode('/', array_map('rawurlencode', explode('/', $path)));
	$url = $scheme.$host.$path;
	
	$url = filter_var($url, FILTER_SANITIZE_URL);
	
	if (filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_PATH_REQUIRED) === false) {
		exit;
	}

	$curl_options = array(
		CURLOPT_FAILONERROR => true,
		CURLOPT_FOLLOWLOCATION => true,
		CURLOPT_RETURNTRANSFER => false,
		CURLOPT_SSL_VERIFYHOST => false,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_HTTPGET => true,
		CURLOPT_HEADER => false,
		CURLOPT_VERBOSE => true,
		CURLOPT_BINARYTRANSFER => true,
		CURLOPT_TIMEOUT => 900,
		CURLOPT_USERAGENT => "Mozilla/5.0 (Windows NT x.y; WOW64; rv:10.0) Gecko/20100101 Firefox/10.0",
	);

	$curl = curl_init($url);
	curl_setopt_array($curl, $curl_options); 
	header('Content-Type: '.curl_getinfo($curl, CURLINFO_CONTENT_TYPE));
	header('Content-Disposition: filename="'.$file.'"');
	$result = curl_exec($curl);
	curl_close($curl);
	 
?>
