<?php

/**
 * 404
 *
 * Error page
 * 
 * @author your name <email>
 */
 
require_once ABSPATH.'app/controllers/Application.php';
 
class Controller_404 extends Controller_Application
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
        $this->view['title'] = '404 - My Site';
    
    } // index()
    
} // Controller_404