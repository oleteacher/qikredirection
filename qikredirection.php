<?php

    /**
    * Plugin Name: QikRedirection
    * Plugin URI: http://qiksoft.com
    * Version: 2.8
    * Author: QikSoft
    * Author URI: http://webhostingrecon.com
    * Description: Redirect any Page or Post to URL of choice. Best for page redirection.
    * Text Domain: qiksoft
    * License: MIT
    * License URI: https://opensource.org/licenses/MIT
    */
    
    define("PG_R_PLUGIN_DIR", WP_PLUGIN_DIR."/qikredirection");
    define("PG_R_PLUGIN_URL", WP_PLUGIN_URL."/qikredirection");
    
    require_once(PG_R_PLUGIN_DIR.'/functions.php');
    require_once(PG_R_PLUGIN_DIR.'/class/pgredirection.php');
    
    $plugin_great_redirection = new pgRedirection();
    
?>