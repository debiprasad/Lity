<?php

/**
 * Last.fm
 * 
 * You must add the following config to app/config/application.php:
 * 
 * $config['lastfm'] => array('api_key' => 'your api key',
 *                            'secret' => 'you secret key',
 *                            'useragent' => '');
 * 
 * @author Wibeset <support@wibeset.com>
 * @package services
 * @see <a href="http://www.lastfm.fr/api/intro">last.fm API</a>
 * 
 */

class Lity_Service_Lastfm
{
    /**
	 * API url
	 */
	private $_url = 'http://ws.audioscrobbler.com/2.0/';

	/**
	 * Call a method
	 * 
	 * @param string $method 
	 * @param array  $parameters
	 * @return array result
	 * 
	 */
	public function call($method, $parameters)
	{
		//
		$url = $this->_url.'?method='.$method.'&api_key='.app()->config['lastfm']['api_key'].'&';
		
		//
		foreach ($parameters as $pk => $pv)
			$url .= $pk.'='.$pv.'&';
	
		//
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, app()->config['lastfm']['useragent']);
		$result = curl_exec($ch);
		curl_close($ch);
		
		return simplexml_load_string($result);
		
	} // call()
	
} // Lity_Service_Lastfm