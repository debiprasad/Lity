<?php

/**
 * Bootstrap
 *
 */
 
// Application's encoding
mb_internal_encoding("UTF-8");

// Test
if ($_SERVER['HTTP_HOST'] == 'test.mysite.com') {

  // base config
  define('ENV', 'test');

}
else if ($_SERVER['HTTP_HOST'] == 'mysite.com') {

  // base config
  define('ENV', 'production');

}
// Development
else {

  // base config
  define('ENV', 'development');

  error_reporting(E_ALL);
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  ini_set('error_log', './logs/error_log');

}

// Set request
define('REQUEST', $_GET['request']);

// Application's directories
define('ABSPATH', realpath('.').'/');

// Start Application
require_once ABSPATH.'lity/Web.php';
require_once ABSPATH.'app/controllers/Application.php';

// dispatch
try {
  Lity_Web::get_instance()->run();
} catch (Exception $e) {
  logdata("Exception caught '".$e->getMessage()."'");
  redirect_to('');
}
