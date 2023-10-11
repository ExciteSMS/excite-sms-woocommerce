<?php
// Add a menu item to the WooCommerce settings menu
function excitesms_add_admin_menu() {
    add_submenu_page(
        'woocommerce',
        'ExciteSMS Settings',
        'ExciteSMS',
        'manage_options',
        'excitesms-settings',
        'excitesms_settings_page'
    );
}
add_action('admin_menu', 'excitesms_add_admin_menu');

// Create the settings page
function excitesms_settings_page() {
    ?>
    <div class="wrap">
        <h2>ExciteSMS Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('excitesms_options');
            do_settings_sections('excitesms-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function excitesms_register_settings() {
    register_setting('excitesms_options', 'excitesms_api_key');
    register_setting('excitesms_options', 'excitesms_sender_id');

    add_settings_section('excitesms_section', 'ExciteSMS API Settings', null, 'excitesms-settings');
    add_settings_field('excitesms_api_key', 'API Key', 'excitesms_api_key_callback', 'excitesms-settings', 'excitesms_section');
    add_settings_field('excitesms_sender_id', 'Sender ID', 'excitesms_sender_id_callback', 'excitesms-settings', 'excitesms_section');
}
add_action('admin_init', 'excitesms_register_settings');

// API Key field callback
function excitesms_api_key_callback() {
    $value = get_option('excitesms_api_key');
    echo "<input type='text' name='excitesms_api_key' value='$value' />";
}

// Sender ID field callback
function excitesms_sender_id_callback() {
    $value = get_option('excitesms_sender_id');
    echo "<input type='text' name='excitesms_sender_id' value='$value' />";
}
