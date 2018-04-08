<div>
    <label for="">Integration </label>
    <?php
    $cwp_integrations = get_option('clients_wp_integrations') ? get_option('clients_wp_integrations') : '';
    $clients_page_integration = get_post_meta($post->ID, '_clients_page_integration', true);
    $clients_page_integration_folder = get_post_meta($post->ID, '_clients_page_integration_folder', true);
    ?>
    <select  name="_clients_page_integration" id="integration-select-type"  class="cwp-dropdown">
        <option selected="true" disabled="disabled">Select Integration</option>
        <?php
        foreach ($cwp_integrations as $integration) {
            $integration_settings = get_option('cwp'.$integration['key'].'_settings_options') ? get_option('cwp'.$integration['key'].'_settings_options') : '';

            if(isset($integration_settings['app_token'])) {
                if(isset($integration_settings['app_domain'])) {
                    ?> <option value="<?= $integration['key'] ?>" data-domain='<?= $integration_settings['app_domain'] ?>' data-key='<?= $integration_settings['app_token'] ?>' <?php selected($clients_page_integration, $integration['key'] ); ?>><?=  $integration['label'] ?></option> <?php
                } else {
                    ?> <option value="<?= $integration['key'] ?>" data-key='<?= $integration_settings['app_token'] ?>' <?php selected($clients_page_integration, $integration['key'] ); ?>><?=  $integration['label'] ?></option> <?php
                }
            }
        }
        ?>
    </select>
    <select  name="_clients_page_integration_folder" id="integration-select-folder"  class="cwp-dropdown">
        <option selected="true" disabled="disabled">Select Root Folder</option>
        <?php
            if($clients_page_integration_folder) {
                ?> <option class="root-folder" value="<?= $clients_page_integration_folder ?>" selected="true"></option> <?php
            }
        ?>
    </select>
</div>
<div>
    <label for="">Shortcode </label>
    <?php
    $cwp_shortcodes = is_array(get_option('clients_wp_shortcodes')) ? get_option('clients_wp_shortcodes') : '';
    ?>
    <select  name="_clients_page_shortcode"  class="cwp-dropdown">
        <option>---</option>
        <?php
        foreach ($cwp_shortcodes['label'] as $key => $label) {
            ?> <option value="<?= $cwp_shortcodes['shortcode'][$key] ?>" <?php selected( get_post_meta($post->ID, '_clients_page_shortcode', true), $cwp_shortcodes['shortcode'][$key] ); ?>><?=  $label ?></option> <?php
        }
        ?>
    </select>
</div>
<div>
    <label for="">Client </label>
    <div class="clients_dropdown-wrapper">
        <span class="spinner query_spinner" style="display: none; float: none"></span>
        <select name="_clients_page_client" class="cwp-dropdown">
            <?php
            $screen = get_current_screen();
            if ( $screen->action != 'add' ) {
                $cwlp = new Clients_WP_Client_Page;
                $clients = $cwlp->get_clients_not_in_shortcode(get_post_meta($post->ID, '_clients_page_shortcode', true), [get_post_meta($post->ID, '_clients_page_client', true)]);
                foreach ($clients as $client) {
                    $selected = ($client->ID == get_post_meta($post->ID, '_clients_page_client', true)) ? 'selected' : '';
                    echo '<option value="'.$client->ID.'" '.$selected.'>'.$client->post_title.'</option>';
                }
            } else {
                // value populated by ajax
            }
            ?>
        </select>
    </div>
</div>