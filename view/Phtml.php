<?php

/**
 * PHP HTML
 * 
 * @author Wibeset <support@wibeset.com>
 * @package view
 *
 */

class Lity_View_Phtml
{
	/**
	 * @var $_type
	 */
	protected $_type = 'view';
	
	/**
	 * @var $_layout
	 */
	protected $_layout;
	
	/**
	 * @var $_view
	 */
	protected $_view;
	
	/**
	 * @var $_component
	 */
	protected $_component;
	
	/**
	 * @var $_partial
	 */
	protected $_partial;
	
	/**
	 * @var $_tpl
	 */
	protected $_tpl;	
	
	/**
	 * @var $_parameters
	 */
	protected $_parameters = array();
	
	/**
	 * @var $_css
	 */
	private $_css = array();

	/**
	 * @var $_js
	 */
	private $_js = array();

	/**
	 * Constructor
	 * 
	 */
	public function __construct($type = 'view')
	{
		$this->_type = $type;
		
	} // __construct()

	/**
	 * Set type
	 * 
	 * @param string $type
	 * 
	 */
	public function set_type($type)
	{
		$this->_type = $type;
		
	} // set_type()

	/**
	 * Set template
	 * 
	 * @param string $tpl
	 * 
	 */
	public function set_template($tpl)
	{
		// View
		if ($this->_type == 'view') {
			
	    if (strpos($tpl, "/") && $this->_type != 'component') {
				$this->_tpl = ABSPATH."app/views/".$tpl.".php";
	    } else {
				$map_to = (isset(app()->route['map_to']) ? app()->route['map_to'].'/' : '');
				$controller = app()->route['controller'];
				$this->_tpl = ABSPATH."app/views/".$map_to.$controller."/".$tpl.".php";
	    }
			
		} 
		// Layout
		else if ($this->_type == 'layout') {
	    $this->_tpl = ABSPATH."app/views/layouts/".$tpl.".php";
		} 
		// Component
		else if ($this->_type == 'component') {
	    $this->_tpl = ABSPATH."app/components/".$tpl.".php";
		} 
		// Partial
		else if ($this->_type == 'partial') {
	    $this->_tpl = ABSPATH."app/views/".$tpl.".php";
		}

	} // set_template()

	/**
	 * Add base css
	 * 
	 */
	protected function add_base_css()
	{
		$css_path   = ABSPATH."public/css/";
		$controller = app()->route['controller'];
		$action     = app()->route['action'];

		$css = array();

		$css_controller = (isset(app()->route['map_to']) ? app()->route['map_to'].'/' : '').$controller.'/'.$controller;
		if (file_exists($css_path.$css_controller.'.css')) {
	    $css[]['name'] = $css_controller;
		}

		$css_action = (isset(app()->route['map_to']) ? app()->route['map_to'].'/' : '').$controller.'/'.$action;
		if (file_exists($css_path.$css_action.'.css')) {
	    $css[]['name'] = $css_action;
		}

		$this->set('css', array_merge($css, $this->_css));

	} // add_base_css()

	/**
	 * Add css file to layout
	 * 
	 * @param string $css
	 * 
	 */
	public function add_css($css)
	{
		if (!isset($this->_css[$css]))
			$this->_css[$css]['name'] = $css;
		
	} // add_css()

	/**
	 * Add base javascript files to layout
	 * 
	 */
	protected function add_base_js()
	{
		$ba = $this->get_base_attributes();

		if ($ba != null) {
	    $js_path = ABSPATH."public/js/";
	    $controller = app()->route['controller'];
	    $action = app()->route['action'];

	    $js_controller = $controller.'/'.$controller;
	    if (file_exists($js_path.$js_controller.'.js')) {
				$this->_js[]['name'] = $js_controller;
	    }

	    $js_action = $controller.'/'.$action;
	    if (file_exists($js_path.$js_action.'.js')) {
				$this->_js[]['name'] = $js_action;
	    }
		}

		$this->set('js', $this->_js);

	} // add_base_js()

	/**
	 * Add javascript file to layout
	 * 
	 * @param string $js
	 * 
	 */
	public function add_js($js)
	{
		$this->_js[]['name'] = $js;
		
	} // add_js()
	
	/**
	 * Set a value to template
	 * 
	 * @param string $name
	 * @param mixed  $value
	 * 
	 */
	public function set($name, $value)
	{
		$this->_parameters[$name] = $value;
		$name = '_'.$name;
		$this->{$name} = $value;
		
	} // set()

	/**
	 * Set a bunch of value from an array
	 * 
	 * @param array $values
	 * 
	 */
	public function set_all($values)
	{
		if (is_array($values)) {
	    foreach ($values as $name => $value) {
				$this->set($name, $value);
	    }
		}
		
	} // set_all()
	
	/**
	 * Render a layout
	 * 
	 */
	protected function render_layout()
	{
		// layout template if not already set..
		if ($this->_layout === null)
			$this->set_template(app()->parameters['lity']['layout']);
		
		if (isset(app()->config['template']['automatically_add_css']) && app()->config['template']['automatically_add_css'] == true)
			$this->add_base_css();
		
		$this->set('js', $this->_js);
		
	} // render_layout()
	
	/**
	 * Render a view
	 * 
	 */
	protected function render_view()
	{
		if (isset(app()->parameters['lity']['view']))
			$this->set_template(app()->parameters['lity']['view']);
		else
			$this->set_template(app()->route['action']);

	} // render_view()

	/**
	 * Render a component view
	 * 
	 */
	protected function render_component()
	{
		if ($this->_tpl == null && $this->_component != null)
			$this->set_template($this->_component);

	} // render_component()
	
	/**
	 * Render a partial
	 * 
	 */
	protected function render_partial()
	{
	} // render_partial()
	
	/**
	 * Render
	 * 
	 * @param array $values
	 * @return string HTML
	 * 
	 */
	public function render($values = null)
	{
		// basics attributes..
		if ($values != null && is_array($values))
			$this->set_all($values);
		
		if ($this->_type == 'layout')
			$this->render_layout();
		else if ($this->_type == 'view')
			$this->render_view();
		else if ($this->_type == 'component')
			$this->render_component();
		else if ($this->_type == 'partial')
			$this->render_partial();
				
		ob_start();
		include($this->_tpl);
		$__result = ob_get_contents();
		ob_end_clean();

		// Minify HTML (remove spaces)
		if (isset(app()->config['template']['minify']) && app()->config['template']['minify'] == true) {
			$__result = mb_ereg_replace("\t", "", $__result);			
			$__result = mb_ereg_replace("[ ]{2,}", " ", $__result);
		}
		
		return $__result;		

	} // render()
	 	
} // Lity_View_Phtml
