<?php

function cwp_get_current_user_groups() {
    if (!is_user_logged_in())
        return [];

    $user_groups = get_user_meta( get_current_user_id(), '_clients_wp_user_groups', true );

    return $user_groups;
}

function cwp_get_group_owner($group_id) {
    $email = get_post_meta($group_id, '_bt_client_group_owner', true);

    if (!$email)
        return;

    $user = get_user_by('email', $email);

    return $user;
}

function cwp_get_group_members($group_id) {
    $args = array(
        'meta_query' => array(
            array(
                'key'     => '_clients_wp_user_groups',
                'value'   => $group_id,
                'compare' => 'LIKE'
            )
        )
    );
    $user_query = new WP_User_Query( $args );
    $users = $user_query->get_results();

    return $users;
}

if ( ! function_exists( 'cwp_license_key_callback' ) ) {
    function cwp_license_key_callback( $args ) {

        $messages = array();
        $license  = get_option( $args['options']['is_valid_license_option'] );


        $cwp_settings = get_option('clients_wp_settings');
        $value = $cwp_settings[$args['id']];

        if( ! empty( $license ) && is_object( $license ) ) {

            // activate_license 'invalid' on anything other than valid, so if there was an error capture it
            if ( false === $license->success ) {

                switch( $license->error ) {

                    case 'expired' :

                        $class = 'expired';
                        $messages[] = sprintf(
                            __( 'Your license key expired on %s. Please <a href="%s" target="_blank">renew your license key</a>.', 'clients-wp' ),
                            date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) ),
                            'http://www.ninja2pdf.com/checkout/?edd_license_key=' . $value . '&utm_campaign=admin&utm_source=licenses&utm_medium=expired'
                        );

                        $license_status = 'license-' . $class . '-notice';

                        break;

                    case 'revoked' :

                        $class = 'error';
                        $messages[] = sprintf(
                            __( 'Your license key has been disabled. Please <a href="%s" target="_blank">contact support</a> for more information.', 'clients-wp' ),
                            'http://www.ninja2pdf.com/support?utm_campaign=admin&utm_source=licenses&utm_medium=revoked'
                        );

                        $license_status = 'license-' . $class . '-notice';

                        break;

                    case 'missing' :

                        $class = 'error';
                        $messages[] = sprintf(
                            __( 'Invalid license. Please <a href="%s" target="_blank">visit your account page</a> and verify it.', 'clients-wp' ),
                            'http://www.ninja2pdf.com/your-account?utm_campaign=admin&utm_source=licenses&utm_medium=missing'
                        );

                        $license_status = 'license-' . $class . '-notice';

                        break;

                    case 'invalid' :
                    case 'site_inactive' :

                        $class = 'error';
                        $messages[] = sprintf(
                            __( 'Your %s is not active for this URL. Please <a href="%s" target="_blank">visit your account page</a> to manage your license key URLs.', 'clients-wp' ),
                            $args['name'],
                            'http://www.ninja2pdf.com/your-account?utm_campaign=admin&utm_source=licenses&utm_medium=invalid'
                        );

                        $license_status = 'license-' . $class . '-notice';

                        break;

                    case 'item_name_mismatch' :

                        $class = 'error';
                        $messages[] = sprintf( __( 'This appears to be an invalid license key for %s.', 'clients-wp' ), $args['name'] );

                        $license_status = 'license-' . $class . '-notice';

                        break;

                    case 'no_activations_left':

                        $class = 'error';
                        $messages[] = sprintf( __( 'Your license key has reached its activation limit. <a href="%s">View possible upgrades</a> now.', 'clients-wp' ), 'http://www.ninja2pdf.com/your-account/' );

                        $license_status = 'license-' . $class . '-notice';

                        break;

                    case 'license_not_activable':

                        $class = 'error';
                        $messages[] = __( 'The key you entered belongs to a bundle, please use the product specific license key.', 'clients-wp' );

                        $license_status = 'license-' . $class . '-notice';
                        break;

                    default :

                        $class = 'error';
                        $error = ! empty(  $license->error ) ?  $license->error : __( 'unknown_error', 'clients-wp' );
                        $messages[] = sprintf( __( 'There was an error with this license key: %s. Please <a href="%s">contact our support team</a>.', 'clients-wp' ), $error, 'http://www.ninja2pdf.com/support' );

                        $license_status = 'license-' . $class . '-notice';
                        break;
                }

            } else {

                switch( $license->license ) {

                    case 'valid' :
                    default:

                        $class = 'valid';

                        $now        = current_time( 'timestamp' );
                        $expiration = strtotime( $license->expires, current_time( 'timestamp' ) );

                        if( 'lifetime' === $license->expires ) {

                            $messages[] = __( 'License key never expires.', 'clients-wp' );

                            $license_status = 'license-lifetime-notice';

                        } elseif( $expiration > $now && $expiration - $now < ( DAY_IN_SECONDS * 30 ) ) {

                            $messages[] = sprintf(
                                __( 'Your license key expires soon! It expires on %s. <a href="%s" target="_blank">Renew your license key</a>.', 'clients-wp' ),
                                date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) ),
                                'http://www.ninja2pdf.com/checkout/?edd_license_key=' . $value . '&utm_campaign=admin&utm_source=licenses&utm_medium=renew'
                            );

                            $license_status = 'license-expires-soon-notice';

                        } else {

                            $messages[] = sprintf(
                                __( 'Your license key expires on %s.', 'clients-wp' ),
                                date_i18n( get_option( 'date_format' ), strtotime( $license->expires, current_time( 'timestamp' ) ) )
                            );

                            $license_status = 'license-expiration-date-notice';

                        }

                        break;

                }

            }

        } else {
            $class = 'empty';

            $messages[] = sprintf(
                __( 'To receive updates, please enter your valid %s license key.', 'clients-wp' ),
                $args['name']
            );

            $license_status = null;
        }

        //$class .= ' ' . $args['field_class'];

        $size = ( isset( $args['size'] ) && ! is_null( $args['size'] ) ) ? $args['size'] : 'regular';
        $html = '<input type="text" class="' . sanitize_html_class( $size ) . '-text" id="clients_wp_settings[' . cwp_sanitize_key( $args['id'] ) . ']" name="clients_wp_settings[' . cwp_sanitize_key( $args['id'] ) . ']" value="' . esc_attr( $value ) . '"/>';

        if ( ( is_object( $license ) && 'valid' == $license->license ) || 'valid' == $license ) {
            $html .= '<input type="submit" class="button-secondary" name="' . $args['id'] . '_deactivate" value="' . __( 'Deactivate License',  'clients-wp' ) . '"/>';
        }

        $html .= '<label for="clients_wp_settings[' . cwp_sanitize_key( $args['id'] ) . ']"> '  . wp_kses_post( $args['desc'] ) . '</label>';

        if ( ! empty( $messages ) ) {
            foreach( $messages as $message ) {

                $html .= '<div class="clients-wp-license-data clients-wp-license-' . $class . ' ' . $license_status . '">';
                $html .= '<p>' . $message . '</p>';
                $html .= '</div>';

            }
        }

        wp_nonce_field( cwp_sanitize_key( $args['id'] ) . '-nonce', cwp_sanitize_key( $args['id'] ) . '-nonce' );

        echo $html;
    }
}

function cwp_sanitize_key( $key ) {
    $raw_key = $key;
    $key = preg_replace( '/[^a-zA-Z0-9_\-\.\:\/]/', '', $key );

    /**
     * Filter a sanitized key string.
     *
     * @since 2.5.8
     * @param string $key     Sanitized key.
     * @param string $raw_key The key prior to sanitization.
     */
    return apply_filters( 'cwp_sanitize_key', $key, $raw_key );
}