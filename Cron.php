<?php

/**
 * Cron
 * 
 * @author Wibeset <support@wibeset.com>
 * @package lity
 *
 */

define('MODE', 'cron');

require_once ABSPATH.'lity/core/Application.php';

class Lity_Cron extends Lity_Application
{
    /**
	 * Run application
	 * 
	 */
	public function run()
	{
	    // run application
		parent::run();
				
		// execute controller/action
		$this->execute_controller();
		
		// shutdown procedure
		$this->shutdown();
		
	} // run()

	/**
	 * Initialize application
	 */
	protected function initialize()
	{
	    // args
		$args = $_SERVER['argv'];

		array_shift($args);

		// environment
		if (isset($args[0]))
			$this->parameters['lity']['env'] = array_shift($args);
		else
			die("Environment must be set!\nUsage: cron.php <environment> <request> <arg1> <arg2> <argn>\n");

		// request url
		if (isset($args[0]))
			$this->parameters['lity']['request'] = array_shift($args);
		else
			die("Request must be set!\nUsage: cron.php <environment> <request> <arg1> <arg2> <argn>\n");

		// parameters?
		if (count($args) > 0) {
			
	    $cnt = 0;
	    foreach ($args as $arg) {				
				if (strpos($arg, ':')) {
					$arg = explode(':', $arg);
					$this->route[$arg[0]] = $arg[1];
				} else {
					$cnt++;
					$this->route['arg'.$cnt] = $arg;
				}
	    }
			
		}
		
	} // initialize()

	/**
	 * Execute controller
	 * 
	 */
	protected function execute_controller()
	{
	    $request = explode('/', $this->parameters['lity']['request']);

		$controller_name = $request[0];
		$action_name = $request[1];
		
		// controller exists?
		if (!file_exists(ABSPATH.'app/controllers/'.ucfirst($controller_name).'.php')) {
	    die("Controller ".$controller_name." doesn't exists!");
		}

		//
		require_once ABSPATH.'app/controllers/'.ucfirst($controller_name).'.php';
		$controller_class_name = 'Controller_'.ucfirst($controller_name);
		$this->controller = new $controller_class_name();

		// initialize controller
		$this->controller->initialize();

		if (!method_exists($this->controller, $action_name))
			die("Action ".$action_name." doesn't exists!");

		// execute action
		$this->controller->{$action_name}();

	} // execute_controller()
	
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

} // Lity_Cron