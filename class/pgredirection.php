<?php

    class pgRedirection {
        var $config = array(
            'meta_names' => array(
                'url' => '_pg_r_url',
                'display' => '_pg_r_display'
            )
        );
        
        var $option_names = array(
            'settings' => 'pg_r_settings'
        );
        
        var $options = array(
            'settings' => array(  
            )
        );
        
        function saveOption($option_set) {
            return update_option($this->option_names[$option_set], $this->options[$option_set]);
        }
        
        function loadOption($option_set) {
            $option = get_option($this->option_names[$option_set]);
            if (false == $option) {
                $this->saveOption($option_set);
            } else {
                $this->options[$option_set] = array_merge($this->options[$option_set], $option);                
            }
        }
        
        function __construct() {
            $this->loadOption('settings');  
            
            if (is_admin()) {
                add_action('admin_init', array($this, 'registerOptions')); 
                add_action('admin_menu', array($this, 'adminMenuHandler')); 
                
                add_action('admin_menu', array($this, 'metaBoxes')); 
                add_action('save_post', array($this, 'saveEntry'));
                
                wp_enqueue_script("pg-r-admin", PG_R_PLUGIN_URL."/script/pg-r-admin.js", array("jquery"));  
            } else {
                add_action('template_redirect', array($this, 'redirect'));
                add_filter('get_pages', array($this, 'getPagesHandler'));
//                add_filter('query_posts', array($this, 'getPostsHandler'));
//                add_filter('get_post', array($this, 'getPostsHandler'));
            }  
        }
        
        function getPagesHandler($pages) {
            
//            var_dump(func_get_args());
            foreach ($pages as $f => $page) {
                if (1 == intval(get_post_meta($page->ID, $this->config['meta_names']['display'], true))) {
                    unset($pages[$f]);
                }
            }
            return $pages;
        }
        
        function getPostsHandler($pages) {
//               var_dump(array());
//               var_dump(func_get_args());
//               query_posts()
        }
        
        function redirect() {            
            if (is_feed() || !is_singular()) {
                return;                
            }
            
            global $wp_query;
            $post_id = $wp_query->get_queried_object_id();
            $url = get_post_meta($post_id, $this->config['meta_names']['url'], true);
            
            if ($url) {  
                wp_redirect($url, 301);
                die();
            }
        }
        
        function metaBoxes() {
            if (current_user_can('edit_pages')) {
                add_meta_box('pg-r', __('QikRedirection'), array($this, 'editEntry'), 'page');    
            }
            
            if (current_user_can('edit_posts')) {
                add_meta_box('pg-r', __('QikRedirection'), array($this, 'editEntry'), 'post');    
            }
        }
        
		//updated to help secure process
        function editEntry($post) {
            $url = null;
            
            if (0 < $post->ID) {
                $url = get_post_meta($post->ID, $this->config['meta_names']['url'], true);                
                $display = get_post_meta($post->ID, $this->config['meta_names']['display'], true);                
            }
                
            ?>
                <p>
                    <input type="text" name="<?php echo $this->config['meta_names']['url']; ?>" class="code widefat" value="<?php echo esc_attr($url); ?>" />
                </p>
                <p> 
                    Enter the full URL to which visitors should automatically be redirected.
                </p>
                <?php if ('page' == $post->post_type) : ?>
                    <p>
                        <label><input type="checkbox" name="<?php echo $this->config['meta_names']['display']; ?>" value="1" <?php echo 1 == intval($display) ? 'checked="checked"' : ''; ?> /> Hide From Menu (page only)</label>
                    </p> 
                <?php endif; ?>
            <?php
        }
        
        function saveEntry($post_id) {
            if (!$_POST || wp_is_post_revision($post_id) || !current_user_can('edit_post', $post_id)) {
                return;    
            }
            
            if (current_user_can('edit_post', $post_id)) {
                $url = $_POST[$this->config['meta_names']['url']];
                $url = stripslashes($url);
                $url = esc_url_raw($url);
                $url = addslashes($url);
                
                if ($url) {
                    update_post_meta($post_id, $this->config['meta_names']['url'], $url);
                } else {
                    delete_post_meta($post_id, $this->config['meta_names']['url']);
                }
                
                $post = get_post($post_id);
                if ('page' == $post->post_type) {
                    $display = $_POST[$this->config['meta_names']['display']];  
                    
                    if ($display) {
                        update_post_meta($post_id, $this->config['meta_names']['display'], $display);
                    } else {
                        delete_post_meta($post_id, $this->config['meta_names']['display']);
                    }
                }
            }
        }
        
        function adminMenuHandler() {
            add_menu_page(__('Settings'), __('QikRedirection'), 'manage_options', 'pg-r-handle', array($this, 'settingsPage'));
            add_submenu_page('pg-r-handle', __('Settings'), __('Settings'), 'manage_options', 'pg-r-handle', array($this, 'settingsPage'));
        }
        
        function registerOptions() {                       
            register_setting($this->option_names['settings'], $this->option_names['settings'], array($this, "settingsValidationHandler"));     
        }
        
        function settingsValidationHandler($input) {
            /*$redirections = array();
            
            if (isset($input['redirections'])) {
                foreach ($input['redirections']['start'] as $f => $v) {
                    if (!empty($v)) {
                        $redirections[$v] = $input['redirections']['end'][$f];
                    }                                                     
                }
                
                $input['redirections'] = $redirections;
            }*/        
            
            return $input;                              
        }
        
        function settingsPage() {
            pgRedirectionsFunctions::loadView('settings', array('pg_r' => $this));
        }
    }
    
?>