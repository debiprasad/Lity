<?php

/**
 * Db
 * 
 * @author  Wibeset <support@wibeset.com>
 * @package db
 * 
 */

abstract class Lity_Db
{
	/**
	 * @var $_is_connected
	 */
	protected $_is_connected = false;
	
	/**
	 * @var $_db_link
	 */
	protected $_db_link;
	
	/**
	 * @var $_host
	 */
	protected $_host;

	/**
	 * @var $_user
	 */
	protected $_user;

	/**
	 * @var $_password
	 */
	protected $_password;
	
	/**
	 * @var $_name
	 */
	protected $_name;
		
	/**
	 * @var $_query
	 */
	protected $_query;
	
	/**
	 * Constructor
	 * 
	 * @param string $host
	 * @param string $user
	 * @param string $password
	 * 
	 */
	public function __construct($host, $name, $user, $password)
	{
		$this->_host = $host;
		$this->_name = $name;
		$this->_user = $user;
		$this->_password = $password;
				
	} // __construct()

	/**
	 * Is connected?
	 * 
	 */
	public function is_connected()
	{
		return $this->_is_connected;
		
	} // is_connected()

	/**
	 * Connect to database
	 * 
	 */
	abstract public function connect();

	/**
	 * Selected database
	 * 
	 * @param string $name
	 * 
	 */
	abstract public function select_db($name);

	/**
	 * Set query
	 * 
	 * @param string $query
	 * 
	 */
	public function set_query($query)
	{
		$this->_query = $query;
		$this->_result = false;
		
	} // set_query()

	/**
	 * Get query
	 * 
	 * @return string query
	 * 
	 */
	public function get_query()
	{
		return $this->_query;
		
	} // get_query()

	/**
	 * Run current query
	 * 
	 * @return bool result
	 * 
	 */
	abstract public function run_query();

	/**
	 * Get number of affected rows by last query
	 * 
	 * @return mixed affected rows
	 * 
	 */
	abstract public function get_affected_rows();

	/**
	 * Get number of rows
	 *
	 * @return mixed number of rows
	 * 
	 */
	abstract public function get_num_rows();

	/**
	 * Fetch result
	 * 
	 * @param string $method
	 * @return mixed rows or false
	 * 
	 */
	abstract public function fetch_result($method = 'assoc');

	/**
	 * Free result
	 * 
	 */
	abstract public function free_result();

	/**
	 * Get query's result
	 * 
	 * @return bool
	 * 
	 */
	public function get_query_result()
	{
		return $this->_result;
		
	} // get_query_result()

	/**
	 * Get last insert id
	 * 
	 * @return mixed id
	 * 
	 */
	abstract public function last_insert_id();

	/**
	 * Shutdown database connection
	 * 
	 */
	abstract public function shutdown();

} // Lity_Db
