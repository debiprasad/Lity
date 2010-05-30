<?php

/**
 * Validator
 *
 * @author  Wibeset <support@wibeset.com>
 * @package helpers
 *
 */

class Lity_Helper_Validator
{
	/**
	 * @var array default errors messages
	 */
	private $_default_error_messages = array(
																					 'inclusion' => "is not included in the list",
																					 'exclusion' => "is reserved",
																					 'invalid' => "is invalid",
																					 'confirmation' => "doesn't match confirmation",
																					 #'accepted ' => "must be accepted",
																					 'empty' => "can't be empty",
																					 'too_long' => "is too long (max is %d characters)",
																					 'too_short' => "is too short (min is %d characters)",
																					 #'taken' => "has already been taken",
																					 'not_a_number' => "is not a number",
																					 'not_a_string' => "is not a string",
																					 'not_a_email' => "is not a valid email address",
																					 'not_between' => "is not in the interval"
																					 );

	/**
	 * Validate a bunch of attributes
	 * 
	 * @param  array $attributes key value array of attributes 
	 * @param  array $rules      rules to apply 
	 * @return bool              true if valid
	 * 
	 */
	public function validate_all($attributes, $rules)
	{
		//
		$is_valid = true;
		
		// Apply each rules
		foreach ($rules as $field_name => $field_rules) {
			
	    if (isset($attributes[$field_name])) {
				$field_value = $attributes[$field_name];

				foreach ($field_rules as $condition => $rule) {
					if (!$this->validate($condition, 
															 $field_name, 
															 $field_value, 
															 $rule, 
															 (!empty($field_rules['message']) ? $field_rules['message'] : "")))
						$is_valid = false;
				}
	    }
			
		}

		return $is_valid;

	} // validate_all()

	/**
	 * Validate an attribute
	 * 
	 * This function apply an error (error()) to application if attribute is invalid
	 * 
	 * @param  string $type
	 * @param  string $field field's name
	 * @param  string $value field's value
	 * @param  string $rule  rule to apply
	 * @return bool
	 * 
	 */
	public function validate($type, $field, $value, $rule, $message = "")
	{
		$is_valid = true;
		
		switch ($type) {
			
		 case 'present':
	    $default_message = $this->_default_error_messages['empty'];
	    $is_valid = $this->presence_of($value);
	    break;
			
		 case 'minlen':
	    $default_message = str_replace('%d', $rule, $this->_default_error_messages['too_short']);
	    $is_valid = $this->minimal_length_of($value, $rule);
	    break;
			
		 case 'maxlen':
	    $default_message = str_replace('%d', $rule, $this->_default_error_messages['too_long']);
	    $is_valid = $this->maximal_length_of($value, $rule);
	    break;
			
		 case 'between':
	    $default_message = $this->_default_error_messages['not_between'];
	    $is_valid = $this->between_of($value, $rule);
	    break;
			
		 case 'inclusion':
	    $default_message = $this->_default_error_messages['inclusion'];
	    $is_valid = $this->inclusion_of($value, $rule);
	    break;
			
		 case 'exclusion':
	    $default_message = $this->_default_error_messages['exclusion'];
	    $is_valid = $this->exclusion_of($value, $rule);
	    break;
			
		 case 'number':
	    $default_message = $this->_default_error_messages['not_a_number'];
	    $is_valid = $this->numericality_of($value);
	    break;
			
		 case 'format':
	    $default_message = $this->_default_error_messages['invalid'];
	    $is_valid = $this->format_of($value, $rule);
	    break;
			
		 case 'email':
	    $default_message = $this->_default_error_messages['not_a_email'];
	    $is_valid = $this->email_of($value);
	    break;
			
		}

		// Apply error to application
		if (!$is_valid) {
			error($field, ($message != "" ? $message : $field.' '.$default_message));
		}

		return $is_valid;
		
	} // validate();

	/**
	 * Check if the attribute is empty
	 * 
	 * @param  string|array|int $value
	 * @return bool
	 * 
	 */
	public function presence_of($value)
	{
		return !empty($value);
		
	} //  presence_of()

	/**
	 * Check the minimal length of the attribute
	 * 
	 * @param  int  $value
	 * @param  int  $minlen
	 * @return bool
	 * 
	 */
	public function minimal_length_of($value, $minlen)
	{
		return (mb_strlen($value) >= $minlen);
		
	} // minimal_length_of()

	/**
	 * Check the maximal length of the attribute
	 * 
	 * @param  int  $value
	 * @param  int  $maxlen
	 * @return bool
	 * 
	 */
	public function maximal_length_of($value, $maxlen)
	{
		return (mb_strlen($value) <= $maxlen);
		
	} // maximal_length_of()

	/**
	 * Check if the value is between
	 * 
	 * @param  int    $value
	 * @param  string $between '<minumum number>..<maximum number>'
	 * @return bool
	 * 
	 */
	public function between_of($value, $between)
	{
		$between = explode("..", $between);
		return ($value >= $between[0] && $value <= $between[1]);
		
	} // between_of()

	/**
	 * Check if the value is include in the $words list
	 * 
	 * @param  string|array $value
	 * @param  string       $words
	 * @return bool
	 * 
	 */
	public function inclusion_of($value, $words)
	{
		return mb_strstr($words, $value);
		
	} // validateInclusionOf()

	/**
	 * Check if the value is NOT included in the $words list
	 * 
	 * @param  string|array $value
	 * @param  string       $words
	 * @return bool
	 * 
	 */
	public function exclusion_of($value, $words)
	{
		return !mb_strstr($words, $value);
		
	} // validateExclusionOf()

	/**
	 * Check if the value is a number
	 * 
	 * @param  int  $value
	 * @return bool
	 * 
	 */
	public function numericality_of($value)
	{
		return is_numeric($value);
		
	} // numericality_of()

	/**
	 * Check if the value match against $pattern
	 * 
	 * @param  string $value
	 * @param  string $pattern regular expression
	 * @return bool
	 * 
	 */
	public function format_of($value, $pattern)
	{
		return preg_match($pattern, $value);
		
	} // format_of()

	/**
	 * Check if the value is a valid email
	 * 
	 * @param  string $value
	 * @return bool
	 * 
	 */
	public function email_of($value)
	{
		return $this->format_of($value, '/^[A-z0-9][\w.-]*@[A-z0-9][\w\-\.]+\.[A-z0-9]{2,6}$/');
		
	} // email_of()

	/**
	 * 
	 */
	
	protected static $_instance = null;

	public function get_instance()
	{
		if (self::$_instance == null)
			self::$_instance = new self();
		
		return self::$_instance;
		
	} // get_instance()

} // Lity_Helper_Validator
