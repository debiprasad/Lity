<?php

/**
 * Lity
 * 
 * Script to initialize project, create new controllers, new models, new plugins,
 * new helpers and new services.
 *
 * @author Wibeset <support@wibeset.com>
 */

define('ABSPATH', dirname(__FILE__).'/../');
 
/**
 * Show help
 *
 * @return void
 */
function show_help()
{
   echo "Script to initialize project, create new controllers, new models, new plugins,".
       " new helpers and new services.\n".
       "\n".
       "usage: php lity/Lity.php <command>\n".
       "\n".
       "  init       \t Initialize project\n".
       "  controller \t Add a new controller\n".
       "  environment\t Add a new environment config file\n".
       "  helper     \t Add a new helper\n".
       "  model      \t Add a new model\n".
       "  plugin     \t Add a new plugin\n".
       "  service    \t Add a new service\n";
    
} // show_help()

if (count($argv) == 1) {
    show_help();
    exit;
}

// Execute command
switch ($argv[1]) {
  
    // Initialize project
    case 'init':
        require_once ABSPATH.'lity/core/scripts/Project.php';
        $project = new Lity_Core_Script_Project();
        $project->run();
        break;
      
    default:
        show_help();
        break;
      
}