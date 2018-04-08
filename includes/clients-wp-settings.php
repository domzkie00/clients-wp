<div class="wrap wrap-<?= $_GET['tab'] ?>" >
    <h2>Clients WP Settings </h2>

    <?php include_once(CWP_PATH_INCLUDES . '/clients-wp-settings-tab.php'); ?>

    <?php settings_errors() ?>

    <?php
    if ($_GET['page'] == 'clients-wp-settings' && !isset($_GET['tab'])) {
        $clients_wp_settings = get_option('clients_wp_settings');
        $user_not_logged_in = isset($clients_wp_settings['user_not_logged_in']) ? $clients_wp_settings['user_not_logged_in'] : '';
        $for_member_content = isset($clients_wp_settings['for_member_content']) ? $clients_wp_settings['for_member_content'] : '';
    ?>
        <div id="edittag">
            <form method="post" action="options.php">
                <?php settings_fields( 'clients_wp_settings' ); ?>
                <?php do_settings_sections( 'clients_wp_settings' ); ?>
                <h3>Error Messages</h3>
                <table class="form-table" id="cwp-shortcode-table">
                    <tbody>
                        <tr class="form-field" id="referer-input">
                                <th scope="row"><label for="name">Not logged in user</label></th>
                            <td>
                                <input name="clients_wp_settings[user_not_logged_in]" class="cwp-label" type="text" size="40" placeholder="Enter Message" value="<?= $user_not_logged_in ?>">
                                <p class="description">Message to show when user is not logged in and view client page.</p>
                            </td>
                        </tr>
                        <tr class="form-field" id="referer-input">
                                <th scope="row"><label for="name">Restricted Content.</label></th>
                            <td>
                                <input name="clients_wp_settings[for_member_content]" class="cwp-label" type="text" size="40" placeholder="Enter Message" value="<?= $for_member_content ?>">
                                 <p class="description">Message to show when content is for members only.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <?php submit_button() ?>
            </form>
        </div>
    <?php } ?>


    <?php if (isset($_GET['tab']) && $_GET['tab'] == 'licenses') { ?>
        <form method="post" action="options.php">
            <?php settings_fields( 'clients_wp_settings' ); ?>
            <?php do_settings_sections( 'clients_wp_settings' ); ?>
            <?php
            if (isset($_GET['tab']) && $_GET['tab'] == 'licenses') {
                include_once(CWP_PATH_INCLUDES . '/clients-wp-licenses.php');
            }
            ?>
            <?php submit_button(); ?>
        </form>
    <?php } ?>
    
</div>