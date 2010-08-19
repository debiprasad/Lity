<?php

/**
 * Web Service Context
 *
 * @author Wibeset <support@wibeset.com>
 * @package lity
 *
 */

define('MODE', 'service');

require_once ABSPATH.'lity/core/Application.php';

class Lity_Service extends Lity_Application
{
    /**
	 * @var $_format
	 */
	protected $_format = 'json';

	/**
	 * @var $_result
	 */
	protected $_result = '';

	/**
	 * Run application...
	 *
	 */
	public function run()
	{		
	    // run application
		parent::run();		
		
		// execute router to define route settings
		$this->execute_router();
		
		// execute controller/action
		$this->_result = $this->execute_controller();
		
		// output result
		$this->output();
		
		// shutdown procedure
		$this->shutdown();
		
	} // run()

	/**
	 * Initialize application
	 *
	 */
	protected function initialize() 
	{
	    // request & format
		$request = explode('.', REQUEST);

		if (count($request) == 0) {
			$request = $request[0];
		} else {
			if (strstr($request[1], '?')) 
			    $this->_format = mb_substr($request[1], 0, mb_strpos($request[1], '?'));
			else 
			    $this->_format = $request[1];
			$request = $request[0];
		}

		// request url
		$this->parameters['lity']['request'] = $request;

		// environment
		if (defined('ENV') && ENV != '')
			$this->parameters['lity']['env'] = ENV;
		else
			$this->parameters['lity']['env'] = 'development';

		// post parameters?
		if (!empty($_POST))
			$this->_post = $_POST;
		// get parameters?
		if (!empty($_GET))
	    $this->_get = $_GET;
		// file(s) parameters?
		if (!empty($_FILES))
			$this->_files = $_FILES;

	} // initialize()

	/**
	 * Execute controller
	 *
	 */
	protected function execute_controller()
	{
	    $map_to = (isset($this->route['map_to']) ? $this->route['map_to'].'/' : '');
		$controller_name = $this->route['controller'];
		$action_name = $this->route['action'];

		// controller exists?
		if (!file_exists(ABSPATH.'app/controllers/'.$map_to.ucfirst($controller_name).'.php')) {
			redirect_to('');
		}		

		// log execution...
		if (isset(app()->config['logger']) && isset(app()->config['logger']['core']) 
		    && app()->config['logger']['core'] == true) {
			logdata('Executing controler '.$map_to.$controller_name.'/'.$action_name.' from request '.
			        $this->route['request']);
		}

		//
		require_once ABSPATH.'app/controllers/'.$map_to.ucfirst($controller_name).'.php';		
		$controller_class_name = 'Controller_'.ucfirst($controller_name);
		$this->controller = new $controller_class_name();		

		// initialize controller
		$this->controller->initialize();

		if (!method_exists($this->controller, $action_name))
			redirect_to('');

		// execute action
		return $this->controller->{$action_name}();

	} // execute_controller()

	/**
	 * Output result
	 *
	 */
	public function output()
	{
	    // header to sent
		header('Content-type: text/html; charset='.(isset($this->config['encoding']) ? $this->config['encoding'] : 'UTF-8'));

		// format result
		switch ($this->_format) {

		 case 'xml':
			break;

		 case 'php':
			echo serialize($this->_result);
			break;

		 case 'json':
		 default:
			echo json_encode($this->_result);
			break;

		}

	} // output()

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

} // Lity_Service
