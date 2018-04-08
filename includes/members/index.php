<?php
$users = cwp_get_group_members($_GET['groupid'])
?>
<div class="wrap">
    <h2>Members</h2>
    
    <a href="<?= admin_url('admin.php?page=clients-wp-settings&view=members&groupid=') . $_GET['groupid'] . '&cwp-action=add-member' ?>" class="button button-secondary">Add Member</a>
    <br />
    <br />
    <table class="wp-list-table widefat fixed striped posts">
        <thead>
            <tr>
                <th>User</th>
                <th>ID</th>
                <th>Roles</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($users as $key => $user): ?>
                    <tr>
                        <td><a href="<?= get_edit_user_link($user->data->ID) ?>"><?= $user->data->user_login ?></a></td>
                        <td><?= $user->data->ID ?></td>
                        <td><?= implode(',', $user->roles) ?></td>
                        <td>
                            <?php
                            if (cwp_get_group_owner($_GET['groupid'])->ID == $user->data->ID) {
                                echo 'Owner';
                            } else {
                                ?><a href="<?= admin_url('admin.php?page=clients-wp-settings&view=members&groupid=') . $_GET['groupid'] . '&remove-member=' . $user->data->ID ?>" style="color:#a00" onclick="return confirm('Are you sure?')">Remove from Group</a> | <a href="<?= admin_url('admin.php?page=clients-wp-settings&view=members&groupid=') . $_GET['groupid'] . '&set-group-admin=' . $user->data->ID ?>">Set as Admin</a><?php
                            }
                            ?>                            
                        </td>
                    </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>