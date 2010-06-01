<?php

/**
 * Initialize project
 * 
 * Script to initialize project
 *
 * @author Wibeset <support@wibeset.com>
 */
 
require_once ABSPATH.'lity/core/scripts/Script.php';
 
class Lity_Core_Script_Project extends Lity_Core_Script
{
    /**
     * @var $_directories
     */
    private $_directories = array(
        'app' => 0755,
        'app/controllers' => 0755,
        'app/config' => 0755,
        'app/models' => 0755,
        'app/helpers' => 0755,
        'app/plugins' => 0755,
        'app/services' => 0755,
        'app/views' => 0755,
        'app/views/layouts' => 0755,
        'app/views/home' => 0755,
        'app/views/404' => 0755,
        'cache' => 0777,
        'public' => 0755,
        'public/css' => 0755,
        'public/js' => 0755,
        'public/img' => 0755,
        'logs' => 0777
        );
        
    /**
     * @var $_envs
     */
    private $_envs = array(
        'Application',
        'Development',
        'Test',
        'Production'
        );
                
    /**
     * Run
     *
     */
    public function run()
    {   
        $this->_create_directories();
        $this->_create_configs();
        $this->_create_home();
        $this->_create_404();
        
    } // run()
    
    /**
     * Create directories
     *
     */
    private function _create_directories()
    {
        foreach ($this->_directories as $directory => $mode) {
            $old = umask(0);
            @mkdir($directory, $mode);
            umask($old);
        }

    } // _create_directories()
    
    /**
     * Create configuration files
     *
     */
    private function _create_configs()
    {
        // Copy configuration files
        foreach ($this->_envs as $env) {
            if (!file_exists(ABSPATH.'app/config/'.$env.'.php'))
                @copy(ABSPATH.'lity/core/scripts/templates/config/'.$env.'.php', 
                    ABSPATH.'app/config/'.$env.'.php');
        }
        
        // Copy .htaccess
        copy(ABSPATH.'lity/core/scripts/templates/config/htaccess', 
            ABSPATH.'.htaccess');

        // Copy bootstrap
        copy(ABSPATH.'lity/core/scripts/templates/config/index.php', 
            ABSPATH.'index.php');
        
    } // _create_configs()
    
    /**
     * Create Home
     *
     */
    private function _create_home()
    {
        // Copy controller
        @copy(ABSPATH.'lity/core/scripts/templates/controllers/Home.php', 
            ABSPATH.'app/controllers/Home.php');
            
        // Copy controller
        @copy(ABSPATH.'lity/core/scripts/templates/controllers/Application.php', 
            ABSPATH.'app/controllers/Application.php');
            
        // Copy layout
        @copy(ABSPATH.'lity/core/scripts/templates/layouts/main.php', 
            ABSPATH.'app/views/layouts/main.php');
            
        // Copy view
        @copy(ABSPATH.'lity/core/scripts/templates/views/home.php', 
            ABSPATH.'app/views/home/index.php');
            
    } // _create_home()
    
    /**
     * Create 404
     *
     */
    private function _create_404()
    {
        // Copy controller
        @copy(ABSPATH.'lity/core/scripts/templates/controllers/404.php', 
            ABSPATH.'app/controllers/404.php');
            
        // Copy view
        @copy(ABSPATH.'lity/core/scripts/templates/views/404.php', 
            ABSPATH.'app/views/404/index.php');
            
    } // _create_404()

} // Lity_Core_Script_Project