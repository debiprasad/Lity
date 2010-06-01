<?php

/**
 * Home
 *
 * This is the homepage
 * 
 * @author your name <email>
 */
 
require_once ABSPATH.'app/controllers/Application.php';
 
class Controller_Home extends Controller_Application
{
    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize()
    {
        parent::initialize();
        
    } // initialize()
    
    /**
     * Index
     *
     */
    public function index()
    {
        $this->view['title'] = 'Homepage - My Site';
    
    } // index()
    
} // Controller_Home