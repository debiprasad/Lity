<?php

/**
 * Application
 * Everything common between each controllers 
 * 
 * @author your name <email>
 */
 
class Controller_Application
{
    /**
     * Initialize controller
     *
     */
    public function initialize()
    {
    
        // Set layout (app/views/layouts/main.php)
        use_layout('main');
        
        // Set language
        lang('en_US'); 
       
        // Add urls to view
        $this->view['urlbase'] = app()->config['urlbase'];
        $this->view['urlimg'] = app()->config['urlimg'];
        $this->view['urlcss'] = app()->config['urlcss'];
        $this->view['urljs'] = app()->config['urljs'];
        
    } // initialize()

} // Controller_Application