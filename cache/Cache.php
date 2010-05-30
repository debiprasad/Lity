<?php

/**
 * Cache
 * 
 * @author Wibeset <support@wibeset.com>
 * @package cache
 *
 */

abstract class Lity_Cache
{
	/**
	 * @var $_type
	 */
	protected $_type = 'file';
	
	/**
	 * @var $_object
	 */
	protected $_object;
	
	/**
	 * Get cache type
	 * 
	 * @return string type
	 * 
	 */
	protected function get_type()
	{
		return $this->_type;
	} // get_type()
	
	/**
	 * Set cache type
	 * 
	 * @param string $name
	 * 
	 */
	protected function set_type($type)
	{
		$this->_type = $type;
		
	} // set_type()

	/**
	 * Get cache object
	 * 
	 * @return object object
	 * 
	 */
	protected function get_object()
	{
		return $this->_object;
		
	} // get_object()
	
	/**
	 * Set cache object
	 * 
	 * @param object $object
	 * 
	 */
	protected function set_object($object)
	{
		$this->_object = $object;
		
	} // set_object()

	/**
	 * Get
	 *
	 */
	//abstract public function get($name, $maxtime = 0);

	/**
	 * Set
	 *
	 */
	abstract public function set($name, $data, $maxtime = 0);
	
	/**
	 * Delete
	 *
	 */
	abstract public function delete($name);

	/**
	 * Clear
	 *
	 */
	abstract public function clear();
	
} // Lity_Cache
