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
       "usage: php lity/Lity.php <command> <args>\n".
       "\n".
       "  init       \t Initialize project\n\n".
       "  controller \t Add a new controller\n\n".
       "  environment\t Add a new environment config file\n\n".
       "  helper     \t Add a new helper\n\n".
       "  model      \t Add a new model\n\n".
       "  \t<arg1> Model's name\n".
       "  \t<arg2> Table's name\n\n".
       "  plugin     \t Add a new plugin\n\n".
       "  service    \t Add a new service\n\n";
    
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
        
    // Add a new model
    case 'model':
        require_once ABSPATH.'lity/core/scripts/Model.php';
        $model = new Lity_Core_Script_Model();
        $model->run($argv);
        break;
      
    default:
        show_help();
        break;
      
}