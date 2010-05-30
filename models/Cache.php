<?php

/**
 * Cache
 * 
 * @author Wibeset <support@wibeset.com>
 * @package models
 * 
 * CREATE TABLE cache (
 *   id int unsigned auto_increment primary key,
 *   name varchar(32) not null,
 *   data text not null,
 *   maxtime int unsigned default 0,
 *   created_at int(10) unsigned not null,
 *   updated_at int(10) unsigned default 0,
 * ) default charset=utf8;
 * 
 */

class Lity_Model_Cache extends Lity_Db_Activerecord
{
	/**
	 * @var $table
	 */
	public $table = "cache";
	
	/**
	 * @var $fields
	 */
	public $fields = array(
												 "id",
												 "name",
												 "data",
												 "maxtime",
												 "created_at",
												 "updated_at"
												 );
	
	/**
	 * Get data
	 * 
	 * @param string $data serialize data
	 * @return mixed data
	 * 
	 */
	public function get_data($data)
	{
		return @unserialize($data);
		
	} // get_data()

	/**
	 * Set data
	 * 
	 * @param mixed $data data to store
	 * @return string data
	 * 
	 */
	public function set_data($data)
	{
		return serialize($data);
		
	} // set_data()
	
} // Lity_Model_Cache
