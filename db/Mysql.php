<?php

/**
 * MySQL
 * 
 * @author  Wibeset <support@wibeset.com>
 * @package db
 * 
 */

require_once ABSPATH."lity/db/Db.php";

class Lity_Db_Mysql extends Lity_Db
{
	/**
	 * Connect to database
	 * 
	 */
	public function connect()
	{
		$this->_db_link = mysql_connect(
																		$this->_host,
																		$this->_user,
																		$this->_password
																		);
		
		$this->_is_connected = true;
		
		$this->select_db($this->_name);
		
	} // connect()
	
	/**
	 * Selected database
	 * 
	 * @param string $name
	 * 
	 */
	public function select_db($name)
	{
		mysql_select_db($name, $this->_db_link);
		
	} // select_db()
	
	/**
	 * Run current query
	 * 
	 * @return bool result
	 * 
	 */
	public function run_query()
	{
		if (!$this->is_connected())
			$this->connect();

		$time_start = microtime(true);
		
		// Run query...
		$this->_result = mysql_query($this->_query, $this->_db_link);
		
		$time_stop = microtime(true) - $time_start;
		
		// Log query
		if (isset(app()->config['logger']) && isset(app()->config['logger']['queries']) && app()->config['logger']['queries'] == true)
			logdata("Running query '".$this->get_query()."' (".sprintf("%01.5f", $time_stop)."s)");
		
		return $this->_result ? true : false;
		
	} // run_query()
	
	/**
	 * Get number of affected rows by last query
	 * 
	 * @return mixed affected rows
	 * 
	 */
	public function get_affected_rows()
	{
		$affected_rows = mysql_affected_rows($this->_db_link);
		return $affected_rows != -1 ? $affected_rows : false;
		
	} // get_affected_rows()
	
	/**
	 * Get number of rows
	 *
	 * @return mixed number of rows
	 * 
	 */
	public function get_num_rows()
	{
		if (!$this->_result)
			return false;
		
		return mysql_num_rows($this->_result);
		
	} // get_num_rows()
	
	/**
	 * Fetch result
	 * 
	 * @param string $method
	 * @return mixed rows or false
	 * 
	 */
	public function fetch_result($method = 'assoc')
	{
		// No result
		if (!$this->_result)
			return false;

		switch ($method) {
			
			// Fetch a result row as an associative array
		 case 'assoc':
		 default:		
	    $row = mysql_fetch_assoc($this->_result);
			break;
			
			// Get a result row as an enumerated array
		 case 'row':
			$row = mysql_fetch_row($this->_result);
			break;

			// Fetch a result row as an object
		 case 'object':
			$row = mysql_fetch_object($this->_result);
			break;
			
		}
		
		return $row;
		
	} // fetch_result()
	
	/**
	 * Free result
	 * 
	 */
	public function free_result()
	{
		mysql_free_result();
		
	} // free_result()
	
	/**
	 * Get last insert id
	 * 
	 * @return mixed id
	 * 
	 */
	public function last_insert_id()
	{
		$last_id = mysql_insert_id($this->_db_link);
		return ((int)$last_id > 0 ? $last_id : false);
		
	} // last_insert_id()
	
	/**
	 * Escape string
	 * 
	 * @param string $string
	 * @return string Escaped string
	 * 
	 */
	public function escape($string)
	{
		return mysql_real_escape_string($string, $this->_db_link);
		
	} // escape()
	
	/**
	 * Shutdown database connection
	 * 
	 */
	public function shutdown()
	{
		if ($this->is_connected()) {
			
	    $this->_is_connected = false;
	    $this->free_result();
	    mysql_close();
			
		}
		
	} // shutdown()
	
	/**
	 *
	 */

	protected static $_instance = array();

	public function get_instance($name, $host, $db_name, $user, $password)
	{
		if (!isset(self::$_instance[$name]))
			self::$_instance[$name] = new self($host, $db_name, $user, $password);

		return self::$_instance[$name];

	} // get_instance()		

} // Lity_Db_Mysql
