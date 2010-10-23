<?php

/**
 * <modelname>
 * 
 * @author Your name <your@email.com>
 */
 
require_once ABSPATH.'lity/db/Activerecord.php';
 
class Model_<modelname> extends Lity_Db_Activerecord
{
    /**
     * Table's name
     */
    public $table = '<table>';
    
    /**
     * Fields
     */
    public $fields = array(
                           'id',
                           'created_at',
                           'updated_at'
                           );
       
    /**
     * Field's validation rules
     */                    
    public $rules = array();
    
    /**
     * Accessors
     */
    public $accessors = array();

} // Model_<modelname>