<?php

/**
 * Cache - Memcache
 *
 * @see <a href="http://ca.php.net/manual/en/book.memcache.php">PHP: Memcache</a>
 * @author Wibeset <support@wibeset.com>
 * @package cache
 *
 */

require_once ABSPATH."lity/cache/Cache.php";

class Lity_Cache_Memcache extends Lity_Cache
{
	/**
	 * @var $_host
	 */
	private $_host;

	/**
	 * @var $_port
	 */
	private $_port;

	/**
	 * Constructor
	 *
	 */
	public function __construct($host, $port)
	{
		$this->_host = $host;
		$this->_port = $port;

		$this->set_type('memcached');
		$this->set_object(new Memcache);
		$this->get_object()->connect($host, $port);

	} // __construct()

	/**
	 * Close connection
	 *
	 * @return bool success
	 *
	 */
	public function close()
	{
		$this->get_object()->close();
		
	} // close()

	/**
	 * Get
	 *
	 * @param string $name
	 * @return mixed
	 *
	 */
	public function get($name)
	{
		return $this->get_object()->get($name);
		
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
		$this->get_object()->set($name, $data, $maxtime);
		
	} // set()

	/**
	 * Delete from cache
	 *
	 * @param string $name
	 *
	 */
	public function delete($name)
	{
		return $this->get_object()->delete($name);
		
	} // delete()

	/**
	 * Clear
	 *
	 * @return bool success
	 *
	 */
	public function clear()
	{
		return $this->get_object()->flush();
		
	} // clear()

	/**
	 * Increment item's value
	 *
	 * @param string $name
	 * @param int    $value Increment the item by value
	 * @return mixed the item value or false on failure
	 *
	 */
	public function increment($name, $value = 1)
	{
		$this->get_object()->increment($name, $value);
		
	} // increment()

	/**
	 * Decrement item's value
	 *
	 * @param string $name
	 * @param int    $value Decrement the item by value
	 * @return mixed the item value or false on failure
	 *
	 */
	public function decrement($name, $value = 1)
	{
		$this->get_object()->decrement($name, $value);
		
	} // decrement()

	/**
	 * Get server status
	 *
	 * @return int 0 if server is failed, non-zero otherwise
	 *
	 */
	public function get_server_status()
	{
		return $this->get_object()->getServerStatus($this->_host, $this->_port);
		
	} // get_server_status()

	/**
	 * Get statistics of the server
	 *
	 * @param string $type   The type of statistics to fetch. Valid values are {reset, malloc, maps, cachedump, slabs, items, sizes}.
	 * @param int    $slabid Used in conjunction with type  set to cachedump to identify the slab to dump from. The cachedump command ties up the
	 *                       server and is strictly to be used for debugging purposes.
	 * @param int    $limit  Used in conjunction with type  set to cachedump to limit the number of entries to dump.
	 * @return array
	 *
	 */
	public function get_stats($type = null, $slabid = null, $limit = 100)
	{
		return $this->get_object()->getStats($type, $slabid, $limit);
		
	} // get_stats()

	/**
	 * Get version of the server
	 *
	 * @return string
	 *
	 */
	public function get_version()
	{
		return $this->get_object()->getVersion();
		
	} // get_version()

	/**
	 * Enable automatic compression of large values
	 *
	 * @param int   $threshold   Controls the minimum value length before attempting to compress automatically.
	 * @param float $min_savings Specifies the minimum amount of savings to actually store the value compressed.
	 *                           The supplied value must be between 0 and 1. Default value is 0.2 giving a minimum 20% compression savings.
	 * @return bool success
	 *
	 */
	public function set_compress_threshold($threshold, $min_savings = 0.2)
	{
		return $this->get_object()->setCompressThreshold($threshold, $min_savings);
		
	} // set_compress_treshold()

	/**
	 * Destructor
	 *
	 */
	public function __destruct()
	{
		$this->close();
		
	} // _destruct()

	/**
	 *
	 */

	protected static $_instance = array();

	public function get_instance($name, $host, $port)
	{
		if (!isset(self::$_instance[$name]))
			self::$_instance[$name] = new self($host, $port);

		return self::$_instance[$name];

	} // get_instance()

} // Lity_Cache_Memcache
