<?php

/**
 * Test
 *
 * @author your name <email>
 * @package Config
 */
 
/**
 * Urls
 */
 
$config['urlbase'] = 'http://.../';
$config['urlcss'] = 'http://.../public/css/';
$config['urlimg'] = 'http://.../public/img/';
$config['urljs'] = 'http://.../public/js/';

/**
 * Database
 *
 * @see <a href="http://wibeset.com/lity/database">Database</a>
 */

$config['db'] = array(
                      'default' => array(
                                         'type' => '',
                                         'name' => '',
                                         'host' => '',
                                         'user' => '',
                                         'password' => ''
                                         )
                      );
                      
/**
 * Logger
 *
 * @see <a href="http://wibeset.com/lity/logging">Logger</a>
 */

$config['logger'] = array('in' => 'file',
                          'email' => '',
                          'core' => false,
                          'queries' => false,
                          );