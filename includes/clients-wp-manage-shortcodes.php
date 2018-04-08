<div class="wrap" >
    <h2>Manage Shortcodes</h2>
    <div id="edittag">
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