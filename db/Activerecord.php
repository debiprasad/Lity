<?php

/**
 * Active Record
 *
 * @author  Wibeset <support@wibeset.com>
 * @package db
 *
 */

class Lity_Db_Activerecord
{
	/**
	 * Database's instance
	 */
	private $_db;
	
	/**
	 * Database's name
	 */
	private $_db_name;
	
	/**
	 * Ids from a resultset
	 */
	private $_ids = array();
	
	/**
	 * @var $_attributes
	 */
	private $_attributes = array();
	
	/**
	 * Resultset as array of models
	 */
	private $_objs = array();	
	
	/**
	 * Resultset as array of array
	 */
	private $_objs_array = array();

	/**
	 * Query's parameters (condition, order, limit, etc)
	 */
	private $_parameters = array();
	
	/**
	 * Pagination
	 */
	private $_pagination = array(
															 'first' => 1,
															 'previous' => 1,
															 'next' => 1,
															 'last' => 1,
															 'current' => 1,
															 'pages' => 1,
															 'limit' => '0,1',
															 'count' => 1
															 );

	/**
	 * Initialize model
	 * 
	 */
	public function initialize()
	{
		$this->initialize_db();
		$this->fields = array_flip($this->fields);
		
	} // initialize()

	/**
	 * Initialize database
	 * 
	 */
	public function initialize_db()
	{
		$this->_db = db();
		
		if ($this->_db_name !== null) 
			$this->_db->select_db($this->_db_name);
		
	} // initialize_db()

	/**
	 * Reinitialize/clean model
	 * 
	 */
	public function clear()
	{
		$this->_attributes = array();
		
	} // clear()

	/**
	 * Get/set primary key (must be an auto_increment)
	 * 
	 * @param  int $id
	 * @return int 
	 * 
	 */
	public function id($id = null)
	{
		// Get id's name
		$field = array_shift(array_flip($this->fields));
		
		// Set id
		if ($id !== null)
			$this->$field = $id;
		
		return $this->attribute($field);
		
	} // id()

	/**
	 * Get id's name
	 * 
	 * @return string id's name
	 * 
	 */
	public function id_name()
	{
		return array_shift(array_flip($this->fields));
		
	} // id_name()

	/**
	 * Return hash of found ids with find call.
	 * 
	 * @return array $this->_ids
	 * 
	 */
	public function ids()
	{
		return $this->_ids;
		
	} // ids()

	/**
	 * Return string of found ids with find call.
	 * 
	 * @return string ids as string
	 * 
	 */
	public function ids_to_string()
	{
		return implode(', ', $this->ids());
		
	} // ids_to_string()

	/**
	 * Return find results to arrays instead of objects
	 * 
	 * @return array
	 * 
	 */
	public function to_array()
	{
		return $this->_objs_array;
		
	} // to_array()

	/**
	 * Getter
	 * 
	 * @param  string $name field's name
	 * @return string       field's value
	 * 
	 */
	public function __get($name)
	{
		$gettername = 'get_'.$name;

		$value = $this->attribute($name);

		if (method_exists($this, $gettername))
			return $this->$gettername($value);
		else if ($value !== null && is_string($value))
			return stripslashes($value);
		else if ($value !== null)
			return $value;

		return null;
		
	} // __get()

	/**
	 * Setter
	 * 
	 * @param string $name  field's name
	 * @param string $value field's value
	 * 
	 */
	public function __set($name, $value)
	{
		if (isset($this->fields[$name]) || (!empty($this->accessors) && array_search($name, $this->accessors) !== false)) {
						
	    $settername = 'set_'.$name;
	    if(method_exists($this, $settername))
	      $this->_attributes[$name] = $this->$settername($value);
	    else
	      $this->_attributes[$name] = $value;
		}
		
	} // __set()

	/**
	 * Returns an array of column objects for the table associated with this class.
	 * 
	 * @return array
	 * 
	 */
	public function fields()
	{
		return $this->fields;
		
	} // fields()

	/**
	 * Returns true if the given attribute is in the attributes hash
	 * 
	 * @return bool
	 * 
	 */
	public function has_attribute($attribute)
	{
		if ($this->attribute($name) !== null)
			return true;
		
		return false;
		
	} // has_attribute()

	/**
	 * Return an attribute's value if is set
	 * 
	 * @param  string $name field's name
	 * @return string       field's value
	 */
	private function attribute($name)
	{
		if (isset($this->_attributes[$name]))
			return $this->_attributes[$name];
		
		return null;
		
	} // attribute()

	/**
	 * Returns a hash of all the attributes with their names as keys and clones of their objects as values..
	 * 
	 * @param  array $attributes array of attributes to set
	 * @return array             all fields' values
	 */
	public function attributes($attributes = null)
	{
		if ($attributes) {			
	    foreach ($attributes as $attributek => $attributev)
	      $this->$attributek = $attributev;
		}
		
		return $this->_attributes;
		
	} // attributes()

	/**
	 * Set attributes without passing thru setter (__set)
	 * 
	 * @param array $attributes array of attributes to set
	 */
	private function attributes_nosetter($attributes)
	{
		foreach ($attributes as $attrk => $attrv) {
	    $this->_attributes[$attrk] = $attrv;
		}
		
	} // attributes_nosetter()

	/**
	 * Returns true if a connection that's accessible to this class have already been opened
	 * 
	 * @return bool
	 * 
	 */
	public function is_connected()
	{
		return $this->_db->is_connected();
		
	} // is_connected()

	/**
	 * Return last query executed
	 * 
	 * @return string query
	 * 
	 */
	public function get_query()
	{
		return $this->_db->get_query();
		
	} // get_query()

	/**
	 * Execute a select query
	 * 
	 * @param  array  $parameters
	 * @param  string $object_key field's name to use as array keys
	 * @return array              array of models 
	 */
	public function find($parameters, $object_key = null)
	{
		//
		$this->_objs = array();

		// Execute query...
		if (!$this->_execute_query('find', $parameters)) {
	    return array();
		}

		// Create models
		while ($attributes = $this->_db->fetch_result()) {
			
			// Initialize model
			$class_name = get_class($this);
	    $o = new $class_name();
	    $o->initialize();
			
			// Set attributes into model
			$o->attributes_nosetter($attributes);
			
			// Add id to ids
	    $this->_ids[$o->id()] = $o->id();
			
			// Stack model into objects' array
			if ($object_key) {
				$this->_objs[$o->$object_key] = $o;
				$this->_objs_array[$o->$object_key] = $attributes;
			} else {
				$this->_objs[] = $o;
				$this->_objs_array[] = $attributes;
			}
		}

		return $this->_objs;

	} // find()

	/**
	 * Select first row that match parameters
	 * 
	 * @param  array $parameters
	 * @return array
	 * 
	 */
	public function find_first($parameters)
	{
		//
		$this->_objs = array();

		// Execute query...
		if (!$this->_execute_query('find_first', $parameters)) {
	    return array();
		}

		$attributes = $this->_db->fetch_result();

		// This entry doesn't exist, throw exception!
		if ($attributes == false) {
	    throw new no_record_found(0);
		}

		// return found row into model object
		$class_name = get_class($this);
		$o = new $class_name();
		$o->initialize();
		$o->attributes_nosetter($attributes);
		
		return $o;

	} // find_first()

	/**
	 * Execute a custom select
	 * 
	 * @param  string $sql 
	 * @param  array  $parameters
	 * @param  string $object_key
	 * @return array
	 * 
	 */	
	public function find_by_sql($sql, $parameters = array(), $object_key = null)
	{
		//
		$this->_objs = array();

		$parameters['sql'] = $sql;

		// get result
		if (!$this->_execute_query('find_by_sql', $parameters)) {
	    return array();
		}

		// Create models
		while ($attributes = $this->_db->fetch_result()) {
			
			// Initialize model
			$class_name = get_class($this);
	    $o = new $class_name();
	    $o->initialize();
			
			// Set attributes into model
			$o->attributes_nosetter($attributes);
			
			// Add id to ids
	    $this->_ids[$o->id()] = $o->id();
			
			// Stack model into objects' array
			if ($object_key) {
				$this->_objs[$o->$object_key] = $o;
				$this->_objs_array[$o->$object_key] = $attributes;
			} else {
				$this->_objs[] = $o;
				$this->_objs_array[] = $attributes;
			}
		}	 

		return $this->_objs;

	} // find_by_sql()

	/**
	 * Force insert into table
	 * 
	 * @param  array $parameters query's parameters
	 * @return int               inserted id
	 * 
	 */
	public function insert($parameters = array())
	{
		$this->created_at = time();
		$this->updated_at = 0;
		
		$id = $this->_execute_query('insert', $parameters);
		
		return $this->id($id);
		
	} // insert()

	/**
	 * insert more than one row in one query
	 *
	 * @param  array $rows       each row is an array of field_name => field_value
	 * @param  array $parameters 
	 * @return 
	 * 
	 */
	public function insert_all($rows, $parameters = array())
	{
		$fields = array();
		$values = array();

		foreach ($rows as $row) {
			
	    $rowvalues = array();

	    // Stack fields & values
	    foreach ($row as $rowk => $rowv) {
				$fields[$rowk] = $rowk;
				$rowvalues[] = "'".mysql_real_escape_string(trim($rowv))."'";
	    }

	    $values[] = implode(', ', $rowvalues);
			
		}

		$parameters['fields'] = $fields;
		$parameters['values'] = $values;

		return $this->_execute_query('insert_all', $parameters);

	} // insert_all()

	/**
	 * Insert into table from a select
	 * 
	 * @todo
	 * 
	 */
	public function insert_select()
	{
	} // insert_select()

	/**
	 * Force update
	 */
	public function update()
	{
		$this->updated_at = time();
		return $this->_execute_query('update');
		
	} // update()

	/**
	 * Multiple update in one query
	 *
	 * @param  array $condition query's where
	 * @return 
	 *
	 */
	public function update_all($condition)
	{
		return $this->_execute_query('update_all', array('condition' => $condition));
		
	} // update_all()

	/**
	 * Insert/Update
	 * 
	 * @param  array    $parameters
	 * @param  bool     $skip_validation set to true to skip validation
	 * @return bool|int                  return id if saved 
	 *                                   return false if not saved
	 * 
	 */
	public function save($parameters = null, $skip_validation = false)
	{
		//
		$this->_set_parameters($parameters);

		// Before update
		if ($this->id() > 0 && method_exists($this, "before_update")) {
			if (!$this->before_update()) return false;
		} 
		// Before insert
		else if ($this->id() === null && method_exists($this, "before_insert")) {
			if (!$this->before_insert()) return false;
		}
		// Before save
		if (method_exists($this, "before_save"))
			if (!$this->before_save()) return false;

		// Validation fields
		if (!$skip_validation) {
	    $valid  = $this->validate();
	    $cvalid = $this->_process_custom_validate();
	    if (!$valid || !$cvalid)
	      return false;
		}

		// Execute query
		$update = $insert = false;
		if ($this->id() > 0) {
	    $result = $this->update();
	    $update = true;
		} else {
	    $result = $this->insert();
	    $insert = true;
		}

		// After save
		if (method_exists($this, "after_save")) {
			$this->after_save();
		}
		// After update
		if ($update && method_exists($this, "after_update")) {
			$this->after_update();
		} 
		// After insert
		else if ($insert && method_exists($this, "after_insert")) {
			$this->after_insert();
		}

		return $result;

	} // save()

	/**
	 * Delete row(s)
	 * 
	 * @param  array $parameters condition, etc
	 * @return bool
	 */
	public function destroy($parameters = array())
	{
		return $this->_execute_query('destroy', $parameters);
		
	} // destroy()
	
	/**
	 * Delete all rows
	 * 
	 */
	public function destroy_all($parameters)
	{
		return $this->_execute_query('destroy_all', $parameters);
		
	} // destroy_all

	/**
	 * Return an hash of pagination
	 *
	 * Parameter paginate must be set to true into find()/find_first()/fint_by_sql() parameters OR
	 * call after count_by_sql()
	 * 
	 * @return array array('first'    => first page  
	 *                     'previous' => previous page
	 *                     'next'     => next page
	 *                     'last'     => last page
	 *                     'current'  => current page
	 *                     'pages'    => total of pages
	 *                     'count'    => number of rows
	 *                     )
	 * 
	 */
	public function pagination()
	{
		// Limit
		$limit = explode(',', $this->_pagination['limit']);
		$page  = (int)($limit[0] / $limit[1]) + 1;
		$limit = $limit[1];

		// Do not execute found_rows() query if not necessary!
		if ($page == 1 && count($this->_objs) < $limit) {
			$this->_pagination['count'] = count($this->_objs);
			return $this->_pagination;
		}

		// Get number of rows matching last query
		if (!$this->_execute_query('found_rows')) {
	    return $this->pagination;
		}

		$res  = $this->_db->fetch_result('row');
		$rows = $res[0];

		$last  = ($rows / $limit);
		$last  = ((int)$last == $last ? (int)$last : (int)$last + 1);

		return array('first' => 1,
								 'previous' => ($page - 1 < 1 ? 1 : $page - 1),
								 'next' => ($page + 1),
								 'last' => $last,
								 'current' => $page,
								 'pages' => $last,
								 'count' => $rows);

	} // pagination()

	/**
	 * Returns the result of an SQL statement that should only include a COUNT(*) in the SELECT part
	 * 
	 * @todo
	 * 
	 */
	public function count_by_sql($sql)
	{
	} // count_by_sql()

	/**
	 * Validate attributes depending on definitionRules
	 * 
	 */
	public function validate()
	{
		return helper('validator')->validate_all($this->_attributes, $this->rules);
		
	} // validate()

	/**
	 * Process custom validate
	 * 
	 * This function is called on each save(). All you need to do is add a function into your model named with "validate_" and the name 
	 * of the field you wanna do a custom validation. For example, if you got a field named 'body', you will call your function
	 * 'validate_body'. This function must returned a boolean.
	 * 
	 * @return bool 
	 * 
	 */
	private function _process_custom_validate()
	{
		$valid = true;

		$methods = get_class_methods(get_class($this));
		foreach($methods as $method) {
	    if (strpos($method, 'validate_') === 0) {
				if (!$this->$method()) {
					$valid = false;
				}
	    }
		}

		return $valid;

	} // _process_custom_validate()

	/**
	 * Accepts an array or string. The string is returned untouched, but the array has each value
	 * sanitized and interpolated into the sql statement
	 * 
	 * @todo
	 * 
	 */
	public function sanitize_sql()
	{
	} // sanitize_sql()

	/**
	 * Set query's parameters
	 * 
	 * @param array $parameters
	 * 
	 */
	private function _set_parameters($parameters)
	{
		// parameters is an id...
		if (!is_array($parameters))
			$parameters = array('condition' => $this->id_name().'='.$parameters);

		// pagination limit...
		if (isset($parameters['limit'])) {
	    $this->_pagination['limit'] = $parameters['limit'];
		}

		$this->_parameters = $parameters;

	} // _set_parameters()

	/**
	 * Build a query
	 * 
	 * @param string $type find        select from query
	 *                     find_first  select first row matching query
	 *                     find_by_sql custom query
	 *                     found_rows  get number of results matching last query
	 *                     insert      insert a row
	 *                     insert_all  insert rows 
	 *                     update      update a row
	 *                     update_all  update rows
	 *                     destroy     delete row(s)
	 * 
	 */
	private function _build_query($type)
	{
		switch ($type) {
			
		 case 'find':
		 case 'find_first':
	    $query = 'select '.
	      (isset($this->_parameters['paginate']) ? 'sql_calc_found_rows ' : '').
	      (isset($this->_parameters['select']) ? implode(', ', $this->_parameters['select']) : '*').
	      ' from '.$this->table.' '.
				(isset($this->_parameters['use_index']) ? $this->_parameters['use_index'].' ' : '').
				(isset($this->_parameters['join']) ? $this->_parameters['join'] : '');
	    break;
		 case 'find_by_sql':
	    $query = $this->_parameters['sql'];
	    break;

		 case 'found_rows':
	    $query = 'select found_rows()';
	    break;

		 case 'insert':
	    $query = 'insert '.
				(isset($this->_parameters['ignore']) ? 'ignore' : '').
				' into '.$this->table.
	      ' ('.$this->_implode_attributes(0).')'.
	      ' values ('.$this->_implode_attributes(1).')'.
				(isset($this->_parameters['ignore']) && is_string($this->_parameters['ignore']) ?
				 ' on duplicate key update '.$this->_parameters['ignore'] : '');
	    break;
		 case 'insert_all':
	    $query = 'insert '.
				(isset($this->_parameters['ignore']) ? 'ignore' : '').
				' into '.$this->table.
	      ' ('.implode(', ', $this->_parameters['fields']).')'.
	      ' values ('.implode('), (', $this->_parameters['values']).')'.
				(isset($this->_parameters['ignore']) && is_string($this->_parameters['ignore']) ?
				 ' on duplicate key update '.$this->_parameters['ignore'] : '');
	    break;

		 case 'update':
	    if (!isset($this->_parameters['condition']))
	      $this->_parameters['condition'] = $this->id_name().'='.$this->id();
	    $query = "update ".$this->table.' set '.$this->_implode_attributes(2);
	    break;

		 case 'update_all':
	    $query = "update ".$this->table.' set '.$this->_implode_attributes(2);
	    break;

		 case 'destroy':
	    if (!isset($this->_parameters['condition']))
	      $this->_parameters['condition'] = $this->id_name().'='.$this->id();
	    $query = 'delete low_priority from '.$this->table;
	    break;			
		 case 'destroy_all':
	    $query = 'delete low_priority from '.$this->table;
	    break;
		}

		// where...
		if (isset($this->_parameters['condition'])) {
	    $query .= ' where '.$this->_parameters['condition'];
		}
		// group by...
		if (isset($this->_parameters['group'])) {
	    $query .= ' group by '.$this->_parameters['group'];
		}
		// order by...
		if (isset($this->_parameters['order'])) {
	    $query .= ' order by '.$this->_parameters['order'];
		}
		// limit...
		if (isset($this->_parameters['limit'])) {
	    $query .= ' limit '.$this->_parameters['limit'];
		}
		
		return $query;

	} // _build_query()

	/**
	 * Execute a query
	 * 
	 * @param  string $type
	 * @param  array  $parameters
	 * @return 
	 */
	private function _execute_query($type, $parameters = array())
	{
		// set parameters
		$this->_set_parameters($parameters);

		// run query
		$this->_db->set_query($this->_build_query($type));
		$this->_db->run_query();

		// reset parameters
		$this->_set_parameters(array());

		switch ($type) {
		 case 'insert':
	    return $this->_db->last_insert_id();
	    break;

		 case 'find':
		 case 'find_first':
		 case 'find_by_sql':
		 case 'found_rows':
		 case 'update':
		 case 'update_all':
		 case 'destroy':
	    return $this->_db->get_affected_rows() === false ? false : true;
	    break;
		}

		return false;

	} // execute_query()

	/**
	 * Fields and/or values as string
	 * 
	 * @param  int    $mode 0 fields comma separated (ex: 'field1, field2, fieldn')
	 *                      1 values comma separated (ex: '"value1", "value2", "valuen"')
	 *                      2 fields=values comma separated (ex: 'field1="value1", field2="value2", fieldn="valuen"')
	 * @return string
	 * 
	 */
	private function _implode_attributes($mode)
	{
		$query = '';

		switch ($mode) {
	    // fields' name
		 case 0:
	    foreach ($this->attributes() as $attributek => $attributev) {
				if (isset($this->fields[$attributek]))
					$query .= $attributek.", ";
	    }
	    $query = substr($query, 0, strlen($query)-2);
	    break;
	    // fields' value
		 case 1:
	    foreach ($this->attributes() as $attributek => $attributev) {
				if (isset($this->fields[$attributek]))
					$query .= "'".mysql_real_escape_string($attributev)."', ";
	    }
	    $query = substr($query, 0, strlen($query)-2);
	    break;
	    // fields' name & value
		 case 2:
	    foreach ($this->attributes() as $field => $value) {
				if (isset($this->fields[$field])) {
					$query .= $field."='".mysql_real_escape_string($value)."', ";
				}
	    }
	    $query = substr($query, 0, strlen($query)-2);
	    break;
		}

		return $query;

	} // _implode_attributes()

} // Lity_Db_Activerecord

/**
 * No record found exception
 * 
 */
class no_record_found extends Exception
{
	public function __construct($id)
	{
		parent::__construct("No record found for id: ".$id, (int)$id);
		
	} // __construct()

} // no_record_found
