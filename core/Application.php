<?php

/**
 * Application
 *
 */

require_once ABSPATH.'lity/core/Base.php';

class Lity_Application
{
	/**
	 * @var $config
	 */
	public $config = array(
												 'encoding' => 'UTF-8'
												 );

	/**
	 * @var $view
	 */
	protected $_view_parameters = array();

	/**
	 * @var $parameters
	 */
	public $parameters = array(
														 'lity' => array(
																						 'layoutType' => 'html'
																						 )
														 );

	/**
	 * @var $_post, $_get, $_files
	 */
	public $_post, $_get, $_files = array();

	/**
	 * @var $route
	 */
	public $route = array(
												'ajax' => false,
												'bare' => false,
												'request' => '',
												'controller' => 'home',
												'action' => 'index'
												);

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
	} // __construct()

	/**
	 * Run applicat>ion...
	 *
	 */
	public function run()
	{
		// character encoding
		if (!defined('ENCODING'))
			define('ENCODING', 'UTF-8');

		// initialize qoz context
		$this->initialize();

		// load app configs
		$this->load_configs();

	} // run()

	/**
	 * Set parameter
	 *
	 * @param array $parameters
	 *
	 */
	public function set_parameters($parameters)
	{
		foreach ($parameters as $parameter_name => $parameter_value) {
			$this->parameters[$parameter_name] = $parameter_value;
		}

	} // set_parameters()

	/**
	 * Load configuration files
	 *
	 */
	protected function load_configs()
	{
		// Load main application config
		require_once ABSPATH.'app/config/Application.php';
		$this->config = $config;

		// Load environment config
		require_once ABSPATH.'app/config/'.ucfirst($this->parameters['lity']['env']).'.php';
		$this->config += $config;

	} // load_configs()

	/**
	 * Read a config file
	 *
	 */
	public function read_config()
	{
		foreach (func_get_args() as $config_name) {

			$config_name = explode('/', $config_name);
			$filename = ucfirst(array_pop($config_name));
			$config_name = implode('/', $config_name).'/'.$filename;

			@require ABSPATH.'app/config/'.$config_name.'.php';

			$this->config += $config;

		}

	} // read_config()

	/**
	 * Execute router...
	 *
	 */
	protected function execute_router()
	{
		$request = router()->dispatch();
		
	} // execute_router()

	/**
	 * @var $_router, $_view
	 */
	protected $_router,
		$_view;

	/**
	 * Get router
	 *
	 * @return object router
	 *
	 */
	public function router()
	{
		require_once(ABSPATH.'lity/core/Router.php');
		return Lity_Router::get_instance();

	} // router()

	/**
	 * Get view
	 *
	 * @return object view
	 *
	 */
	public function view()
	{
		if ($this->_view == null) {
	    require_once(ABSPATH.'lity/view/Phtml.php');
	    $this->_view = new Lity_view_Phtml();
		}

		return $this->_view;

	} // view()

	/**
	 * Shutdown application
	 *
	 */
	protected function shutdown()
	{
		//
		if (isset(app()->config['logger']) && isset(app()->config['logger']['core']) && app()->config['logger']['core'] == true) {
			logdata("End.\n");
		}

	} // shutdown()

} // Lity_Application
