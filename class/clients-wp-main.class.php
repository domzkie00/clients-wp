<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Clients_WP{
    
    private static $instance;

    public static function get_instance()
    {
        if( null == self::$instance ) {
            self::$instance = new Clients_WP();
        }

        return self::$instance;
    }

    function __construct(){
        add_action('admin_enqueue_scripts', array( $this, 'admin_scripts' ));
        add_action('wp_enqueue_scripts', array($this, 'public_scripts'));
    }

    public function admin_scripts($hook){
        global $post;
        wp_enqueue_style('clients-wp-autocomplete', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.css', array(), '1.0.7');
        wp_enqueue_script('clients-wp-auto-complete', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-autocomplete/1.0.7/jquery.auto-complete.min.js', array( 'jquery' ), '1.0.7', true);

        wp_register_style( 'clients-wp-admin-style', CWP_URL . '/assets/css/admin-styles.css', '1.0', true );
        wp_enqueue_style( 'clients-wp-admin-style' );

        wp_register_script( 'clients-wp-admin-script', CWP_URL . '/assets/js/admin-scripts.js', '1.0', true );
        $cwpscript = array(
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'selected_client' => ($post) ? get_post_meta($post->ID, '_clients_page_client', true) : ''
        );
        wp_localize_script('clients-wp-admin-script', 'cwpscript', $cwpscript );
        wp_enqueue_script( 'clients-wp-admin-script' );

        wp_register_style('cwp-admin-style', CWP_URL . '/assets/css/cwp-admin-style.css', '1.0', true );
        wp_enqueue_style('cwp-admin-style');
    }

    public function public_scripts(){
        //wp_enqueue_script('clients-wp-jquery', 'https://code.jquery.com/jquery-1.12.4.js', array(), '1.12.4', true);
        //wp_enqueue_style('clients-wp-bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css', array(), '3.3.7');
        //wp_enqueue_style('clients-wp-datatable-bootstrap-style', 'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap.min.css', array(), '1.10.16');
        //wp_enqueue_script('clients-wp-datatable-jquery', 'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js', array( 'jquery' ), '1.10.16', true);
        //wp_enqueue_script('clients-wp-datatable-bootstrap-script', 'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js', array( 'jquery' ), '1.10.16', true);

        wp_register_style('cwp-style', CWP_URL . '/assets/css/cwp-admin-style.css', '1.0', true );
        wp_enqueue_style('cwp-style');
    }
}