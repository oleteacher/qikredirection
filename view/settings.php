<div class="wrap" id="pg-r-settings">
    <h2>QikRedirected Posts</h2>    
    <table cellspacing="0" class="widefat fixed">
        <thead>
            <tr>
                <th class="manage-column column-title" id="page" scope="col">Post Title</th>
                <th class="manage-column column-redirect" id="url" scope="col">Redirects To</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th class="manage-column column-title" id="page" scope="col">Post Title</th>
                <th class="manage-column column-redirect" id="url" scope="col">Redirects To</th>
            </tr>
        </tfoot>
        <tbody>
            <?php 
                $posts = get_posts(array('post_type' => 'post'));   
                
                foreach ($posts as $f => $post) {
                    $url = get_post_meta($post->ID, $pg_r->config['meta_names']['url'], true);
                    $display = get_post_meta($post->ID, $pg_r->config['meta_names']['display'], true);
                    
                    if (empty($url)) {
                        unset($posts[$f]);
                    } else {
                        $posts[$f]->{$pg_r->config['meta_names']['url']} = $url;
                        $posts[$f]->{$pg_r->config['meta_names']['display']} = $display;
                    }
                }
                
                foreach ($posts as $post) {
                    ?>
                        <tr valign="middle">
                            <td class="column-page">
                                <?php 
                                    echo edit_post_link($post->post_title, '', '', $post->ID);
                                ?>
                            </td>
                            <td class="column-url">
                                <a target="_blank" href="<?php echo $post->{$pg_r->config['meta_names']['url']}; ?>"><?php echo $post->{$pg_r->config['meta_names']['url']}; ?></a>
                            </td>                  
                        </tr>  
                    <?php
                }    
              ?>
        </tbody>
    </table>
    <h2>QikRedirected Pages</h2>    
    <table cellspacing="0" class="widefat fixed">
        <thead>
            <tr>
                <th class="manage-column column-title" id="title" scope="col">Page Title</th>
                <th class="manage-column column-redirect" id="url" scope="col">Redirects To</th>
                <th class="manage-column column-hidden" id="hidden" scope="col">Hidden</th>     
            </tr>
        </thead>
        <tfoot>
            <tr>                                                                     
                <th class="manage-column column-title" id="title" scope="col">Page Title</th>
                <th class="manage-column column-redirect" id="url" scope="col">Redirects To</th>
                <th class="manage-column column-hidden" id="hidden" scope="col">Hidden</th>   
            </tr>
        </tfoot>
        <tbody>
            <?php 
                $pages = get_pages(array());
                
                foreach ($pages as $f => $page) {
                    $url = get_post_meta($page->ID, $pg_r->config['meta_names']['url'], true);
                    $display = get_post_meta($page->ID, $pg_r->config['meta_names']['display'], true);
                    
                    if (empty($url)) {
                        unset($pages[$f]);
                    } else {
                        $pages[$f]->{$pg_r->config['meta_names']['url']} = $url;
                        $pages[$f]->{$pg_r->config['meta_names']['display']} = $display;
                    }
                }
                
                foreach ($pages as $page) {
                    ?>
                        <tr valign="middle">
                            <td class="column-page">
                                <?php 
                                    echo edit_post_link($page->post_title, '', '', $page->ID);
                                ?>
                            </td>
                            <td class="column-url">
                                <a target="_blank" href="<?php echo $page->{$pg_r->config['meta_names']['url']}; ?>"><?php echo $page->{$pg_r->config['meta_names']['url']}; ?></a>
                            </td>
                            <td class="column-hidden">
                                <?php echo 1 == intval($page->{$pg_r->config['meta_names']['display']}) ? 'Yes' : 'No'; ?>
                            </td>                   
                        </tr>  
                    <?php
                }    
              ?>
        </tbody>
    </table>
</div> 