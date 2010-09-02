<?php

/**
 * Base functions
 *
 * @author  Wibeset <support@wibeset.com>
 * @package core
 *
 */

/**
 * Get application instance
 *
 */
function app() {
	if (MODE == 'web')
		return Lity_Web::get_instance();
	else if (MODE == 'cron')
		return Lity_Cron::get_instance();
	else if (MODE == 'service')
		return Lity_Service::get_instance();
	return null;
} // app()

/**
 * Read configuration file(s)
 *
 * @deprecated use app()->read_config()
 */
function read_config() {
	foreach (func_get_args() as $config) {
		$config = explode('/', $config);	    
		$config[] = ucfirst(array_pop($config));	    
		@require_once ABSPATH.'app/config/'.implode('/', $config).'.php';
	}
} // read_config()

/**
 * Get current environment
 *
 */
function env() {
	return app()->parameters['lity']['env'];
} // env()

/**
 * Router methods
 *
 */

/**
 * Get router instance
 *
 */
function router() {
	return app()->router();
} // router()

/**
 * Logger methods
 *
 */

/**
 * Log data
 *
 * @param string $data debug    string to log
 * @param bool   $warn_by_email set to true to be warn by email
 *
 */
function logdata($data, $warn_by_email = false) {
	if (!isset(app()->config['logger']))
		return;
	require_once ABSPATH.'lity/core/Logger.php';
	return Lity_Logger::get_instance()->output($data, $warn_by_email);
} // logdata()

/**
 * Database methods
 *
 */

/**
 * Get a database instance
 *
 * @param string $name config's key name
 * @return database object
 *
 */
function db($name = 'default') {

	if (!isset(app()->config['db'][$name]))
		return null;

	$config = app()->config['db'][$name];

	switch ($config['type']) {

	 default:
	 case 'mysql':
		require_once ABSPATH."lity/db/Mysql.php";
		$db = Lity_Db_Mysql::get_instance($name, $config['host'], $config['name'], $config['user'], $config['password']);
		break;

		// @todo
	 case 'mysqli':
		break;

		// @todo
	 case 'sqlite':
		break;

	}

	return $db;

} // db()

/**
 * Load a new model (alias for model())
 *
 * @param  string $model_name name of the model
 * @return object $model      a new model
 *
 */
function model($model_name) {

	// Load model from Application
	if (file_exists(ABSPATH . 'app/models/'.ucfirst($model_name).'.php')) {
		require_once ABSPATH . 'app/models/'.ucfirst($model_name).'.php';
		$m = 'Model_'.ucfirst($model_name);
	}
	// Fallback to framework models
	else if (file_exists(ABSPATH.'lity/models/'.ucfirst($model_name).'.php')) {
		require_once ABSPATH.'lity/models/'.ucfirst($model_name).'.php';
		$m = 'Lity_Model_'.ucfirst($model_name);
	}

	$model = new $m();
	$model->initialize();

	return $model;

} // model()

/**
 * Session methods
 *
 */

/**
 * Get session instance
 *
 */
function session() {
	require_once(ABSPATH.'lity/session/Session.php');
	return Lity_Session::get_instance();
} // session()

/**
 * Helper methods
 *
 */

/**
 * Load an helper
 *
 * @param  string $helper_name name of the helper to load
 * @return object $helper
 *
 */
function helper($helper_name) {

	// Helper is already loaded, return it!
	if (isset(app()->parameters['lity']['helpers'][$helper_name]))
		return app()->parameters['lity']['helpers'][$helper_name];

	// Load helper from Application
	if (file_exists(ABSPATH . 'app/helpers/' . ucfirst($helper_name) . '.php')) {
		require_once ABSPATH . 'app/helpers/' . ucfirst($helper_name) . '.php';
		$helper = 'Helper_' . ucfirst($helper_name);
	}
	// Fallback to framework helpers
	else if (file_exists(ABSPATH.'lity/helpers/' . ucfirst($helper_name) . '.php')) {
		require_once ABSPATH.'lity/helpers/' . ucfirst($helper_name) . '.php';
		$helper = 'Lity_Helper_' . ucfirst($helper_name);
	}

	// Store helper's instance into Application's parameters
	app()->parameters['lity']['helpers'][$helper_name] = new $helper();

	return app()->parameters['lity']['helpers'][$helper_name];

} // helper()

/**
 * Plugin methods
 *
 */

/**
 * Load a plugin
 *
 * @param  string $plugin_name name of the plugin to load
 * @return object $plugin
 *
 */
function plugin($plugin_name) {

	// Load from Application
	if (file_exists(ABSPATH.'app/plugins/'.ucfirst($plugin_name).'.php')) {
		require_once ABSPATH.'app/plugins/'.ucfirst($plugin_name).'.php';
		$plugin = 'Plugin_'.ucfirst($plugin_name);
	}
	// Fallback to Lity plugins
	else if (file_exists(ABSPATH.'lity/plugins/'.ucfirst($plugin_name).'.php')) {
		require_once ABSPATH.'lity/plugins/'.ucfirst($plugin_name).'.php';
		$plugin = 'Lity_Plugin_'.ucfirst($plugin_name);
	}

	return new $plugin();

} // plugin()

/**
 * Services methods
 *
 */

/**
 * Load a service
 *
 * @param  string $service_name name of the service to load
 * @return object $service
 *
 */
function service($service_name) {

	// Load from Application
	if (file_exists(ABSPATH.'app/services/'.ucfirst($service_name).'.php')) {
		require_once ABSPATH.'app/services/'.ucfirst($service_name).'.php';
		$service = 'Service_'.ucfirst($service_name);
	}
	// Fallback to Lity plugins
	else if (file_exists(ABSPATH.'lity/services/'.ucfirst($service_name).'.php')) {
		require_once ABSPATH.'lity/services/'.ucfirst($service_name).'.php';
		$service = 'Lity_Service_'.ucfirst($service_name);
	}

	return new $service();

} // service()

/**
 * Error methods
 *
 */

/**
 * Get/Set an error
 *
 * @param  string $error_name  error's name
 * @param  string $error_value error's value
 * @return string              error's value
 *
 */
function error($error_name, $error_value = null) {

	// Set an error
	if ($error_value != null) {
		app()->parameters['lity']['errors'][$error_name] = $error_value;
		app()->views['error_'.$error_name] = $error_value;
	}

	if (isset(app()->parameters['lity']['errors'][$error_name]))
		return app()->parameters['lity']['errors'][$error_name];

	return false;

} // error()

/**
 * Get all errors
 *
 */
function errors() {
	return (isset(app()->parameters['lity']['errors']) ? app()->parameters['lity']['errors'] : array());
} // errors()

/**
 * Get number of error(s)
 *
 */
function errors_count() {
	return (isset(app()->parameters['lity']['errors']) ? count(app()->parameters['lity']['errors']) : 0);
} // errors_count()

/**
 * Has application failed depending on error(s)
 *
 */
function has_failed() {
	if (errors_count() > 0)
		return true;
	return false;
} // has_failed()

/**
 * Translation
 *
 */

/**
 * Get/Set current language
 *
 * @see <a href="http://php.net/manual/en/function.setlocale.php">PHP: setlocale</a>
 * @param  string $lang language
 * @return string       current language
 *
 */
function lang($lang = null) {
	if ($lang != null) {
		app()->parameters['lity']['lang'] = $lang;
		setlocale(LC_ALL, $lang);
	}
	return isset(app()->parameters['lity']['lang']) ? app()->parameters['lity']['lang'] : 'en_US';
} // lang()

/**
 * Translate
 *
 * @param  string $language_file file to load
 * @param  string $str           string to translate
 * @param  array  $place_holders replacements
 * @return string                string translate or untranslate
 *
 */
function __($language_file, $str, $place_holders = array()) {

	if (!isset(app()->parameters['lity']['languages'][$language_file])) {
		if ($keys = @include_once(ABSPATH.'app/languages/'.lang().'/'.ucfirst($language_file).".php"))
			app()->parameters['lity']['languages'][$language_file] = $keys;
		else
			return $str;
	}

	if (!isset(app()->parameters['lity']['languages'][$language_file][$str]))
		return $str;

	$str = app()->parameters['lity']['languages'][$language_file][$str];

	if (strstr($str, "{{")) {
		if (preg_match_all('/{{([a-zA-Z0-9_\/]*)}}/', $str, $matchs)) {
	    foreach ($matchs[1] as $mv) {
				if (isset($place_holders[$mv])) {
					$str = str_replace("{{".$mv."}}", $place_holders[$mv], $str);
				}
	    }
		}
	}

	return $str;

} // __()

/**
 * Translate and echoing
 *
 * @param  string $language_file file to load
 * @param  string $str           string to translate
 * @param  array  $place_holders replacements
 *
 */
function _e($language_file, $str, $place_holders = array()) {
	echo __($language_file, $str, $place_holders);
} // _e()

/**
 *
 */
function _n($singular, $plural, $cnt) {
	return ($cnt > 1 ? $plural : $singular);
} // _n()

/**
 * Views / Layouts
 *
 */

/**
 * Set layout
 *
 * @param string|bool $layout_name layout's name
 *                                 set to false to not render layout
 *
 */
function use_layout($layout_name) {
	app()->parameters['lity']['layout'] = $layout_name;
} // use_layout()

/**
 * Change layout content type
 * Default is text/html
 *
 * @param string $type content type
 *
 */
function use_layout_type($type) {
	app()->parameters['lity']['content_type'] = $type;
} // use_layout_type()

/**
 * Change current view
 *
 * @param string $view_name
 *
 */
function use_view($view_name) {
	app()->parameters['lity']['view'] = $view_name;
} // use_view()

/**
 * Render a partial
 *
 * @param  string $partial_name partial's name
 * @param  array  $values       values to replace into partial
 * @return string               HTML
 *
 */
function render_partial($partial_name, $values = array()) {

	$partial_name = explode('/', $partial_name);
	$partial_view = '_'.array_pop($partial_name);
	$partial_name = implode('/', $partial_name).'/'.$partial_view;

	$partial = app()->view();
	$partial->set_type('partial');
	$partial->set_template($partial_name);

	// render html
	return $partial->render($values);

} // render_partial()

/**
 * Add a css file to layout
 *
 */
function add_css() {
	foreach (func_get_args() as $cssName)
		app()->view()->add_css($cssName);
} // add_css()

/**
 * Add a js file to layout
 *
 */
function add_js() {
	foreach (func_get_args() as $jsName)
		app()->view()->add_js($jsName);
} // add_js()

/**
 * Cache methods
 *
 */

/**
 * Get cache
 *
 * You must add config 'cache' to your config:
 *
 * $config['cache'] = array('default' => array('type' => string, // file, memcached, apc or db
 *                                             'host' => string, // if memcached or db
 *                                             'port' => int,    // if memcached
 *                                             'db_name' => string,  // database name
 *                                             'db_user' => string,
 *                                             'db_password' => string
 *                                             ),
 *                          );
 *
 *
 * @param string $name config's key name
 * @return Cache
 *
 */
function cache($name = 'default') {

	if (!isset(app()->config['cache'][$name]))
		return null;

	$config = app()->config['cache'][$name];

	switch ($config['type']) {

	 default:
	 case 'file':
		require_once ABSPATH."lity/cache/File.php";
		$cache = Lity_Cache_File::get_instance();
		break;

	 case 'memcache':
		require_once ABSPATH."lity/cache/Memcache.php";
		$cache = Lity_Cache_Memcache::get_instance($name, $config['host'], $config['port']);
		break;

	 case 'apc':
		require_once ABSPATH."lity/cache/APC.php";
		$cache = Lity_Cache_Apc::get_instance($name);
		break;

	 case 'db':
		require_once ABSPATH."lity/cache/Db.php";
		$cache = Lity_Cache_Db::get_instance($name, $config['name']);
		break;

	 case 'html':
		require_once ABSPATH."lity/cache/Html.php";
		$cache = Lity_Cache_Html::get_instance();
		break;

	}

	return $cache;

} // cache()

/**
 * HTTP redirection
 *
 */
function redirect_to($destination = null) {

	if ($destination === null)
		$destination = app()->config['urlbase'];
	else if (substr($destination, 0, 4) != 'http')
		$destination = app()->config['urlbase'].$destination;

	if (isset(app()->route['ajax']) && app()->route['ajax'] == true)
		echo "<script type=\"text/javascript\">window.location = '".$destination."';</script>";
	else
		header('Location: '. $destination);

	exit;

} // redirect_to()

/**
 * Redirect to 404 page
 * 
 */
function redirect_to_404()
{
	if (!empty(app()->config['404']))
		redirect_to(app()->config['404']);
	
	redirect_to('');
	
} // redirect_to_404()
