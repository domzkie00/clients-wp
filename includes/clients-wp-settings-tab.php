<h2 class="nav-tab-wrapper">

    <a href="<?= admin_url( 'edit.php?post_type=bt_client&page=clients-wp-settings' ); ?>" class="nav-tab <?= ($_GET['page'] == 'clients-wp-settings' && !isset($_GET['tab'])) ? 'nav-tab-active' : '' ?>"><?php _e( 'Settings', 'clients-wp' ) ?></a>

    <a href="<?= admin_url( 'edit.php?post_type=bt_client&page=clients-wp-settings&tab=licenses' ); ?>" class="nav-tab <?= ($_GET['page'] == 'clients-wp-settings' && $_GET['tab'] == 'licenses') ? 'nav-tab-active' : '' ?>"><?php _e( 'Licenses', 'clients-wp' ) ?></a>

</h2>