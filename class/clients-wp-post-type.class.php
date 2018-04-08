<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Clients_WP_Post_Type {

    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
        add_filter('manage_bt_client_posts_columns', array($this, 'bt_client_edit_columns'));
        add_action('manage_bt_client_posts_custom_column', array($this, 'bt_client_custom_columns'), 10, 2);
        add_filter('manage_bt_client_page_posts_columns', array($this, 'bt_client_page_edit_columns'));
        add_action('manage_bt_client_page_posts_custom_column', array($this, 'bt_client_page_custom_columns'), 10, 2);
        add_action('save_post', array($this, 'save_bt_client_fields' ));
        add_action('save_post', array($this, 'save_bt_client_page_fields' ));
    }

    public function register_post_type() {
        register_post_type( 'bt_client', array(
            'labels' => array(
                'name' => __('Clients', 'cl-wp'),
                'singular_name' => __('Client', 'cl-wp'),
                'add_new' => _x('New Client', 'Client', 'cl-wp' ),
                'add_new_item' => __('Add New Client', 'cl-wp' ),
                'edit_item' => __('Edit Client', 'cl-wp' ),
                'new_item' => __('New Client', 'cl-wp' ),
                'view_item' => __('View Client', 'cl-wp' ),
                'search_items' => __('Search Clients', 'cl-wp' ),
                'not_found' =>  __('No Clients found', 'cl-wp' ),
                'not_found_in_trash' => __('No Clients found in Trash', 'cl-wp' ),
            ),
            'description' => __('Clients WP Clients', 'cl-wp'),
            'public' => false,
            'publicly_queryable' => false,
            'query_var' => true,
            'rewrite' => array( 'slug' => 'client' ),
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => true,
            'menu_position' => 80, // probably have to change, many plugins use this
            'menu_icon' => 'dashicons-groups',
            'supports' => array(
                'title',
                'editor'
            ),
        ));

        register_post_type( 'bt_client_page', array(
            'labels' => array(
                'name' => __('Client Pages', 'cl-wp'),
                'singular_name' => __('Client Page', 'cl-wp'),
                'add_new' => _x('New Client Page', 'Client', 'cl-wp' ),
                'add_new_item' => __('Add New Client Page', 'cl-wp' ),
                'edit_item' => __('Edit Client Page', 'cl-wp' ),
                'new_item' => __('New Client Page', 'cl-wp' ),
                'view_item' => __('View Client Page', 'cl-wp' ),
                'search_items' => __('Search Client Pages', 'cl-wp' ),
                'not_found' =>  __('No Client Pages found', 'cl-wp' ),
                'not_found_in_trash' => __('No Client Pages found in Trash', 'cl-wp' ),
            ),
            'description' => __('Clients WP Client Pages', 'cl-wp'),
            'public' => false,
            'publicly_queryable' => false,
            'query_var' => true,
            'rewrite' => false,
            'exclude_from_search' => true,
            'show_ui' => true,
            'show_in_menu' => 'edit.php?post_type=bt_client',
            'supports' => array(
                'title',
                'editor'
            ),
        ));

    }


    function bt_client_edit_columns($columns) {
        $newcolumns = array(
            'cb' => '<input type="checkbox" />',
            'title' => esc_html__('Title', 'cl-wp'),
            'id'    => esc_html__('ID', 'cl-wp'),
            'owner'    => esc_html__('Owner', 'cl-wp'),
            'members'    => esc_html__('Members', 'cl-wp'),
            'date'  => 'Date',
            'actions'    => esc_html__('Actions', 'cl-wp'),
        );
        
        $columns= array_merge($newcolumns, $columns);
        
        return $columns;
    }

    public function bt_client_custom_columns($column, $post_id) {

        if ($column == 'title') {
            echo get_the_title($post_id);
        }
        elseif ($column == 'id') {
            echo $post_id;
        }
        elseif ($column == 'owner') {
            echo get_post_meta($post_id, '_bt_client_group_owner', true);
        }
        elseif ($column == 'members') {
            // echo 6;
            echo count(cwp_get_group_members($post_id));
        }
        elseif ($column == 'actions') {
            echo '<a href="'.  admin_url( 'admin.php?page=clients-wp-settings&view=members&groupid=' ) . $post_id .'">Members<a>';
        }
        
    }

    function bt_client_page_edit_columns($columns) {
        $newcolumns = array(
            'cb' => '<input type="checkbox" />',
            'title' => esc_html__('Title', 'cl-wp'),
            'shortcode'    => esc_html__('Shortcode', 'cl-wp'),
            'client'    => esc_html__('Client', 'cl-wp'),
            'date'  => 'Date',
        );
        
        $columns= array_merge($newcolumns, $columns);
        
        return $columns;
    }

    public function bt_client_page_custom_columns($column, $post_id) {

        if ($column == 'shortcode') {
            $clients_wp_shortcodes = get_option('clients_wp_shortcodes');
            if($clients_wp_shortcodes) {
                $key = array_search(get_post_meta($post_id, '_clients_page_shortcode', true), $clients_wp_shortcodes['shortcode']);
                echo $clients_wp_shortcodes['label'][$key] . ' - ' . $clients_wp_shortcodes['shortcode'][$key];
            } else {
                echo '-';
            }
        }
        if ($column == 'client') {
            $client = get_post(get_post_meta($post_id, '_clients_page_client', true));
            echo $client->post_title;
        }
    }

    public function save_bt_client_fields( $post_id ){
        // Avoid autosaves
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        $slug = 'bt_client';
        if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] ) {
            return;
        }

        if ( isset( $_POST['_bt_client_group_owner']  ) ) {
            update_post_meta( $post_id, '_bt_client_group_owner',  $_POST['_bt_client_group_owner']);

            // automatically set as member
            $user = get_user_by('email', $_POST['_bt_client_group_owner']);
            if ($user) {
                $user_groups = get_user_meta( $user->ID, '_clients_wp_user_groups', true );
                if (is_array($user_groups)) {
                    if (!in_array($post_id, $user_groups)) {
                        $new_user_groups = array_merge($user_groups, [$post_id]);
                        update_user_meta( $user->ID, '_clients_wp_user_groups', $new_user_groups);
                    }
                } else {
                    update_user_meta( $user->ID, '_clients_wp_user_groups', array($post_id));
                }
            } else {
                add_action( 'admin_notices', function(){
                    ?>
                    <div class='error'>
                        <p>
                            <?php echo __( 'A group must need an user owner. Add user of exising email.', 'cl-wp' ); ?>
                        </p>
                    </div>
                    <?php
                });
            }
            
        }

        if ( isset( $_POST['_bt_client_group_seats']  ) ) 
            update_post_meta( $post_id, '_bt_client_group_seats',  $_POST['_bt_client_group_seats']);

        if ( isset( $_POST['_bt_tags']  ) ) {
            $users = get_users();
            foreach ($users as $user) {
                $cwpinfusionsoft_settings_options = get_option('cwpinfusionsoft_settings_options');

                if(isset($cwpinfusionsoft_settings_options) && is_plugin_active('ninja2pdf-infusionsoft/ninja2pdf-infusionsoft.php')) {
                    $app_key    = isset($cwpinfusionsoft_settings_options['app_key']) ? $cwpinfusionsoft_settings_options['app_key'] : '';
                    $app_secret = isset($cwpinfusionsoft_settings_options['app_secret']) ? $cwpinfusionsoft_settings_options['app_secret'] : '';
                    $app_token  = isset($cwpinfusionsoft_settings_options['app_token']) ? $cwpinfusionsoft_settings_options['app_token'] : '';

                    $infusionsoft = new \Infusionsoft\Infusionsoft(array(
                        'clientId'     => $app_key,
                        'clientSecret' => $app_secret,
                        'redirectUri'  => admin_url( 'edit.php?post_type=bt_client' ),
                    ));

                    $infusionsoft->setToken(unserialize($app_token));

                    $data = array(
                        'FirstName' => $_POST['post_title'],
                        'Email' => $_POST['_bt_client_group_owner']
                    );

                    $contact_id = $infusionsoft->contacts('xml')->add($data);
                    foreach($_POST['_bt_tags'] as $tag) {
                        $result = $infusionsoft->contacts('xml')->addToGroup($contact_id, $tag);
                    }
                }
            }
            
            
            update_post_meta( $post_id, '_bt_tags', serialize($_POST['_bt_tags']));
        }

    }

    public function save_bt_client_page_fields( $post_id ){
        // Avoid autosaves
        if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        $slug = 'bt_client_page';
        if ( ! isset( $_POST['post_type'] ) || $slug != $_POST['post_type'] ) {
            return;
        }

        if ( isset( $_POST['_clients_page_client']  ) ) 
            update_post_meta( $post_id, '_clients_page_client',  $_POST['_clients_page_client']);

        if ( isset( $_POST['_clients_page_shortcode']  ) ) 
            update_post_meta( $post_id, '_clients_page_shortcode',  $_POST['_clients_page_shortcode']);

        if ( isset( $_POST['_clients_page_integration']  ) ) 
            update_post_meta( $post_id, '_clients_page_integration',  $_POST['_clients_page_integration']);

        if ( isset( $_POST['_clients_page_integration_folder']  ) ) 
            update_post_meta( $post_id, '_clients_page_integration_folder',  $_POST['_clients_page_integration_folder']);
    }

}

new Clients_WP_Post_Type;