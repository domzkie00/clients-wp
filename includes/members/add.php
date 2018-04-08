<div class="wrap wrap-<?= $_GET['tab'] ?>">

    <h2>Add Member</h2>

    <form method="post" action="">
        <input type="hidden" name="clients_wp_add_member" value="1">
        <input type="hidden" name="_group_id" value="<?= $_GET['groupid'] ?>">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row">
                        Is this a new member?
                    </th>
                    <td>
                        <input type="checkbox" name="_is_new_member" value="1">
                    </td>
                </tr>
                <tr class="form-field term-name-wrap">
                    <th scope="row">
                        User Email
                    </th>
                    <td>
                        <input type="email" name="_user_email" class="member-email-input" />
                        <p class="description">Enter the email address of a user account to add to the group.</p>
                    </td>
                </tr>
                <tr class="form-field term-name-wrap is-new-member-field">
                    <th scope="row">
                        User First Name
                    </th>
                    <td>
                        <input type="text" name="_user_first_name" >
                        <p class="description">Enter the first name of the user to add to the group.</p>
                    </td>
                </tr>
                <tr class="form-field term-name-wrap is-new-member-field">
                    <th scope="row">
                        User Last Name
                    </th>
                    <td>
                        <input type="text" name="_user_last_name" >
                        <p class="description">Enter the last name of the user to add to the group.</p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php submit_button(); ?>
    </form>

</div>