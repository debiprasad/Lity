<?php

/**
 * Number
 *
 * @author  Wibeset <support@wibeset.com>
 * @package helpers
 * @todo    
 *
 */

class Lity_Helper_Number
{
	/**
	 * Returns a formatted-for-humans file size
	 * 
	 */
	public function to_human_size($size)
	{
	} // human_size()
	
	/**
	 * Formats a number into a currency string (ex: $1,234,567.89)
	 * 
	 */
	public function to_currency($number, $options = array('unit' => '&pound;', 'separatir' => ',', 'delimiter' => ''))
	{
	} // to_currency()
	
	/**
	 * Formats a number as into a percentage string (ex: 100.00%)
	 * 
	 */
	public function to_percentage($number, $options = array('precision' => 2))
	{
	} // to_percentage()
	
	/**
	 * Formats a number into a US phone number string (ex: (418) 549-1919)
	 * 
	 */
	public function to_phone($number, $options = array('area_code' => true, 'delimiter' => '-'))
	{
	} // to_phone()		
	
} // Lity_Helper_Number
