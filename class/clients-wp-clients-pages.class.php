<?php if ( ! defined( 'ABSPATH' ) ) exit;
session_start();

class Clients_WP_Client_Page {

    public function __construct() {
        add_action('wp_ajax_get_clients_not_in_shortcode', array($this, 'get_clients_not_in_shortcode_ajax'));
        add_action('init', array($this, 'cwp_register_shortcodes'));
        add_shortcode('clientswp_user_register_form', array($this, 'user_register'));
        add_shortcode('clientswp_group_add_user_form', array($this, 'add_user_to_group'));
    }

    public function get_clients_not_in_shortcode($shortcode, $current_clients = array()) {
        $client_pages = get_posts( array(
                'posts_per_page' => -1,
                'post_type' => 'bt_client_page',
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => '_clients_page_shortcode',
                        'value' => $shortcode,
                    )
                )
            )
        );

        $associated_clients = array();
        foreach($client_pages as $client_page) {
            array_push($associated_clients, get_post_meta($client_page->ID, '_clients_page_client', true));
        }

        if (!empty($current_clients)) {
            $associated_clients = array_diff($associated_clients, $current_clients);
        }

        $clients_query = new WP_Query( array(
                'post_type' => 'bt_client',
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC',
                'post__not_in' => $associated_clients
            )
        );

        return $clients_query->posts;
    }

    public function get_clients_not_in_shortcode_ajax() {
        if (isset($_POST['data'])):

            $clients = ($_POST['data']['_client']) ? [$_POST['data']['_client']] : [];
            $results = $this->get_clients_not_in_shortcode($_POST['data']['_shortcode'], $clients);

            echo json_encode($results);
            die();
        endif;
    }

    public function cwp_register_shortcodes() {
        if(!get_option('clients_wp_shortcodes')) {
            $keys = array('label' => array(''), 'shortcode' => array('[cwp_]'));
            add_option('clients_wp_shortcodes', $keys);
        }
        
        $clients_wp_shortcodes = get_option('clients_wp_shortcodes');
        $cwp_shortcodes = isset($clients_wp_shortcodes) ? $clients_wp_shortcodes : '';

        foreach ($cwp_shortcodes['shortcode'] as $key => $shortcode) {
            if (empty($shortcode))
                continue;

            // register shortcode starts here
            add_shortcode(substr($shortcode, 1, -1), function() use ($shortcode) {
                $clients_wp_settings = get_option('clients_wp_settings');
                $user_not_logged_in = isset($clients_wp_settings['user_not_logged_in']) ? $clients_wp_settings['user_not_logged_in'] : 'You should be logged in to see this contents.';
                $for_member_content = isset($clients_wp_settings['for_member_content']) ? $clients_wp_settings['for_member_content'] : 'You are not allowed to see this contents.';

                if (!is_user_logged_in())
                    return $user_not_logged_in;

                $user_groups = cwp_get_current_user_groups();
                if (empty($user_groups))
                    return $for_member_content;


                $client_page_query = new WP_Query( array(
                        'post_type' => 'bt_client_page',
                        'post_status' => 'publish',
                        'meta_query' => array(
                            'relation' => 'AND',
                            array(
                                'key' => '_clients_page_shortcode',
                                'value' => $shortcode,
                                'compare' => '='
                            ),
                            array(
                                'key' => '_clients_page_client',
                                'value' => $user_groups,
                                'compare' => 'IN'
                            )
                        )
                    )
                );

                if (count($client_page_query->posts) < 1) {
                    return $for_member_content;
                }
                else {
                    return $client_page_query->posts[0]->post_content;
                }

            });
        }
    }

    public function user_register()
    {
        global $post;

        $clients_query = new WP_Query( array(
                'post_type' => 'bt_client',
                'post_status' => 'publish',
                'orderby' => 'title',
                'order' => 'ASC'
            )
        );

        include_once(CWP_PATH_INCLUDES . '/clients-wp-enroll-client.php');
        session_destroy();
    }

    public function add_user_to_group()
    {
        if ( !is_user_logged_in() ) {
            echo 'You must be logged in to access content.';
        } else {
            global $current_user;
            $current_user = wp_get_current_user();
            $client_group_id = get_user_meta( $current_user->ID, '_clients_wp_user_groups', true );

            if(!$client_group_id) {
                echo 'You must be a client group admin to view content.';
            } else {
                $client_group = new WP_Query('post_type=bt_client&p='.$client_group_id[0]);

                if (cwp_get_group_owner($client_group_id[0])->ID == $current_user->ID) {
                    include_once(CWP_PATH_INCLUDES . '/clients-wp-add-usertogroup.php');
                } else {
                    echo 'You must be a client group admin to view content.';
                }       
            }
        }
        session_destroy();
    }

    // end
}

$cwp_client_page = new Clients_WP_Client_Page;