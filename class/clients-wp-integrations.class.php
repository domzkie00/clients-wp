<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Clients_WP_Integrations {
	public function __construct() {
		add_action('init', array($this, 'cwp_register_integrations'));
	}

	public function cwp_register_integrations() {
        $clients_wp_integrations = get_option('clients_wp_integrations');
        if(isset($clients_wp_integrations)){
        	update_option('clients_wp_integrations', '');
    	} else {
    		add_option('clients_wp_integrations', '');
    	}
    }
}

$cwp_integrations = new Clients_WP_Integrations;