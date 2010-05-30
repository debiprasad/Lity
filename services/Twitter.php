<?php

/**
 * Twitter
 *
 * @note cURL must be enabled
 * @author Wibeset <support@wibeset.com>
 * @package services
 * @see <a href="http://twitter.com/">Twitter</a>
 * @see <a href="http://apiwiki.twitter.com/REST+API+Documentation">Twitter API documentation</a>
 *
 */

class Lity_Service_Twitter
{
    /**
	 * @var $_url
	 */
	private $_url = "http://twitter.com/";

	/**
	 * cURL instance
	 */
	private $_ch;

	/**
	 * Authentication
	 */
	private $_username;
	private $_password;
	private $_authentication;

	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
	    $this->_ch = curl_init();
		curl_setopt($this->_ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->_ch, CURLOPT_HEADER, 0);
		curl_setopt($this->_ch, CURLOPT_VERBOSE, 0);
		
	} // __construct()

	/**
	 * Destructor
	 */
	public function __destruct()
	{
	    curl_close($this->_ch);
	    
	} // __destruct()

	/**
	 * Set user authentication
	 *
	 * @param string $username
	 * @param string $password
	 *
	 */
	public function set_authentication($username, $password)
	{
	    $this->_username = $username;
		$this->_password = $password;
		$this->_authentication = $this->_username.':'.$this->_password;
		
	} // set_authentication()

	/**
	 * Update the authenticating user's status
	 * Authentication must be setted.
	 *
	 * @param string $status_text new user's status
	 * @return array result
	 *
	 */
	public function update($status_text)
	{
	    curl_setopt($this->_ch, CURLOPT_URL, $this->_url."statuses/update.json");
		curl_setopt($this->_ch, CURLOPT_USERPWD, $this->_authentication);
		curl_setopt($this->_ch, CURLOPT_POST, 1);
		curl_setopt($this->_ch, CURLOPT_POSTFIELDS, array('status' => $status_text));

		$result = curl_exec($this->_ch);

		return json_decode($result, true);

	} // update()
	
	/**
	 * Get a timeline
	 * 
	 * @param string $type 
	 * @param array  $parameters
	 * @return array result
	 * 
	 */
	public function timeline($type = 'user', $parameters = array())
	{
	    $extra_url = '?';
		foreach ($parameters as $pk => $pv) 
			$extra_url .= $pk.'='.$pv.'&';
	
		curl_setopt($this->_ch, CURLOPT_URL, $this->_url."statuses/".$type."_timeline.json".$extra_url);

		$result = curl_exec($this->_ch);

		return json_decode($result, true);
		
	} // timeline()

} // Lity_Service_Twitter