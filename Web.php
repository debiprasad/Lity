<?php

/**
 * Web Application Context
 *
 * @package Lity
 * @author Wibeset <support@wibeset.com>
 *
 */

define('MODE', 'web');

require_once ABSPATH.'lity/core/Application.php';

class Lity_Web extends Lity_Application
{
	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
	} // __construct()

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
		$this->execute_controller();

		// render layout/view
		$this->render();

		// shutdown procedure
		$this->shutdown();

	} // run()

	/**
	 * Initialize
	 * Set request and environment. Store posts/get/files parameters.
	 *
	 */
	protected function initialize()
	{
		// request url
		if (defined('REQUEST') && REQUEST != '')
		  $this->parameters['lity']['request'] = REQUEST;
		else
		  $this->parameters['lity']['request'] = '';

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
		// 
		$map_to = (isset($this->route['map_to']) ? $this->route['map_to'] : '');
		$map_to_dir = ($map_to != '' ? $map_to.'/' : '');
		$controller_name = $this->route['controller'];
		$action_name = $this->route['action'];

		// controller exists?
		if (!file_exists(ABSPATH.'app/controllers/'.$map_to_dir.ucfirst($controller_name).'.php')) {
			if (!empty(app()->config['404'])) {
			    $this->route['map_to'] = $map_to_dir = '';
			    $this->route['controller'] = $controller_name = app()->config['404'];
			    $this->route['action'] = $action_name = 'index';
			} else {
			    redirect('');
			}
		}

		// log execution...
		if (isset(app()->config['logger']) && isset(app()->config['logger']['core']) 
		    && app()->config['logger']['core'] == true) {
		  logdata('Executing controler '.$map_to_dir.$controller_name.'/'.$action_name.' from request '.$this->route['request']);
		}

		//
		require_once ABSPATH.'app/controllers/'.$map_to_dir.ucfirst($controller_name).'.php';
		$controller_class_name = 'Controller_'.($map_to != '' ? ucfirst($map_to).'_' : '').ucfirst($controller_name);
		$this->controller = new $controller_class_name();
		$this->controller->view = array();
		
		// action exists?
		if (!method_exists($this->controller, $action_name)) {
		    if (!empty(app()->config['404'])) {
		        $this->route['map_to'] = $map_to_dir = '';
		        $this->route['controller'] = $controller_name = app()->config['404'];
		        $this->route['action'] = $action_name = 'index';
		    } else {
		        redirect('');
		    }
		}

		// initialize controller
		$this->controller->initialize();

		// are we caching?
		$this->cache = isset($this->controller->actions_to_cache[$action_name]) ? true : false;

		// file to cache
		if ($this->cache) {
			$request = $this->parameters['lity']['request'];
			if (substr($request, -1, 1) == '/') $request = substr($request, 0, strlen($request)-1);
			$this->file_to_cache = ABSPATH.'cache/actions/'.lang().'/'.$request.'.html';
		}
		// update cache?
		$this->update_cache = $this->cache ? (@filemtime($this->file_to_cache) < time() - $this->controller->actions_to_cache[$action_name]) : false;

		// execute action
		if (!$this->cache || ($this->cache && $this->update_cache)) $this->controller->{$action_name}();

		$this->_view_parameters = $this->controller->view;

	} // execute_controller()

	/**
	 * Render action's template and layout's template
	 *
	 */
	protected function render()
	{
		// get page from cache
		if ($this->cache && !$this->update_cache) {
			$html = file_get_contents($this->file_to_cache);
		}
		// render view's template
		else {
			$this->view()->set_type('view');
			$html = $this->view()->render($this->_view_parameters);
		}

		// cache action
		if ($this->cache && $this->update_cache) {
			$dir = explode('/', $this->file_to_cache);
			$dir = array_slice($dir, 0, -1);
			if (count($dir) > 0) @mkdir(implode('/', $dir), 0777, true);
			file_put_contents($this->file_to_cache, $html);
		}

		// render layout's template
		// do not render if request is ajax or bare
		if (isset($this->parameters['lity']['layout']) && $this->parameters['lity']['layout'] != false && $this->route['ajax'] !== true && $this->route['bare'] !== true) {
			$this->view()->set_type('layout');
			$this->_view_parameters = array_merge($this->_view_parameters, array('action_content' => $html));
			$html = $this->view()->render($this->_view_parameters);
		}

		// RSS header's content-type
		if (isset($this->parameters['lity']['content_type']))
		  header('Content-type: '.$this->parameters['lity']['content_type']);
		// text/html default content-type
		else
		  header('Content-type: text/html; charset='.(isset($this->config['encoding']) ? $this->config['encoding'] : 'UTF-8'));

		// output html..
		echo $html;

	} // render()

	/**
	 * Delete an action's cache
	 *
	 */
	public function delete_action_cache($action)
	{
		$html_file = a('file');
		$html_file->set(ABSPATH.'cache/actions/'.lang().'/'.$action.'.html', false);

		if ($html_file->exists()) {
			$html_file->destroy();
		}

	} // delete_action_cache()

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

} // Lity_Web
