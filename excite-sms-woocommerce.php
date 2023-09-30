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

    // Get order data
    $order = wc_get_order($order_id);
    $recipient = $order->get_billing_phone(); // 
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
