<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Clients_WP_Pages {

    public function __construct() {
        add_action('admin_menu', array( $this, 'admin_menus'), 10 );
        add_action('admin_init', array( $this, 'register_settings' ));
    }

    public function register_settings() {
        register_setting( 'clients_wp_settings', 'clients_wp_settings', '' );
        register_setting( 'clients_wp_shortcodes', 'clients_wp_shortcodes', '' );
    }

    public function admin_menus(){
        add_submenu_page ( 'edit.php?post_type=bt_client' , 'Manage Shortcodes' , 'Manage Shortcodes' , 'manage_options' , 'clients-wp-manage-shortcodes' , array( $this , 'clients_wp_shortcodes' ));
        add_submenu_page ( 'edit.php?post_type=bt_client' , 'Settings' , 'Settings' , 'manage_options' , 'clients-wp-settings' , array( $this , 'clients_wp_settings' ));
    }

    public function clients_wp_settings() {
        if (isset($_GET['cwp-action']) && $_GET['cwp-action'] == 'add-member') {
            include_once(CWP_PATH_INCLUDES . '/members/add.php');
        }
        elseif (isset($_GET['view']) && $_GET['view'] == 'members') {
            include_once(CWP_PATH_INCLUDES . '/members/index.php');
        }
        else {
            include_once(CWP_PATH_INCLUDES . '/clients-wp-settings.php');
        }
    }

    public function clients_wp_shortcodes() {
        include_once(CWP_PATH_INCLUDES . '/clients-wp-manage-shortcodes.php');
    }

}

new Clients_WP_Pages;