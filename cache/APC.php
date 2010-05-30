<?php

/**
 * Cache - APC
 * 
 * @see <a href="http://ca.php.net/manual/en/book.apc.php>PHP: APC</a>
 * @author Wibeset <support@wibeset.com>
 * @package cache
 *
 */

require_once ABSPATH."lity/cache/Cache.php";	

class Lity_Cache_Apc extends Lity_Cache
{
	/**
	 * Constructor
	 *
	 */
	public function __construct($host, $port)
	{
		$this->set_type('apc');
		$this->set_object(null);

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
		return apc_fetch($name);
		
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
		apc_store($name, $data, $maxtime);
		
	} // set()

	/**
	 * Delete from cache
	 *
	 * @param string $name
	 *
	 */
	public function delete($name)
	{
		return apc_delete($name);
		
	} // delete()

	/**
	 * Stores a file in the bytecode cache, bypassing all filters
	 *
	 * @see <a href="http://www.php.net/manual/en/function.apc-compile-file.php">PHP apc_compile_file</a>
	 * @param string $filename
	 * @return bool success
	 *
	 */
	public function compile_file($filename)
	{
		return apc_compile_file($filename);
		
	} // compile_file()

	/**
	 * Stores a directory in the bytecode cache, bypassing all filters
	 *
	 * @param string $root
	 * @param bool   $recursively
	 * @return bool success
	 *
	 */
	public function compile_dir($root, $recursively = true)
	{
		$compiled = true;

		switch ($recursively) {

			// Compile files in subdirectories
		 case true:
			foreach (glob($root.DIRECTORY_SEPARATOR.'*', GLOB_ONLYDIR) as $dir) {
				$compiled = $compiled && $this->compile_dir($dir, $recursively);
			}

			// Compile files in root directory
		 case false:
			foreach (glob($root.DIRECTORY_SEPARATOR.'*.php') as $filename) {
				$compiled = $compiled && $this->compile_file($filename);
			}
			break;

		}

		return $compiled;

	} // compile_dir()

	/**
	 * Get infos
	 *
	 * @see <a href="http://php.net/manual/en/function.apc-cache-info.php">PHP apc_cache_info</a>
	 * @param string $type    null, user or filehits
	 * @param bool   $limited If limited  is TRUE, the return value will exclude the individual list of cache entries. 
	 *                        This is useful when trying to optimize calls for statistics gathering. 
	 * @return array data
	 *
	 */
	public function info($type = null, $limited = false)
	{
		return apc_cache_info($type, $limited);
		
	} // info()
	
	/**
	 * Clear
	 * 
	 * @return bool success
	 * 
	 */
	public function clear()
	{
		return apc_clear_cache();
		
	} // clear()
	
	/**
	 * Defines a set of constants for retrieval and mass-definition 
	 * 
	 * @param string $key            The key serves as the name of the constant set being stored.
	 * @param array  $contants       An associative array of constant_name => value  pairs.
	 * @param bool   $case_sensitive 
	 * @return bool success
	 * 
	 */
	public function define_constants($key, $constants, $case_sensitive = true)
	{
		return apc_define_constants($key, $constants, $case_sensitive = true);
		
	} // define_constants()
	
	/**
	 * Loads a set of constants from the cache
	 * 
	 * @param string $key            The key serves as the name of the constant set being stored.
	 * @param bool   $case_sensitive 
	 * @return bool success
	 * 
	 */
	public function load_constants($key, $case_sensitive = true)
	{
		return apc_load_constants($key, $case_sensitive = true);
		
	} // load_constants()
	
	/**
	 * Retrieves APC's Shared Memory Allocation information 
	 * 
	 * @param bool $limited 
	 * @return mixed Array of Shared Memory Allocation data; FALSE on failure. 
	 * 
	 */
	public function sma_info($limited = false)
	{
		return apc_sma_info($limited);
		
	} // sma_info();

	/**
	 *
	 */

	protected static $_instance = array();

	public function get_instance($name)
	{
		if (!isset(self::$_instance[$name]))
			self::$_instance[$name] = new self($host, $port);

		return self::$_instance[$name];

	} // get_instance()

} // Lity_Cache_Apc
