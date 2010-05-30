<?php

/**
 * Cache - Db
 *
 * @author Wibeset <support@wibeset.com>
 * @package cache
 *
 */

require_once ABSPATH."lity/cache/Cache.php";	

class Lity_Cache_Db extends Lity_Cache
{
	/**
	 * Constructor
	 * 
	 * @param string $db_config_name database config's key name
	 *
	 */
	public function __construct($db_config_name)
	{
		$this->set_type('db');
		$this->set_object(db($db_config_name));

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
		try {

			$cache = model('cache')->find_first(array('condition' => 'name = "'.$name.'"'));

			if ($cache->maxtime > 0 && $cache->updated_at < time() - $cache->maxtime) {
				$cache->destroy();
				return false;
			}

			return $cache->data;

		} catch (no_record_found $e) {
			return false;
		}

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
		try {
			$cache = model('cache')->find_first(array('condition' => 'name = "'.$name.'"'));
			$cache->updated_at = time();
		} catch (no_record_found $e) {
			$cache = model('cache');
		}

		$cache->data = $data;
		$cache->maxtime = $maxtime;
		$cache->save();

	} // set()

	/**
	 * Delete from cache
	 *
	 * @param string $name
	 *
	 */
	public function delete($name)
	{
		return model('cache')->destroy(array('condition' => 'name = "'.$name.'"'));
		
	} // delete()

	/**
	 * Clear
	 *
	 * @return bool success
	 *
	 */
	public function clear()
	{
		return model('cache')->destroy_all();
		
	} // clear()

	/**
	 *
	 */

	protected static $_instance = array();

	public function get_instance($name, $db_config_name)
	{
		if (!isset(self::$_instance[$name]))
			self::$_instance[$name] = new self($db_config_name);

		return self::$_instance[$name];

	} // get_instance()

} // Lity_Cache_Db
