<?php
/*
Plugin Name: ExciteSMS for WooCommerce
Description: Send SMS notifications using ExciteSMS API in WooCommerce.
Version: 1.0
Author: kazashim Kuzasuwata
Author URI: https://github.com/kazashim
*/

// Register settings fields and create a dedicated database table
function excite_sms_initialize() {
    add_filter('woocommerce_settings_tabs_array', 'excite_sms_add_settings_tab', 50);
    add_action('woocommerce_settings_tabs_excite_sms', 'excite_sms_settings_tab_content');
    add_action('woocommerce_update_options_excite_sms', 'excite_sms_update_settings');
    
    // Create a dedicated database table
    excite_sms_create_settings_table();
}

add_action('admin_init', 'excite_sms_initialize');

// Add a new tab to the WooCommerce settings page
function excite_sms_add_settings_tab($settings_tabs) {
    $settings_tabs['excite_sms'] = __('ExciteSMS', 'excite-sms-woocommerce');
    return $settings_tabs;
}

// Display settings fields
function excite_sms_settings_tab_content() {
    $api_key = get_option('excite_sms_api_key');
    $sender_id = get_option('excite_sms_sender_id');
    $test_phone_number = get_option('excite_sms_test_phone_number');
    
    ?>
    <h2><?php _e('ExciteSMS Settings', 'excite-sms-woocommerce'); ?></h2>
    <?php
    woocommerce_admin_fields(
        array(
            'section_title' => array(
                'name' => __('ExciteSMS Settings', 'excite-sms-woocommerce'),
                'type' => 'title',
                'desc' => 'Configure settings for ExciteSMS integration.',
                'id' => 'excite_sms_section_title',
            ),
            'api_key' => array(
                'name' => __('API Key', 'excite-sms-woocommerce'),
                'type' => 'text',
                'desc' => __('Enter your ExciteSMS API Key.', 'excite-sms-woocommerce'),
                'id' => 'excite_sms_api_key',
                'css' => 'min-width:300px;',
                'std' => $api_key,
            ),
            'sender_id' => array(
                'name' => __('Sender ID', 'excite-sms-woocommerce'),
                'type' => 'text',
                'desc' => __('Enter your Sender ID.', 'excite-sms-woocommerce'),
                'id' => 'excite_sms_sender_id',
                'css' => 'min-width:300px;',
                'std' => $sender_id,
            ),
            'test_phone_number' => array(
                'name' => __('Test Phone Number', 'excite-sms-woocommerce'),
                'type' => 'text',
                'desc' => __('Enter a phone number for testing SMS notifications. Leave empty for production.', 'excite-sms-woocommerce'),
                'id' => 'excite_sms_test_phone_number',
                'css' => 'min-width:300px;',
                'std' => $test_phone_number,
            ),
            'section_end' => array(
                'type' => 'sectionend',
                'id' => 'excite_sms_section_end',
            ),
        )
    );
    ?>
    <button class="button-primary" id="excite-sms-send-test" style="margin-top: 10px;">Send Test Message</button>
    <?php
}

// Create a dedicated database table for ExciteSMS settings
function excite_sms_create_settings_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'excite_sms_settings';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            api_key varchar(255) NOT NULL,
            sender_id varchar(255) NOT NULL,
            test_phone_number varchar(255) NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

// Save settings
function excite_sms_update_settings() {
    $api_key = sanitize_text_field($_POST['excite_sms_api_key']);
    $sender_id = sanitize_text_field($_POST['excite_sms_sender_id']);
    $test_phone_number = sanitize_text_field($_POST['excite_sms_test_phone_number']);

    update_option('excite_sms_api_key', $api_key);
    update_option('excite_sms_sender_id', $sender_id);
    update_option('excite_sms_test_phone_number', $test_phone_number);
}

// Send SMS when an order is completed
function excite_sms_send_sms_on_order_completed($order_id) {
    // Get API Key and Sender ID from settings
    $api_key = get_option('excite_sms_api_key');
    $sender_id = get_option('excite_sms_sender_id');
    $test_phone_number = get_option('excite_sms_test_phone_number');

    // Get order data
    $order = wc_get_order($order_id);
    $recipient = $order->get_billing_phone(); // Adjust this based on your WooCommerce setup
    $message = 'Your order has been completed.';

    // Determine whether to send a test message or a real order message
    if ($recipient === $test_phone_number) {
        // This is a test message
        $recipient = $test_phone_number;
        $message = 'This is a test message from ExciteSMS plugin.';
    }

    // Prepare data for API request
    $data = array(
        'recipient' => $recipient,
        'sender_id' => $sender_id,
        'message' => $message,
    );

    // Make a request to the ExciteSMS API using cURL
    $response = wp_safe_remote_post(
        'https://gateway.excitesms.tech/api/v3/sms/send',
        array(
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
            ),
            'body' => json_encode($data),
        )
    );

    // Handle the API response as needed
    if (is_wp_error($response)) {
        error_log('Error sending SMS: ' . $response->get_error_message());
    }
}

add_action('woocommerce_order_status_completed', 'excite_sms_send_sms_on_order_completed');

// Enqueue JavaScript for the Send Test Message button
function excite_sms_enqueue_scripts() {
    wp_enqueue_script('excite-sms-admin', plugin_dir_url(__FILE__) . 'excite-sms-admin.js', array('jquery'), '1.0', true);

    // Pass the AJAX URL to the script
    wp_localize_script('excite-sms-admin', 'excite_sms_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'excite_sms_enqueue_scripts');

// JavaScript function to send a test message
function excite_sms_send_test_message() {
    $api_key = get_option('excite_sms_api_key');
    $sender_id = get_option('excite_sms_sender_id');
    $test_phone_number = get_option('excite_sms_test_phone_number');
    $message = 'This is a test message from ExciteSMS plugin.';

    // Prepare data for API request
    $data = array(
        'recipient' => $test_phone_number,
        'sender_id' => $sender_id,
        'message' => $message,
    );

    // Make a request to the ExciteSMS API using cURL
    $response = wp_safe_remote_post(
        'https://gateway.excitesms.tech/api/v3/sms/send',
        array(
            'headers' => array(
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
            ),
            'body' => json_encode($data),
        )
    );

    // Handle the API response as needed
    if (is_wp_error($response)) {
        echo 'Error sending test message: ' . $response->get_error_message();
    } else {
        echo 'Test message sent to ' . $test_phone_number;
    }

    die(); // End AJAX request
}

add_action('wp_ajax_excite_sms_send_test_message', 'excite_sms_send_test_message');