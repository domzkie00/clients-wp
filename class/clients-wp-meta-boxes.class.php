<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Clients_WP_Metas {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'Clients_WP_meta_boxes'));

    }

    public function Clients_WP_meta_boxes() {
        remove_meta_box('wpf-meta', 'bt_client', 'normal');
        remove_meta_box('um-admin-access-settings', 'bt_client', 'side');


        remove_meta_box('wpf-meta', 'bt_client_page', 'normal');
        remove_meta_box('um-admin-access-settings', 'bt_client_page', 'side');

        add_meta_box(
            'client_wp_options',
            __( 'Options', 'cl-wp' ), 
            array( $this, 'client_wp_options' ),
            'bt_client',
            'normal',
            'high'
        );

        add_meta_box(
            'client_wp_pages_options',
            __( 'Options', 'cl-wp' ), 
            array( $this, 'client_wp_pages_options' ),
            'bt_client_page',
            'side',
            'high'
        );
    }

    public function client_wp_options() {
        global $post;
        include_once(CWP_PATH_INCLUDES . '/clients-wp-options-metabox.php');
    }

    public function client_wp_pages_options() {
        global $post;
        include_once(CWP_PATH_INCLUDES . '/clients-wp-pages-options-metabox.php');
    }

}

new Clients_WP_Metas;