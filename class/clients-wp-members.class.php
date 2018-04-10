<?php if ( ! defined( 'ABSPATH' ) ) exit;

class Clients_WP_Members {

    public function __construct() {
        add_action('init', array( $this, 'add_member') );
        add_action('admin_init', array($this, 'set_user_as_group_admin'));
        add_action('admin_init', array($this, 'remove_member'));
        add_action('wp_ajax_get_clients_wp_users', array($this, 'get_clients_wp_users'));
    }

    public function get_clients_wp_users() {
        $args = array(
            'search' =>  '*'.stripslashes( $_POST['search'] ).'*',
            'search_columns' => array('user_email' ),
            'meta_query' => array(
                array(
                    'key'     => '_clients_wp_user_groups',
                    'compare'   => 'NOT EXISTS'
                )
            )
        );
        $user_query = new WP_User_Query( $args );
        $users = $user_query->get_results();

        $users_email = array();
        foreach ($users as $key => $user) {
            $users_email[] = $user->data->user_email;
        }

        wp_send_json_success( $users_email );
    }

    public function add_member() {
        if (isset($_POST['clients_wp_add_member']) && $_POST['clients_wp_add_member']):

            if (!isset($_POST['_is_new_member'])) {

                $user = get_user_by('email', $_POST['_user_email']);
                if (!$user) {
                    add_action( 'admin_notices', function(){
                        ?>
                        <div class='error'>
                            <p>
                                <?php echo __( 'User not found on given email!', 'cl-wp' ); ?>
                            </p>
                        </div>
                        <?php
                    });

                }

                $user_groups = get_user_meta( $user->ID, '_clients_wp_user_groups', true );

                if (is_array($user_groups)) {
                    if (in_array($_GET['groupid'], $user_groups)) {
                        wp_die('This user is already in this group.');
                    } else {
                        add_action( 'admin_notices', function(){
                            ?>
                            <div class='error'>
                                <p>
                                    <?php echo __( 'This user is already a member of another group.', 'cl-wp' ); ?>
                                </p>
                            </div>
                            <?php
                        });
                    }
                } else {
                    update_user_meta( $user->ID, '_clients_wp_user_groups', array($_GET['groupid']));
                }
                
            } else {

                if(isset($_POST['user_registration']) || isset($_POST['add_user_to_group'])) {
                    $error = false;
                    $err_msg = null;

                    $user = get_user_by('email', $_POST['user_email']);

                    if($user) {
                        $error = true;
                        $user_groups = get_user_meta( $user->ID, '_clients_wp_user_groups', true );
                        if (is_array($user_groups)) {
                            if (in_array($_POST['client_group'], $user_groups)) {
                                $err_msg = 'Email already registered to selected client group';
                            }
                        }

                        if($err_msg == null) {
                            $err_msg = 'Email already registered to a client group';
                        }
                    }

                    if(isset($_POST['user_registration'])) {
                        if(($err_msg == null) && ($_POST['user_password'] != $_POST['user_confirmpassword'])) {
                            $error = true;
                            $err_msg = 'Password confirmation mismatch!';
                        }
                    }

                    if($error) {
                        $_SESSION['error'] = $err_msg;
                        $_SESSION['email'] = $_POST['user_email'];
                        $_SESSION['fname'] = $_POST['user_fname'];
                        $_SESSION['lname'] = $_POST['user_lname'];
                        $_SESSION['gid'] = $_POST['client_group'];
                        header("Location: " . $_SERVER["HTTP_REFERER"]);
                    }

                    if(!$error) {
                        if(isset($_POST['user_registration'])) {
                            $user_data = array(
                                'user_email' => $_POST['user_email'],
                                'user_login' => $_POST['user_email'],
                                'user_pass' => $_POST['user_password'],
                                'first_name' => $_POST['user_fname'],
                                'last_name' => $_POST['user_lname'],
                            );
                        }
                        if(isset($_POST['add_user_to_group'])) {
                            $user_data = array(
                                'user_email' => $_POST['user_email'],
                                'user_login' => $_POST['user_email'],
                                'user_pass' => $_POST['user_fname'].'1234',
                                'first_name' => $_POST['user_fname'],
                                'last_name' => $_POST['user_lname'],
                            );
                        }

                        $user_id = wp_insert_user( $user_data ) ;
                        update_user_meta( $user_id, '_clients_wp_user_groups', $_POST['client_group']);

                        if(isset($_POST['user_registration'])) {
                            $_SESSION['success'] = 'You successfully registered to a client group.';
                        }
                        if(isset($_POST['add_user_to_group'])) {
                            $_SESSION['success'] = 'You successfully added user to your client group.';
                        }
                    }
                } else {
                    $user_data = array(
                        'user_email' => $_POST['_user_email'],
                        'user_login' => $_POST['_user_email'],
                        'user_pass' => $_POST['_user_first_name'].'1234!',
                        'first_name' => $_POST['_user_first_name'],
                        'last_name' => $_POST['_user_last_name'],
                    );

                    $user_id = wp_insert_user( $user_data ) ;
                    update_user_meta( $user_id, '_clients_wp_user_groups', array($_GET['groupid']));
                }
            }

            // wp_redirect(admin_url('admin.php?page=clients-wp-settings&view=members&groupid=') . $_GET['groupid']);
        
        endif;
    }

    public function set_user_as_group_admin() {
        if (isset($_GET['set-group-admin']) &&  $_GET['set-group-admin'] != '') {
            $group_id = $_GET['groupid'];
            $user = get_user_by('ID', $_GET['set-group-admin']);

            if ($user) {
                update_post_meta( $group_id, '_bt_client_group_owner',  $user->data->user_email);
            }

            wp_redirect(admin_url('admin.php?page=clients-wp-settings&view=members&groupid=') . $group_id);
        }
    }

    public function remove_member() {
        if (isset($_GET['remove-member']) &&  $_GET['remove-member'] != '') {
            $group_id = $_GET['groupid'];
            $user = get_user_by('ID', $_GET['remove-member']);

            if ($user) {
                delete_user_meta( $user->ID, '_clients_wp_user_groups' );
            }

            wp_redirect(admin_url('admin.php?page=clients-wp-settings&view=members&groupid=') . $group_id);
        }
    }

}

new Clients_WP_Members;