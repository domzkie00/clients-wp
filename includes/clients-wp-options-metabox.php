<table class="form-table">
    <tbody>
        <tr class="form-row form-required">
            <th scope="row">
                <label for="bt-name">Owner</label>
            </th>

            <td>
                <input type="email" name="_bt_client_group_owner" id="bt-client_group-name" class="regular-text member-email-input" autocomplete="off" value="<?= get_post_meta($post->ID, '_bt_client_group_owner', true) ?>">
                <p class="description">Enter the email address of the user account to set as the group owner.</p>
            </td>
        </tr>
        <tr class="form-row form-required">
            <th scope="row">
                <label for="bt-seats">Seats</label>
            </th>

            <td>
                <input type="number" min="1" step="1" name="_bt_client_group_seats" id="bt-client-group-eats" class="regular-text" autocomplete="off" value="<?= get_post_meta($post->ID, '_bt_client_group_seats', true) ?>">
                <p class="description"> The number of seats for this group</p>
            </td>
        </tr>
        <tr class="form-row form-required">
            <th scope="row">
                <label for="bt-tags">Enroll User with Tags</label>
            </th>

            <td>
                <?php
                $cwpinfusionsoft_settings_options = get_option('cwpinfusionsoft_settings_options');
                if(!is_plugin_active('cwp-infusionsoft/cwp-infusionsoft.php')) {
                    echo '<select class="disabled-select">';
                        echo '<option disabled selected>Clients WP - Infusionsoft add-on plugin required.</option>';
                    echo '</select>';
                } else {
                    $post_id = get_the_ID();
                    $post_tags = get_post_meta( $post_id, '_bt_tags', true );
                    $available_tags  = isset($cwpinfusionsoft_settings_options['tags']) ? $cwpinfusionsoft_settings_options['tags'] : '';
                    $available_tags = unserialize($available_tags);
                    
                    echo '<div class="select_tags-container">';
                    if(empty($available_tags)) {
                        echo '<select class="disabled-select">';
                            echo '<option disabled selected>Please synchronize first with Infusionsoft.</option>';
                        echo '</select>';
                    } else {
                        if($post_tags) {
                            $post_tags_arr = unserialize($post_tags);
                            foreach($post_tags_arr as $used_tag) {
                                echo '<div class="one-select-tag">';
                                    echo '<select name="_bt_tags[]">';
                                    foreach($available_tags as $tag) {
                                        if($tag['Id'] == $used_tag) {
                                            echo '<option value="'.$tag['Id'].'" selected>'.$tag['GroupName'].'</option>';
                                        } else {
                                            echo '<option value="'.$tag['Id'].'">'.$tag['GroupName'].'</option>';
                                        }
                                    }
                                    echo '</select>
                                    <span class="removetag">Remove</span>';
                                echo '</div>';
                            }
                        } else {
                            echo '<div class="one-select-tag">';
                                echo '<select name="_bt_tags[]" id="_bt_tags">';
                                foreach($available_tags as $tag) {
                                    echo '<option value="'.$tag['Id'].'">'.$tag['GroupName'].'</option>';
                                }
                                echo '</select>
                                <span class="removetag">Remove</span>';
                            echo '</div>';

                        }
                        echo '<a href="javascript:;" class="addmoretag">Add Tag</a>';
                    }
                    echo '</div>';
                }
                ?>
                <p class="description"> Auto enroll users that has Infusionsoft tags selected</p>
            </td>
        </tr>
    </tbody>
</table>