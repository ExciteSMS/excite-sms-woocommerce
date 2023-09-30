<?php
/*
Plugin Name: ExciteSMS for WooCommerce
Description: Send SMS notifications using ExciteSMS API in WooCommerce.
Version: 1.0
Author: Kazashim Kuzasuwat
*/

// Add settings fields to WooCommerce
function excite_sms_add_settings() {
    // Define settings fields
    $settings = array(
        'excite_sms_api_key' => array(
            'title' => 'ExciteSMS API Key',
            'type' => 'text',
            'desc' => 'Enter your ExciteSMS API Key',
            'id' => 'excite_sms_api_key',
        ),
        'excite_sms_sender_id' => array(
            'title' => 'Sender ID',
            'type' => 'text',
            'desc' => 'Enter your Sender ID',
            'id' => 'excite_sms_sender_id',
        ),
        'excite_sms_test_phone_number' => array(
            'title' => 'Test Phone Number',
            'type' => 'text',
            'desc' => 'Enter a phone number for testing SMS notifications',
            'id' => 'excite_sms_test_phone_number',
        ),
    );

    // Register settings
    foreach ($settings as $key => $field) {
        add_option($field['id'], '', '', 'yes');
        add_settings_field($field['id'], $field['title'], 'excite_sms_display_setting', 'woocommerce', 'general', array('id' => $field['id'], 'type' => $field['type'], 'desc' => $field['desc']));
        register_setting('woocommerce', $field['id']);
    }
}

add_action('admin_init', 'excite_sms_add_settings');

// Display settings fields
function excite_sms_display_setting($args) {
    $option = get_option($args['id']);
    $type = $args['type'];

    switch ($type) {
        case 'text':
            echo "<input type='text' id='{$args['id']}' name='{$args['id']}' value='$option' />";
            echo "<p class='description'>{$args['desc']}</p>";
            break;
    }
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
}

add_action('woocommerce_order_status_completed', 'excite_sms_send_sms_on_order_completed');

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

// Enqueue JavaScript for the Send Test Message button
function excite_sms_enqueue_scripts() {
    wp_enqueue_script('excite-sms-admin', plugin_dir_url(__FILE__) . 'excite-sms-admin.js', array('jquery'), '1.0', true);

    // Pass the AJAX URL to the script
    wp_localize_script('excite-sms-admin', 'excite_sms_ajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('admin_enqueue_scripts', 'excite_sms_enqueue_scripts');