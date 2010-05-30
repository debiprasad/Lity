<?php

/**
 * Cache - File
 *
 * @author Wibeset <support@wibeset.com>
 * @package cache
 *
 */

require_once ABSPATH."lity/cache/Cache.php";	

class Lity_Cache_File extends Lity_Cache
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
	 * Get
	 *
	 * @param string $name
	 * @return mixed
	 *
	 */
	public function get($name)
	{
		$this->get_object()->set(ABSPATH.'cache/objects/'.$name, false);

		if (!$this->get_object()->exists()) {
			return false;
		}

		$data = @unserialize($this->get_object()->read());

		if ($data['maxtime'] > 0 && $this->get_object()->get_last_change() < time() - $data['maxtime']) {
			$this->get_object()->destroy();
			return false;
		}

		return $data['data'];

	} // get()

	/**
	 * Set cache
	 *
	 * @param string $name
	 * @param mixed  $data
	 * @param int    $maxtime
	 *
	 */
	public function set($name, $data, $maxtime = 0)
	{
		$data = array('maxtime' => $maxtime,
									'data' => $data);

		$this->get_object()->set(ABSPATH.'cache/objects/'.$name, true);
		$this->get_object()->write(serialize($data), 'w+');

	} // set()

	/**
	 * Delete from cache
	 * 
	 * @param string $name
	 *
	 */
	public function delete($name)
	{
		$this->get_object()->set(ABSPATH.'cache/objects/'.$name, false);

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

} // Lity_Cache_File
