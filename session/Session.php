<?php

/**
 * Session manager
 * 
 * Simple session manager based on PHP session.
 *
 * @author  Wibeset <support@wibeset.com>
 * @package session
 * @todo    Add support for session store into database
 *
 */

class Lity_Session
{
	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
	} // __construct()

	/**
	 * Start session
	 * 
	 */	
	public function start()
	{
		session_start();
		
	} // start()

	/**
	 * Destroy session
	 * 
	 */
	public function destroy()
	{
		session_destroy();
		
	} // destroy()

	/**
	 * Add data to session
	 * 
	 * @param string $name
	 * @param string $value
	 * 
	 */
	public function set($name, $value)
	{
		$_SESSION[$name] = $value;
		
	} // set()

	/**
	 * Get data from session
	 * 
	 * @param  string $name
	 * @return string       data
	 */
	public function get($name)
	{
		if (!isset($_SESSION[$name]))
			return null;
		
		return $_SESSION[$name];
		
	} // get()

	/**
	 * Get all data from session
	 * 
	 */
	public function get_all()
	{
		return $_SESSION;
		
	} // get_all()

	/**
	 * Remove data from session
	 * 
	 * @param string $name
	 * 
	 */
	public function remove($name)
	{
		unset($_SESSION[$name]);
		
	} // remove()

	/**
	 * 
	 * 
	 */
	
	protected static $_instance = null;

	public static function get_instance()
	{
		if (self::$_instance == null)
			self::$_instance = new self();
		
		return self::$_instance;
		
	} // get_instance()

} // Lity_Session
