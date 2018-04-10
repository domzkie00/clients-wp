<div class="wrap" >
    <h2>Manage Shortcodes</h2>

    <div style="margin-bottom: 30px;">
        <label style="font-size: 18px; font-weight: 700;">Clients WP Forms</label>
        <div style="margin-top: 10px;">
            <div style="font-size: 16px; width: 300px; display: inline-block;">User Registration Form: </div>
            <span style="font-size: 20px;">[clientswp_user_register_form]</span>
        </div>

        <div>
            <div style="font-size: 16px; width: 300px; display: inline-block;">Add User to Client Group Form: </div>
            <span style="font-size: 20px;">[clientswp_group_add_user_form]</span>
        </div>
    </div>

    <div id="edittag">
        <label style="font-size: 18px; font-weight: 700;">Clients WP Pages</label>
    <?php settings_errors() ?>
    <?php
        $clients_wp_shortcodes = get_option('clients_wp_shortcodes');
        $cwp_shortcodes = isset($clients_wp_shortcodes) ? $clients_wp_shortcodes : '';
        ?> 
        <form method="post" action="options.php">
            <?php settings_fields( 'clients_wp_shortcodes' ); ?>
            <?php do_settings_sections( 'clients_wp_shortcodes' ); ?> 
            <table class="form-table" id="cwp-shortcode-table">
                <tbody>
            <?php
            if (empty($cwp_shortcodes)):
                ?>
                <tr class="form-field" id="referer-input">
                    <td>
                        <input name="clients_wp_shortcodes[label][]" class="cwp-label" type="text" size="40" placeholder="Enter Shortcode Name">
                    </td>
                    <td>
                        <input name="clients_wp_shortcodes[shortcode][]" class="cwp-shortcode" type="text" size="40" readonly="readonly">
                    </td>
                </tr>
                <?php
            else:
                foreach ($cwp_shortcodes['label'] as $key => $label):
                    ?>
                    <tr class="form-field" id="<?= ($key == 0) ? 'referer-input' : '' ?>">
                        <td>
                            <input name="clients_wp_shortcodes[label][]" class="cwp-label" type="text" size="40" placeholder="Enter Shortcode Name" value="<?= $label ?>">
                        </td>
                        <td>
                            <input name="clients_wp_shortcodes[shortcode][]" class="cwp-shortcode" type="text" size="40" readonly="readonly"  value="<?= $cwp_shortcodes['shortcode'][$key] ?>">
                        </td>
                        <?php
                        if ($key != 0) {
                            ?>
                            <td>
                                <a href="#" class="button button-secondary remove-map">remove</a>
                            <td>
                            <?php
                        }
                        ?>
                    </tr>
                    <?php
                endforeach;
            endif;
            ?> 
                </tbody>
            </table>
            <button class="button button-secondarys" id="cwp-add-shortcode" style="margin-left: 10px">Add Shortcode</button>
            <?php submit_button() ?>
        </form>
        <?php
    ?>
    </div>
</div>