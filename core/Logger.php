<?php

/**
 * Logger
 * 
 * @author Wibeset <support@wibeset.com>
 * @package core
 * @todo Add method output_to_database
 *       Warn by email
 *
 */

class Lity_Logger
{
	/**
	 * Output to...
	 */
	private $_output_to = 'file';
	
	/**
	 * Constructor
	 * 
	 */
	public function __construct()
	{
		// log in...
		$this->_output_to = (isset(app()->config['logger']['in']) && 
												 ($output_to = app()->config['logger']['in']) !== null ? $output_to : 'file');

	} // __construct()
	
	/**
	 * Log data
	 * 
	 * @param string $data          data to log
	 * @param bool   $warn_by_email be warned by email
	 * 
	 */
	public function output($data, $warn_by_email = false)
	{
		// Output string to ...
		switch ($this->_output_to) {
			
			// Output to database
		 case 'database':
			$this->_output_to_database($data);
			break;
			
			// Output into file
		 case 'file':
		 default:
			$this->_output_to_file($data);
			break;
						
		}
		
		// Send a warning by mail
		if ($warn_by_email) {
		}
		
	} // output()
	
	/**
	 * Log data to file
	 * 
	 * @param string $data data to log
	 * 
	 */
	private function _output_to_file($data)
	{
		$output_file = fopen(ABSPATH.'logs/'.env().'.log', 'a+');
		fwrite($output_file, "[".date('m/d/Y H:i:s')."] ".$data."\n");
		fclose($output_file);
		
	} // _output_to_file()
	
	/**
	 * Log data to database
	 * 
	 * @param string $data data to log
	 * 
	 */
	private function _output_to_database($data)
	{
	} // _output_to_database()	
	
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

} // Lity_Logger
