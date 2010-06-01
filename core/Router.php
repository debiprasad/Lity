<?php

/**
 * Router
 *
 * @author  Wibeset <support@wibeset.com>
 * @package core
 * 
 */

class Lity_Router
{
	/**
	 * @var $_params
	 */
	private $_params = array();
	
	/**
	 * @var $_routes
	 */
	private $_routes = array();

	/**
	 * @var $_url_replacement
	 */	
	private $_url_replacement = array(
									0 => array("*"),
									1 => array("[a-zA-Z0-9_]+")
									);

	/**
	 * Constructor
	 *
	 */
	public function __construct()
	{
	} // __construct()

	/**
	 * Get routes from cache
	 *
	 * @param string $version version number (ex: 1.2)
	 * @return bool
	 * 
	 */
	public function restore_from_cache($version)
	{
		$route_file = ABSPATH.'cache/routes.'.$version;
		
		if (file_exists($route_file)) {
			
			ob_start();
			include($route_file);
			$routes = ob_get_contents();
			ob_end_clean();

			$routes = @unserialize($routes);
			
			if (is_array($routes)) {
				$this->_routes = $routes;
				return true;
			}
			
		}

		return false;

	} // restore_from_cache()
	
	/**
	 * Save routes to cache
	 * 
	 * @param  string    $version version number (ex: 1.2)
	 * @throws Exception Unable to open file for writing
	 */
	public function save_to_cache($version)
	{
		$route_file = ABSPATH.'cache/routes.'.$version;
		
		if (!@file_put_contents($route_file, serialize($this->_routes))) {
			throw new Exception('Unable to open '.$route_file.' for writing');
		}
		
	} // save_to_cache()

	/**
	 * Add a route
	 *
	 * @param string $route  route's uri
	 * @param array  $params route's params
	 *
	 */
	public function add($route, $params = null)
	{
		// array of elements (ex.: :id, :method, etc)
		$elements = array();

		// explode the route to build a regular expression
		$routeex = explode('/', $route);

		$regex = "";
		$regexcpt = 1;

		// build the regular expression..
		foreach ($routeex as $rk => $rv) {
			
	    if ($rv != "" && $rv{0} == ":") {
				// :id, :method, etc
				if (strstr($rv, "(number)")) {
					$r = str_replace($rv, "([0-9]+)", $rv);
					$rv = str_replace("(number)", "", $rv);
				} else if ($str = strstr($rv, "(")) {
					$r = str_replace($rv, $str, $rv);
					$rv = str_replace($str, "", $rv);
				} else {
					$r = str_replace($rv, "([a-zA-Z0-9_]+)", $rv);
				}

				$elements[str_replace(':', '', $rv)] = $regexcpt;
	    } else {
				// other..
				$r = str_replace($rv, "({$rv})", $rv);
	    }
	    $regex .= $r."/";
	    $regexcpt++;
		}

		$regex = substr($regex, 0, strlen($regex) - 1);

		// replace %any% and %number% by regex..
		$regex = "%^".str_replace($this->_url_replacement[0], $this->_url_replacement[1], $regex)."(.*)$%";

		// add the new route...
		$this->_routes[] = array($route, $regex, $params, $elements);

	} // add()

	/**
	 * Find first route that match URL
	 *
	 */
	public function dispatch()
	{
		$url = app()->parameters['lity']['request'];

		$urlparams = array();

		if (substr($url, -1, 1) == "/")
			$url = substr($url, 0, strlen($url) - 1);

		$urlex = explode("/", $url);
		if (is_array($urlex)) {
	    if ($urlex[0] == "ajax" || $urlex[0] == 'bare') {
				$urlparams[$urlex[0]] = true;
				array_shift($urlex);
				$url = implode("/", $urlex);
	    }
		}

		// check if url fit with a route..
		foreach ($this->get_routes() as $route) {

	    list($routev, $regex, $params, $elements) = $route;

	    $urlparams = array();

	    // url fit... get the params and stop.
	    if (preg_match($regex, $url, $regs)) {

				// check for specials elements (ex.: "/:id/", "/:method/")
				foreach ($elements as $ek => $ev) {
					if (!isset($params[$ek]) || $params[$ek] == "")
						// element is not already in params, add it now..
						$params[$ek] = $regs[$ev];
				}

				// additionnal params
				$urlex   = explode("/", $url);
				$regexex = explode("/", $regex);
				if (count($urlex) > count($regexex)) {
					$additionnal_params = array_slice($urlex, count($regexex), count($urlex) - count($regexex));
					$acpt = 1;
					foreach ($additionnal_params as $apk => $apv) {
						if ($apv != '')
							$params["arg".$acpt] = $apv;
						$acpt++;
					}
				}

				// set params
				$urlparams = $params;
				$urlparams['request'] = $url;
				$urlparams['route'] = $routev;

				// get out of the "foreach"..
				break;

	    }

		}

		foreach ($urlparams as $upk => $upv) {
	    app()->route[$upk] = strtolower($upv);
		}
		
	} // dispatch()

	/**
	 * Get routes
	 * 
	 * @return array routes
	 * 
	 */
	public function get_routes()
	{
		return $this->_routes;
		
	} // get_routes()

	/**
	 * 
	 * 
	 */
	
	protected static $_instance = null;

	public static function get_instance()
	{
		if (self::$_instance == null) {
	    self::$_instance = new self();
		}
		
		return self::$_instance;
		
	} // get_instance()

} // Lity_Router
