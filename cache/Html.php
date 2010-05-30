<?php

/**
 * Cache - HTML
 *
 * @author Wibeset <support@wibeset.com>
 * @package cache
 *
 */

require_once ABSPATH."lity/cache/Cache.php";

class Lity_Cache_Html extends Lity_Cache
{
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
		$this->set_type('file');
		$this->set_object(plugin('file'));

	} // __construct()	

	/**
	 * Get cache
	 *
	 * @param string $name
	 * @param int    $maxtime
	 * @return string|bool cache
	 *
	 */
	public function get($name, $maxtime = 0)
	{
		$this->get_object()->set(ABSPATH.'cache/html/'.$name.'.html', false);

		if (!$this->get_object()->exists()) {
			return false;
		}

		if ($this->get_object()->get_last_change() < time() - $maxtime) {
			return $this->get_object()->read();
		}

		return false;

	} // get()
	
	/**
	 * Do not use...
	 * 
	 */
	public function set($name, $data, $maxtime = 0)
	{
	} // set()

	/**
	 * Start to cache...
	 *
	 */
	public function start()
	{
		ob_start();
		
	} // start()

	/**
	 * Stop
	 * 
	 * @param string $name
	 * @return string HTML
	 *
	 */
	public function stop($name)
	{
		// Get buffer
		$html = ob_get_contents();
		ob_end_clean();
				
		$this->get_object()->set(ABSPATH.'cache/html/'.$name.'.html', true);
		$this->get_object()->write($html, 'w+');

		return $html;

	} // stop()

	/**
	 * Delete cache
	 * 
	 * @param string $name
	 * @return bool success
	 *
	 */
	public function delete($name)
	{
		$this->get_object()->set(ABSPATH.'cache/html/'.$name.'.html', false);

		if ($this->get_object()->exists()) {
			return $this->get_object()->destroy();
		}
		
		return false;		

	} // delete()
	
	/**
	 * Clear
	 * 
	 * @return bool success
	 * 
	 */
	public function clear()
	{
	} // clear()	

	/**
	 * 
	 */
	
	protected static $_instance = null;

	public function get_instance()
	{
		if (self::$_instance == null)
			self::$_instance = new self();
		
		return self::$_instance;
		
	} // get_instance()

} // Lity_Cache_Html
