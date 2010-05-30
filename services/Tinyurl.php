<?php

/**
 * Tinyurl.com
 * 
 * @author Wibeset <support@wibeset.com>
 * @package services
 * 
 */

class Lity_Service_Tinyurl
{
    /**
	 * Get a short url for $url
	 * 
	 * @param string $url url to shorten
	 * @return string url shortened
	 * 
	 */
	public function create($url)
	{
	    $ch = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, "http://tinyurl.com/api-create.php?url=".$url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		
		$result = curl_exec($ch);
		
		curl_close($ch);
		
		return $result;
		
	} // create()

} // Lity_Service_Tinyurl