<?php
/**
 * Plugin Name: Clients WP
 * Plugin URI:  https://www.gravity2pdf.com
 * Description: Manage RCP clients group through Clients WP
 * Version:     1.0
 * Author:      gravity2pdf
 * Author URI:  https://github.com/raphcadiz
 * Text Domain: cl-wp
 */

if (!class_exists('Clients_WP')):

    define( 'CWP_PATH', dirname( __FILE__ ) );
    define( 'CWP_PATH_INCLUDES', dirname( __FILE__ ) . '/includes' );
    define( 'CWP_PATH_CLASS', dirname( __FILE__ ) . '/class' );
    define( 'CWP_PATH_INTEGRATIONS', dirname( __FILE__ ) . '/integrations' );
    define( 'CWP_FOLDER', basename( CWP_PATH ) );
    define( 'CWP_URL', plugins_url() . '/' . CWP_FOLDER );
    define( 'CWP_URL_INCLUDES', CWP_URL . '/includes' );
    define( 'CWP_URL_CLASS', CWP_URL . '/class' );
    define( 'CWP_URL_INTEGRATIONS', CWP_URL . '/integrations' );
    define( 'CWP_VERSION', 1.0 );

    register_activation_hook( __FILE__, 'clients_wp_activation' );
    function clients_wp_activation(){
        if ( ! is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
            wp_die('Sorry, but this plugin requires the Restrict Content Pro to be installed and active.');
        }

    }

    add_action( 'admin_init', 'clients_wp_activate' );
    function clients_wp_activate(){
        if ( ! is_plugin_active( 'restrict-content-pro/restrict-content-pro.php' ) ) {
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
    }

    /*
     * include necessary files
     */
    require_once(CWP_PATH_CLASS . '/clients-wp-main.class.php');
    require_once(CWP_PATH_CLASS . '/clients-wp-integrations.class.php');
    require_once(CWP_PATH_CLASS . '/clients-wp-post-type.class.php');
    require_once(CWP_PATH_CLASS . '/clients-wp-pages.class.php');
    require_once(CWP_PATH_CLASS . '/clients-wp-clients-pages.class.php');
    require_once(CWP_PATH_CLASS . '/clients-wp-members.class.php');
    //require_once(CWP_PATH_CLASS . '/clients-wp-clients-pages.class.php');

    // require_once(CWP_PATH_CLASS . '/clients-members-table.class.php');
    // require_once(CWP_PATH_CLASS . '/Clients_WP-merges.class.php');
    // require_once(CWP_PATH_CLASS . '/Clients_WP-pdf-options.class.php');
    // require_once(CWP_PATH_CLASS . '/Clients_WP-integrations.class.php');
    require_once(CWP_PATH_CLASS . '/clients-wp-meta-boxes.class.php');
    // require_once(CWP_PATH_CLASS . '/Clients_WP-processing.class.php');
    // require_once(CWP_PATH_CLASS . '/Clients_WP-license-handler.class.php');

    require_once(CWP_PATH_CLASS . '/clients-wp-license-handler.class.php');
    require_once(CWP_PATH_INCLUDES . '/functions.php');

    /* Intitialize licensing
     * for this plugin.
     */
    if( class_exists( 'Clients_WP_License_Handler' ) ) {
        // Important: Do not change this code it handles your lincensing otherwise you'll get error on activation
        $cwp = new Clients_WP_License_Handler( __FILE__, 'Clients WP', CWP_VERSION, 'gravity2pdf', null, null, 6703);
    }


    add_action( 'plugins_loaded', array( 'Clients_WP', 'get_instance' ) );
endif;