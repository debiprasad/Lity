<?php

/**
 * Add a new model
 *
 * @author Wibeset <support@wibeset.com>
 */
 
class Lity_Core_Script_Model
{                
    /**
     * Run
     *
     * @param array $argv Args
     */
    public function run($argv)
    {   
        if (!isset($argv[2]) || !isset($argv[3]))
            die("usage: php lity/Lity.php model <model's name> <table's name>\n".
                "exemple: php lity/Lity.php model blog blogs\n");
    
        $this->_create_model($argv[2], $argv[3]);
        
    } // run()
    
    /**
     * Create model
     *
     */
    private function _create_model($name, $table)
    {
        // Get file
        $model = file_get_contents(ABSPATH.'lity/core/scripts/templates/models/Model.php');
        
        // Replace stuff
        $from = array('<modelname>', '<table>');
        $to = array(ucfirst($name), $table);
        $model = str_replace($from, $to, $model);
        
        file_put_contents(ABSPATH.'app/models/'.ucfirst($name).'.php', $model);
            
    } // _create_model()
    
} // Lity_Core_Script_Model