<?php

    class pgRedirectionsFunctions {
        static function loadView($view_name, $data) {
            extract($data);
            include(PG_R_PLUGIN_DIR."/view/{$view_name}.php");
        }    
    }   
     
?>